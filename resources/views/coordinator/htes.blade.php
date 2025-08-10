@extends('layouts.coordinator')

@section('title', 'Manage HTEs')

@section('content')
@php
    $canManageHTEs = auth()->user()->coordinator->can_add_hte == 1;
@endphp

<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-8">
        <h1 class="page-header">HOST TRAINING ESTABLISHMENTS</h1>
      </div>
      <div class="col-sm-4">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Coordinator</li>
          <li class="breadcrumb-item active text-muted">HTEs</li>
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
          @if($canManageHTEs)
          <div class="d-flex flex-grow-1 justify-content-end p-0">
            <button class="btn btn-outline-success btn-sm d-flex mr-2" id="importBtn">
              <span class="d-none d-sm-inline mr-1">Import</span>
              <svg xmlns="http://www.w3.org/2000/svg" class="table-cta-icon" viewBox="0 0 256 256">
                <path d="M200,24H72A16,16,0,0,0,56,40V64H40A16,16,0,0,0,24,80v96a16,16,0,0,0,16,16H56v24a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V40A16,16,0,0,0,200,24ZM72,160a8,8,0,0,1-6.15-13.12L81.59,128,65.85,109.12a8,8,0,0,1,12.3-10.24L92,115.5l13.85-16.62a8,8,0,1,1,12.3,10.24L102.41,128l15.74,18.88a8,8,0,0,1-12.3,10.24L92,140.5,78.15,157.12A8,8,0,0,1,72,160Zm56,56H72V192h56Zm0-152H72V40h56Zm72,152H144V192a16,16,0,0,0,16-16v-8h40Zm0-64H160V104h40Zm0-64H160V80a16,16,0,0,0-16-16V40h56Z"></path>
              </svg>                
            </button>
            <a href="{{ route('coordinator.new_h') }}" class="btn btn-primary btn-sm d-flex" id="registerBtn">
              <span>Register</span>
            </a>
          </div>
          @endif
        </div>      

        <div class="card-body table-responsive p-0">
          <table class="table table-bordered text-nowrap mb-0">
            <thead class="table-light">
              <tr>
                <th width="10%">HTE ID</th>
                <th>Name</th>
                <th>Representative</th>
                <th>Industry</th>
                <th>Slots</th>
                <th width="10%">MOA Status</th>
                <th style="white-space: nowrap; width: 12%">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($htes as $hte)
              <tr>
                <td>HTE-{{ str_pad($hte->id, 3, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $hte->organization_name }}</td>
                <td>{{ $hte->user->fname}}  {{ $hte->user->lname}}</td>
                <td class="text-capitalize">{{ $hte->type }}</td>
                <td class="align-middle text-center text-medium text-success">
                  0/{{ $hte->slots }}
                </td>
                <td class="text-center">
                  @if($hte->moa_path)
                    <span class="badge badge-sm bg-success-subtle text-success px-3 py-2 rounded-4 w-100">Submitted</span>
                  @else
                    <span class="badge badge-sm bg-danger-subtle text-danger px-3 py-2 rounded-4 w-100">Missing</span>
                  @endif
                </td>
                <td class="text-center px-2 align-middle" style="white-space: nowrap;">
                <div class="d-flex gap-1" style="width: 100%;">
                    <!-- View button - always visible -->
                    <a href="{{ route('coordinator.hte.show', $hte->id) }}" class="btn btn-primary btn-sm flex-grow-1" style="min-width: 80px;">
                        <span class="d-none d-sm-inline">View</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="table-action-icon" viewBox="0 0 256 256">
                            <path d="M208,32H48A16,16,0,0,0,32,48V208a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V48A16,16,0,0,0,208,32Zm0,176H48V48H208ZM90.34,165.66a8,8,0,0,1,0-11.32L140.69,104H112a8,8,0,0,1,0-16h48a8,8,0,0,1,8,8v48a8,8,0,0,1-16,0V115.31l-50.34,50.35a8,8,0,0,1-11.32,0Z"></path>
                        </svg>                              
                    </a>
                    
                    <!-- Unregister button - conditionally visible -->
                    @if($canManageHTEs)
                    <button class="btn btn-danger btn-sm flex-grow-1 remove-hte" data-hte-id="{{ $hte->id }}"
                            style="min-width: 80px;">
                    <span class="d-none d-sm-inline">Unregister</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="table-action-icon" viewBox="0 0 256 256">
                        <path d="M216,48H176V40a24,24,0,0,0-24-24H104A24,24,0,0,0,80,40v8H40a8,8,0,0,0,0,16h8V208a16,16,0,0,0,16,16H192a16,16,0,0,0,16-16V64h8a8,8,0,0,0,0-16ZM96,40a8,8,0,0,1,8-8h48a8,8,0,0,1,8,8v8H96Zm96,168H64V64H192ZM112,104v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Zm48,0v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Z"></path>
                    </svg>                              
                    </button>
                    @endif
                </div>
                </td>
              </tr>
              @endforeach
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
</section>

<!-- @if(!$canManageHTEs)
<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Notice:</strong> You have view-only access to HTEs. Management functions are restricted.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif -->

@endsection