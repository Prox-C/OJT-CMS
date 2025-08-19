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
          @if($canManageHTEs)
          <div class="d-flex flex-grow-1 justify-content-end p-0">
            <button class="btn btn-outline-success btn-sm d-flex mr-2" id="importBtn">
              <span class="d-none d-sm-inline mr-1 fw-medium">Import</span>
              <i class="ph-fill ph-microsoft-excel-logo custom-icons-i"></i>              
            </button>
            <a href="{{ route('coordinator.new_h') }}" class="btn btn-primary btn-sm d-flex" id="registerBtn">
              <span>Register</span>
            </a>
          </div>
          @endif
        </div>      

        <div class="card-body table-responsive py-0 px-3">
          <!-- Loading Overlay -->
          <div id="tableLoadingOverlay" 
            style="position: absolute; 
            width: 100%; 
            height: 100%; 
            background: rgba(255,255,255,0.85); 
            display: flex; 
            flex-direction: column;
            justify-content: center; 
            align-items: center; 
            z-index: 1000;
            gap: 1rem;">
            <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
            <span class="text-primary">Loading HTEs...</span>
          </div>
          <table id="htesTable" class="table table-bordered mb-0">
            <thead class="table-light">
              <tr>
                <th width="11%">HTE ID</th>
                <th>Name</th>
                <th>Representative</th>
                <th>Slots</th>
                <th width="10%">MOA Status</th>
                <th width="3%">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($htes as $hte)
              <tr>
                <td>HTE-{{ str_pad($hte->id, 3, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $hte->organization_name }}</td>
                <td>{{ $hte->user->fname}}  {{ $hte->user->lname}}</td>
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
                <td class="text-center px-2 align-middle">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="actionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ph-fill ph-gear custom-icons-i"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="actionDropdown">
                            <!-- View Option -->
                            <a class="dropdown-item" href="{{ route('coordinator.hte.show', $hte->id) }}">
                                <i class="ph-fill ph-eye custom-icons-i mr-2"></i>View
                            </a>
                            
                            <!-- Unregister Option (conditionally visible) -->
                            @if($canManageHTEs)
                            <a class="dropdown-item text-danger" href="#" data-toggle="modal" data-target="#unregisterModal{{ $hte->id }}">
                                <i class="ph-fill ph-trash custom-icons-i mr-2"></i>Unregister
                            </a>
                            @endif
                        </div>
                    </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
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