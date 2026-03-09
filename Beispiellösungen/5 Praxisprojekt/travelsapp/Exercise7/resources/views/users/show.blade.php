<html>
    <head>
        <title>Show user {{ $user->id }}</title>
    </head>
    <body>
    <h1>Show user {{ $user->id }}</h1>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <ul>
                        <li>Name: {{ $user->name }}</li>
                        <li>E-Mail: {{ $user->email }}</li>
                        <li>Created at: {{ $user->created_at }}</li>
                        <li>Updated at: {{ $user->updated_at }}</li>
                    </ul>

                    <form method="GET" action="{{ route('users.edit', ["user" => $user->id]) }}">
                        <input type="submit" value="Edit" />
                    </form>
                    <form method="POST" action="{{ route('users.destroy', ["user" => $user->id]) }}">
                        <input type="submit" value="Delete" />
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
    </body>
</html>
