@extends('layouts.coordinator')

@section('title', 'HTE Details')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-white text-dark">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-building mr-2"></i>
                        {{ $hte->organization_name }} - Details
                    </h3>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <!-- HTE Profile Section -->
                        <div class="col-md-4 d-flex flex-column">
                            <div class="text-center mb-4">
                                <img src="{{ asset('storage/' . auth()->user()->pic) }}" 
                                     class="img-thumbnail rounded-circle" 
                                     style="width: 200px; height: 200px; object-fit: cover;"
                                     alt="Organization Logo">
                            </div>
                            
                            <div class="border p-3 rounded bg-light flex-grow-1 mt-0">
                                <h5 class="mb-3"><i class="fas fa-info-circle mr-2"></i>Basic Information</h5>
                                <ul class="list-unstyled">
                                    <li class="mb-2">
                                        <strong>Status:</strong> 
                                        <span class="badge badge-{{ $hte->status == 'active' ? 'success' : 'primary' }}">
                                            {{ ucfirst($hte->status) }}
                                        </span>
                                    </li>
                                    <li class="mb-2"><strong>Type:</strong> {{ ucfirst($hte->type) }}</li>
                                    <li class="mb-2"><strong>Available Slots:</strong> {{ $hte->slots }}</li>
                                    <li class="mb-2"><strong>MOA Status:</strong>
                                        @if($hte->moa_path)
                                            <span class="text-success">Submitted</span>
                                            <a href="{{ Storage::url($hte->moa_path) }}" target="_blank" class="btn btn-sm btn-outline-primary ml-2">
                                                View MOA
                                            </a>
                                        @else
                                            <span class="small badge bg-danger-subtle text-danger py-1 px-3 rounded-pill" style="font-size: 14px">Missing</span>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- Contact & Details Section -->
                        <div class="col-md-8">
                            <div class="border p-3 rounded my-3 mt-lg-0 bg-light">
                                <h5 class="mb-3"><i class="fas fa-address-card mr-2"></i>Contact Information</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Contact Person:</strong><br>
                                        {{ $hte->user->fname }} {{ $hte->user->lname }}</p>
                                        
                                        <p><strong>Email:</strong><br>
                                        {{ $hte->user->email }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Contact Number:</strong><br>
                                        {{ $hte->user->contact ?? 'N/A' }}</p>
                                        
                                        <p><strong>Address:</strong><br>
                                        {{ $hte->address ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Skills Section -->
                            <div class="border p-3 rounded mb-3 bg-light">
                                <h5 class="mb-3"><i class="fas fa-tasks mr-2"></i>Required Skills</h5>
                                @if($hte->skills->count() > 0)
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($hte->skills as $skill)
                                            <span class="badge bg-secondary-subtle text-dark py-2 px-3 rounded-pill">{{ $skill->name }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">No specific skills requested</p>
                                @endif
                            </div>
                            
                            <!-- Description -->
                            <div class="border p-3 rounded bg-light">
                                <h5 class="mb-3"><i class="fas fa-align-left mr-2"></i>Organization Description</h5>
                                <p>{{ $hte->description ?? 'No description provided' }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    @if($canManage)
                    <div class="row mt-4">
                        <div class="col-md-12 text-right">
                            <a href="{{ route('coordinator.hte.edit', $hte->id) }}" class="btn btn-primary mr-2">
                                <i class="fas fa-edit mr-1"></i> Update Info
                            </a>
                            <button class="btn btn-danger" data-toggle="modal" data-target="#unregisterModal">
                                <i class="fas fa-trash-alt mr-1"></i> Unregister HTE
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Interns Table Section -->
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-users mr-2"></i>
                        Deployed Interns
                    </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Year Level</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Unregister Modal -->
@if($canManage)
<div class="modal fade" id="unregisterModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirm Unregistration</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to unregister this HTE? This action cannot be undone.</p>
                <p class="text-danger"><strong>Warning:</strong> Any ongoing internships will be affected.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="{{ route('coordinator.hte.destroy', $hte->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Confirm Unregister</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection