{{-- resources/views/student/coordinator-evaluation.blade.php --}}
@extends('layouts.intern')

@section('title', 'Evaluate Your Coordinator')

@section('content')
<section class="content-header px-0 px-sm-2">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">COORDINATOR EVALUATION</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Intern</li>
          <li class="breadcrumb-item active text-muted">Evaluate Coordinator</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
    <div class="container-fluid px-0 px-sm-2">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="ph ph-star text-warning me-2"></i>
                    Rate Your Coordinator: {{ $coordinator->user->fname }} {{ $coordinator->user->lname }}
                </h5>
                <small class="text-muted">HTE: {{ $hte->hte->organization_name ?? 'N/A' }}</small>
            </div>
            <div class="card-body">
                <form id="evaluationForm" method="POST" action="{{ route('coordinator-evaluation.store') }}">
                    @csrf
                    
                    <div class="alert alert-info">
                        <i class="ph ph-info me-2"></i>
                        Please rate your coordinator based on your experience during the internship. 
                        Your honest feedback will help us improve the program.
                    </div>
                    
                    @foreach($criteriaLabels as $key => $label)
                    <div class="form-group mb-4">
                        <label class="fw-bold mb-2">{{ $label }}</label>
                        <div class="rating-stars">
                            <div class="btn-group btn-group-toggle w-100" data-toggle="buttons">
                                @foreach($ratingLabels as $value => $ratingLabel)
                                <label class="btn btn-outline-warning flex-fill">
                                    <input type="radio" name="{{ $key }}" value="{{ $value }}" 
                                           required data-criterion="{{ $key }}">
                                    <span class="d-flex flex-column">
                                        <i class="ph-star{{ $value >= 1 ? '-fill' : '' }} fs-4"></i>
                                        <small>{{ $ratingLabel }}</small>
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @error($key)
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    @endforeach
                    
                    <div class="form-group mb-4">
                        <label class="fw-bold">Additional Comments</label>
                        <textarea name="comments" class="form-control" rows="4" 
                                  placeholder="Share your overall experience working with this coordinator..."></textarea>
                    </div>
                    
                    <div class="form-group mb-4">
                        <label class="fw-bold">Suggestions for Improvement</label>
                        <textarea name="suggestions" class="form-control" rows="3" 
                                  placeholder="Any suggestions to help improve the coordinator's support for future interns?"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="ph-paper-plane-tilt me-2"></i>
                            Submit Evaluation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Preview star rating on hover
    $('.rating-stars .btn').hover(function() {
        var $group = $(this).closest('.btn-group');
        var index = $group.find('.btn').index(this);
        
        $group.find('.btn').each(function(i) {
            if (i <= index) {
                $(this).find('.ph-star').removeClass('ph-star').addClass('ph-star-fill');
            } else {
                $(this).find('.ph-star-fill').removeClass('ph-star-fill').addClass('ph-star');
            }
        });
    }, function() {
        var $group = $(this).closest('.btn-group');
        var selected = $group.find('input:checked').val();
        
        $group.find('.btn').each(function(i) {
            if (selected && i < selected) {
                $(this).find('.ph-star').removeClass('ph-star').addClass('ph-star-fill');
            } else {
                $(this).find('.ph-star-fill').removeClass('ph-star-fill').addClass('ph-star');
            }
        });
    });
    
    // Form validation
    $('#evaluationForm').on('submit', function(e) {
        var allFilled = true;
        $('input[type="radio"][required]').each(function() {
            var name = $(this).attr('name');
            if (!$('input[name="' + name + '"]:checked').length) {
                allFilled = false;
                $(this).closest('.form-group').addClass('has-error');
            }
        });
        
        if (!allFilled) {
            e.preventDefault();
            alert('Please rate all criteria before submitting.');
            return false;
        }
        
        return confirm('Are you sure you want to submit this evaluation? Your feedback will be anonymous to the coordinator.');
    });
});
</script>

<style>
.rating-stars .btn {
    transition: all 0.3s ease;
}

.rating-stars .btn:hover {
    transform: translateY(-2px);
}

.rating-stars .btn.active,
.rating-stars .btn:active {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
}

.has-error .rating-stars {
    border: 1px solid #dc3545;
    border-radius: 4px;
    padding: 5px;
}

.ph-star-fill {
    color: #ffc107;
}
</style>
@endpush