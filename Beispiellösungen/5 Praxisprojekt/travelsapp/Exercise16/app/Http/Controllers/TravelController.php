<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\ReviewState;
use App\Models\ReviewType;
use App\Models\Travel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TravelController extends Controller
{
    public function index(Request $request)
    {
        $query = Travel::query();

        return view('travels.index', [
            'travels' => $query->paginate(10),
        ]);
    }

    public function create(Request $request)
    {
        $participants = $this->getRequestParticipants($request) ?? [];
        $users = $this->getUsers();

        // Keep old inputs if post to same page
        if ($request->method() == "POST")
            $request->flashExcept(["participants", "next_review_user"]);

        return view('travels.create', [
            'participants' => $participants,
            'users' => $users
        ]);
    }

    public function store(Request $request, Travel $travel)
    {
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

    public function show(Request $request, Travel $travel)
    {
        return view('travels.show', ['travel' => $travel]);
    }

    public function edit(Request $request, Travel $travel)
    {
        return view('travels.edit', ['travel' => $travel]);
    }

    public function update(Request $request, Travel $travel) {
        $travel->update($request->all());

        return redirect()->route('travels.show', ['travel' => $travel]);
    }

    public function destroy(Travel $travel)
    {
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
        return User::whereIn(User::EMAIL, $participants)->get();
    }

    function getUsers() {
        return User::all();
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
}
