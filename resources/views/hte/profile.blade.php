@extends('layouts.hte')

@section('title', 'My Profile')

@section('content')

<style>
.skill-modal-item {
    transition: all 0.2s ease;
}

.skill-modal-item:hover {
    background-color: #f8f9fa;
}

.cursor-pointer {
    cursor: pointer;
}

.skill-filter-container {
    position: sticky;
    top: 0;
    background: white;
    z-index: 10;
    padding: 1rem;
    border-bottom: 1px solid #dee2e6;
}

.skills-list-container {
    max-height: 50vh;
    overflow-y: auto;
}

.selected-skills-container {
    max-height: 50vh;
    overflow-y: auto;
}

.selected-skill-badge {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 0.75rem;
    margin-bottom: 0.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.2s ease;
}

.selected-skill-badge:hover {
    background-color: #e9ecef;
}

.selected-skill-badge .skill-name {
    font-weight: 500;
}

.selected-skill-badge .remove-skill {
    color: #dc3545;
    cursor: pointer;
    opacity: 0.7;
    transition: opacity 0.2s ease;
}

.selected-skill-badge .remove-skill:hover {
    opacity: 1;
}

.empty-selected {
    text-align: center;
    padding: 2rem;
    color: #6c757d;
}

.search-container {
    position: sticky;
    top: 0;
    background: white;
    z-index: 10;
    padding: 1rem;
    border-bottom: 1px solid #dee2e6;
}
</style>

<section class="content-header">
    <div class="container-fluid px-0 px-sm-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="page-header">PROFILE MANAGEMENT</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item fw-medium">HTE</li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<section class="content">
    <div class="container-fluid px-0 px-sm-2">
        <div class="card shadow-sm ">
            <div class="card-header"><span class="fw-medium text-primary"><i class="ph ph-building-apartment custom-icons-i me-2"></i>Account Details</span></div>
            <div class="card-body">
                <!-- Profile Picture Upload -->
                <div class="text-center mb-5">
                    <div class="position-relative d-inline-block">
                        <img id="profileImage" 
                            src="{{ auth()->user()->pic ? asset('storage/'.auth()->user()->pic) : asset('profile_pics/profile.jpg') }}" 
                            class="rounded-circle shadow" 
                            width="150" 
                            height="150" 
                            alt="Profile Picture">
                        <label for="profileUpload" class="btn btn-sm btn-primary rounded-circle position-absolute bottom-0 end-0">
                            <i class="fas fa-camera"></i>
                            <input id="profileUpload" type="file" accept="image/*" class="d-none">
                        </label>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">JPG, PNG (Max 2MB)</small>
                    </div>
                </div>

                <!-- Account Information Form -->
                <form action="{{ route('hte.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label for="organization_name" class="form-label">Organization Name*</label>
                            <input type="text" class="form-control" id="organization_name" name="organization_name" 
                                   value="{{ auth()->user()->hte->organization_name }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email*</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ auth()->user()->email }}" disabled>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="fname" class="form-label">First Name*</label>
                                    <input type="text" class="form-control" id="fname" name="fname" 
                                           value="{{ auth()->user()->fname }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="lname" class="form-label">Last Name*</label>
                                    <input type="text" class="form-control" id="lname" name="lname" 
                                           value="{{ auth()->user()->lname }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="contact" class="form-label">Contact Number*</label>
                            <input type="text" class="form-control" id="contact" name="contact" 
                                   value="{{ auth()->user()->contact }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label">Address*</label>
                            <input type="text" class="form-control" id="address" name="address" 
                                   value="{{ auth()->user()->hte->address }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="organization_type" class="form-label">Organization Type*</label>
                            <select class="form-select" id="organization_type" name="organization_type" disabled>
                                <option value="private" {{ auth()->user()->hte->type == 'private' ? 'selected' : '' }}>Private</option>
                                <option value="government" {{ auth()->user()->hte->type == 'government' ? 'selected' : '' }}>Government</option>
                                <option value="ngo" {{ auth()->user()->hte->type == 'ngo' ? 'selected' : '' }}>NGO</option>
                                <option value="educational" {{ auth()->user()->hte->type == 'educational' ? 'selected' : '' }}>Educational</option>
                                <option value="other" {{ auth()->user()->hte->type == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" placeholder="Tell us more about your organization..." rows="3">{{ auth()->user()->hte->description }}</textarea>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <small class="text-muted">Leave blank to keep current password</small>
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end py-3 rounded-3 mt-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ph-fill ph-floppy-disk-back custom-icons-i mr-1"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Skills Section -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Required Skills</h5>
                <button type="button" class="btn btn-sm btn-outline-light text-muted border-0 ml-auto" data-toggle="modal" data-target="#skillsModal">
                    <i class="ph-fill ph-gear-six custom-icons-i mr-1"></i>Manage Skills
                </button>
            </div>
            <div class="card-body">
                @if(auth()->user()->hte->skills->count() > 0)
                    <div class="d-flex flex-wrap gap-2">
                        @foreach(auth()->user()->hte->skills as $skill)
                            <span class="badge bg-secondary-subtle text-secondary py-2 px-3 rounded-pill">
                                <i class="fas fa-check-circle mr-1"></i> {{ $skill->name }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-circle mr-2"></i> No skills selected yet
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Skills Modal - XL Size -->
<div class="modal fade" id="skillsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Required Skills</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="{{ route('hte.skills.update') }}" id="skillsForm">
                @csrf
                @method('PUT')
                
                <div class="modal-body p-0">
                    <div class="row g-0">
                        <!-- Left Side - Skills Selection -->
                        <div class="col-md-8 border-end">
                            <div class="container-fluid px-0">
                                <!-- Search Bar -->
                                <div class="search-container">
                                    <div class="mb-3">
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-search text-muted"></i>
                                            </span>
                                            <input type="text" 
                                                   id="skillSearchInput" 
                                                   class="form-control border-start-0 ps-0" 
                                                   placeholder="Search for a skill...">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i>
                                            Minimum 5 skills required
                                        </small>
                                        <span class="badge bg-light text-dark border">
                                            <i class="fas fa-check text-primary me-1"></i>
                                            Selected: <span id="selectedCount" class="fw-bold">0</span>/5
                                        </span>
                                    </div>
                                </div>

                                <!-- Skills List -->
                                <div class="skills-list-container" id="skillsList">
                                    @foreach($skills as $skill)
                                    <div class="skill-item" data-skill-id="{{ $skill->skill_id }}" data-skill-name="{{ strtolower($skill->name) }}" data-skill-name-display="{{ $skill->name }}">
                                        <div class="skill-modal-item p-3 border-bottom cursor-pointer">
                                            <div class="form-check mb-0">
                                                <input type="checkbox" name="skills[]" value="{{ $skill->skill_id }}" 
                                                    class="form-check-input skill-checkbox"
                                                    id="skill_{{ $skill->skill_id }}"
                                                    {{ in_array($skill->skill_id, $selectedSkills) ? 'checked' : '' }}>
                                                <label class="form-check-label fw-medium w-100 cursor-pointer" for="skill_{{ $skill->skill_id }}">
                                                    {{ $skill->name }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    <div id="noResultsMessage" class="text-center py-4 text-muted d-none">
                                        <i class="fas fa-search fa-2x mb-2 d-block"></i>
                                        No skills found
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side - Selected Skills -->
                        <div class="col-md-4">
                            <div class="p-3">
                                <h6 class="mb-3">
                                    <i class="fas fa-check-circle text-primary me-2"></i>
                                    Selected Skills
                                    <span id="selectedSkillsCount" class="badge bg-primary ms-2">0</span>
                                </h6>
                                <div id="selectedSkillsList" class="selected-skills-container">
                                    <!-- Dynamic selected skills will appear here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="submit" id="submitSkillsBtn" class="btn btn-primary" disabled>
                        <i class="ph-fill ph-floppy-disk-back custom-icons-i mr-1"></i> Save Skills
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Skills modal functionality
const skillSearchInput = document.getElementById('skillSearchInput');
const skillItems = document.querySelectorAll('.skill-item');
const noResultsMessage = document.getElementById('noResultsMessage');
const skillCheckboxes = document.querySelectorAll('.skill-checkbox');
const submitBtn = document.getElementById('submitSkillsBtn');
const selectedCountSpan = document.getElementById('selectedCount');
const selectedSkillsList = document.getElementById('selectedSkillsList');
const selectedSkillsCountSpan = document.getElementById('selectedSkillsCount');

function updateSelectedSkillsDisplay() {
    const selectedCheckboxes = document.querySelectorAll('.skill-checkbox:checked');
    const selectedCount = selectedCheckboxes.length;
    
    // Update counters
    selectedCountSpan.textContent = selectedCount;
    selectedSkillsCountSpan.textContent = selectedCount;
    submitBtn.disabled = selectedCount < 5;
    
    // Clear and rebuild selected skills list
    selectedSkillsList.innerHTML = '';
    
    if (selectedCount === 0) {
        selectedSkillsList.innerHTML = '<div class="empty-selected"><i class="fas fa-info-circle fa-2x mb-2 d-block"></i>No skills selected yet<br><small>Select at least 5 skills</small></div>';
        return;
    }
    
    selectedCheckboxes.forEach(checkbox => {
        const skillItem = checkbox.closest('.skill-item');
        const skillName = skillItem.getAttribute('data-skill-name-display');
        const skillId = checkbox.value;
        
        const selectedSkillDiv = document.createElement('div');
        selectedSkillDiv.className = 'selected-skill-badge';
        selectedSkillDiv.setAttribute('data-skill-id', skillId);
        selectedSkillDiv.innerHTML = `
            <div>
                <div class="skill-name">${skillName}</div>
            </div>
            <i class="fas fa-times-circle remove-skill" data-skill-id="${skillId}"></i>
        `;
        
        // Add remove functionality
        const removeIcon = selectedSkillDiv.querySelector('.remove-skill');
        removeIcon.addEventListener('click', function(e) {
            e.stopPropagation();
            const skillIdToRemove = this.getAttribute('data-skill-id');
            const checkboxToUncheck = document.querySelector(`.skill-checkbox[value="${skillIdToRemove}"]`);
            if (checkboxToUncheck) {
                checkboxToUncheck.checked = false;
                updateSelectedSkillsDisplay();
                // Trigger change event to update the checkbox styling if needed
                const event = new Event('change');
                checkboxToUncheck.dispatchEvent(event);
            }
        });
        
        selectedSkillsList.appendChild(selectedSkillDiv);
    });
}

function searchSkills() {
    const searchTerm = skillSearchInput.value.toLowerCase().trim();
    let visibleCount = 0;
    
    skillItems.forEach(item => {
        const skillName = item.getAttribute('data-skill-name');
        
        if (searchTerm === '' || skillName.includes(searchTerm)) {
            item.style.display = '';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });
    
    noResultsMessage.classList.toggle('d-none', visibleCount > 0);
}

// Initial setup
updateSelectedSkillsDisplay();

// Add event listeners
skillCheckboxes.forEach(checkbox => {
    checkbox.addEventListener('change', updateSelectedSkillsDisplay);
});

if (skillSearchInput) {
    skillSearchInput.addEventListener('input', searchSkills);
}

// Reset modal state when opened
$('#skillsModal').on('shown.bs.modal', function () {
    // Reset search input
    if (skillSearchInput) {
        skillSearchInput.value = '';
        searchSkills();
    }
    updateSelectedSkillsDisplay();
});
</script>

@endsection