<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация безопасника</title>
    <script src="https://unpkg.com/imask"></script>
    @vite('resources/css/candidatesProfiles/style.css')
</head>
<body>
    @include('profile-candidates.layout.header')
    <main>
        @yield("content")
    </main>
</body>
</html>
