@extends('layouts.coordinator')

@section('title', 'Deployment')

@section('content')
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="page-header">ENDORSEMENT</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item fw-medium">Coordinator</li>
          <li class="breadcrumb-item active text-muted">Deploy</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header bg-white">
        <h3 class="card-title">Select HTE</h3>
      </div>
      <div class="card-body">
        <div class="form-group">
          <label>Host Training Establishment</label>
          <select id="hteSelect" class="form-control select2 ">
            <option value="" selected disabled>Select an HTE</option>
            @foreach($htes as $hte)
              <option value="{{ $hte->id }}" data-skills="{{ $hte->skills->pluck('skill_id')->toJson() }}">
                {{ $hte->organization_name }}
              </option>
            @endforeach
          </select>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-header bg-white">
        <h3 class="card-title">Top Candidates</h3>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="internsTable" class="table table-bordered">
            <thead class="thead-light">
              <tr>
                <th width="3%" class="text-center">#</th>
                <th>Name</th>
                <th>Department</th>
                <th width="13%">Status</th>
                <th>Matching Skills</th>
                <th width="11%">Match %</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="7" class="text-center text-muted">Select an HTE to view recommended interns</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@section('scripts')

@endsection