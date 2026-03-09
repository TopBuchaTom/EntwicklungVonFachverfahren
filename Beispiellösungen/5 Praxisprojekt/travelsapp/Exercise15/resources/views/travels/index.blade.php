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
                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Location</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Amount</th>
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
                                <td>{{ $travel->amount }} {{ $travel->details }}</td>
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
                    <a href="{{ route('travels.create') }}" class="btn">Create</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
