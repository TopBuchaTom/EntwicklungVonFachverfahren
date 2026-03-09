<html>
    <head>
        <title>Create User</title>
    </head>
    <body>
        <h1>Create user</h1>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="{{ route('users.store') }}">
                        <div>
                            <label for="name">Name</label>:
                            <input type="text" name="name" value="{{ old('name') }}" class="{{ $errors->has('name') ? 'has-error' : '' }}" />
                        </div>
                        <div>
                            <label for="password">Password</label>:
                            <input type="text" name="password" value="{{ old('password') }}" class="{{ $errors->has('password') ? 'has-error' : '' }}" />
                        </div>
                        <div>
                            <label for="email">E-Mail</label>:
                            <input type="text" name="email" value="{{ old('email') }}" class="{{ $errors->has('email') ? 'has-error' : '' }}" />
                        </div>
                        <div>
                            <label for="is_admin">Admin</label>:
                            <input type="checkbox" name="is_admin" value="1" />
                        </div>
                        <div>
                            <label for="is_verifier">Verifier</label>:
                            <input type="checkbox" name="is_verifier" value="1" />
                        </div>
                        <div>
                            <label for="is_approver">Approver</label>:
                            <input type="checkbox" name="is_approver" value="1" />
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
    </body>
</html>
