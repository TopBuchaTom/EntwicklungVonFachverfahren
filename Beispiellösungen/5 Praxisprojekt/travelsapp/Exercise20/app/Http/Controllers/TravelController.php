<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Review;
use App\Models\ReviewState;
use App\Models\ReviewType;
use App\Models\Travel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

// Datenbank zurücksetzen für Tests
// php artisan migrate:refresh --seed

class TravelController extends Controller
{
    // GET /travels: Show a list of all travels filtered by review type and review state
    function index(Request $request) {
        $userId = $this->getUserId();
        $type = strtoupper($request->review_type ?? "request");
        $state = $request->review_state ?? "";

        // whereRelation nicht verwenden, da dies separate Exists macht und kein and
        $query = Travel::query()->with(Travel::REVIEWS)->whereHas(Travel::REVIEWS, function($reviews) use ($userId, $type, $state) {
            $reviews->where(Review::USER_ID, $userId)->where(Review::TYPE, $type);

            if (!empty($state))
                $reviews->where(Review::STATE, $state);
        });

        return view('travels.index', [
            'travels' => $query->paginate(10),
            'review_type' => $type,
            'review_state' => $state
        ]);
    }

    // GET /travels/{id}: Show summary for specified travel with the option to switch between different review states
    function show(Request $request, Travel $travel) {
        // First calculate review version because initial travel object state is required to apply events
        $reviewId = $request->review ?? -1;
        $reviewTravel = $this->getPersistedTravel($travel, $reviewId)->load(Travel::REVIEWS);
        $reviewParticipants = $this->getPersistedParticipants($travel, $reviewId);
        $reviewEvents = Event::getEntityEvents($travel, $reviewId)->merge(Event::getListEvents(User::class, $travel->id, $reviewId));
        $reviewIndex = ($reviewId == -1)
            ? $reviewTravel->reviews()->count()
            : $reviewTravel->reviews()->get()->search(function($review) use ($reviewId) { return $review->id == $reviewId; }) + 1;

        // Calculate latest version of travel object
        $travel = $this->getPersistedTravel($travel)->load(Travel::REVIEWS);
        $participants = $this->getPersistedParticipants($travel);
        $events = Event::getEntityEvents($travel)->merge(Event::getListEvents(User::class, $travel->id));

        return view('travels.show', [
            'travel' => $travel,
            'participants' => $participants,
            'events' => $events,
            'reviewIndex' => $reviewIndex,
            'reviewTravel' => $reviewTravel,
            'reviewParticipants' => $reviewParticipants,
            'reviewEvents' => $reviewEvents
        ]);
    }

    // GET /travels/create: Open empty form to create new travel request
    // POST /travels/create: Rerender form with previous inputs but changed participants
    function create(Request $request) {
        $participants = $this->getRequestParticipants($request) ?? [];
        $users = $this->getUsers();

        // Keep old inputs if post to same page
        if ($request->method() == "POST")
            $request->flashExcept(["participants", "next_review_user"]);

        return view('travels.create', [
            'participants' => $participants,
            'users' => $users,
            'tabIndex' => $request->input("tabIndex", 0)
        ]);
    }

    // GET /travels/{id}/edit: Open form and show data of travel request with specified id
    // PUT /travels/{id}/edit: Rerender form with previous inputs but changed participants
    function edit(Request $request, Travel $travel) {
        $travel = $this->getPersistedTravel($travel);
        [$currentReview, $previousReview, $nextReviewUser] = $this->getRequestReviews($request, $travel);

        $participants = $this->getRequestParticipants($request) ?? $this->getPersistedParticipants($travel);
        $users = $this->getUsers();

        // Keep old inputs if put to same page
        if ($request->method() == "PUT")
            $request->flashExcept(["participants", "next_review_user"]);

        return view('travels.edit', [
            'travel' => $travel,
            'participants' => $participants,
            'users' => $users,
            'current_review_type' => $currentReview->type,
            'previous_review' => $previousReview,
            'next_review_user' => $nextReviewUser,
            'tabIndex' => $request->input("tabIndex", 0)
        ]);
    }

    // POST /travels: Save new travel request and show summary (validation error: GET /travels/create with old inputs is called)
    function store(Request $request, Travel $travel) {
        // Validate data
        $nextReviewUser = $this->validateDataForReview($request);

        // Create travel
        $travel = $travel->create($request->merge([Travel::APPLICANT_ID => $this->getUserId()])->all());

        // Create participants
        $participantUsers = $this->getParticipantUsers($request->participants);
        $travel->participants()->saveMany($participantUsers);

        // Create initial review
        $this->createReview($travel->id, $this->getUserId(), ReviewType::Request->value, ReviewState::Accepted->value, $request->current_review_comment, 0);

        // Create next review
        $this->createReview($travel->id, $nextReviewUser->id, ReviewType::Verification->value, ReviewState::Pending->value, null, 0);

        return $this->show($request, $travel);
    }

    // PUT /travels/{id}: Save updated travel request and show summary (validation error: GET /travels/{id}/edit with old inputs is called)
    public function update(Request $request, Travel $travel) {
        // Validate data
        $nextReviewUser = $this->validateDataForReview($request);

        // Load current data
        $travel = $this->getPersistedTravel($travel, -1);
        $reviews = $travel->load(Travel::REVIEWS)->reviews()->get();
        $currentReview = $reviews->last();

        // Create travel update event
        $changed = Event::addUpdateEvent(Travel::class, $travel->id, $currentReview->id, $travel, $request->all());

        // Create participants update event
        $changed |= Event::addListEvent(User::class, $travel->id, $currentReview->id,
            $this->getParticipantUsers($this->getPersistedParticipants($travel)),
            $this->getParticipantUsers($this->getRequestParticipants($request))
        );

        // Update current review
        $this->updateReview($currentReview,
            $request->has("action_accept") ? ReviewState::Accepted->value : ReviewState::Declined->value,
            $request->current_review_comment, $changed
        );

        // Create next review if review type is verification or next user is applicant
        if ($currentReview->type != ReviewType::Approval->value || $nextReviewUser->id != $reviews->first()->user_id)
            $this->createReview($travel->id, $nextReviewUser->id, ReviewType::Verification, ReviewState::Pending, null, 0);

        return $this->show($request, $travel);
    }

    // DELETE /travels/{id}: Delete travel withs specified id
    public function destroy(Travel $travel) {
        $travel->delete();

        return redirect()->route('travels.index');
    }

    private function getUserId() {
        return auth()->user()->id;
    }

    private function validateDataForReview(Request $request) {
        $request->validate([
            'title' => 'required|max:255',
            'location' => 'required|max:255',
            'start' => 'required|date',
            'end' => 'required|date',
            'amount' => 'required|decimal:0,2',
            'next_review_user' => 'required|max:255',
            'current_review_comment' => 'max:255',
            'current_review_confirmation' => 'required|accepted'
        ]);

        $nextReviewUser = User::where(User::EMAIL, $request->next_review_user)->first();

        if (!isset($nextReviewUser))
            throw ValidationException::withMessages(["next_review_user" => "Invalid user!"]);

        return $nextReviewUser;
    }

    private function getRequestReviews(Request $request, Travel $travel) {
        $reviews = $travel->load(Travel::REVIEWS)->reviews()->get();
        $currentReview = $reviews->last();
        $firstReview = $reviews->first();
        $previousReview = $reviews->get($reviews->count() - 2, 0);
        $nextReviewUser = $request->next_review_user;

        if ($request->has("action_back_to_applicant"))
            $nextReviewUser = $firstReview->user()->first()->email;
        else if ($request->has("action_back_to_previous_user"))
            $nextReviewUser = $previousReview->user()->first()->email;
        else if ($request->has("action_toggle_review_type")) {
            $currentReview->type = ($currentReview->type == ReviewType::Verification->value)
                ? ReviewType::Approval->value
                : ReviewType::Verification->value;
            $currentReview->save();
        }

        if ($currentReview->type == ReviewType::Approval->value)
            $nextReviewUser = $reviews->get(0)->user()->first()->email;

        return [$currentReview, $previousReview, $nextReviewUser];
    }

    private function getRequestParticipants(Request $request) {
        if (old('participants', null) != null)
            return old('participants');

        if (!isset($request->participants))
            return null;

        $participants = array_values($request->participants); // reindex
        $changeParticipant = $request->action_change_participant;

        if (isset($changeParticipant)) {
            if ($changeParticipant == "*") {
                $participantUser = $request->participant_user;

                if (!in_array($participantUser, $participants))
                    array_push($participants, $participantUser);
            }
            else {
                $participantUser = $changeParticipant;

                $participantUserIndex = array_search($participantUser, $participants);
                if ($participantUserIndex !== false) {
                    array_splice($participants, $participantUserIndex, 1);
                }
            }
        }

        return $participants;
    }

    private function getParticipantUsers($participants) {
        return User::whereIn(USER::EMAIL, $participants)->get();
    }

    function getUsers() {
        return User::all();
    }

    function getPersistedTravel($travel, $reviewId = -1) {
        return Event::getEntityWithState($travel, $reviewId);
    }

    function getPersistedParticipants($travel, $reviewId = -1) {
        return Event::getListWithState(User::class, $travel->participants, $travel->id, $reviewId)
            ->map(function($participant) { return $participant->email; });
    }

    private function createReview($travelId, $userId, $type, $state, $comment, $changed) {
        Review::create([
            Review::TRAVEL_ID => $travelId,
            Review::USER_ID => $userId,
            Review::TYPE => $type,
            Review::STATE => $state,
            Review::CHANGED => $changed,
            Review::COMMENT => $comment
        ]);
    }

    private function updateReview($currentReview, $state, $comment, $changed) {
        $currentReview->state = $state;
        $currentReview->comment = $comment;
        $currentReview->changed = $changed;
        $currentReview->save();
    }
}
