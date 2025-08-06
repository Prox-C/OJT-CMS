  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a class="brand-link">
      <img src="{{ asset('assets/images/EVSU_Official_Logo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">OJT-CMS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('storage/' . auth()->user()->pic) }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="" class="d-block">{{ Auth::user()->fname }} {{ Auth::user()->lname }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="{{route('hte.dashboard')}}" class="nav-link {{ Request::is('hte/dashboard') ? 'current-page' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg"class="nav-link-icon" viewBox="0 0 256 256"><path d="M120,56v48a16,16,0,0,1-16,16H56a16,16,0,0,1-16-16V56A16,16,0,0,1,56,40h48A16,16,0,0,1,120,56Zm80-16H152a16,16,0,0,0-16,16v48a16,16,0,0,0,16,16h48a16,16,0,0,0,16-16V56A16,16,0,0,0,200,40Zm-96,96H56a16,16,0,0,0-16,16v48a16,16,0,0,0,16,16h48a16,16,0,0,0,16-16V152A16,16,0,0,0,104,136Zm96,0H152a16,16,0,0,0-16,16v48a16,16,0,0,0,16,16h48a16,16,0,0,0,16-16V152A16,16,0,0,0,200,136Z"></path></svg>              
                <p>Dashboard</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{route('intern.docs')}}" class="nav-link {{ Request::is('intern/docs*') ? 'current-page' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-link-icon" viewBox="0 0 256 256"><path d="M200,24H56A16,16,0,0,0,40,40V216a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V40A16,16,0,0,0,200,24ZM96,48h64a8,8,0,0,1,0,16H96a8,8,0,0,1,0-16Zm84.81,150.4a8,8,0,0,1-11.21-1.6,52,52,0,0,0-83.2,0,8,8,0,1,1-12.8-9.6A67.88,67.88,0,0,1,101,165.51a40,40,0,1,1,53.94,0A67.88,67.88,0,0,1,182.4,187.2,8,8,0,0,1,180.81,198.4ZM152,136a24,24,0,1,1-24-24A24,24,0,0,1,152,136Z"></path></svg>                
                <p>Interns</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="/my-reports" class="nav-link {{ Request::is('my-reports') ? 'current-page' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-link-icon" viewBox="0 0 256 256"><path d="M208,32H184V24a8,8,0,0,0-16,0v8H88V24a8,8,0,0,0-16,0v8H48A16,16,0,0,0,32,48V208a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V48A16,16,0,0,0,208,32ZM84,184a12,12,0,1,1,12-12A12,12,0,0,1,84,184Zm44,0a12,12,0,1,1,12-12A12,12,0,0,1,128,184Zm0-40a12,12,0,1,1,12-12A12,12,0,0,1,128,144Zm44,40a12,12,0,1,1,12-12A12,12,0,0,1,172,184Zm0-40a12,12,0,1,1,12-12A12,12,0,0,1,172,144Zm36-64H48V48H72v8a8,8,0,0,0,16,0V48h80v8a8,8,0,0,0,16,0V48h24Z"></path></svg>                
                <p>Schedule</p>
            </a>
          </li>

          <!-- <li class="nav-item">
            <a href="/my-internship" class="nav-link {{ Request::is('my-internship') ? 'current-page' : '' }}">

                <svg xmlns="http://www.w3.org/2000/svg" class="nav-link-icon" viewBox="0 0 256 256"><path d="M152,112a8,8,0,0,1-8,8H112a8,8,0,0,1,0-16h32A8,8,0,0,1,152,112Zm80-40V200a16,16,0,0,1-16,16H40a16,16,0,0,1-16-16V72A16,16,0,0,1,40,56H80V48a24,24,0,0,1,24-24h48a24,24,0,0,1,24,24v8h40A16,16,0,0,1,232,72ZM96,56h64V48a8,8,0,0,0-8-8H104a8,8,0,0,0-8,8Zm120,57.61V72H40v41.61A184,184,0,0,0,128,136,184,184,0,0,0,216,113.61Z"></path></svg>
                <p>Internship</p>
            </a>
          </li> -->

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>