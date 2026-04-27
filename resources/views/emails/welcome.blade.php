<x-mail::message>
# Welcome to the Platform, {{ $user->name }}!

We are thrilled to have you with us. Your account has been successfully verified, and you're now ready to manage your business formations with ease.

<x-mail::panel>
Your account is fully active and you can now access all features of the **{{ config('app.name') }}** platform.
</x-mail::panel>

<x-mail::button :url="route('app.dashboard')">
Go to Dashboard
</x-mail::button>

If you have any questions or need assistance, our support team is always here to help.

To your success,
The {{ config('app.name') }} Team
</x-mail::message>