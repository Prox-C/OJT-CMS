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

<!-- HTE: Skill Selection -->
<script>
$(document).ready(function() {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    
    const updateSelectionCount = () => {
        const selected = $('.skill-checkbox:checked').length;
        $('#selectedCount').text(selected);
        $('#submitBtn').prop('disabled', selected < 5);
    };

    $('.skill-checkbox').change(updateSelectionCount);
    updateSelectionCount();

    $('#skillsForm').submit(function(e) {
        e.preventDefault();
        
        if ($('.skill-checkbox:checked').length < 5) {
            alert('Please select at least 5 skills');
            return;
        }

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            data: $(this).serialize(),
            success: function(response) {
                if (response.redirect) {
                    window.location.href = response.redirect;
                }
            },
            error: function(xhr) {
                // Completely commented out error handling
                // if (xhr.status !== 419) {
                //     toastr.error(xhr.responseJSON?.message || 'Error saving skills');
                // }
                
                // Optional: You might want to keep this for debugging
                console.log('Error occurred:', xhr.status, xhr.responseText);
            }
        });
    });
});
</script>


<!-- HTE: MOA Handling -->
<script>
$(document).ready(function() {
    // Initialize Bootstrap custom file input
    if (typeof bsCustomFileInput !== 'undefined') {
        bsCustomFileInput.init();
    }

    // Initialize MOA handlers once
    initializeMoaHandlers();

    function initializeMoaHandlers() {
        // Show selected file name - using event delegation
        $(document).off('change', '.custom-file-input').on('change', '.custom-file-input', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });

        // MOA Upload Form Submission - using event delegation with proper cleanup
        $(document).off('submit', '#moaUploadForm').on('submit', '#moaUploadForm', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let submitBtn = $('#uploadBtn');
            
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Uploading...');
            
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                success: function(response) {
                    toastr.success(response.message);
                    // Update UI without refresh
                    $('.card-body').html(`
                        <div class="w-100">
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle mr-2"></i>
                                ${response.message}
                            </div>
                            
                            <div class="embed-responsive embed-responsive-16by9 mb-3">
                                <iframe src="${response.file_url}" 
                                        class="embed-responsive-item"
                                        style="border: 1px solid #eee;"
                                        frameborder="0"></iframe>
                            </div>
                            
                            <div class="d-flex justify-content-center gap-3">
                                <a href="${response.file_url}" 
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
                    `);
                    initializeMoaHandlers();
                },
                error: function(xhr) {
                    let errorMsg = xhr.responseJSON?.message || 'Error uploading MOA';
                    toastr.error(errorMsg);
                    submitBtn.prop('disabled', false).html('<i class="fas fa-upload mr-1"></i> Upload MOA');
                }
            });
        });

        // MOA Removal - using event delegation with proper cleanup
        $(document).off('click', '#removeMoaBtn').on('click', '#removeMoaBtn', function(e) {
            e.stopImmediatePropagation(); // Prevent multiple handlers from firing
            
            if (!confirm('Are you sure you want to remove your MOA? This action cannot be undone.')) {
                return;
            }
            
            let btn = $(this);
            let originalText = btn.html();
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Removing...');
            
            $.ajax({
                url: btn.data('url'),
                method: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                headers: { 
                    'Accept': 'application/json'
                },
                success: function(response) {
                    toastr.success(response.message);
                    // Update UI without refresh
                    $('.card-body').html(`
                        <div class="d-flex flex-column align-items-center justify-content-center py-4">
                            <div class="alert alert-warning text-center mb-4 w-100">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                ${response.message}
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
                                        Please upload a signed copy of the Memorandum of Agreement in PDF format.
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
                    `);
                    
                    // Reinitialize Bootstrap custom file input
                    if (typeof bsCustomFileInput !== 'undefined') {
                        bsCustomFileInput.init();
                    }
                    
                    // Reinitialize event handlers
                    initializeMoaHandlers();
                },
                error: function(xhr) {
                    let errorMsg = xhr.responseJSON?.message || 'Error removing MOA';
                    if (xhr.status === 419) {
                        errorMsg = 'Session expired. Please refresh the page and try again.';
                        location.reload();
                    }
                    toastr.error(errorMsg);
                    btn.prop('disabled', false).html(originalText);
                }
            });
        });
    }
});
</script>

<!-- Coordinator: HTE Preview -->
<script>
$(document).ready(function() {
    // Enhance MOA preview modal
    $('#moaPreviewModal').on('shown.bs.modal', function() {
        // Resize the iframe to fit content
        const iframe = $('#moaPreviewFrame');
        iframe.height(iframe.parent().height());
    });
});
</script>
