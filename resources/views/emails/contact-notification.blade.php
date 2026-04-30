<x-mail::message>
# New Contact Request

You have received a new contact request from the website.

**Details:**

@foreach($data as $key => $value)
@if($value)
- **{{ ucfirst(str_replace('_', ' ', $key)) }}:** {{ $value }}
@endif
@endforeach

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
