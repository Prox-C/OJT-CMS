@component('mail::message')
# Hello {{ $internName }},

Your intern account has been created by your coordinator.

@component('mail::panel')
**Temporary Password:** {{ $tempPassword }}
@endcomponent

Please set your permanent password by clicking the button below:

@component('mail::button', ['url' => $setupLink, 'color' => 'success'])
Set Your Password
@endcomponent

This link will expire in 24 hours. If you didn't request this account, please contact your coordinator.

Thanks,  
{{ config('app.name') }}
@endcomponent