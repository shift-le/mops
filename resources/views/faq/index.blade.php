<!DOCTYPE html>
<html>
<head><title>FAQ一覧</title></head>
<body>
    <h1>FAQ一覧</h1>
    <ul>
        @foreach ($faqs as $faq)
            <li>
                <a href="{{ url('/faq/' . $faq['id']) }}">
                    {{ $faq['title'] }}
                </a>
            </li>
        @endforeach
    </ul>
</body>
</html>
