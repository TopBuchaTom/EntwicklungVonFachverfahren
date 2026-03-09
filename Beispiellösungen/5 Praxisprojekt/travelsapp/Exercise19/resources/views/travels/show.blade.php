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
                    </ul>
                    <br/>
                    <a class="btn" href="{{ route('travels.edit', ["travel" => $travel->id]) }}">Edit</a>
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
