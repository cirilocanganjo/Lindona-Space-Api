<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Marketplace API') }}</title>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #0a0a0a;
            color: #ededec;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .card {
            max-width: 480px;
            width: 100%;
            text-align: center;
        }

        .badge {
            display: inline-block;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #a1a09a;
            border: 1px solid #3e3e3a;
            border-radius: 999px;
            padding: 0.25rem 0.75rem;
            margin-bottom: 1.25rem;
        }

        h1 {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        p {
            font-size: 0.95rem;
            line-height: 1.6;
            color: #a1a09a;
        }

        .version {
            margin-top: 1.5rem;
            font-size: 0.8rem;
            color: #62605b;
        }

        nav {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            display: flex;
            gap: 0.75rem;
        }

        nav a {
            font-size: 0.85rem;
            color: #ededec;
            text-decoration: none;
            border: 1px solid #3e3e3a;
            border-radius: 6px;
            padding: 0.4rem 1rem;
            transition: border-color 0.15s;
        }

        nav a:hover {
            border-color: #62605b;
        }
    </style>
</head>
<body>
    @if (Route::has('login'))
        <nav>
            @auth
                <a href="{{ url('/dashboard') }}">Dashboard</a>
            @else
                <a href="{{ route('login') }}">Entrar</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Registrar</a>
                @endif
            @endauth
        </nav>
    @endif

    <div class="card">
        <span class="badge">API</span>
        <h1>MARKETPLACE API</h1>
        <p>
            Marketplace que conecta centros comerciais, lojistas e clientes,
            centralizando produtos e compras em uma única plataforma.
        </p>

        <h1>v1.0.0</h1>
    </div>
</body>
</html>
