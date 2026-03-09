<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Details for travel $travel->id") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <dialog @if (request()->has("review")) open @endif>
                        <header>
                            View of workflow state {{$reviewIndex}}
                        </header>
                        <form>
                            <ul>
                                <li>Title: {{ $reviewTravel->title }}</li>
                                <li>Location: {{ $reviewTravel->location }}</li>
                                <li>Start: {{ $reviewTravel->start }}</li>
                                <li>End: {{ $reviewTravel->end }}</li>
                                <li>Amount: {{ $reviewTravel->amount }}</li>
                                <li>Details: {{ $reviewTravel->details }}</li>
                                <li>
                                    Participants
                                    <ul>
                                        @if(empty($reviewParticipants))
                                        <li>None</li>
                                        @endif
                                        @foreach ($reviewParticipants as $participant)
                                        <li>{{$participant}}</li>
                                        @endforeach
                                    </ul>
                                </li>
                                <li>State: {{ $reviewTravel->reviews()->get()->last()->state }}</li>
                                <li>
                                    <details>
                                        <summary>Change events</summary>
                                        <ul>
                                            @if($reviewEvents->isEmpty())
                                            <li>None</li>
                                            @endif
                                            @foreach ($reviewEvents as $event)
                                            <li>{{$event->action_type}} {{$event->entity}}:
                                                @foreach (json_decode($event->data) as $key => $value)
                                                {{$key}} => {{$value}};
                                                @endforeach
                                            </li>
                                            @endforeach
                                        </ul>
                                    </details>
                                </li>
                            </ul>
                            <footer>
                                <input type="submit" formmethod="dialog" value="Close">
                            </footer>
                        </form>
                    </dialog>

                    Current state
                    <ul>
                        <li>Title: {{ $travel->title }}</li>
                        <li>Location: {{ $travel->location }}</li>
                        <li>Start: {{ $travel->start }}</li>
                        <li>End: {{ $travel->end }}</li>
                        <li>Amount: {{ $travel->amount }}</li>
                        <li>Details: {{ $travel->details }}</li>
                        <li>
                            Participants
                            <ul>
                                @if(empty($participants))
                                <li>None</li>
                                @endif
                                @foreach ($participants as $participant)
                                <li>{{$participant}}</li>
                                @endforeach
                            </ul>
                        </li>
                        <li>State: {{ $travel->reviews()->get()->last()->state }}</li>
                        <li>
                            <details>
                                <summary>Change events</summary>
                                <ul>
                                    @if($events->isEmpty())
                                    <li>None</li>
                                    @endif
                                    @foreach ($events as $event)
                                    <li>{{$event->action_type}} {{$event->entity}}:
                                        @foreach (json_decode($event->data) as $key => $value)
                                        {{$key}} => {{$value}};
                                        @endforeach
                                    </li>
                                    @endforeach
                                </ul>
                            </details>
                        </li>
                    </ul>
                    <br/>
                    <a href="{{ route('travels.edit', ["travel" => $travel->id]) }}">Edit</a>
                    <br/>
                    <br/>

                    Workflow:
                    <table>
                    @foreach ($travel->reviews()->get() as $review)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $review->type }}</td>
                        <td>{{ $review->state }}</td>
                        <td>{{ $review->user->name }}</td>
                        <td>{{ $review->changed ? "CHANGED" : "UNCHANGED" }}</td>
                        <td>{{ $review->created_at }}</td>
                        <td>
                            <a href="{{ route('travels.show', ["travel" => $travel->id, "review" => $review->id]) }}" class="btn">View</a>
                            @if($review->state == "PENDING" && $review->user_id == auth()->user()->id && $review->type != "REQUEST")
                            @endif
                        </td>
                    </tr>
                    @if(isset($review->comment))
                    <tr>
                        <td></td>
                        <td colspan="6">Comment: {{ $review->comment }}</td>
                    </tr>
                    @endif
                    @endforeach
                    </table>

                    <br/>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
