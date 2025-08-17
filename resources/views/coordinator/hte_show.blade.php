@extends('layouts.coordinator')

@section('title', 'HTE Details')

@section('content')
<div class="container-fluid">
    <!-- MOA Preview Modal -->
    <div class="modal fade" id="moaPreviewModal" tabindex="-1" role="dialog" aria-labelledby="moaPreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-white text-dark">
                    <h5 class="modal-title" id="moaPreviewModalLabel">
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" style="position: relative; top: -3px" fill="currentColor" viewBox="0 0 256 256"><path d="M80.3,120.26A58.29,58.29,0,0,1,81,97.07C83.32,87,87.89,80,92.1,80c2.57,0,2.94.67,3.12,1,.88,1.61,4,10.93-12.63,46.52A28.87,28.87,0,0,1,80.3,120.26ZM232,56V200a16,16,0,0,1-16,16H40a16,16,0,0,1-16-16V56A16,16,0,0,1,40,40H216A16,16,0,0,1,232,56ZM84,160c2-3.59,3.94-7.32,5.9-11.14,10.34-.32,22.21-7.57,35.47-21.68,5,9.69,11.38,15.25,18.87,16.55,8,1.38,16-2.38,23.94-11.2,6,5.53,16.15,11.47,31.8,11.47a8,8,0,0,0,0-16c-17.91,0-24.3-10.88-24.84-11.86a7.83,7.83,0,0,0-6.54-4.51,8,8,0,0,0-7.25,3.6c-6.78,10-11.87,13.16-14.39,12.73-4-.69-9.15-10-11.23-18a8,8,0,0,0-14-3c-8.88,10.94-16.3,17.79-22.13,21.66,15.8-35.65,13.27-48.59,9.6-55.3C107.35,69.84,102.59,64,92.1,64,79.66,64,69.68,75,65.41,93.46a75,75,0,0,0-.83,29.81c1.7,8.9,5.17,15.73,10.16,20.12-3,5.81-6.09,11.43-9,16.61H56a8,8,0,0,0,0,16h.44c-4.26,7.12-7.11,11.59-7.18,11.69a8,8,0,0,0,13.48,8.62c.36-.55,5.47-8.57,12.29-20.31H200a8,8,0,0,0,0-16Z"></path></svg>
                        Memorandum of Agreement - {{ $hte->organization_name }}
                    </h5>
                    <button type="button" class="close text-secondary" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    @if($hte->moa_path)
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe id="moaPreviewFrame" src="{{ Storage::url($hte->moa_path) }}" 
                                class="embed-responsive-item" 
                                style="border: none;"
                                allowfullscreen></iframe>
                    </div>
                    @else
                    <div class="text-center py-5 bg-light">
                        <i class="fas fa-file-pdf fa-5x text-muted mb-3"></i>
                        <h5 class="text-muted">No MOA Document Available</h5>
                        <p class="text-muted">This HTE hasn't uploaded their MOA yet</p>
                    </div>
                    @endif
                </div>
                <div class="modal-footer bg-white">
                    @if($hte->moa_path)
                    <a href="{{ Storage::url($hte->moa_path) }}" class="btn btn-success text-light" download>
                        <span class="text-white">Download File</span>
                    </a>
                    @endif
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>


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
                                    <!-- <li class="mb-2">
                                        <strong>Status:</strong> 
                                        <span class="badge badge-{{ $hte->status == 'active' ? 'success' : 'primary' }}">
                                            {{ ucfirst($hte->status) }}
                                        </span>
                                    </li> -->
                                    <li class="mb-2"><strong>Type:</strong> {{ ucfirst($hte->type) }}</li>
                                    <li class="mb-2"><strong>Available Slots:</strong> {{ $hte->slots }}</li>
                                    <li class="mb-2 align-middle"><strong>MOA:</strong>
                                        @if($hte->moa_path)
                                            <button class="btn btn-sm btn-outline-primary ml-2" data-toggle="modal" data-target="#moaPreviewModal">
                                                <i class="fas fa-eye mr-1"></i> Preview
                                            </button>
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
                                <h5 class="mb-3"><i class="fas fa-tasks mr-2"></i>Preferred Skills</h5>
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
                        <div class="col-md-12 text-right bg-light p-3 rounded">
                            <a href="{{ route('coordinator.hte.edit', $hte->id) }}" class="btn btn-primary mr-2 text-white">
                               <span class="text-white"><i class="fas fa-edit mr-1"></i> Update Info</span> 
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
                <div class="card-header bg-white text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-users mr-2"></i>
                        Endorsements
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