<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
      <link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Bootstrap 5 theme fix (optional but recommended) -->
    <style>
        .select2-container .select2-selection--multiple {
        min-height: 38px;
        border: 1px solid #ced4da;
        border-radius: .375rem;
        }

        svg {
            overflow: hidden;
            vertical-align: middle;
            / font-size: 5px; /
            width: 25px;
            height: 25px;
            margin-bottom: 5px;
        }
        .leading-5{margin: 18px 0px;}
        .btn-danger, .swal2-modal .swal2-actions button.swal2-cancel {
            color: #fff;
            background-color: #f00!important;
            border-color: #ff0040!important;
        }
            .btn-primary:hover, .fc .fc-toolbar.fc-header-toolbar .fc-toolbar-chunk .fc-button-group .fc-button.fc-button-active:hover, .swal2-modal .swal2-actions button.swal2-confirm:hover, .wizard>.actions a:hover {
            color: #fff;
            background-color: #e67825!important;
            border-color: #e67825!important;
        }
        .sidebar .sidebar-body .nav .nav-item.active .nav-link {
            color: #e67825!important;
        }
        #app > main > div > div > nav > div.flex.gap-2.items-center.justify-between.sm\:hidden {
            display: none !important;
        }
        .tasklist
        {
            background: black !important; 
            color: #fff !important; 
        }
        
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                        <li> <a  href="{{ route('tasks.index') }}" class=" btn btn-primary nav-link tasklist">
                                Tasks
                            </a></li>
                            <li class="nav-item dropdown">
                           
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <!-- jQuery (required for Select2) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function () {
  $('#usersSelect').select2({
    placeholder: "Select users to assign",
    allowClear: true,
    width: '100%'
  });
});
</script>

<script>
toastr.options = {
  "closeButton": true,
  "progressBar": true,
  "positionClass": "toast-top-right",
  "timeOut": "3000",
  "extendedTimeOut": "1000",
  "preventDuplicates": true
};
</script>
@if(session('success'))
<script>
  toastr.success("{{ session('success') }}");
</script>
@endif

@if(session('error'))
<script>
  toastr.error("{{ session('error') }}");
</script>
@endif

@if ($errors->any())
<script>
  toastr.error("{{ $errors->first() }}");
</script>
@endif
@stack('scripts')

</body>
</html>
