{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.coordinator')

@section('title', 'Coordinator | Home')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">DASHBOARD</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Coordinator</li>
          <li class="breadcrumb-item active text-muted">Dashboard</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      {{-- Interns Card --}}
      <div class="col-lg-6 col-md-6 col-12">
        <div class="small-box bg-danger">
          <div class="inner p-3 d-flex flex-column justify-content-center align-items-start">
            <h2 class="fw-medium">{{ $myStudentsCount }}</h2>
            <p>Student Interns</p>
          </div>
          <div class="infobox-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="box-icon" viewBox="0 0 256 256"><path d="M176,207.24a119,119,0,0,0,16-7.73V240a8,8,0,0,1-16,0Zm11.76-88.43-56-29.87a8,8,0,0,0-7.52,14.12L171,128l17-9.06Zm64-29.87-120-64a8,8,0,0,0-7.52,0l-120,64a8,8,0,0,0,0,14.12L32,117.87v48.42a15.91,15.91,0,0,0,4.06,10.65C49.16,191.53,78.51,216,128,216a130,130,0,0,0,48-8.76V130.67L171,128l-43,22.93L43.83,106l0,0L25,96,128,41.07,231,96l-18.78,10-.06,0L188,118.94a8,8,0,0,1,4,6.93v73.64a115.63,115.63,0,0,0,27.94-22.57A15.91,15.91,0,0,0,224,166.29V117.87l27.76-14.81a8,8,0,0,0,0-14.12Z"></path></svg>          </div>
          <a href="#" class="small-box-footer">Manage Interns <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <!-- HTEs -->
      <div class="col-lg-6 col-md-6 col-12">
          <!-- small card -->
          <div class="small-box bg-info"> 
              <div class="inner p-3 d-flex flex-column justify-content-center align-items-start">
                  <h2 class="fw-medium">{{ $totalHtesCount }}</h2>
                  <p>Host Training Establishments</p>
              </div>
              <div class="infobox-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="box-icon" style="position: relative; right: 6px" viewBox="0 0 256 256"><path d="M240,208h-8V72a8,8,0,0,0-8-8H184V40a8,8,0,0,0-8-8H80a8,8,0,0,0-8,8V96H32a8,8,0,0,0-8,8V208H16a8,8,0,0,0,0,16H240a8,8,0,0,0,0-16ZM80,176H64a8,8,0,0,1,0-16H80a8,8,0,0,1,0,16Zm0-32H64a8,8,0,0,1,0-16H80a8,8,0,0,1,0,16Zm64,64H112V168h32Zm-8-64H120a8,8,0,0,1,0-16h16a8,8,0,0,1,0,16Zm0-32H120a8,8,0,0,1,0-16h16a8,8,0,0,1,0,16Zm0-32H120a8,8,0,0,1,0-16h16a8,8,0,0,1,0,16Zm56,96H176a8,8,0,0,1,0-16h16a8,8,0,0,1,0,16Zm0-32H176a8,8,0,0,1,0-16h16a8,8,0,0,1,0,16Zm0-32H176a8,8,0,0,1,0-16h16a8,8,0,0,1,0,16Z"></path></svg>              </div>
              <a href="#" class="small-box-footer">
                  Manage HTEs <i class="fas fa-arrow-circle-right"></i>
              </a>
          </div>
      </div>

      <!-- Internships -->
      <!-- <div class="col-lg-4 col-md-6 col-12">
          <div class="small-box bg-success"> 
              <div class="inner p-3 d-flex flex-column justify-content-center align-items-start">
                  <h2 class="fw-medium">72</h2>
                  <p>Internships</p>
              </div>
              <div class="infobox-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" class="box-icon" viewBox="0 0 256 256"><path d="M152,112a8,8,0,0,1-8,8H112a8,8,0,0,1,0-16h32A8,8,0,0,1,152,112Zm80-40V200a16,16,0,0,1-16,16H40a16,16,0,0,1-16-16V72A16,16,0,0,1,40,56H80V48a24,24,0,0,1,24-24h48a24,24,0,0,1,24,24v8h40A16,16,0,0,1,232,72ZM96,56h64V48a8,8,0,0,0-8-8H104a8,8,0,0,0-8,8Zm120,57.61V72H40v41.61A184,184,0,0,0,128,136,184,184,0,0,0,216,113.61Z"></path></svg>
              </div>
              <a href="#" class="small-box-footer">
                  Manage Internships <i class="fas fa-arrow-circle-right"></i>
              </a>
          </div>
      </div> -->


    </div>
  </div>
</section>
@endsection
