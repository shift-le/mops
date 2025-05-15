<!DOCTYPE html>
<html>
<head><title>掲示板一覧</title></head>
<body>
    <h1>掲示板一覧</h1>
    <ul>
        @foreach ($posts as $post)
            <li>
                <a href="{{ url('/board/' . $post['id']) }}">
                    {{ $post['title'] }}
                </a>
            </li>
        @endforeach
    </ul>
</body>
</html>
