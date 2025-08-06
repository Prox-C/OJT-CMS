@component('mail::message')
# HTE Account Registration

Greetings {{ $contactName }},

As a representative of **{{ $organizationName }}**, a selected Host Training Establishment (HTE) for
the internship programs of Eastern Visayas State University, you are required to set up your account 
on our Internship Management System.

**Email:** {{ $contactEmail }}  
**Temporary Password:** {{ $tempPassword }}

@component('mail::button', ['url' => $setupLink, 'color' => 'success'])
Set Your Password
@endcomponent

@if($hasMoa)
## MOA Attachment
Please find attached the MOA template.
@endif

Thanks,  
{{ config('app.name') }}
@endcomponent