@extends('layouts.plain')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-main text-white d-flex justify-content-between">
                    <h5 style="position: relative; top: 4px">Verify Organization Details</h5>
                </div>
                
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('hte.confirm-details') }}">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">First Name*</label>
                                <input type="text" class="form-control" name="contact_first_name" 
                                       value="{{ $hte->user->fname }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Last Name*</label>
                                <input type="text" class="form-control" name="contact_last_name" 
                                       value="{{ $hte->user->lname }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="{{ $hte->user->email }}" disabled>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact Number*</label>
                                <input type="text" class="form-control" name="contact_number" 
                                    value="{{ $hte->user->contact }}" required>
                            </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Address*</label>
                            <input type="text" class="form-control" name="address" 
                                   value="{{ $hte->address }}" required>
                        </div>
                        </div>



                        <div class="mb-3">
                            <label class="form-label">Organization Name*</label>
                            <input type="text" class="form-control" name="organization_name" 
                                   value="{{ $hte->organization_name }}" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Organization Type*</label>
                                <select class="form-select" name="organization_type" required>
                                    @foreach(['private', 'government', 'ngo', 'educational', 'other'] as $type)
                                        <option value="{{ $type }}" {{ $hte->type == $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Available Slots*</label>
                                <input type="number" class="form-control" name="slots" 
                                       value="{{ $hte->slots }}" min="1" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3">{{ $hte->description }}</textarea>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                Confirm and Continue ->
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection