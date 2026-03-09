<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('users.index') }}">
                        Filter: <input type="text" name="filter_value" value="{{ $filter_value }}" list="list_user" />
                        <datalist id="list_user">
                            @foreach ($users as $user)
                            <option value="{{ $user->name }}">
                            @endforeach
                        </datalist>
                        <select name="filter_criteria" value="{{ $filter_criteria }}">
                            <option value="name" {{ $filter_criteria == 'name' ? 'selected' : '' }}>Name</option>
                            <option value="email" {{ $filter_criteria == 'email' ? 'selected' : '' }}>E-Mail</option>
                        </select>
                        with permission:
                        <input type="checkbox" {{ $filter_isadmin ? 'checked' : '' }} name="filter_isadmin" id="filter_isadmin" value="1" />
                        <label for="filter_isadmin">admin</label>
                        <input type="checkbox" {{ $filter_isverifier ? 'checked' : '' }} name="filter_isverifier" id="filter_isverifier" value="1" />
                        <label for="filter_isverifier">verifier</label>
                        <input type="checkbox" {{ $filter_isapprover ? 'checked' : '' }} name="filter_isapprover" id="filter_isapprover" value="1" />
                        <label for="filter_isapprover">approver</label>


                        <input type="submit" value="Update" />
                        <div>

                        </div>

                        @method('GET')
                        @csrf
                    </form>
                </div>
                <div class="p-6 bg-white border-b border-gray-200">
                    <table>
                        <thead class="hidden">
                            <tr>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>
                                    <a href="{{ route('users.show', ["user" => $user->id]) }}" class="btn">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $users->links() }}
                    <a href="{{ route('users.create') }}" class="btn">Create</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
