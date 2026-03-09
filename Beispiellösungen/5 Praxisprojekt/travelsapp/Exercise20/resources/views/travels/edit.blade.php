<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __($current_review_type == "VERIFICATION" ? "Verify travel $travel->id" : "Approve travel $travel->id") }}
        </h2>
    </x-slot>
<style>
fieldset > div > label { width: 200px; }

    </style>



    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('travels.edit', ['travel' => $travel->id]) }}">
                        <section class="tab">
                            <input type="radio" name="tabIndex" value="0" id="tab0" hidden @checked(old("tabIndex", $tabIndex) == 0) />
                            <input type="radio" name="tabIndex" value="1" id="tab1" hidden @checked(old("tabIndex", $tabIndex) == 1) />
                            <input type="radio" name="tabIndex" value="2" id="tab2" hidden @checked(old("tabIndex", $tabIndex) == 2) />
                            <header>
                                <nav>
                                    <label for="tab0">Travel</label><label for="tab1">Participants</label><label for="tab2">Previous Review</label>
                                </nav>
                            </header>
                            <article class="tab0">
                                <div>
                                    <label for="title">Title</label>
                                    <input type="text" name="title" value="{{ old('title', $travel->title) }}" class="{{ $errors->has('title') ? 'has-error' : '' }}" />
                                </div>
                                <div>
                                    <label for="location">Location</label>
                                    <input type="text" name="location" value="{{ old('location', $travel->location) }}" list="list_location" class="{{ $errors->has('location') ? 'has-error' : '' }}" />
                                    <datalist id="list_location">
                                        <option>Berlin</option>
                                        <option>München</option>
                                        <option>Hof</option>
                                    </datalist>
                                </div>
                                <div>
                                    <label for="start">Start</label>
                                    <input type="datetime-local" name="start" value="{{ old('start', $travel->start) }}" class="{{ $errors->has('start') ? 'has-error' : '' }}" />
                                </div>
                                <div>
                                    <label for="end">End</label>
                                    <input type="datetime-local" name="end" value="{{ old('end', $travel->end) }}" class="{{ $errors->has('end') ? 'has-error' : '' }}" />
                                </div>
                                <div>
                                    <label for="amount">Amount</label>
                                    <input type="number" name="amount" value="{{ old('amount', $travel->amount) }}" step="0.01" class="{{ $errors->has('amount') ? 'has-error' : '' }}" />
                                </div>
                                <div>
                                    <label for="details">Details</label>
                                    <textarea name="details" class="{{ $errors->has('details') ? 'has-error' : '' }}">{{ old('details', $travel->details) }}</textarea>
                                </div>
                                <footer>
                                    <nav>
                                        <label for="tab1">Next</label>
                                    </nav>
                                </footer>
                            </article>
                            <article class="tab1">
                                <select name="participant_user">
                                    @foreach ($users->filter(fn($el) => $el->email != Auth::user()->email && !collect(old('participants', $participants))->contains($el->email)) as $user)
                                    <option value="{{ $user->email }}">{{ $user->email }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="participants" value="{{json_encode(old('participants', $participants))}}" />
                                <button type="submit" name="action_change_participant" value="*">Add participant</button>
                                <div>
                                    <table>
                                        <thead class="hidden">
                                            <tr>
                                                <th>User</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(empty(old('participants', $participants)))
                                            <tr>
                                                <td>Currently no participants</td>
                                                <td></td>
                                            </tr>
                                            @endif
                                            @foreach (old('participants', $participants) as $participant)
                                            <tr>
                                                <td>{{$participant}}</td>
                                                <td>
                                                    <button type="submit" name="action_change_participant" value="{{$participant}}">Remove participant</button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <footer>
                                    <nav>
                                        <label for="tab0">Previous</label>
                                        <label for="tab2">Next</label>
                                    </nav>
                                </footer>
                            </article>
                            <article class="tab2">
                                <div>
                                    <label for="previous_review_type">Type</label>
                                    <input type="text" name="previous_review_type" value="{{ old('type', $previous_review->type) }}" readonly />
                                </div>
                                <div>
                                    <label for="previous_review_state">State</label>
                                    <input type="text" name="previous_review_state" value="{{ old('type', $previous_review->state) }}" readonly />
                                </div>
                                <div>
                                    <label for="previous_review_comment">Optional comment</label>
                                    <textarea type="text" name="previous_review_comment" rows="10">{{ $previous_review->comment }}</textarea>
                                </div>
                                <footer>
                                    <nav>
                                        <label for="tab1">Previous</label>
                                    </nav>
                                </footer>
                            </article>
                        </section>
                        <br/>
                        <fieldset>
                            <legend>Next review</legend>
                            <div>
                                <label for="next_review_user">User</label>
                                <select name="next_review_user" class="{{ $errors->has('next_review_user') ? 'has-error' : '' }}">
                                    <option value=""></option>
                                    @foreach ($users->filter(function($el) { return $el->email != Auth::user()->email; }) as $user)
                                    <option value="{{ $user->email }}" @selected($user->email == old('next_review_user', $next_review_user))>{{ $user->email }}</option>
                                    @endforeach
                                </select>
                                @if($current_review_type != "APPROVAL")
                                <input type="submit" name="action_back_to_applicant" value="Back to applicant" />
                                <input type="submit" name="action_back_to_previous_user" value="Back to previous user" />
                                @else
                                Travel approval accept or decline is sent back to applicant.
                                @endif
                            </div>
                        </fieldset>
                        <br/>
                        <div>
                            <label for="current_review_comment">Comment</label>
                            <textarea type="text" name="current_review_comment" class="{{ $errors->has('current_review_comment') ? 'has-error' : '' }}">{{ old('current_review_comment') }}</textarea>
                        </div>
                        <br/>
                        <div>
                            <label><input type="checkbox" name="current_review_confirmation"> All information is correct</label>
                        </div>
                        <input type="submit" name="action_accept" value="Accept" class="btn-yes" formaction="{{ route('travels.update', ['travel' => $travel->id]) }}" />
                        <input type="submit" name="action_decline" value="Decline" class="btn-no" formaction="{{ route('travels.update', ['travel' => $travel->id]) }}" />
                        @if(auth()->user()->is_approver)
                        <input type="submit" name="action_toggle_review_type" value="Move to {{ $current_review_type == "VERIFICATION" ? "approval" : "verification" }}" formaction="{{ route('travels.edit', ['travel' => $travel->id]) }}" />
                        @endif
                        @if ($errors->any())
                        <div class="errors">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        @method('PUT')
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

