{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.hte')

@section('title', 'HTE | Home')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">DASHBOARD</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Student</li>
          <li class="breadcrumb-item active text-muted">Dashboard</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      {{-- Status Card --}}
      <div class="col-lg-4 col-md-6 col-12">
        <div class="small-box bg-danger">
          <div class="inner p-3 d-flex flex-column justify-content-center align-items-start">
            <h2 class="fw-medium">Pending</h2>
            <p>Status</p>
          </div>
          <div class="infobox-icon">
            <svg xmlns="http://www.w3.org/2000/svg" class="box-icon" viewBox="0 0 256 256"><path d="M112,120a16,16,0,1,1-16-16A16,16,0,0,1,112,120ZM232,56V200a16,16,0,0,1-16,16H40a16,16,0,0,1-16-16V56A16,16,0,0,1,40,40H216A16,16,0,0,1,232,56ZM135.75,166a39.76,39.76,0,0,0-17.19-23.34,32,32,0,1,0-45.12,0A39.84,39.84,0,0,0,56.25,166a8,8,0,0,0,15.5,4c2.64-10.25,13.06-18,24.25-18s21.62,7.73,24.25,18a8,8,0,1,0,15.5-4ZM200,144a8,8,0,0,0-8-8H152a8,8,0,0,0,0,16h40A8,8,0,0,0,200,144Zm0-32a8,8,0,0,0-8-8H152a8,8,0,0,0,0,16h40A8,8,0,0,0,200,112Z"></path></svg>          
          </div>
          <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <!-- Docs -->
      <div class="col-lg-4 col-md-6 col-12">
          <!-- small card -->
          <div class="small-box bg-info"> 
              <div class="inner p-3 d-flex flex-column justify-content-center align-items-start">
                  <h2 class="fw-medium">0 out of 8</h2>
                  <p>Requirements</p>
              </div>
              <div class="infobox-icon">
                  <svg xmlns="http://www.w3.org/2000/svg" class="box-icon" viewBox="0 0 256 256"><path d="M213.66,82.34l-56-56A8,8,0,0,0,152,24H56A16,16,0,0,0,40,40V216a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V88A8,8,0,0,0,213.66,82.34ZM160,176H96a8,8,0,0,1,0-16h64a8,8,0,0,1,0,16Zm0-32H96a8,8,0,0,1,0-16h64a8,8,0,0,1,0,16Zm-8-56V44l44,44Z"></path></svg>
              </div>
              <a href="#" class="small-box-footer">
                  More info <i class="fas fa-arrow-circle-right"></i>
              </a>
          </div>
      </div>

      <!-- My Internship -->
      <div class="col-lg-4 col-md-6 col-12">
          <div class="small-box bg-success"> 
              <div class="inner p-3 d-flex flex-column justify-content-center align-items-start">
                  <h2 class="fw-medium">0 out of 22</h2>
                  <p>Weekly Reports</p>
              </div>
              <div class="infobox-icon">
                <svg xmlns="http://www.w3.org/2000/svg" class="box-icon" viewBox="0 0 256 256"><path d="M208,32H48A16,16,0,0,0,32,48V208a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V48A16,16,0,0,0,208,32ZM117.66,149.66l-32,32a8,8,0,0,1-11.32,0l-16-16a8,8,0,0,1,11.32-11.32L80,164.69l26.34-26.35a8,8,0,0,1,11.32,11.32Zm0-64-32,32a8,8,0,0,1-11.32,0l-16-16A8,8,0,0,1,69.66,90.34L80,100.69l26.34-26.35a8,8,0,0,1,11.32,11.32ZM192,168H144a8,8,0,0,1,0-16h48a8,8,0,0,1,0,16Zm0-64H144a8,8,0,0,1,0-16h48a8,8,0,0,1,0,16Z"></path></svg>
              </div>
              <a href="#" class="small-box-footer">
                  More info <i class="fas fa-arrow-circle-right"></i>
              </a>
          </div>
      </div>


    </div>
  </div>
</section>
@endsection
