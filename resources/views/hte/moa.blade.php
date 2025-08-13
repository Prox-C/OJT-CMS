@extends('layouts.hte')

@section('title', 'Memorandum of Agreement')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-8">
        <h1 class="page-header">MEMORANDUM OF AGREEMENT</h1>
      </div>
      <div class="col-sm-4">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">HTE</li>
          <li class="breadcrumb-item active text-muted">MOA</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content d-flex flex-grow-1 align-items-center justify-content-center">
  <div class="container-fluid">
    <div class="row justify-content-center align-items-center min-vh-80">
      <div class="col-lg-10">
        <div class="card shadow">
          <div class="card-header bg-white text-white">
            <h3 class="card-title">MOA Submission</h3>
          </div>
          
          <div class="card-body p-4">
            @if($hte->moa_path)
              <!-- MOA Preview Section -->
              <div class="w-100">
                <div class="alert alert-success">
                  <i class="fas fa-check-circle mr-2"></i>
                  Your Memorandum of Agreement has been submitted.
                </div>
                
                <div class="embed-responsive embed-responsive-16by9 mb-3">
                  <iframe src="{{ Storage::url($hte->moa_path) }}" 
                          class="embed-responsive-item"
                          style="border: 1px solid #eee;"
                          frameborder="0"></iframe>
                </div>
                
                <div class="d-flex justify-content-center gap-3">
                  <a href="{{ Storage::url($hte->moa_path) }}" 
                     class="btn btn-primary" 
                     target="_blank">
                    <i class="fas fa-download mr-1"></i> Download MOA
                  </a>
                  
                  <button class="btn btn-danger" 
                          id="removeMoaBtn"
                          data-url="{{ route('hte.moa.delete') }}">
                    <i class="fas fa-trash-alt mr-1"></i> Remove MOA
                  </button>
                </div>
              </div>
            @else
              <!-- MOA Upload Form -->
              <div class="d-flex flex-column align-items-center justify-content-center py-4">
                <div class="alert alert-warning text-center mb-4 w-100">
                  <i class="fas fa-exclamation-circle mr-2"></i>
                  Action Required: Upload a signed copy of the Memorandum of Agreement to recieve endorsements.
                </div>
                
                <form id="moaUploadForm" 
                      action="{{ route('hte.moa.upload') }}" 
                      method="POST" 
                      enctype="multipart/form-data"
                      class="w-100"
                      style="max-width: 500px;">
                  @csrf
                  
                  <div class="form-group">
                    <div class="custom-file">
                      <input type="file" 
                             class="custom-file-input" 
                             id="moaFile" 
                             name="moa_file"
                             accept=".pdf"
                             required>
                      <label class="custom-file-label" for="moaFile">Choose PDF file (max 5MB)</label>
                    </div>
                    <small class="form-text text-muted text-center">
                      Please upload in PDF format.
                    </small>
                  </div>
                  
                  <div class="text-center mt-4">
                    <button type="submit" 
                            class="btn btn-success btn-lg"
                            id="uploadBtn">
                      <i class="fas fa-upload mr-1"></i> Upload MOA
                    </button>
                  </div>
                </form>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@push('scripts')

@endpush