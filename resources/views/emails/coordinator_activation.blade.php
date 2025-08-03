@component('mail::message')
# Hello {{ $coordinatorName }},

Your coordinator account has been successfully created.

@if($tempPassword)
**Temporary Password:** {{ $tempPassword }}  
*(You'll be required to change this after first login)*  
@endif

@component('mail::button', ['url' => $setupLink, 'color' => 'success'])
Set Your Password
@endcomponent

This activation link will expire in 24 hours. If you didn't request this account, please contact the administrator immediately.

Thanks,  
{{ config('app.name') }}
@endcomponent