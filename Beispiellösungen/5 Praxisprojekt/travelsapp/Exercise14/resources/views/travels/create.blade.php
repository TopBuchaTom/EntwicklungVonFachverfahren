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
                    <form method="POST" action="{{ route('travels.store') }}">
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
                        <input type="submit" name="saveItem" value="Save" />
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

