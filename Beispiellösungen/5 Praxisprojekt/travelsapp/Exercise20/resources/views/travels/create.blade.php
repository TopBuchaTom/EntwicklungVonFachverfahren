<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create travel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('travels.create') }}">
                        <section class="tab">
                            <input type="radio" name="tabIndex" value="0" id="tab0" hidden @checked(old("tabIndex", $tabIndex) == 0) />
                            <input type="radio" name="tabIndex" value="1" id="tab1" hidden @checked(old("tabIndex", $tabIndex) == 1) />
                            <header>
                                <nav>
                                    <label for="tab0">Travel</label><label for="tab1">Participants</label>
                                </nav>
                            </header>
                            <article class="tab0">
                                <div>
                                    <label for="title">Title</label>
                                    <input type="text" name="title" value="{{ old('title') }}" class="{{ $errors->has('title') ? 'has-error' : '' }}" />
                                </div>
                                <div>
                                    <label for="location">Location</label>
                                    <input type="text" name="location" value="{{ old('location') }}" list="list_location" class="{{ $errors->has('location') ? 'has-error' : '' }}" />
                                    <datalist id="list_location">
                                        <option>Berlin</option>
                                        <option>München</option>
                                        <option>Hof</option>
                                    </datalist>
                                </div>
                                <div>
                                    <label for="start">Start</label>
                                    <input type="datetime-local" name="start" value="{{ old('start') }}" class="{{ $errors->has('start') ? 'has-error' : '' }}" />
                                </div>
                                <div>
                                    <label for="end">End</label>
                                    <input type="datetime-local" name="end" value="{{ old('end') }}" class="{{ $errors->has('end') ? 'has-error' : '' }}" />
                                </div>
                                <div>
                                    <label for="amount">Amount</label>
                                    <input type="number" name="amount" value="{{ old('amount') }}" step="0.01" class="{{ $errors->has('amount') ? 'has-error' : '' }}" />
                                </div>
                                <div>
                                    <label for="details">Details</label>
                                    <textarea name="details" class="{{ $errors->has('details') ? 'has-error' : '' }}">{{ old('details') }}</textarea>
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
                                    <option value="{{ $user->email }}" @selected($user->email == old('next_review_user'))>{{ $user->email }}</option>
                                    @endforeach
                                </select>
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
                        <input type="submit" name="action_request_approval" value="Request travel" formaction="{{ route('travels.store') }}" />

                        @if ($errors->any())
                        <div class="errors">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
