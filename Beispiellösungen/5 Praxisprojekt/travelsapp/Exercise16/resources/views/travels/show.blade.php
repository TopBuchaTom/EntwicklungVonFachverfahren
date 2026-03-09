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
                    <ul>
                        <li>Title: {{ $travel->title }}</li>
                        <li>Location: {{ $travel->location }}</li>
                        <li>Start: {{ $travel->start }}</li>
                        <li>End: {{ $travel->end }}</li>
                        <li>Amount: {{ $travel->amount }}</li>
                        <li>Details: {{ $travel->details }}</li>
                    </ul>

                    <form method="GET" action="{{ route('travels.edit', ["travel" => $travel->id]) }}">
                        <input type="submit" value="Edit" />
                    </form>
                    <form method="POST" action="{{ route('travels.destroy', ["travel" => $travel->id]) }}">
                        <input type="submit" value="Delete" />
                        @method('DELETE')
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

