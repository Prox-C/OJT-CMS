@component('mail::message')
# HTE Account Registration

Dear {{ $contactName }},

Your organization **{{ $organizationName }}** has been registered.

**Email:** {{ $contactEmail }}  
**Temporary Password:** {{ $tempPassword }}

@component('mail::button', ['url' => $setupLink])
Set Your Password
@endcomponent

@if($hasMoa)
## MOA Attachment
Please find attached the MOA template.
@endif

Thanks,  
{{ config('app.name') }}
@endcomponent