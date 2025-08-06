<!-- jQuery -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>

<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<!-- JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Custom Scripts -->

<!-- Filter Skills By Department -->
<script>
jQuery(document).ready(function($) {
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
    
    // Delete skill functionality
    $(document).on('click', '.delete-skill', function() {
        if (confirm('Are you sure you want to delete this skill?')) {
            const skillId = $(this).data('id');
            // Add your AJAX delete logic here
        }
    });
});
</script>

<script>
// Department deletion handling
$(document).on('submit', '.delete-form', function(e) {
    e.preventDefault();
    
    if (confirm('Are you sure you want to delete this department? This action cannot be undone.')) {
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: {
                _method: 'DELETE',
                _token: $(this).find('input[name="_token"]').val()
            },
            success: function(response) {
                location.reload();
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseJSON.message);
            }
        });
    }
});

// Add department form submission
$(document).on('submit', '#addDepartmentForm', function(e) {
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
            $('#addDepartmentForm').find('button[type="submit"]').prop('disabled', false);
        }
    });
});
</script>

<!-- Document handling scripts -->
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
                    row.find('td:eq(2)').html('<span class="badge bg-danger-subtle text-danger py-2 px-3 w-100 rounded-pill">Missing</span>');
                    
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

<!-- Profile Management -->
<script>
$(document).ready(function() {
    // Profile Picture Upload
    $('#profileUpload').change(function(e) {
        const file = e.target.files[0];
        if (!file) return;
        
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            return;
        }

        const formData = new FormData();
        formData.append('profile_pic', file);
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
            url: '{{ route("intern.profile.picture") }}',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#profileImage').attr('src', response.url);
                toastr.success('Profile picture updated successfully');
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON.message || 'Error uploading picture');
            }
        });
    });

    $('#skillsForm').submit(function(e) {
        e.preventDefault();
        const form = $(this);
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST', // This will be converted to PUT by @method('PUT')
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    // Update the skills display
                    $('#skillsBadges').html(
                        response.skills.map(skill => 
                            `<span class="badge bg-primary py-2 px-3 mr-2 mb-2">
                                <i class="fas fa-check-circle mr-1"></i> ${skill}
                            </span>`
                        ).join('')
                    );
                    $('#skillsModal').modal('hide');
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Error updating skills');
            }
        });
    });
});
</script>
