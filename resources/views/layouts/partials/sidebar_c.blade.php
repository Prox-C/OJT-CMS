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
          <a href="#" class="d-block">{{ Auth::user()->fname }} {{ Auth::user()->lname }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="{{route('coordinator.dashboard')}}" class="nav-link {{ Request::is('coordinator/dashboard') ? 'current-page' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg"class="nav-link-icon" viewBox="0 0 256 256"><path d="M120,56v48a16,16,0,0,1-16,16H56a16,16,0,0,1-16-16V56A16,16,0,0,1,56,40h48A16,16,0,0,1,120,56Zm80-16H152a16,16,0,0,0-16,16v48a16,16,0,0,0,16,16h48a16,16,0,0,0,16-16V56A16,16,0,0,0,200,40Zm-96,96H56a16,16,0,0,0-16,16v48a16,16,0,0,0,16,16h48a16,16,0,0,0,16-16V152A16,16,0,0,0,104,136Zm96,0H152a16,16,0,0,0-16,16v48a16,16,0,0,0,16,16h48a16,16,0,0,0,16-16V152A16,16,0,0,0,200,136Z"></path></svg>              
                <p>Dashboard</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{route('coordinator.interns')}}" class="nav-link {{ Request::is('coordinator/interns*') ? 'current-page' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-link-icon" viewBox="0 0 256 256"><path d="M200,24H56A16,16,0,0,0,40,40V216a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V40A16,16,0,0,0,200,24ZM96,48h64a8,8,0,0,1,0,16H96a8,8,0,0,1,0-16Zm84.81,150.4a8,8,0,0,1-11.21-1.6,52,52,0,0,0-83.2,0,8,8,0,1,1-12.8-9.6A67.88,67.88,0,0,1,101,165.51a40,40,0,1,1,53.94,0A67.88,67.88,0,0,1,182.4,187.2,8,8,0,0,1,180.81,198.4ZM152,136a24,24,0,1,1-24-24A24,24,0,0,1,152,136Z"></path></svg>
                <p>Interns</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{{route('coordinator.htes')}}" class="nav-link {{ Request::is('coordinator/htes*') ? 'current-page' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-link-icon" viewBox="0 0 256 256"><path d="M248,208H232V96a8,8,0,0,0,0-16H184V48a8,8,0,0,0,0-16H40a8,8,0,0,0,0,16V208H24a8,8,0,0,0,0,16H248a8,8,0,0,0,0-16ZM80,72H96a8,8,0,0,1,0,16H80a8,8,0,0,1,0-16Zm-8,48a8,8,0,0,1,8-8H96a8,8,0,0,1,0,16H80A8,8,0,0,1,72,120Zm64,88H88V160h48Zm8-80H128a8,8,0,0,1,0-16h16a8,8,0,0,1,0,16Zm0-40H128a8,8,0,0,1,0-16h16a8,8,0,0,1,0,16Zm72,120H184V96h32Z"></path></svg>
                <p>HTEs</p>
            </a>
          </li>

          <!-- <li class="nav-item">
            <a href="/internships" class="nav-link {{ Request::is('internships') ? 'current-page' : '' }}">

                <svg xmlns="http://www.w3.org/2000/svg" class="nav-link-icon" viewBox="0 0 256 256"><path d="M152,112a8,8,0,0,1-8,8H112a8,8,0,0,1,0-16h32A8,8,0,0,1,152,112Zm80-40V200a16,16,0,0,1-16,16H40a16,16,0,0,1-16-16V72A16,16,0,0,1,40,56H80V48a24,24,0,0,1,24-24h48a24,24,0,0,1,24,24v8h40A16,16,0,0,1,232,72ZM96,56h64V48a8,8,0,0,0-8-8H104a8,8,0,0,0-8,8Zm120,57.61V72H40v41.61A184,184,0,0,0,128,136,184,184,0,0,0,216,113.61Z"></path></svg>
                <p>Internships</p>
            </a>
          </li> -->

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>