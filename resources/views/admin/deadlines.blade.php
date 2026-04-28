@extends('layouts.admin')

@section('title', 'Manage Deadlines')

@section('content')
<section class="content-header">
    @include('layouts.partials.scripts-main')

    <div class="container-fluid px-3">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="page-header">DEADLINES MANAGEMENT</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item fw-medium">Coordinator</li>
                    <li class="breadcrumb-item active text-muted">Deadlines</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="ph ph-calendar-blank custom-icons-i me-2"></i>
                    Important Deadlines
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="40%">Deadline Name</th>
                                <th width="30%">Deadline Date</th>
                                <th width="20%">Status</th>
                                <th width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($deadlines as $deadline)
                            <tr>
                                <td class="align-middle">
                                    <strong>{{ $deadline->name }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        @if($deadline->name == 'Intern Document Deadline')
                                            Deadline for interns to submit all pre-deployment requirements
                                        @else
                                            Deadline for HTE deployment completion
                                        @endif
                                    </small>
                                </td>
                                <td class="align-middle">
                                    @if($deadline->deadline)
                                        <i class="ph ph-calendar me-1"></i>
                                        {{ $deadline->formatted_deadline }}
                                    @else
                                        <span class="text-muted">
                                            <i class="ph ph-minus-circle me-1"></i>
                                            Not set
                                        </span>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    @if($deadline->deadline)
                                        <span class="badge bg-{{ $deadline->status_badge }}-subtle text-{{ $deadline->status_badge }} py-2 px-3 rounded-pill">
                                            <i class="ph ph-{{ $deadline->isOverdue() ? 'warning-circle' : ($deadline->daysRemaining() <= 3 ? 'clock' : 'check-circle') }} me-1"></i>
                                            {{ $deadline->status_text }}
                                        </span>
                                        @if(!$deadline->isOverdue() && $deadline->daysRemaining() > 0)
                                            <br>
                                            <small class="text-muted">Until {{ $deadline->formatted_deadline }}</small>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary py-2 px-3 rounded-pill">
                                            <i class="ph ph-info me-1"></i>
                                            No deadline set
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center align-middle">
                                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3 edit-deadline-btn"
                                            data-toggle="modal"
                                            data-target="#editDeadlineModal"
                                            data-id="{{ $deadline->id }}"
                                            data-name="{{ $deadline->name }}"
                                            data-deadline="{{ $deadline->deadline ? $deadline->deadline->format('Y-m-d') : '' }}">
                                        <i class="ph ph-pencil me-1"></i>
                                        Edit
                                    </button>
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

<!-- Edit Deadline Modal -->
<div class="modal fade" id="editDeadlineModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">
                    <i class="ph ph-pencil custom-icons-i me-2"></i>
                    Edit Deadline
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editDeadlineForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="deadline_name" class="form-label">Deadline Name</label>
                        <input type="text" class="form-control" id="deadline_name" readonly disabled>
                    </div>
                    <div class="form-group mb-3">
                        <label for="deadline_date" class="form-label">Deadline Date</label>
                        <input type="date" class="form-control" id="deadline_date" name="deadline">
                        <small class="form-text text-muted">Leave empty to remove the deadline</small>
                    </div>
                    <div class="alert alert-info">
                        <i class="ph ph-info me-2"></i>
                        Setting a deadline will notify interns and HTEs about important dates.
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Handle edit button click
    $('.edit-deadline-btn').on('click', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const deadline = $(this).data('deadline');
        
        $('#deadline_name').val(name);
        $('#deadline_date').val(deadline);
        $('#editDeadlineForm').attr('action', '/admin/deadlines/' + id);
    });
    
    // Handle form submission
    $('#editDeadlineForm').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const url = form.attr('action');
        const formData = new FormData(this);
        
        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    toastr.success('Deadline updated successfully');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    toastr.error('Error updating deadline');
                }
            },
            error: function(xhr) {
                toastr.error('Error updating deadline: ' + (xhr.responseJSON?.message || 'Unknown error'));
            }
        });
    });
});
</script>
@endsection