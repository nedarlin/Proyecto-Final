<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Odonto Sonrisas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
        <div class="container">
            <a class="navbar-brand fw-semibold" href="{{ route('inicio') }}">Odonto Sonrisas</a>
            <ul class="navbar-nav ms-auto flex-row align-items-center gap-3">
                <li class="nav-item"><a class="nav-link" href="{{ route('inicio') }}#servicios">Servicios</a></li>

                @if (session()->has('paciente_id'))
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-link nav-link">Cerrar sesion</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Iniciar sesion</a></li>
                    <li class="nav-item"><a class="btn btn-primary btn-sm" href="{{ route('registro') }}">Registrarse</a></li>
                @endif
            </ul>
        </div>
    </nav>

    <main class="flex-grow-1 d-flex flex-column">
        @yield('content')
    </main>

    <footer class="border-top text-center text-muted py-3">
        <small>&copy; {{ date('Y') }} Odonto Sonrisas. Todos los derechos reservados.</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>