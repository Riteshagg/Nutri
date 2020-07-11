<!doctype html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Recuperar Password - Nutri4Solutions </title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/libs/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/fonts/fontawesome/css/fontawesome-all.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/fonts/circular-std/style.css') }}" rel="stylesheet">
    <style>
        html,
        body {
            height: 100%;
        }

        body {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            padding-top: 40px;
            padding-bottom: 40px;
        }
    </style>
</head>

<body>
<div class="splash-container">
    <div class="card ">
        <div class="card-header text-center">
            <span class="splash-description">Recuperar Password</span>
        </div>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf


                <div class="form-group">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="email" autofocus>

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                </div>

                <button type="submit" class="btn btn-primary btn-lg btn-block">Recuperar Password</button>

            </form>
        </div>
    </div>
</div>

<!-- Optional JavaScript -->
<script src=" {{ asset('assets/vendor/jquery/jquery-3.3.1.min.js') }}"></script>
<script src=" {{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.js') }}"></script>
</body>
</html>