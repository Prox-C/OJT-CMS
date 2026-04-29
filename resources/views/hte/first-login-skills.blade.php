@extends('layouts.plain')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h3 mb-1 text-primary">Select Required Skills</h2>
                    <p class="text-muted mb-0">Choose the skills you require from interns</p>
                </div>
                <div class="badge bg-light text-dark fs-6 px-3 py-2 border">
                    <i class="fas fa-check-circle text-primary me-1"></i>
                    Selected: <span id="selectedCount" class="fw-bold">0</span>/5 minimum
                </div>
            </div>

            <!-- Main Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="alert bg-info-subtle text-info border-0 mb-0 py-2">
                        <i class="ph-fill ph-info me-2"></i>
                        Choose at least 5 skills you require from interns.
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <form id="skillsForm" method="POST" action="{{ route('hte.save-skills') }}">
                        @csrf
                        @method('POST')
                        
                        <!-- Search and Filter Bar -->
                        <div class="search-filter-container p-3 border-bottom bg-light">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-search text-muted"></i>
                                        </span>
                                        <input type="text" 
                                               id="skillSearchInput" 
                                               class="form-control border-start-0 ps-0" 
                                               placeholder="Search for a skill...">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <select id="departmentFilter" class="form-select">
                                        <option value="all">All Departments</option>
                                        @foreach($departments as $department)
                                            @if($department->skills->count() > 0)
                                                <option value="dept_{{ $department->dept_id }}">{{ $department->dept_name }} ({{ $department->short_name }})</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3 d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Minimum 5 skills required
                                </small>
                                <span class="badge bg-light text-dark border">
                                    <i class="fas fa-check text-primary me-1"></i>
                                    Selected: <span id="selectedCountHeader" class="fw-bold">0</span>/5
                                </span>
                            </div>
                        </div>

                        <!-- Skills Container with Left-Right Layout -->
                        <div class="row g-0">
                            <!-- Left Side - Skills List -->
                            <div class="col-md-8 border-end">
                                <div class="skills-container" style="max-height: 55vh; overflow-y: auto;">
                                    @foreach($departments as $department)
                                        @if($department->skills->count() > 0)
                                            <div class="department-group p-3 border-bottom" data-dept-id="dept_{{ $department->dept_id }}">
                                                <h5 class="mb-3 text-primary department-header">
                                                    <i class="fas fa-building me-2"></i>
                                                    {{ $department->dept_name }} 
                                                    <small class="text-muted">({{ $department->short_name }})</small>
                                                </h5>
                                                
                                                <div class="row g-3">
                                                    @foreach($department->skills as $skill)
                                                        <div class="col-md-6 col-lg-4 skill-item" 
                                                             data-skill-id="{{ $skill->skill_id }}"
                                                             data-skill-name="{{ strtolower($skill->name) }}"
                                                             data-skill-name-display="{{ $skill->name }}">
                                                            <div class="skill-card p-3 border rounded h-100">
                                                                <div class="form-check mb-0">
                                                                    <input type="checkbox" 
                                                                           name="skills[]" 
                                                                           value="{{ $skill->skill_id }}"
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
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                    <div id="noResultsMessage" class="text-center py-5 text-muted d-none">
                                        <i class="fas fa-search fa-3x mb-3 d-block opacity-50"></i>
                                        <h6>No skills found</h6>
                                        <small>Try a different search term or department</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Side - Selected Skills Summary -->
                            <div class="col-md-4">
                                <div class="selected-skills-panel p-3" style="max-height: 55vh; overflow-y: auto;">
                                    <h6 class="mb-3">
                                        <i class="fas fa-check-circle text-primary me-2"></i>
                                        Selected Skills
                                        <span id="selectedSkillsCount" class="badge bg-primary ms-2">0</span>
                                    </h6>
                                    <div id="selectedSkillsList">
                                        <div class="empty-selected text-center py-4 text-muted">
                                            <i class="fas fa-info-circle fa-2x mb-2 d-block opacity-50"></i>
                                            <p class="mb-0 small">No skills selected yet</p>
                                            <small>Select at least 5 skills</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-white py-3 border-top-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    <i class="fas fa-asterisk text-danger me-1"></i>
                                    You can select multiple skills from different departments
                                </div>
                                <button type="submit" id="submitBtn" class="btn px-4" disabled>
                                    <i class="fas fa-save me-2"></i>Save Skills
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.skill-card {
    transition: all 0.2s ease;
    cursor: pointer;
    background-color: #fff;
}

.skill-card:hover {
    background-color: #f8f9fa;
    border-color: #0d6efd !important;
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.skill-card .form-check-input:checked ~ .form-check-label {
    color: #0d6efd;
    font-weight: 600;
}

.skill-card .form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.skills-container::-webkit-scrollbar,
.selected-skills-panel::-webkit-scrollbar {
    width: 6px;
}

.skills-container::-webkit-scrollbar-track,
.selected-skills-panel::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.skills-container::-webkit-scrollbar-thumb,
.selected-skills-panel::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.skills-container::-webkit-scrollbar-thumb:hover,
.selected-skills-panel::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
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
    padding: 2rem 1rem;
    color: #6c757d;
}

.search-filter-container {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.cursor-pointer {
    cursor: pointer;
}

.department-group.hidden-department {
    display: none;
}

.skill-item.hidden-skill {
    display: none;
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

.btn-success {
    background-color: #198754;
    border-color: #198754;
    color: white;
}

.btn-success:hover {
    background-color: #157347;
    border-color: #146c43;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const checkboxes = document.querySelectorAll('.skill-checkbox');
    const selectedCountSpan = document.getElementById('selectedCount');
    const selectedCountHeader = document.getElementById('selectedCountHeader');
    const submitBtn = document.getElementById('submitBtn');
    const selectedSkillsList = document.getElementById('selectedSkillsList');
    const selectedSkillsCountSpan = document.getElementById('selectedSkillsCount');
    const skillSearchInput = document.getElementById('skillSearchInput');
    const departmentFilter = document.getElementById('departmentFilter');
    const noResultsMessage = document.getElementById('noResultsMessage');
    
    // Get all skill items for filtering
    const skillItems = document.querySelectorAll('.skill-item');
    const departmentGroups = document.querySelectorAll('.department-group');
    
    // Function to update selected skills display in right panel
    function updateSelectedSkillsDisplay() {
        const selectedCheckboxes = document.querySelectorAll('.skill-checkbox:checked');
        const selectedCount = selectedCheckboxes.length;
        
        // Update counters
        selectedCountSpan.textContent = selectedCount;
        if (selectedCountHeader) selectedCountHeader.textContent = selectedCount;
        selectedSkillsCountSpan.textContent = selectedCount;
        
        // Enable/disable submit button based on minimum requirement
        submitBtn.disabled = selectedCount < 5;
        
        // Update button appearance
        if (submitBtn.disabled) {
            submitBtn.classList.remove('btn-success');
            submitBtn.classList.add('btn-secondary');
        } else {
            submitBtn.classList.remove('btn-secondary');
            submitBtn.classList.add('btn-success');
        }
        
        // Clear and rebuild selected skills list
        selectedSkillsList.innerHTML = '';
        
        if (selectedCount === 0) {
            selectedSkillsList.innerHTML = '<div class="empty-selected"><i class="fas fa-info-circle fa-2x mb-2 d-block opacity-50"></i><p class="mb-0 small">No skills selected yet</p><small>Select at least 5 skills</small></div>';
            return;
        }
        
        // Sort selected skills by name for better UX
        const selectedArray = Array.from(selectedCheckboxes).sort((a, b) => {
            const nameA = a.closest('.skill-item').getAttribute('data-skill-name-display') || '';
            const nameB = b.closest('.skill-item').getAttribute('data-skill-name-display') || '';
            return nameA.localeCompare(nameB);
        });
        
        selectedArray.forEach(checkbox => {
            const skillItem = checkbox.closest('.skill-item');
            const skillName = skillItem.getAttribute('data-skill-name-display');
            const skillId = checkbox.value;
            
            const selectedSkillDiv = document.createElement('div');
            selectedSkillDiv.className = 'selected-skill-badge';
            selectedSkillDiv.setAttribute('data-skill-id', skillId);
            selectedSkillDiv.innerHTML = `
                <div class="skill-name">${skillName}</div>
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
                    // Style the parent card
                    const parentCard = checkboxToUncheck.closest('.skill-card');
                    if (parentCard) {
                        if (!checkboxToUncheck.checked) {
                            parentCard.style.backgroundColor = '';
                            parentCard.style.borderColor = '';
                        }
                    }
                }
            });
            
            selectedSkillsList.appendChild(selectedSkillDiv);
        });
    }
    
    // Function to filter skills based on search and department
    function filterSkills() {
        const searchTerm = skillSearchInput ? skillSearchInput.value.toLowerCase().trim() : '';
        const selectedDept = departmentFilter ? departmentFilter.value : 'all';
        
        let anyVisible = false;
        
        // First, show/hide department groups based on filter
        departmentGroups.forEach(group => {
            const deptId = group.getAttribute('data-dept-id');
            let deptHasVisibleSkills = false;
            
            // Get all skill items in this department
            const skillsInDept = group.querySelectorAll('.skill-item');
            
            skillsInDept.forEach(skill => {
                const skillName = skill.getAttribute('data-skill-name') || '';
                const matchesSearch = searchTerm === '' || skillName.includes(searchTerm);
                const matchesDept = selectedDept === 'all' || deptId === selectedDept;
                
                if (matchesSearch && matchesDept) {
                    skill.style.display = '';
                    deptHasVisibleSkills = true;
                    anyVisible = true;
                } else {
                    skill.style.display = 'none';
                }
            });
            
            // Show/hide the entire department group
            if (selectedDept === 'all' || deptId === selectedDept) {
                if (deptHasVisibleSkills) {
                    group.style.display = '';
                } else {
                    group.style.display = 'none';
                }
            } else {
                group.style.display = 'none';
            }
        });
        
        // Show/hide no results message
        if (noResultsMessage) {
            noResultsMessage.classList.toggle('d-none', anyVisible);
        }
    }
    
    // Add click event to entire skill card for better UX
    document.querySelectorAll('.skill-card').forEach(card => {
        card.addEventListener('click', function(e) {
            // Don't toggle if clicking directly on checkbox
            if (e.target.type !== 'checkbox' && !e.target.classList.contains('form-check-input')) {
                const checkbox = this.querySelector('.skill-checkbox');
                if (checkbox) {
                    checkbox.checked = !checkbox.checked;
                    // Trigger change event
                    const event = new Event('change');
                    checkbox.dispatchEvent(event);
                    // Visual feedback
                    if (checkbox.checked) {
                        this.style.backgroundColor = '#e7f1ff';
                        this.style.borderColor = '#0d6efd';
                    } else {
                        this.style.backgroundColor = '';
                        this.style.borderColor = '';
                    }
                }
            }
        });
    });
    
    // Initialize count and add event listeners
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectedSkillsDisplay();
            // Update card styling
            const card = this.closest('.skill-card');
            if (card) {
                if (this.checked) {
                    card.style.backgroundColor = '#e7f1ff';
                    card.style.borderColor = '#0d6efd';
                } else {
                    card.style.backgroundColor = '';
                    card.style.borderColor = '';
                }
            }
        });
        
        // Initialize card styling for pre-checked items
        if (checkbox.checked) {
            const card = checkbox.closest('.skill-card');
            if (card) {
                card.style.backgroundColor = '#e7f1ff';
                card.style.borderColor = '#0d6efd';
            }
        }
    });
    
    // Add search input event listener
    if (skillSearchInput) {
        skillSearchInput.addEventListener('input', filterSkills);
    }
    
    // Add department filter event listener
    if (departmentFilter) {
        departmentFilter.addEventListener('change', filterSkills);
    }
    
    // Initial setup
    updateSelectedSkillsDisplay();
    
    // Reset filters when needed (optional)
    const resetFilters = () => {
        if (skillSearchInput) skillSearchInput.value = '';
        if (departmentFilter) departmentFilter.value = 'all';
        filterSkills();
    };
});
</script>
@endsection