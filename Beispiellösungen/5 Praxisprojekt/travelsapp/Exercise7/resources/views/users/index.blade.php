<html>
    <head>
        <title>Users</title>
    </head>
    <body>
    <h1>Users</h1>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <ul>
                        @foreach ($users as $user)
                        <li>
                            <a href="{{ route('users.show', ["user" => $user->id]) }}">
                                {{ $user->name }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    <a href="{{ route('users.create') }}">Create</a>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>
