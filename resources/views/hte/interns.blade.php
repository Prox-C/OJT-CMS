{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.hte')

@section('title', 'HTE | Home')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">INTERNS DEPLOYED</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">HTE</li>
          <li class="breadcrumb-item active text-muted">Interns</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid d-flex align-items-center justify-content-center" style="min-height: 70vh">
    No deployments yet. Please check again later.
  </div>
</section>
@endsection
