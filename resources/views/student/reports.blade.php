{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.intern')

@section('title', 'Intern | Home')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">WEEKLY REPORTS</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Intern</li>
          <li class="breadcrumb-item active text-muted">Reports</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid d-flex align-items-center justify-content-center" style="min-height: 70vh;">
    <p class="text-muted">
        Not yet deployed. Please check back later.
    </p>
  </div>
</section>
@endsection
