{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'New Coordinator')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">REGISTER COORDINATOR</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Admin</li>
          <li class="breadcrumb-item active text-muted">Coordinator</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Register New Coordinator</h3>
      </div>
      <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form id="coordinatorForm" method="POST" action="{{ route('admin.register_c') }}">
            @csrf
            
            <!-- Row 1: First Name (col-6), Last Name (col-6) -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="fname">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="fname" name="fname" 
                            placeholder="e.g. Bonifacio" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="lname">Last Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="lname" name="lname" 
                            placeholder="e.g. Salvador" required>
                    </div>
                </div>
            </div>

            <!-- Row 2: Email (col-6), Contact (col-6) -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" 
                            placeholder="e.g. juan.delacruz@evsu.edu.ph" required>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="contact">Contact Number <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control" id="contact" name="contact" 
                            placeholder="e.g. 09507395757" required pattern="[0-9]{11}">
                    </div>
                </div>
            </div>

            <!-- Row 3: Faculty ID (col-3), HTE Privilege (col-3), Department (col-6) -->
            <div class="row mt-3">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="faculty_id">Faculty ID <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="faculty_id" name="faculty_id" 
                            placeholder="e.g. 2016-70707" required pattern="\d{4}-\d{5}">
                        <small class="form-text text-muted">Format: XXXX-XXXXX</small>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="can_add_hte">HTE Privilege <span class="text-danger">*</span></label>
                        <select class="form-control" id="can_add_hte" name="can_add_hte" required>
                            <option value="0" selected>Not Allowed</option>
                            <option value="1">Allowed</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="dept_id">Department <span class="text-danger">*</span></label>
                        <select class="form-control" id="dept_id" name="dept_id" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->dept_id }}">{{ $department->dept_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Hidden field for default profile picture -->
            <input type="hidden" name="pic" value="profile_pics/profile.jpg">

            <!-- Footer with buttons -->
            <div class="card-footer d-flex justify-content-between">
                <div>
                    <button type="button" class="btn btn-secondary mr-2" onclick="window.history.back()">
                        Cancel
                    </button>
                    <button type="reset" class="btn btn-outline-secondary">
                        Reset
                    </button>
                </div>
                <button type="submit" class="ml-auto btn btn-primary">
                    Register Coordinator
                </button>
            </div>
        </form>

      </div>
    </div>
  </div>
</section>
@endsection
