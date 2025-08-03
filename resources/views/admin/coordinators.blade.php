{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.admin')

@section('title', 'Manage Coordinators')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">MANAGE COORDINATORS</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Admin</li>
          <li class="breadcrumb-item active text-muted">Coordinators</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="flex-grow-1" style="max-width: 220px;">
                <input type="search" class="form-control form-control-sm" placeholder="Search..." id="coordinatorSearch">
            </div>
            <div class="d-flex flex-grow-1 justify-content-end p-0">
                <button class="btn btn-outline-success btn-sm d-flex mr-2">
                    <span class="d-none d-sm-inline mr-1">Import</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="table-cta-icon" viewBox="0 0 256 256"><path d="M200,24H72A16,16,0,0,0,56,40V64H40A16,16,0,0,0,24,80v96a16,16,0,0,0,16,16H56v24a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V40A16,16,0,0,0,200,24ZM72,160a8,8,0,0,1-6.15-13.12L81.59,128,65.85,109.12a8,8,0,0,1,12.3-10.24L92,115.5l13.85-16.62a8,8,0,1,1,12.3,10.24L102.41,128l15.74,18.88a8,8,0,0,1-12.3,10.24L92,140.5,78.15,157.12A8,8,0,0,1,72,160Zm56,56H72V192h56Zm0-152H72V40h56Zm72,152H144V192a16,16,0,0,0,16-16v-8h40Zm0-64H160V104h40Zm0-64H160V80a16,16,0,0,0-16-16V40h56Z"></path></svg>                
                </button>
                <a href="{{ route('admin.new_c') }}" class="btn btn-primary btn-sm d-flex">
                    <span>Register</span>
                </a>
            </div>
        </div>

        <div class="card-body table-responsive p-0">
            <table class="table table-bordered text-nowrap mb-0" id="coordinatorsTable">
                <thead class="table-light">
                    <tr>
                        <th>Faculty ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Department</th>
                        <th>HTE Privilege</th>
                        <th style="white-space: nowrap; width: 12%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($coordinators as $coordinator)
                    <tr>
                        <td>{{ $coordinator->faculty_id }}</td>
                        <td>{{ $coordinator->user->fname }} {{ $coordinator->user->lname }}</td>
                        <td>{{ $coordinator->user->email }}</td>
                        <td>{{ $coordinator->user->contact }}</td>
                        <td>{{ $coordinator->department->short_name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge {{ $coordinator->can_add_hte ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} px-3 py-2 rounded-pill">
                                {{ $coordinator->can_add_hte ? 'Allowed' : 'Not Allowed' }}
                            </span>
                        </td>
                        <td class="text-center px-2" style="white-space: nowrap;">
                            <a href="{{ route('coordinators.edit', $coordinator->id) }}" class="btn btn-primary btn-sm">
                                <span class="d-none d-sm-inline">Edit</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="table-action-icon" viewBox="0 0 256 256"><path d="M227.31,73.37,182.63,28.68a16,16,0,0,0-22.63,0L36.69,152A15.86,15.86,0,0,0,32,163.31V208a16,16,0,0,0,16,16H92.69A15.86,15.86,0,0,0,104,219.31L227.31,96a16,16,0,0,0,0-22.63ZM92.69,208H48V163.31l88-88L180.69,120ZM192,108.68,147.32,64l24-24L216,84.68Z"></path></svg>
                            </a>
                            <form action="{{ route('coordinators.destroy', $coordinator->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                    <span class="d-none d-sm-inline">Remove</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="table-action-icon" viewBox="0 0 256 256"><path d="M216,48H176V40a24,24,0,0,0-24-24H104A24,24,0,0,0,80,40v8H40a8,8,0,0,0,0,16h8V208a16,16,0,0,0,16,16H192a16,16,0,0,0,16-16V64h8a8,8,0,0,0,0-16ZM96,40a8,8,0,0,1,8-8h48a8,8,0,0,1,8,8v8H96Zm96,168H64V64H192ZM112,104v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Zm48,0v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Z"></path></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No coordinators found</td>
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

@section('scripts')
<script>
    // Simple search functionality
    document.getElementById('coordinatorSearch').addEventListener('input', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('#coordinatorsTable tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });
</script>
@endsection
</section>
@endsection
