@component('mail::message')

Dear {{ $details['name'] }}, <br>

Thank you for joining into attendance system.Please check your password


@component('mail::panel')
Email: {{ $details['email'] }} <br>
Password: {{ $details['password'] }}
@endcomponent
{{-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent --}}

Thanks,<br>
{{ env('APP_NAME') }}
@endcomponent
