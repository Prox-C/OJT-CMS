@extends('layouts.admin')

@section('title', 'HTE MOA Documents')

@section('content')

<style>
.modal-xl {
    max-width: 90%;
}
</style>

<section class="content-header">
  <div class="container-fluid px-3">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">HTE MOA DOCUMENTS</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Admin</li>
          <li class="breadcrumb-item active text-muted">MOAs</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2 p-3">
        <h3 class="card-title">Memorandum of Agreement (MOA) Documents</h3>
      </div>

      <div class="card-body table-responsive py-0 px-3 position-relative">
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
          <i class="ph-bold ph-arrows-clockwise fa-spin fs-3 text-primary"></i>
          <span class="text-primary">Loading HTE MOAs . . .</span>
        </div>
        
        <table id="moasTable" class="table table-bordered mb-0">
          <thead class="table-light">
            <tr>
              <th>Organization Name</th>
              <th>Contact Person</th>
              <th>MOA Status</th>
              <th>Date Uploaded</th>
              <th width="10%">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($htes as $hte)
            <tr>
              <td class="align-middle">
                <div class="d-flex align-items-center">
                  <i class="ph ph-building-office text-primary me-2 fs-5"></i>
                  <span class="fw-medium">{{ $hte->organization_name }}</span>
                </div>
                <br>
                <small class="text-muted">{{ Str::limit($hte->address, 50) }}</small>
              </td>
              <td class="align-middle">
                @if($hte->user->pic)
                  <img src="{{ asset('storage/' . $hte->user->pic) }}" 
                      alt="Profile Picture" 
                      class="rounded-circle me-2 table-pfp" 
                      width="30" height="30">
                @else
                  @php
                    $name = $hte->user->fname . $hte->user->lname;
                    $colors = ['#007bff', '#28a745', '#dc3545', '#6f42c1', '#fd7e14', '#20c997', '#e83e8c', '#17a2b8'];
                    $colorIndex = crc32($name) % count($colors);
                    $randomColor = $colors[$colorIndex];
                  @endphp
                  <div class="rounded-circle me-2 d-inline-flex align-items-center justify-content-center text-white fw-bold" 
                      style="width: 30px; height: 30px; font-size: 11px; background-color: {{ $randomColor }};">
                    {{ strtoupper(substr($hte->user->fname, 0, 1) . substr($hte->user->lname, 0, 1)) }}
                  </div>
                @endif
                {{ $hte->user->lname }}, {{ $hte->user->fname }}
                <br>
                <small class="text-muted">{{ $hte->user->email }}</small>
              </td>
              <td class="align-middle">
                @if($hte->moa_path)
                  @if($hte->moa_is_signed == 'yes')
                    <span class="badge bg-success-subtle text-success py-2 px-3 rounded-pill">
                      <i class="ph ph-check-circle me-1"></i>Signed
                    </span>
                  @else
                    <span class="badge bg-warning-subtle text-warning py-2 px-3 rounded-pill">
                      <i class="ph ph-clock me-1"></i>For Validation
                    </span>
                  @endif
                @else
                  <span class="badge bg-danger-subtle text-danger py-2 px-3 rounded-pill">
                    <i class="ph ph-x-circle me-1"></i>Missing
                  </span>
                @endif
               </td>
              <td class="align-middle">
                @if($hte->moa_path)
                  {{ \Carbon\Carbon::parse($hte->updated_at)->format('M d, Y') }}
                  <br>
                  <small class="text-muted">{{ \Carbon\Carbon::parse($hte->updated_at)->format('g:i A') }}</small>
                @else
                  <span class="text-muted">—</span>
                @endif
               </td>
              <td class="text-center px-2 align-middle">
                @if($hte->moa_path)
                  <button class="btn btn-sm btn-outline-primary rounded-pill px-3 view-moa-btn"
                          data-toggle="modal"
                          data-target="#moaModal"
                          data-url="{{ asset('storage/' . $hte->moa_path) }}"
                          data-name="{{ $hte->organization_name }}">
                    <i class="ph ph-eye me-1"></i>View
                  </button>
                @else
                  <span class="text-muted small">No file</span>
                @endif
               </td>
             </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

<!-- MOA Preview Modal -->
<div class="modal fade" id="moaModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">
                    <i class="ph ph-file-pdf text-danger me-2"></i>
                    <span id="moaTitle">MOA Document</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <iframe id="moaFrame" src="" style="width:100%; height:70vh;" frameborder="0"></iframe>
            </div>
            <div class="modal-footer bg-light">
                <a id="downloadMoaLink" href="#" class="btn btn-primary">
                    <i class="ph ph-download me-1"></i>Download
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@include('layouts.partials.scripts-main')

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#moasTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "language": {
            "emptyTable": "No HTE MOA records found.",
            "search": "_INPUT_",
            "searchPlaceholder": "Search HTE MOAs...",
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ HTEs",
            "paginate": {
                "previous": "«",
                "next": "»"
            }
        },
        "columnDefs": [
            { "orderable": false, "targets": [4] }
        ],
        "order": [[4, 'desc']],
        "initComplete": function() {
            $('#tableLoadingOverlay').fadeOut();
        }
    });

    // Handle MOA view
    $('.view-moa-btn').on('click', function() {
        const url = $(this).data('url');
        const name = $(this).data('name');
        
        $('#moaTitle').text(name + ' - MOA Document');
        $('#moaFrame').attr('src', url);
        $('#downloadMoaLink').attr('href', url);
    });
    
    // Clear iframe when modal is closed
    $('#moaModal').on('hidden.bs.modal', function() {
        $('#moaFrame').attr('src', '');
    });
});
</script>
@endsection