@php use App\Models\InternDocument; @endphp
@extends('layouts.intern')

@section('title', 'Manage Requirements')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">MANAGE DOCUMENTS</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Intern</li>
          <li class="breadcrumb-item active text-muted">Docs</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card shadow-sm">
            <div class="card-body">                
                <!-- Document Counter -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">
                        <span id="statusBadge" class="badge py-2 px-3 
                            @if($documents->count() >= 9)
                                bg-success-subtle text-success
                            @else
                                bg-warning-subtle text-warning
                            @endif">
                            <i class="ph-fill custom-icons-i
                                @if($documents->count() >= 9)
                                    ph-seal-check
                                @else
                                    ph-seal-question
                                @endif 
                                mr-1"></i>
                            <span id="statusText">
                                @if($documents->count() >= 9)
                                    Complete
                                @else
                                    Incomplete
                                @endif
                            </span>
                            (<span id="documentCounter">{{ $documents->count() }}</span>/9)
                        </span>
                    </h5>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="thead-white bg-light">
                            <tr>
                                <th width="45%">Document Name</th>
                                <th width="">Description</th>
                                <th width="10%">Status</th>
                                <th width="13%" style="white-space: no-wrap;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(InternDocument::typeLabels() as $type => $label)
                            @php $document = $documents->where('type', $type)->first(); @endphp
                            <tr data-document-type="{{ $type }}">
                                <td class="align-middle ps-3">{{ $label }}</td>
                                <td class="text-muted small align-middle">
                                    @switch($type)
                                        @case('requirements_checklist') Signed checklist of all required documents @break
                                        @case('certificate_of_registration') Current semester registration certificate @break
                                        @case('report_of_grades') Latest official transcript with OJT qualification @break
                                        @case('application_resume') Formal application letter with updated resume @break
                                        @case('medical_certificate') Health clearance from university clinic @break
                                        @case('parent_consent') Notarized consent form from parent/guardian @break
                                        @case('insurance_certificate') Proof of valid insurance coverage @break
                                        @case('pre_deployment_certification') Certification of orientation attendance @break
                                        @case('ojt_fee_reciept') Official reciept of paid internship fee @break
                                    @endswitch
                                </td>
                                <td class="text-center align-middle">
                                    @if($document)
                                        <span class="badge bg-success-subtle text-success py-2 px-3 rounded-4 w-100 status-badge">Submitted</span><br>
                                        <small>{{ $document->created_at->format('Y-m-d') }}</small>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger py-2 px-3 rounded-pill w-100 status-badge">Missing</span>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    @if($document)
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="actionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="ph-fill ph-gear custom-icons-i"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right py-0 overflow-hidden" aria-labelledby="actionDropdown">
                                            <button class="dropdown-item btn btn-outline-light view-document w-100 fw-medium border-bottom border-lightgray btn-flat text-dark py-2" 
                                                    data-url="{{ Storage::url($document->file_path) }}">
                                                <i class="ph ph-eye custom-icons-i"></i>
                                                <span>View</span>
                                            </button>
                                            <button class="dropdown-item btn btn-outline-light remove-document w-100 fw-medium btn-flat text-danger py-2" 
                                                    data-id="{{ $document->id }}">
                                                <i class="ph ph-trash custom-icons-i"></i>
                                                <span>Delete</span>
                                            </button>
                                        </div>
                                    </div>
                                    @else
                                        <button class="btn btn-sm btn-outline-success upload-document fw-medium" 
                                                data-type="{{ $type }}">
                                            <i class="ph-fill ph-upload custom-icons-i"></i>
                                            <span>Upload</span>
                                        </button>
                                    @endif
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

<!-- Document Preview Modal -->
<div class="modal fade" id="documentModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <iframe id="documentFrame" src="" style="width:100%; height:70vh;" frameborder="0"></iframe>
            </div>
            <div class="modal-footer">
                <a id="downloadLink" href="#" class="btn btn-primary fw-medium">
                    <i class="ph-fill ph-download custom-icons-i mr-1"></i>Download
                </a>
                <button type="button" class="btn btn-secondary fw-medium" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Document Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Upload Document</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="uploadForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type" id="documentType">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="documentFile">Select PDF File (max 5MB)</label>
                        <input type="file" class="form-control" id="documentFile" name="document" accept=".pdf" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-success fw-medium">
                        <i class="ph-fill ph-upload custom-icons-i mr-1"></i>Upload
                    </button>
                    <button type="button" class="btn btn-secondary fw-medium" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize all event handlers
    initializeDocumentHandlers();

    function initializeDocumentHandlers() {
        // Document Preview
        $(document).off('click', '.view-document').on('click', '.view-document', function() {
            const url = $(this).data('url');
            const title = $(this).closest('tr').find('td:first').text();
            
            $('#documentTitle').text(title);
            $('#documentFrame').attr('src', url);
            $('#downloadLink').attr('href', url);
            $('#documentModal').modal('show');
        });

        // Document Upload Init
        $(document).off('click', '.upload-document').on('click', '.upload-document', function() {
            const type = $(this).data('type');
            const title = $(this).closest('tr').find('td:first').text();
            
            $('#documentType').val(type);
            $('#uploadModal .modal-title').text('Upload: ' + title);
            $('#uploadModal').modal('show');
        });

        // Document Removal - This will work 100%
        $(document).off('click', '.remove-document').on('click', '.remove-document', function() {
            if (!confirm('Are you sure you want to remove this document?')) return;
            
            const documentId = $(this).data('id');
            const row = $(this).closest('tr');
            const documentType = row.data('document-type');
            
            // Show loading state
            $(this).html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);
            
            $.ajax({
                url: '{{ route("intern.docs.delete") }}',
                method: 'DELETE',
                data: { id: documentId },
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function() {
                    // Update status column
                    row.find('td:eq(2)').html('<span class="badge bg-danger-subtle text-danger px-2 py-3 w-100">Missing</span>');
                    
                    // Replace action buttons with new upload button
                    row.find('td:eq(3)').html(`
                        <button class="btn btn-sm btn-success upload-document w-100" 
                                data-type="${documentType}">
                            <span>Upload</span>
                            <i class="fas fa-upload"></i>
                        </button>
                    `);
                    
                    updateCounter();
                    initializeDocumentHandlers(); // Rebind events
                },
                error: function(xhr) {
                    alert('Error removing document: ' + (xhr.responseJSON?.message || 'Unknown error'));
                    $(this).html('<span>Delete</span><i class="fas fa-trash"></i>').prop('disabled', false);
                }
            });
        });
    }

    // Form Submission
    $('#uploadForm').submit(function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const submitBtn = $(this).find('button[type="submit"]');
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Uploading...');
        
        $.ajax({
            url: '{{ route("intern.docs.upload") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(response) {
                $('#uploadModal').modal('hide');
                location.reload();
            },
            error: function(xhr) {
                alert(xhr.responseJSON?.message || 'Error uploading document');
                submitBtn.prop('disabled', false).html('<i class="fas fa-upload mr-1"></i> Upload');
            }
        });
    });

    function updateCounter() {
        const count = $('span.badge-success-subtle').length;
        $('#documentCounter').text(count);
    }
});
</script>
@endpush