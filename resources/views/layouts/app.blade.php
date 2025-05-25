<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @if (app()->getLocale()=="ar") dir="rtl" @endif>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Stock Management System</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https:  //cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery UI CSS -->
    <link href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" rel="stylesheet">
    <!-- Custom CSS -->

    @vite(['resources/js/app.js', 'resources/js/my.js', 'resources/css/app.css'])
</head>

<body class="min-vh-100 d-flex flex-column">
    <header class="bg-dark text-white shadow-sm">
        <nav class="container d-flex justify-content-between align-items-center py-3">
            <h1 class="h3 mb-0 fw-bold">@lang("Stock Management System")</h1>

            <a href="{{ route('dashboard') }}" class="btn btn-outline-light">@lang("Dashboard")</a>
            <select name="selectLocale" id="selectLocale">
                <option @if(app()->getLocale() == 'ar') selected @endif value="ar">ar</option>
                <option @if(app()->getLocale() == 'fr') selected @endif value="fr">fr</option>
                <option @if(app()->getLocale() == 'en') selected @endif value="en">en</option>
                <option @if(app()->getLocale() == 'es') selected @endif value="es">es</option>
            </select>

        </nav>
    </header>

    <main class="container flex-grow-1 py-4">
        @yield('content')
    </main>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
    <script>
        $("#selectLocale").on('change',function(){
            var locale = $(this).val();
            window.location.href = "/changeLocale/"+locale;
        })
    </script>

    <footer class="bg-dark text-white text-center py-3">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} Stock Management System. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>
