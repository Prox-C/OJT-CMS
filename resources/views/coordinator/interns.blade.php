{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.coordinator')

@section('title', 'Manage Interns')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">MANAGE INTERNS</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Coordinator</li>
          <li class="breadcrumb-item active text-muted">Interns</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
        <div class="container-fluid">
          <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
              <div class="flex-grow-1" style="max-width: 220px;">
                  <input type="search" class="form-control form-control-sm" placeholder="Search...">
              </div>
              <div class="d-flex flex-grow-1 justify-content-end p-0">
                  <a class="btn btn-outline-success btn-sm d-flex mr-2" data-toggle="modal" data-target="#importModal">
                      <span class="d-none d-sm-inline mr-1">
                          Import
                      </span>
                      <svg xmlns="http://www.w3.org/2000/svg" class="table-cta-icon" viewBox="0 0 256 256">
                          <path d="M200,24H72A16,16,0,0,0,56,40V64H40A16,16,0,0,0,24,80v96a16,16,0,0,0,16,16H56v24a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V40A16,16,0,0,0,200,24ZM72,160a8,8,0,0,1-6.15-13.12L81.59,128,65.85,109.12a8,8,0,0,1,12.3-10.24L92,115.5l13.85-16.62a8,8,0,1,1,12.3,10.24L102.41,128l15.74,18.88a8,8,0,0,1-12.3,10.24L92,140.5,78.15,157.12A8,8,0,0,1,72,160Zm56,56H72V192h56Zm0-152H72V40h56Zm72,152H144V192a16,16,0,0,0,16-16v-8h40Zm0-64H160V104h40Zm0-64H160V80a16,16,0,0,0-16-16V40h56Z"></path>
                      </svg>                
                  </a>

                  <a href="{{ route('coordinator.new_i') }}" class="btn btn-primary btn-sm d-flex">
                      <span>Register</span>
                  </a>
              </div>
              </div>      


              <div class="card-body table-responsive p-0">
              <table class="table table-bordered text-nowrap mb-0">
                  <thead class="table-light">
                  <tr>
                      <th width="10%">Student ID</th>
                      <th>Name</th>
                      <th>Program</th>
                      <th width="5%">Section</th>
                      <th width="5%">Status</th>
                      <th style="white-space: nowrap; width: 12%">Actions</th>
                  </tr>
                  </thead>
                    <tbody>
                      @forelse ($interns as $intern)
                      <tr>
                          <td class="align-middle">{{ $intern->student_id }}</td>
                          <td class="align-middle">
                              <img src="{{ asset('storage/' . $intern->user->pic) }}" 
                                  alt="Profile Picture" 
                                  class="rounded-circle me-2 table-pfp" 
                                  width="30" height="30">
                              {{ $intern->user->fname }} {{ $intern->user->lname }}
                          </td>                          
                          <td class="align-middle">{{ $intern->department->dept_name ?? 'N/A' }}</td>
                          <td class="align-middle text-center">{{ $intern->year_level }}{{ strtoupper($intern->section) }}</td>
                          <td class="align-middle text-center">
                              @php
                                  $status = strtolower($intern->status);
                                  $badgeClass = match($status) {
                                      'incomplete' => 'bg-danger-subtle text-danger',
                                      'pending' => 'bg-warning-subtle text-warning',
                                      'endorsed' => 'bg-success-subtle text-success',
                                      default => 'bg-secondary'
                                  };
                              @endphp
                              <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill w-100">{{ ucfirst($intern->status) }}</span>
                          </td>
                          <td class="text-center px-2 align-middle" style="white-space: nowrap;">
                              <a href="#" class="btn btn-primary btn-sm">
                                  <span class="d-none d-sm-inline">View</span>
                                  <svg xmlns="http://www.w3.org/2000/svg" class="table-action-icon" viewBox="0 0 256 256"><path d="M208,32H48A16,16,0,0,0,32,48V208a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V48A16,16,0,0,0,208,32Zm0,176H48V48H208ZM90.34,165.66a8,8,0,0,1,0-11.32L140.69,104H112a8,8,0,0,1,0-16h48a8,8,0,0,1,8,8v48a8,8,0,0,1-16,0V115.31l-50.34,50.35a8,8,0,0,1-11.32,0Z"></path></svg>                              </a>
                              <a href="#" class="btn btn-danger btn-sm">
                                  <span class="d-none d-sm-inline">Remove</span>
                                  <svg xmlns="http://www.w3.org/2000/svg" class="table-action-icon" viewBox="0 0 256 256"><path d="M216,48H176V40a24,24,0,0,0-24-24H104A24,24,0,0,0,80,40v8H40a8,8,0,0,0,0,16h8V208a16,16,0,0,0,16,16H192a16,16,0,0,0,16-16V64h8a8,8,0,0,0,0-16ZM96,40a8,8,0,0,1,8-8h48a8,8,0,0,1,8,8v8H96Zm96,168H64V64H192ZM112,104v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Zm48,0v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Z"></path></svg>                              </a>
                          </td>
                      </tr>
                      @empty
                      <tr>
                          <td colspan="6" class="text-center text-muted">No intern data found.</td>
                      </tr>
                      @endforelse
                    </tbody>     
              </table>
              </div>

              <div class="card-footer clearfix">
              <ul class="pagination pagination-sm m-0 float-end">
                  <li class="page-item"><a class="page-link" href="#">«</a></li>
                  <li class="page-item"><a class="page-link" href="#">1</a></li>
                  <li class="page-item"><a class="page-link" href="#">2</a></li>
                  <li class="page-item"><a class="page-link" href="#">3</a></li>
                  <li class="page-item"><a class="page-link" href="#">»</a></li>
              </ul>
              </div>
          </div>
        </div>

  </div>
  <!-- Import Modal -->
  <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content rounded-3 overflow-hidden">
              <div class="modal-header bg-white text-dark">
                  <h5 class="modal-title" id="importModalLabel">Import Interns</h5>
                  <button type="button" class="close text-muted" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <form id="importForm" action="{{ route('coordinator.import_interns') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" name="coordinator_id" value="{{ auth()->user()->coordinator->id }}">
                  <input type="hidden" name="dept_id" value="{{ auth()->user()->coordinator->dept_id }}">
                  
                  <div class="modal-body">
                      <div class="mb-3">
                          <label for="importFile" class="form-label">Excel File</label>
                          <input class="form-control" type="file" id="importFile" name="import_file" accept=".xlsx,.xls,.csv" required>
                          <div class="form-text">Download the <a href="{{ asset('templates/intern_import_template.xlsx') }}" download>import template</a> for reference</div>
                      </div>
                      
                      <div class="alert bg-success-subtle">
                          <strong>File Requirements:</strong>
                          <ul class="mb-0">
                              <li>File must be in Excel format (.xlsx, .xls, or .csv)</li>
                              <li>First row should contain headers matching the template</li>
                              <li>Required fields: First Name, Last Name, Email, Contact, Student ID, Birthdate, Year Level, Section, Academic Year, Semester</li>
                          </ul>
                      </div>
                      
                      <div id="importProgress" class="d-none">
                          <div class="progress mb-3">
                              <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                          </div>
                          <div class="text-center">
                              <div class="spinner-border text-primary" role="status">
                                  <span class="sr-only">Loading...</span>
                              </div>
                              <p class="mt-2 mb-0" id="progressText">Processing import...</p>
                          </div>
                      </div>
                      
                      <div id="importResults" class="d-none mt-3">
                          <h5>Import Summary</h5>
                          <div class="alert alert-success">
                              <strong>Successfully registered:</strong> <span id="successCount">0</span> interns
                          </div>
                          <div class="alert alert-danger">
                              <strong>Failed to register:</strong> <span id="failCount">0</span> interns
                          </div>
                          
                          <div id="failDetails" class="d-none">
                              <h6>Error Details:</h6>
                              <div class="table-responsive">
                                  <table class="table table-sm table-bordered">
                                      <thead>
                                          <tr>
                                              <th>Row</th>
                                              <th>Student ID</th>
                                              <th>Name</th>
                                              <th>Error</th>
                                          </tr>
                                      </thead>
                                      <tbody id="failDetailsBody"></tbody>
                                  </table>
                              </div>
                          </div>
                      </div>
                  </div>
                  
                  <div class="modal-footer">
                      <button type="submit" id="importSubmit" class="btn btn-success">Import</button>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
              </form>
          </div>
      </div>
  </div>

</section>
@endsection
