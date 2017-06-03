<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @if (Sentinel::check())
                        <a href="{{ url('/') }}">Home</a>
                        {!! Form::open(['url' => url('logout'),'class'=>'form-inline']) !!}
                           {!! csrf_field() !!}
                          <button class="btn btn-primary btn-lg btn-block register-button" type="submit" >Logout</button>
                       {!! Form::close() !!}
                       
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                        <a href="{{ url('/qrLogin') }}">Qr Login</a>
                        <a href="{{ url('/register') }}">Register</a>
                    @endif
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Laravel Starter page
                    
                </div>
                @if (Sentinel::check() )
                     Your name : {{Sentinel::getUser()->first_name}} <br>
                     Last name : {{Sentinel::getUser()->last_name}} <br>
                     E-mail : {{Sentinel::getUser()->email}} <br>
                    @endif

                <div class="links">
                    <a href="https://github.com/roladn">GitHub</a>
                    <a href="https://rolandalla.com/">Roland Alla</a>
                    <a href="https://www.facebook.com/rolandalla91">Facebook</a>
                </div>
            </div>
        </div>
    </body>
</html>
