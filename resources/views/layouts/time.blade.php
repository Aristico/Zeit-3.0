<!doctype html>
<html lang="de">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/css/all.css">

    <title>Schedlr - Erfasse Deine Arbeitszeit digital</title>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="/">Schedulr</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse navbar-right" id="navbarNavAltMarkup">
        <div class="navbar-nav ml-auto">

          @if(Auth::check())
            <li class="nav-item dropdown mr-auto ">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Zeitkonto
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="{{route('entries.index.month', ['year'=>\Carbon\Carbon::now()->format('Y'), 'month'=>\Carbon\Carbon::now()->format('m')])}}">Aktueller Monat</a>
                <a class="dropdown-item" href="{{route('entries.index.balances')}}">Monatsübersicht</a>
              </div>
            </li>
            <li class="nav-item dropdown mr-auto ">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Einstellungen
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="{{route('user.edit')}}">Informationen Bearbeiten</a>
                <a class="dropdown-item" href="{{route('schedule.edit')}}">Arbeitszeiten Bearbeiten</a>
              </div>
            </li>
            <a class="nav-item nav-link" href="{{route('logout')}}">Logout</a>
          @else
            <a class="nav-item nav-link" href="{{route('user.create')}}">Registrieren</a>
            <a class="nav-item nav-link" href="{{route('login')}}">Login</a>
          @endif
        </div>
      </div>
    </nav>

    <div class="container">


      <br>


        @yield('content')
    </div>
    <script src="/js/all.js"></script>
  </body>
</html>