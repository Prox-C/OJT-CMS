{{-- resources/views/student/coordinator-evaluation-view.blade.php --}}
@extends('layouts.intern')

@section('title', 'My Evaluation')

@section('content')
<section class="content-header px-0 px-sm-2">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">MY EVALUATION</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Intern</li>
          <li class="breadcrumb-item active text-muted">Evaluation Result</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
    <div class="container-fluid px-0 px-sm-2">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="ph-seal-check me-2"></i>
                    Evaluation Submitted Successfully!
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="display-4 text-warning">
                        {{ number_format($evaluation->average_rating, 1) }}/5
                    </div>
                    <p class="text-muted">Overall Rating</p>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar bg-warning" 
                             style="width: {{ ($evaluation->average_rating / 5) * 100 }}%"></div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th width="60%">Criteria</th>
                                <th width="20%">Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($criteriaLabels as $key => $label)
                            <tr>
                                <td>{{ $label }}</td>
                                <td>
                                    <span class="badge bg-warning text-dark">
                                        {{ $evaluation->$key }}/5
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($evaluation->comments)
                <div class="alert alert-light">
                    <strong><i class="ph-chat-text me-2"></i> Your Comments:</strong>
                    <p class="mt-2 mb-0">{{ $evaluation->comments }}</p>
                </div>
                @endif
                
                @if($evaluation->suggestions)
                <div class="alert alert-light">
                    <strong><i class="ph-lightbulb me-2"></i> Your Suggestions:</strong>
                    <p class="mt-2 mb-0">{{ $evaluation->suggestions }}</p>
                </div>
                @endif
                
                <div class="text-muted small mt-3">
                    Submitted on: {{ $evaluation->evaluated_at->format('F d, Y \a\t h:i A') }}
                </div>
                
                <!-- <div class="mt-4 text-center">
                    <a href="{{ route('intern.dashboard') }}" class="btn btn-primary">
                        <i class="ph-house me-2"></i>
                        Back to Dashboard
                    </a>
                </div> -->
            </div>
        </div>
    </div>
</section>
@endsection