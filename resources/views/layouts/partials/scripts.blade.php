<!-- jQuery -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>

<!-- JQueryKnobCharts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-knob/1.2.13/jquery.knob.min.js"></script>

<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>

<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<!-- Toastr -->
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

<!-- Coordinator: Recommended Interns -->
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: 'Select HTE',
    });

    function createKnobDisplay(value, color) {
        const angle = value * 3.6; // % to degrees
        return `
        <div class="d-flex justify-content-center align-items-center">
            <div class="knob-container">
                <div class="knob-display">
                    <div class="knob-bg" style="
                        background: conic-gradient(${color} ${angle}deg, #e9ecef 0);
                    ">
                        <div class="knob-center">${value}%</div>
                    </div>
                </div>
            </div>
        </div>
        `;
    }




    // Rest of your existing HTE selection change handler...
    $('#hteSelect').change(function() {
        const hteId = $(this).val();
        const requiredSkills = $(this).find(':selected').data('skills');
        
        if (!hteId) {
            $('#internsTable tbody').html('<tr><td colspan="7" class="text-center text-muted">Select an HTE to view recommended interns</td></tr>');
            return;
        }

        // Loading state
        $('#internsTable tbody').html('<tr><td colspan="7" class="text-center"><i class="fas fa-spinner fa-spin"></i> Loading recommendations...</td></tr>');

        $.ajax({
            url: '{{ route("coordinator.getRecommendedInterns") }}',
            method: 'POST',
            data: {
                hte_id: hteId,
                required_skills: requiredSkills,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success && response.interns.length > 0) {
                    let html = '';
                    response.interns.forEach((intern, index) => {
                        const isIncomplete = intern.status === 'incomplete';
                        const statusClass = intern.status === 'pending' ? 'text-warning bg-warning-subtle' : 
                                         intern.status === 'endorsed' ? 'text-success' : 'text-danger bg-danger-subtle';
                        
                        // Determine color based on percentage
                        let knobColor = intern.match_percentage >= 70 ? '#198754' :
                                      intern.match_percentage >= 40 ? '#ffc107' :
                                      '#dc3545';
                        
                        html += `
                        <tr ${isIncomplete ? 'class="table-light-gray"' : ''}>
                            <td class="align-middle text-center">${index + 1}</td>
                            <td class="align-middle">${intern.fname} ${intern.lname}</td>
                            <td class="align-middle">${intern.department}</td>
                            <td class="align-middle"><span class="badge px-3 py-2 w-100 rounded-pill ${statusClass}">${intern.status.toUpperCase()}</span></td>
                            <td class="align-middle small text-muted">${intern.matching_skills.join(', ') || 'None'}</td>
                            <td class="align-middle">
                                ${createKnobDisplay(intern.match_percentage, knobColor)}
                            </td>
                            <td class="align-middle">
                                <button class="btn btn-sm btn-primary endorse-btn" 
                                        data-intern-id="${intern.id}" 
                                        data-hte-id="${hteId}"
                                        ${isIncomplete ? 'disabled' : ''}>
                                    <i class="fas fa-paper-plane"></i> Endorse
                                </button>
                            </td>
                        </tr>
                        `;
                    });

                    $('#internsTable tbody').html(html);

                } else {
                    $('#internsTable tbody').html('<tr><td colspan="7" class="text-center">No interns found matching the required skills.</td></tr>');
                }
            },
            error: function(xhr, status, error) {
                let errorMsg = 'Error loading data. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                $('#internsTable tbody').html(`<tr><td colspan="7" class="text-center text-danger">${errorMsg}</td></tr>`);
            }
        });
    });
});
</script>

<!-- Coordinator: Intern Import -->
<script>
// Import form handling
$('#importForm').on('submit', function(e) {
    e.preventDefault();
    
    const form = $(this);
    const submitBtn = $('#importSubmit');
    const progress = $('#importProgress');
    const results = $('#importResults');
    const spinner = progress.find('.spinner-border'); // Get the spinner element
    
    // Show progress, hide results
    progress.removeClass('d-none');
    results.addClass('d-none');
    submitBtn.prop('disabled', true);
    
    // Prepare form data
    const formData = new FormData(this);
    
    // AJAX request
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        xhr: function() {
            const xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percent = Math.round((e.loaded / e.total) * 100);
                    $('.progress-bar').css('width', percent + '%');
                }
            });
            return xhr;
        },
        success: function(response) {
            if (response.success) {
                // Update results display
                $('#successCount').text(response.success_count);
                $('#failCount').text(response.fail_count);
                
                if (response.failures.length > 0) {
                    const failBody = $('#failDetailsBody');
                    failBody.empty();
                    
                    response.failures.forEach(failure => {
                        failBody.append(`
                            <tr>
                                <td>${failure.row}</td>
                                <td>${failure.student_id || 'N/A'}</td>
                                <td>${failure.name || 'N/A'}</td>
                                <td>${failure.errors.join('<br>')}</td>
                            </tr>
                        `);
                    });
                    
                    $('#failDetails').removeClass('d-none');
                } else {
                    $('#failDetails').addClass('d-none');
                }
                
                // Show results and hide spinner
                spinner.addClass('d-none'); // Hide the spinner
                $('#importResults').removeClass('d-none');
                
                // Refresh the interns table via AJAX without reloading the page
                if (response.success_count > 0) {
                    $.get(window.location.href, function(data) {
                        const newTable = $(data).find('#internsTable').html();
                        $('#internsTable').html(newTable);
                    });
                }
            }
        },
        error: function(xhr) {
            let errorMsg = 'An error occurred during import.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
            }
            alert(errorMsg);
        },
        complete: function() {
            submitBtn.prop('disabled', false);
            $('#progressText').text('Import completed');
            $('.progress-bar').removeClass('progress-bar-animated');
            spinner.addClass('d-none'); // Ensure spinner is hidden when complete
        }
    });
});

// Reset modal when closed
$('#importModal').on('hidden.bs.modal', function() {
    $('#importForm')[0].reset();
    $('#importProgress').addClass('d-none');
    $('#importResults').addClass('d-none');
    $('.progress-bar').css('width', '0%').addClass('progress-bar-animated');
    $('#progressText').text('Processing import...');
    // Show spinner again when modal is reopened
    $('#importProgress').find('.spinner-border').removeClass('d-none');
});
</script>
