@extends('layouts.admin')

@section('title', 'Skills')

@push('styles')
<style>
    .table-action-icon {
        width: 16px;
        height: 16px;
        vertical-align: middle;
        margin-top: -3px;
    }
    .badge {
        font-size: 0.75rem;
        font-weight: 500;
    }
    .table-cta-icon {
        width: 14px;
        height: 14px;
    }
</style>
@endpush

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="page-header">MANAGE SKILLS</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item fw-medium">Admin</li>
                    <li class="breadcrumb-item active text-muted">Skills</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="flex-grow-1" style="max-width: 220px;">
                    <select class="form-control form-control-sm" id="departmentFilter">
                        <option value="">All Departments</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->dept_id }}">{{ $department->short_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex flex-grow-1 justify-content-end p-0">
                    <button class="btn btn-outline-success btn-sm d-flex mr-2">
                        <span class="d-none d-sm-inline mr-1">Import</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="table-cta-icon" viewBox="0 0 256 256">
                            <path d="M200,24H72A16,16,0,0,0,56,40V64H40A16,16,0,0,0,24,80v96a16,16,0,0,0,16,16H56v24a16,16,0,0,0,16,16H200a16,16,0,0,0,16-16V40A16,16,0,0,0,200,24ZM72,160a8,8,0,0,1-6.15-13.12L81.59,128,65.85,109.12a8,8,0,0,1,12.3-10.24L92,115.5l13.85-16.62a8,8,0,1,1,12.3,10.24L102.41,128l15.74,18.88a8,8,0,0,1-12.3,10.24L92,140.5,78.15,157.12A8,8,0,0,1,72,160Zm56,56H72V192h56Zm0-152H72V40h56Zm72,152H144V192a16,16,0,0,0,16-16v-8h40Zm0-64H160V104h40Zm0-64H160V80a16,16,0,0,0-16-16V40h56Z"></path>
                        </svg>                
                    </button>
                    <button class="btn btn-primary btn-sm d-flex" data-toggle="modal" data-target="#addSkillModal">
                        <span>Add Skill</span>
                    </button>
                </div>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-bordered text-nowrap mb-0" id="skillsTable">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Skill Name</th>
                            <th>Department</th>
                            <th>Students Count</th>
                            <th style="white-space: nowrap; width: 12%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($skills as $index => $skill)
                        <tr data-dept="{{ $skill->dept_id }}">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $skill->name }}</td>
                            <td>{{ $skill->department->short_name }}</td>
                            <td>{{ $skill->students_count }}</td>
                            <td class="text-center px-2" style="white-space: nowrap;">
                                <button class="btn btn-info btn-sm mr-1" data-toggle="modal" data-target="#editSkillModal_{{ $skill->skill_id }}">
                                    <span class="d-none d-sm-inline">Edit</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="table-action-icon" viewBox="0 0 256 256">
                                        <path d="M227.31,73.37,182.63,28.68a16,16,0,0,0-22.63,0L36.69,152A15.86,15.86,0,0,0,32,163.31V208a16,16,0,0,0,16,16H92.69A15.86,15.86,0,0,0,104,219.31L227.31,96a16,16,0,0,0,0-22.63ZM92.69,208H48V163.31l88-88L180.69,120ZM192,108.68,147.31,64l24-24L216,84.68Z"></path>
                                    </svg>
                                </button>
                                <form action="{{ route('admin.delete_skill', $skill->skill_id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                        <span class="d-none d-sm-inline">Delete</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="table-action-icon" viewBox="0 0 256 256">
                                            <path d="M216,48H176V40a24,24,0,0,0-24-24H104A24,24,0,0,0,80,40v8H40a8,8,0,0,0,0,16h8V208a16,16,0,0,0,16,16H192a16,16,0,0,0,16-16V64h8a8,8,0,0,0,0-16ZM96,40a8,8,0,0,1,8-8h48a8,8,0,0,1,8,8v8H96Zm96,168H64V64H192ZM112,104v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Zm48,0v64a8,8,0,0,1-16,0V104a8,8,0,0,1,16,0Z"></path>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Add Skill Modal -->
<div class="modal fade" id="addSkillModal" tabindex="-1" role="dialog" aria-labelledby="addSkillModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSkillModalLabel">Add New Skill</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="addSkillForm" action="{{ route('admin.new_skill') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="skillName">Skill Name</label>
                        <input type="text" class="form-control" id="skillName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="deptId">Department</label>
                        <select class="form-control" id="deptId" name="dept_id" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->dept_id }}">{{ $department->dept_name }} ({{ $department->short_name }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Skill</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Skill Modals - One for each skill -->
@foreach($skills as $skill)
<div class="modal fade" id="editSkillModal_{{ $skill->skill_id }}" tabindex="-1" role="dialog" aria-labelledby="editSkillModalLabel_{{ $skill->skill_id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSkillModalLabel_{{ $skill->skill_id }}">Edit Skill</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editSkillForm_{{ $skill->skill_id }}" action="{{ route('admin.update_skill', $skill->skill_id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editSkillName_{{ $skill->skill_id }}">Skill Name</label>
                        <input type="text" class="form-control" id="editSkillName_{{ $skill->skill_id }}" name="name" value="{{ $skill->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="editDeptId_{{ $skill->skill_id }}">Department</label>
                        <select class="form-control" id="editDeptId_{{ $skill->skill_id }}" name="dept_id" required>
                            <option value="">Select Department</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->dept_id }}" {{ $skill->dept_id == $department->dept_id ? 'selected' : '' }}>
                                    {{ $department->dept_name }} ({{ $department->short_name }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Skill</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Department filter functionality
    $('#departmentFilter').on('change', function() {
        const deptId = $(this).val();
        const $rows = $('#skillsTable tbody tr');
        
        if (deptId === '') {
            $rows.show();
        } else {
            $rows.hide().filter('[data-dept="' + deptId + '"]').show();
        }
        
        // Update row numbers for visible rows only
        updateRowNumbers();
    });
    
    // Update row numbers based on visible rows
    function updateRowNumbers() {
        let visibleIndex = 1;
        $('#skillsTable tbody tr:visible').each(function() {
            $(this).find('td:first').text(visibleIndex++);
        });
    }
    
    // Add skill form submission
    $('#addSkillForm').submit(function(e) {
        e.preventDefault();
        $(this).find('button[type="submit"]').prop('disabled', true);
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseJSON.message);
                $('#addSkillForm').find('button[type="submit"]').prop('disabled', false);
            }
        });
    });

    // Edit skill form submissions (for all modals)
    $('form[id^="editSkillForm_"]').submit(function(e) {
        e.preventDefault();
        $(this).find('button[type="submit"]').prop('disabled', true);
        
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseJSON.message);
                $(this).find('button[type="submit"]').prop('disabled', false);
            }
        });
    });
});
</script>
@endsection