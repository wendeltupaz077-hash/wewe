@extends('layouts.portal')

@section('page-title', 'Create Facility')

@section('content')
<div class="portal-card reveal facility-card-form">
    <div class="portal-card-header card-header">
        <div class="card-icon">🏥</div>
        <div>
            <h2>Create Facility</h2>
            <div class="record-count">Register a new partner facility</div>
        </div>
    </div>
    <form action="{{ route('portal.facilities.store') }}" method="POST">
        @csrf
        <div class="facility-card-form">
            <div class="form-grid">
                 <div class="form-field input-with-icon">
                     <label for="name">Facility Name</label>
                     <input class="field-input" type="text" id="name" name="name" value="{{ old('name') }}" required placeholder="e.g., Ormo Hospital">
                     <span class="field-icon">🏥</span>
                 </div>
 
                 <div class="form-field">
                     <label for="type">Type</label>
                     <select class="field-select" id="type" name="type">
                         <option value="hospital" {{ old('type') == 'hospital' ? 'selected' : '' }}>Hospital</option>
                         <option value="clinic" {{ old('type') == 'clinic' ? 'selected' : '' }}>Clinic</option>
                         <option value="mobile" {{ old('type') == 'mobile' ? 'selected' : '' }}>Mobile Unit</option>
                     </select>
                 </div>
 
                 <div class="form-field full-width input-with-icon">
                     <label for="address">Address</label>
                     <input class="field-input" type="text" id="address" name="address" value="{{ old('address') }}" placeholder="Street, Barangay">
                     <span class="field-icon">📍</span>
                 </div>
 
                 <div class="form-field">
                     <label for="city">City</label>
                     <input class="field-input" type="text" id="city" name="city" value="{{ old('city') }}" placeholder="City">
                 </div>
 
                 <div class="form-field">
                     <label for="province">Province</label>
                     <input class="field-input" type="text" id="province" name="province" value="{{ old('province') }}" placeholder="Province">
                 </div>
 
                 <div class="form-field input-with-icon">
                     <label for="contact_phone">Contact Phone</label>
                     <input class="field-input" type="text" id="contact_phone" name="contact_phone" value="{{ old('contact_phone') }}" placeholder="+63 9XX XXX XXXX">
                     <span class="field-icon">📞</span>
                 </div>
 
                 <div class="form-field input-with-icon">
                     <label for="contact_email">Contact Email</label>
                     <input class="field-input" type="email" id="contact_email" name="contact_email" value="{{ old('contact_email') }}" placeholder="contact@facility.ph">
                     <span class="field-icon">📧</span>
                 </div>
 
                 <div class="form-field">
                     <label for="head_user_id">Facility Head</label>
                     <select class="field-select" id="head_user_id" name="head_user_id">
                         <option value="">None</option>
                         @foreach($heads as $head)
                             <option value="{{ $head->id }}" {{ old('head_user_id') == $head->id ? 'selected' : '' }}>{{ $head->fullname ?? $head->name }} ({{ $head->role }})</option>
                         @endforeach
                     </select>
                 </div>
 
                 <div class="form-field toggle-row">
                     <div>
                         <label for="accepts_donations">Accepts Donations</label>
                         <p class="field-note">Allow donation receipts and donor intake.</p>
                     </div>
                     <label class="switch {{ old('accepts_donations') ? 'on' : '' }}" data-input-name="accepts_donations">
                         <div class="knob"></div>
                     </label>
                     <input id="accepts_donations" type="hidden" name="accepts_donations" value="{{ old('accepts_donations') ? '1' : '0' }}">
                 </div>
 
                 <div class="form-field toggle-row">
                     <div>
                         <label for="is_locked">Locked</label>
                         <p class="field-note">Disable facility edits while under review.</p>
                     </div>
                     <label class="switch {{ old('is_locked') ? 'on' : '' }}" data-input-name="is_locked">
                         <div class="knob"></div>
                     </label>
                     <input id="is_locked" type="hidden" name="is_locked" value="{{ old('is_locked') ? '1' : '0' }}">
                 </div>
             </div>
         </div>

         <div class="form-actions">
             <a href="{{ route('portal.facilities.index') }}" class="btn btn-ghost">Cancel</a>
             <button type="submit" class="btn btn-primary">Create Facility</button>
         </div>
     </form>
</div>
@endsection
