<x-mail::message>
    # Welcome to {{ config('app.name') }}!

    You're almost done setting up your business. Please click the button below to verify your email and finish creating
    your account.

    <x-mail::button :url="route('app.verify-email', ['token' => $token])">
        Verify Email & Log In
    </x-mail::button>

    Thanks,<br>
    The {{ config('app.name') }} Team
</x-mail::message>