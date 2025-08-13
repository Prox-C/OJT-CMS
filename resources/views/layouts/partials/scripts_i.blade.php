<!-- jQuery -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>

<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<!-- JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "5000"
    };
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

        // Document Removal
        $(document).off('click', '.remove-document').on('click', '.remove-document', function() {
            if (!confirm('Are you sure you want to remove this document?')) return;
            
            const documentId = $(this).data('id');
            const row = $(this).closest('tr');
            const documentType = row.data('document-type');
            
            // Show loading state
            const deleteBtn = $(this);
            deleteBtn.html('<i class="fas fa-spinner fa-spin"></i>').prop('disabled', true);
            
            $.ajax({
                url: '{{ route("intern.docs.delete") }}',
                method: 'DELETE',
                data: { id: documentId },
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                success: function(response) {
                    // Update the row
                    row.find('td:eq(2)').html(`
                        <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill w-100">Missing</span>
                    `);
                    
                    row.find('td:eq(3)').html(`
                        <button class="btn btn-sm btn-success upload-document w-100" 
                                data-type="${documentType}">
                            <span>Upload</span>
                            <i class="fas fa-upload"></i>
                        </button>
                    `);
                    
                    // Get current count before updating
                    const currentCount = parseInt($('#documentCounter').text());
                    $('#documentCounter').text(currentCount - 1);
                    updateStatusBadge(currentCount - 1);
                    
                    toastr.success(response.message);
                    
                    if (response.new_status === 'incomplete') {
                        toastr.info('Status changed to Incomplete');
                    }
                    
                    initializeDocumentHandlers();
                },
                error: function(xhr) {
                    toastr.error('Error removing document: ' + (xhr.responseJSON?.message || 'Unknown error'));
                    deleteBtn.html('<span>Delete</span><i class="fas fa-trash"></i>').prop('disabled', false);
                }
            });
        });
    }

    // Form Submission - No page refresh
    $('#uploadForm').submit(function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const submitBtn = $(this).find('button[type="submit"]');
        const uploadBtn = $(`button[data-type="${$('#documentType').val()}"]`);
        const row = uploadBtn.closest('tr');
        
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
                
                // Update the row
                row.find('td:eq(2)').html(`
                    <span class="badge bg-success-subtle text-success py-2 px-3 rounded-pill w-100">Submitted</span>
                    <br>
                    <small>${response.created_at}</small>
                `);
                
                row.find('td:eq(3)').html(`
                    <button class="btn btn-sm btn-primary view-document w-100 mb-2" 
                            data-url="${response.file_url}">
                        <span>View</span>
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-danger remove-document w-100" 
                            data-id="${response.document_id}">
                        <span>Delete</span>
                        <i class="fas fa-trash"></i>
                    </button>
                `);
                
                // Get current count before updating
                const currentCount = parseInt($('#documentCounter').text());
                $('#documentCounter').text(currentCount + 1);
                updateStatusBadge(currentCount + 1);
                
                toastr.success(response.message);
                
                if (response.new_status === 'pending') {
                    toastr.success('All documents submitted! Status changed to Pending');
                }
                
                initializeDocumentHandlers();
                submitBtn.prop('disabled', false).html('<i class="fas fa-upload mr-1"></i> Upload');
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Error uploading document');
                submitBtn.prop('disabled', false).html('<i class="fas fa-upload mr-1"></i> Upload');
            }
        });
    });

    function updateStatusBadge(count) {
        const badge = $('#statusBadge');
        const icon = badge.find('i');
        const statusText = $('#statusText');
        
        if (count >= 8) {
            badge.removeClass('bg-warning-subtle text-warning')
                 .addClass('bg-success-subtle text-success');
            icon.removeClass('fa-question').addClass('fa-check');
            statusText.text('Complete');
        } else {
            badge.removeClass('bg-success-subtle text-success')
                 .addClass('bg-warning-subtle text-warning');
            icon.removeClass('fa-check').addClass('fa-question');
            statusText.text('Incomplete');
        }
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


