<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Travels') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="GET" action="{{ route('travels.index') }}">
                        <input type="hidden" name="review_type" value="{{ $review_type }}" />
                        <select name="review_state">
                            <option value="" @selected($review_state == "")>All</option>
                            <option value="PENDING" @selected($review_state == "PENDING")>Pending</option>
                            <option value="ACCEPTED" @selected($review_state == "ACCEPTED")>Accepted</option>
                            <option value="DECLINED" @selected($review_state == "DECLINED")>Declined</option>
                        </select>
                        <input type="submit" value="Update">
                    </form>
                    <br/>
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Location</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Amount</th>
                                <th>State</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($travels as $travel)
                            <tr>
                                <td>{{ $travel->title }}</td>
                                <td>{{ $travel->location }}</td>
                                <td>{{ $travel->start }}</td>
                                <td>{{ $travel->end }}</td>
                                <td>
                                    <div class="tooltip">{{ $travel->amount }}
                                        <span class="tooltiptext">{{ $travel->details }}</span>
                                    </div>
                                </td>
                                <td>{{ $travel->reviews()->get()->last()->state }}</td>
                                <td>
                                    <a href="{{ route('travels.show', ["travel" => $travel->id]) }}" class="btn">
                                        View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $travels->links() }}
                    @if($review_type == "REQUEST")
                    <a href="{{ route('travels.create') }}" class="btn">Create</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
