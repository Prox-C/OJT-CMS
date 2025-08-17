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
                <svg xmlns="http://www.w3.org/2000/svg" class="box-icon" viewBox="0 0 256 256"><path d="M200,24H56A16,16,0,0,0,40,40V216a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V40A16,16,0,0,0,200,24ZM96,48h64a8,8,0,0,1,0,16H96a8,8,0,0,1,0-16Zm84.81,150.4a8,8,0,0,1-11.21-1.6,52,52,0,0,0-83.2,0,8,8,0,1,1-12.8-9.6A67.88,67.88,0,0,1,101,165.51a40,40,0,1,1,53.94,0A67.88,67.88,0,0,1,182.4,187.2,8,8,0,0,1,180.81,198.4ZM152,136a24,24,0,1,1-24-24A24,24,0,0,1,152,136Z"></path></svg>
          </div>
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
                  <svg xmlns="http://www.w3.org/2000/svg" class="box-icon" viewBox="0 0 256 256"><path d="M248,208H232V96a8,8,0,0,0,0-16H184V48a8,8,0,0,0,0-16H40a8,8,0,0,0,0,16V208H24a8,8,0,0,0,0,16H248a8,8,0,0,0,0-16ZM80,72H96a8,8,0,0,1,0,16H80a8,8,0,0,1,0-16Zm-8,48a8,8,0,0,1,8-8H96a8,8,0,0,1,0,16H80A8,8,0,0,1,72,120Zm64,88H88V160h48Zm8-80H128a8,8,0,0,1,0-16h16a8,8,0,0,1,0,16Zm0-40H128a8,8,0,0,1,0-16h16a8,8,0,0,1,0,16Zm72,120H184V96h32Z"></path></svg>
              </div>
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
