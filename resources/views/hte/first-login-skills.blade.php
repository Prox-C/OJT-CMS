@extends('layouts.plain')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Select Required Skills</h4>
                        <span class="badge bg-light text-dark">
                            Selected: <span id="selectedCount">0</span>/5 minimum
                        </span>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i> Please select at least 5 skills you require from interns
                    </div>

                    <form id="skillsForm" method="POST" action="{{ route('hte.save-skills') }}">
                        @csrf
                        @method('POST')
                        <div class="skills-container" style="max-height: 60vh; overflow-y: auto;">
                            @foreach($departments as $department)
                                @if($department->skills->count() > 0)
                                    <div class="department-group mb-4">
                                        <h5 class="mb-3 border-bottom pb-2">
                                            <i class="fas fa-graduation-cap mr-2"></i>
                                            {{ $department->dept_name }} ({{ $department->short_name }})
                                        </h5>
                                        
                                        <div class="row">
                                            @foreach($department->skills as $skill)
                                                <div class="col-md-6 mb-3">
                                                    <div class="skill-item p-3 border rounded hover-shadow">
                                                        <label class="d-flex align-items-center m-0">
                                                            <input type="checkbox" 
                                                                   name="skills[]" 
                                                                   value="{{ $skill->skill_id }}"
                                                                   class="skill-checkbox mr-3"
                                                                   {{ in_array($skill->skill_id, $selectedSkills) ? 'checked' : '' }}>
                                                            <span class="font-weight-medium">{{ $skill->name }}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        
                        <div class="card-footer bg-white text-right mt-4">
                            <button type="submit" id="submitBtn" class="btn btn-primary" disabled>
                                <i class="fas fa-save mr-1"></i> Save Skills
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .hover-shadow:hover {
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
    }
    .skill-item {
        transition: all 0.2s ease;
    }
    .skill-item:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush

@push('scripts')

@endpush
@endsection