<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("Edit user $user->id") }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('users.update', ['user' => $user->id]) }}">
                        <div>
                            <label for="name">Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="{{ $errors->has('name') ? 'has-error' : '' }}" />
                        </div>
                        <div>
                            <label for="email">E-Mail</label>
                            <input type="text" name="email" value="{{ old('email', $user->email) }}" class="{{ $errors->has('email') ? 'has-error' : '' }}" />
                        </div>
                        <div>
                            <label for="is_admin">Admin</label>
                            <input type="checkbox" {{ $user->is_admin ? 'checked' : '' }} name="is_admin" id="is_admin" value="1" />
                        </div>
                        <div>
                            <label for="is_verifier">Verifier</label>
                            <input type="checkbox" {{ $user->is_verifier ? 'checked' : '' }} name="is_verifier" id="is_verifier" value="1" />
                        </div>
                        <div>
                            <label for="is_approver">Approver</label>
                            <input type="checkbox" {{ $user->is_approver ? 'checked' : '' }} name="is_approver" id="is_approver" value="1" />
                        </div>
                        <input type="submit" value="Save" />
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




