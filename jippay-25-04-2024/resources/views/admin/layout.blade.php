<!-- resources/views/layout.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
</head>
<body>
    @include('admin/header')

    <div>
        @yield('content')
    </div>

    @include('admin/footer')
</body>
</html>
