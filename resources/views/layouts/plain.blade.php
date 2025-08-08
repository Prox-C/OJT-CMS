<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ config('app.name') }}</title>

    @include('layouts.partials.styles') {{-- Extract styles to a separate file --}}
    
    @stack('styles')
</head>
<body class="hold-transition layout-top-nav">
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
            <div class="container">
                <a href="#" class="navbar-brand">
                    <img src="{{ asset('assets/images/EVSU_Official_Logo.png') }}" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                    <span class="brand-text" style="position: relative; top: -2px">{{ config('app.name') }}</span>
                </a>
                
                <!-- Right navbar links -->
                <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-sign-out-alt mr-1"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /.navbar -->

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Main content -->
            <div class="content">
                <div class="container">
                    @yield('content')
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer
        <footer class="main-footer">
            <div class="container">
                <div class="float-right d-none d-sm-inline">
                    <strong>Version</strong> {{ config('app.version', '1.0.0') }}
                </div>
                <strong>&copy; {{ date('Y') }} {{ config('app.name') }}.</strong> All rights reserved.
            </div>
        </footer>
    </div> -->
    
    @include('layouts.partials.scripts') {{-- Extract scripts to a separate file --}}
</body>
</html>