@push('styles')
    <style>
        /* Global Pointer Cursor */
        button,
        a,
        [role="button"],
        .cursor-pointer,
        [wire\:click],
        [x-on\:click],
        [\\@click] {
            cursor: pointer !important;
        }
    </style>
@endpush

<div
    class="max-w-[1400px] mx-auto bg-white lg:rounded-4xl rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.05)] px-4 lg:px-8 py-8 border border-base-300/30">

    @php
        $clients = $this->clients;
    @endphp

    <!-- Header Section -->
    <div class="mb-6 lg:mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-xl lg:text-2xl font-black tracking-tight mb-1 text-base-content">
                Manage Clients
            </h2>
            <p class="text-base-content/50 text-xs lg:text-sm font-medium max-w-xl">
                Oversee user accounts, adjust wallet balances, and distribute staking rewards.
            </p>
        </div>
        <div class="hidden md:flex items-center gap-3 bg-base-200/50 px-4 py-2.5 rounded-2xl border border-base-300/50">
            <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-wider opacity-50">
                <i data-lucide="users" class="w-3.5 h-3.5"></i>
                Total Clients
            </div>
            <div class="w-px h-4 bg-base-300/50"></div>
            <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-wider">
                <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                <span class="text-primary">{{ $clients->total() }} Users</span>
            </div>
        </div>
    </div>

    <!-- Table Header Actions -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-4 gap-4">
        <div>
            <h3 class="text-lg font-black tracking-tight text-base-content flex items-center gap-2 mb-1">
                Client Directory
                <span
                    class="bg-base-200 text-base-content/40 text-[9px] px-2 py-0.5 rounded-md font-black tracking-widest uppercase">{{ $clients->total() }}
                    Entries</span>
            </h3>
            <p class="text-xs font-medium opacity-40 max-w-sm">Manage user access and financial assets across the
                platform.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
            <div class="dropdown" x-data="{ open: false }">
                <button @click="open = !open"
                    class="btn btn-sm h-9 bg-white border border-base-300/50 hover:bg-base-100 px-4 font-bold text-[10px] rounded-xl shadow-sm">
                    <i data-lucide="arrow-up-down" class="w-3 h-3 opacity-40 mr-1.5"></i>
                    Sort
                    @if($sortBy !== 'created_at' || $sortDirection !== 'desc')
                        <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                    @endif
                </button>
                <ul x-show="open" @click.outside="open = false" x-transition
                    class="absolute z-10 mt-2 p-2 shadow-xl bg-white rounded-xl w-44 border border-base-300/30">
                    @foreach(['name' => 'Name', 'email' => 'Email', 'created_at' => 'Join Date'] as $col => $label)
                        <li>
                            <button wire:click="sort('{{ $col }}')" @click="open = false"
                                class="w-full text-left px-3 py-2 rounded-lg text-xs font-medium transition-all hover:bg-primary/5 hover:text-primary flex items-center justify-between {{ $sortBy === $col ? 'bg-primary/10 text-primary font-bold' : '' }}">
                                {{ $label }}
                                @if($sortBy === $col)
                                    <i data-lucide="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}"
                                        class="w-3 h-3"></i>
                                @endif
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="relative flex-1 lg:max-w-xs">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none opacity-30">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                </span>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search clients..."
                    class="input input-sm h-9 w-full bg-white border border-base-300/50 rounded-xl pl-9 text-[10px] font-medium focus:bg-base-100 transition-all">
            </div>
        </div>
    </div>

    <!-- Table Content -->
    <div class="lg:overflow-visible overflow-x-auto -mx-4 sm:-mx-6 lg:mx-0">
        <table class="table table-sm w-full border-separate border-spacing-y-1.5">
            <thead class="text-base-content/40 font-bold text-[10px] uppercase tracking-widest">
                <tr>
                    <th class="pl-6 bg-transparent cursor-pointer hover:text-primary transition-colors"
                        wire:click="sort('name')">
                        <span class="flex items-center gap-1">User Details
                            @if($sortBy === 'name') <i
                                data-lucide="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                            class="w-3 h-3 text-primary"></i> @endif
                        </span>
                    </th>
                    <th class="bg-transparent">Entities</th>
                    <th class="bg-transparent">Wallet Status</th>
                    <th class="bg-transparent cursor-pointer hover:text-primary transition-colors"
                        wire:click="sort('created_at')">
                        <span class="flex items-center gap-1">Joined
                            @if($sortBy === 'created_at') <i
                                data-lucide="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}"
                            class="w-3 h-3 text-primary"></i> @endif
                        </span>
                    </th>
                    <th class="text-right pr-6 bg-transparent">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clients as $client)
                    <tr class="bg-base-200/20 hover:bg-base-100 transition-all duration-200 cursor-pointer group">
                        <td class="pl-4 py-2.5 rounded-l-2xl border-y border-l border-base-300/30">
                            <div class="flex items-center gap-3">
                                <div class="avatar placeholder">
                                    <div
                                        class="bg-gradient-to-br from-primary/10 to-primary/20 text-primary rounded-full w-9 h-9 flex items-center justify-center ring-2 ring-primary/10">
                                        <span
                                            class="text-[10px] font-black tracking-tighter leading-none">{{ substr($client->name, 0, 1) }}{{ substr(explode(' ', $client->name)[1] ?? '', 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span
                                        class="font-bold text-sm text-base-content tracking-tight group-hover:text-primary transition-colors truncate max-w-[160px]">{{ $client->name }}</span>
                                    <span
                                        class="text-[9px] font-medium opacity-30 lowercase tracking-wider truncate max-w-[160px]">{{ $client->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-2.5 border-y border-base-300/30">
                            <div class="flex items-center gap-1.5">
                                <span class="text-xs font-bold text-base-content/70">{{ $client->businesses_count }}</span>
                                <span class="text-[9px] font-black opacity-20 uppercase tracking-widest">Filings</span>
                            </div>
                        </td>
                        <td class="py-2.5 border-y border-base-300/30">
                            <div class="flex items-center gap-2">
                                @if($client->wallet)
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-success"></span>
                                            <span
                                                class="text-[10px] font-bold text-success uppercase tracking-wider">Active</span>
                                        </div>
                                        <span
                                            class="text-[9px] font-medium opacity-30">{{ count($client->wallet->balances ?? []) }}
                                            Assets</span>
                                    </div>
                                @else
                                    <span class="text-[10px] font-bold opacity-20 uppercase tracking-wider">No Wallet</span>
                                @endif
                            </div>
                        </td>
                        <td class="py-2.5 border-y border-base-300/30 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span
                                    class="text-xs font-bold text-base-content/70">{{ $client->created_at->format('M d, Y') }}</span>
                                <span
                                    class="text-[9px] font-medium opacity-30 tracking-tight">{{ $client->created_at->diffForHumans() }}</span>
                            </div>
                        </td>
                        <td class="py-2.5 pr-4 rounded-r-2xl border-y border-r border-base-300/30 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button wire:click="showContact({{ $client->id }})" wire:loading.attr="disabled"
                                    class="btn btn-ghost btn-sm h-8 px-3 rounded-xl opacity-40 hover:opacity-100 hover:bg-primary/10 hover:text-primary transition-all flex items-center gap-1.5 group/contact">
                                    <div wire:loading.remove wire:target="showContact({{ $client->id }})">
                                        <i data-lucide="contact-round"
                                            class="w-3.5 h-3.5 group-hover/contact:scale-110 transition-transform"></i>
                                    </div>
                                    <div wire:loading wire:target="showContact({{ $client->id }})">
                                        <span class="loading loading-spinner w-3 h-3 text-primary"></span>
                                    </div>
                                    <span class="text-[9px] font-black uppercase tracking-widest">Contact</span>
                                </button>
                                <button wire:click="manageUser({{ $client->id }}, 'wallet')" wire:loading.attr="disabled"
                                    class="btn btn-ghost btn-sm h-8 px-3 rounded-xl opacity-40 hover:opacity-100 hover:bg-primary/10 hover:text-primary transition-all flex items-center gap-1.5 group/wallet">
                                    <div wire:loading.remove wire:target="manageUser({{ $client->id }}, 'wallet')">
                                        <i data-lucide="wallet"
                                            class="w-3.5 h-3.5 group-hover/wallet:scale-110 transition-transform"></i>
                                    </div>
                                    <div wire:loading wire:target="manageUser({{ $client->id }}, 'wallet')">
                                        <span class="loading loading-spinner w-3 h-3 text-primary"></span>
                                    </div>
                                    <span class="text-[9px] font-black uppercase tracking-widest">Wallet</span>
                                </button>

                                <button wire:click="manageTrading({{ $client->id }})" wire:loading.attr="disabled"
                                    class="btn btn-ghost btn-sm h-8 px-3 rounded-xl opacity-40 hover:opacity-100 hover:bg-warning/10 hover:text-warning transition-all flex items-center gap-1.5 group/trading">
                                    <div wire:loading.remove wire:target="manageTrading({{ $client->id }})">
                                        <i data-lucide="line-chart"
                                            class="w-3.5 h-3.5 group-hover/trading:scale-110 transition-transform"></i>
                                    </div>
                                    <div wire:loading wire:target="manageTrading({{ $client->id }})">
                                        <span class="loading loading-spinner w-3 h-3 text-warning"></span>
                                    </div>
                                    <span class="text-[9px] font-black uppercase tracking-widest">Trading</span>
                                </button>

                                <button wire:click="deleteUser({{ $client->id }})"
                                    wire:confirm="Are you sure you want to delete this user? This will permanently remove all their filings and assets."
                                    class="btn btn-ghost btn-sm btn-circle opacity-30 hover:opacity-100 hover:bg-error/10 hover:text-error transition-all"
                                    title="Delete User">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center">
                            <div class="flex flex-col items-center opacity-30">
                                <i data-lucide="users-round" class="w-14 h-14 mb-3" stroke-width="1"></i>
                                <p class="text-lg font-black tracking-tight mb-1">No clients found</p>
                                <p class="text-xs font-medium opacity-60">No user accounts match your search criteria.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($clients->hasPages())
        <div class="flex flex-col sm:flex-row justify-between items-center mt-12 gap-8 px-2">
            <div class="flex flex-col text-center sm:text-left">
                <div class="flex items-center gap-2 mb-1 justify-center sm:justify-start">
                    <i data-lucide="bar-chart-3" class="w-2.5 h-2.5 text-primary opacity-40"></i>
                    <span class="text-[9px] font-black uppercase tracking-[0.3em] opacity-20">Directory Insights</span>
                </div>
                <p class="text-[11px] font-bold text-base-content/40 tracking-tight">
                    Displaying <span
                        class="text-base-content font-black">{{ $clients->firstItem() }}-{{ $clients->lastItem() }}</span>
                    of {{ $clients->total() }} total clients
                </p>
            </div>

            <div class="flex items-center gap-2">
                <button wire:click="previousPage" {{ $clients->onFirstPage() ? 'disabled' : '' }}
                    class="btn btn-ghost h-12 px-6 rounded-2xl border border-base-300/30 bg-white shadow-sm hover:bg-primary hover:text-white hover:border-primary disabled:opacity-60 transition-all group">
                    <i data-lucide="arrow-left"
                        class="w-3.5 h-3.5 mr-2 group-hover:-translate-x-1 transition-transform"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest">Back</span>
                </button>

                <div
                    class="h-12 px-6 bg-white border border-base-300/30 rounded-2xl flex flex-col items-center justify-center shadow-sm min-w-[80px]">
                    <span class="text-[8px] font-black uppercase tracking-[0.2em] opacity-20 leading-none mb-1">Page</span>
                    <span class="text-[11px] font-black tracking-tight leading-none">{{ $clients->currentPage() }} <span
                            class="opacity-20 mx-0.5">/</span> {{ $clients->lastPage() }}</span>
                </div>

                <button wire:click="nextPage" {{ !$clients->hasMorePages() ? 'disabled' : '' }}
                    class="btn btn-ghost h-12 px-6 rounded-2xl border border-base-300/30 bg-white shadow-sm hover:bg-primary hover:text-white hover:border-primary disabled:opacity-60 transition-all group">
                    <span class="text-[10px] font-black uppercase tracking-widest">Next</span>
                    <i data-lucide="arrow-right"
                        class="w-3.5 h-3.5 ml-2 group-hover:translate-x-1 transition-transform"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Wallet & Activity Consolidated Modal -->
    @if(in_array($manageTab, ['wallet', 'activity']) && $managingUser = $this->managingUser)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4">
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeModal"></div>
            <div
                class="relative bg-white rounded-[1.5rem] lg:rounded-[2.5rem] shadow-2xl w-full max-w-5xl border border-base-300/30 animate-in zoom-in-95 fade-in duration-200 overflow-hidden flex flex-col h-[85vh] max-h-[90vh]">
                <div
                    class="bg-base-200/50 px-4 py-4 lg:px-8 lg:py-5 border-b border-base-300/20 flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-3 lg:gap-4">
                        <div
                            class="w-10 h-10 lg:w-12 lg:h-12 bg-primary rounded-xl lg:rounded-2xl flex items-center justify-center shadow-lg shadow-primary/20 text-white">
                            <i data-lucide="{{ $manageTab === 'wallet' ? 'wallet' : 'archive' }}"
                                class="w-5 h-5 lg:w-6 lg:h-6"></i>
                        </div>
                        <div>
                            <h3
                                class="text-sm lg:text-lg font-black tracking-tight text-base-content uppercase leading-tight">
                                Account Management</h3>
                            <p
                                class="text-[8px] lg:text-[10px] font-bold opacity-30 uppercase tracking-widest leading-none mt-0.5">
                                {{ $managingUser->name }}
                            </p>
                        </div>
                    </div>
                    <button wire:click="closeModal"
                        class="btn btn-ghost btn-sm btn-circle bg-base-200/50 opacity-50 hover:opacity-100">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <!-- Tabs Navigation -->
                <div class="flex px-4 lg:px-8 bg-base-100 border-b border-base-200 shrink-0 overflow-x-auto no-scrollbar">
                    <button wire:click="$set('manageTab', 'wallet')"
                        class="pb-3 px-4 lg:px-6 text-[9px] lg:text-[10px] font-black uppercase tracking-widest lg:tracking-[0.2em] transition-all relative cursor-pointer whitespace-nowrap {{ $manageTab === 'wallet' ? 'text-primary' : 'opacity-30 hover:opacity-60' }}">
                        Wallet Management
                        @if($manageTab === 'wallet')
                            <div class="absolute bottom-0 left-0 right-0 h-1 bg-primary rounded-t-full"></div>
                        @endif
                    </button>
                    <button wire:click="$set('manageTab', 'activity')"
                        class="pb-3 px-4 lg:px-6 text-[9px] lg:text-[10px] font-black uppercase tracking-widest lg:tracking-[0.2em] transition-all relative cursor-pointer whitespace-nowrap {{ $manageTab === 'activity' ? 'text-primary' : 'opacity-30 hover:opacity-60' }}">
                        Activity Log
                        @if($manageTab === 'activity')
                            <div class="absolute bottom-0 left-0 right-0 h-1 bg-primary rounded-t-full"></div>
                        @endif
                    </button>
                </div>

                <div class="flex-1 overflow-hidden flex flex-col h-full relative">
                    @if($manageTab === 'wallet')
                        <div class="flex-1 overflow-hidden flex flex-col lg:flex-row h-full">
                            <!-- Asset Slider -->
                            <div
                                class="w-full lg:w-64 border-b lg:border-b-0 lg:border-r border-base-200 bg-base-200/10 flex flex-col h-[220px] lg:h-full shrink-0 min-h-0">
                                <div class="p-4 border-b border-base-200 flex justify-between items-center">
                                    <p class="text-[9px] font-black uppercase tracking-widest opacity-30">Assets</p>
                                </div>
                                <div class="flex-1 overflow-y-auto p-2 space-y-1.5 custom-scrollbar">
                                    @php $userBalances = $managingUser->wallet->balances ?? []; @endphp
                                    @foreach($this->availableAssets as $assetId)
                                        @php $hasBalance = (float) ($userBalances[$assetId] ?? 0) > 0; @endphp
                                        <button wire:click="$set('selectedAssetId', '{{ $assetId }}')"
                                            class="w-full flex items-center justify-between p-2 lg:p-3 rounded-xl transition-all group cursor-pointer {{ $selectedAssetId === $assetId ? 'bg-white shadow-lg border border-base-300/50' : 'hover:bg-white/50' }}">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-10 h-10 rounded-xl bg-primary/5 flex items-center justify-center group-hover:scale-110 transition-transform">
                                                    <i data-lucide="coins"
                                                        class="w-5 h-5 {{ $selectedAssetId === $assetId ? 'text-primary' : 'opacity-20' }}"></i>
                                                </div>
                                                <div class="text-left">
                                                    <p
                                                        class="text-[10px] font-black uppercase tracking-wider {{ $selectedAssetId === $assetId ? 'text-base-content' : 'opacity-40' }}">
                                                        {{ $this->assetNames[$assetId] ?? str($assetId)->replace('-', ' ') }}
                                                    </p>
                                                    @if($hasBalance)
                                                        <p class="text-[8px] font-bold text-success uppercase tracking-widest mt-0.5">
                                                            Funded</p>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($selectedAssetId === $assetId)
                                                <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Adjustment View -->
                            <div class="flex-1 overflow-y-auto p-4 lg:p-8 bg-white custom-scrollbar">
                                <div class="max-w-xl mx-auto space-y-4 lg:space-y-6">
                                    <div
                                        class="bg-base-200/20 rounded-2xl lg:rounded-[2rem] p-4 lg:p-6 border border-base-300/30 flex items-center justify-between shadow-sm">
                                        <div class="flex items-center gap-3 lg:gap-4 text-left">
                                            <div
                                                class="w-10 h-10 lg:w-12 lg:h-12 bg-white rounded-xl flex items-center justify-center shadow-sm border border-base-200 shrink-0">
                                                <i data-lucide="wallet-2" class="w-5 h-5 lg:w-6 lg:h-6 text-primary"></i>
                                            </div>
                                            <div>
                                                <span
                                                    class="text-[7px] lg:text-[8px] font-black uppercase tracking-[0.1em] text-primary/60">Current
                                                    Balance</span>
                                                <h4
                                                    class="text-xs lg:text-base font-black tracking-tight text-base-content uppercase">
                                                    {{ $this->assetNames[$selectedAssetId] ?? str($selectedAssetId)->replace('-', ' ') }}
                                                </h4>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div
                                                class="text-lg lg:text-2xl font-black tracking-tighter text-base-content tabular-nums leading-none">
                                                {{ number_format((float) ($userBalances[$selectedAssetId] ?? 0), 8) }}
                                                <span
                                                    class="text-[8px] lg:text-[10px] opacity-20 uppercase font-black tracking-widest ml-1">{{ $this->assetSymbols[$selectedAssetId] ?? '' }}</span>
                                            </div>
                                            <p
                                                class="text-[9px] lg:text-[11px] font-black text-base-content/30 uppercase tracking-widest mt-1">
                                                ≈
                                                ${{ number_format((float) ($userBalances[$selectedAssetId] ?? 0) * (float) ($this->prices[$selectedAssetId] ?? 0), 2) }}
                                                USD
                                            </p>
                                        </div>
                                    </div>

                                    <div class="bg-base-200/20 rounded-3xl p-4 border border-base-300/30 space-y-8">
                                        <div class="form-control">
                                            <label class="label mb-1"><span
                                                    class="label-text text-[10px] font-black uppercase tracking-widest opacity-40">Direction</span></label>
                                            <div class="flex bg-white rounded-2xl p-1.5 border border-base-300/50 h-13">
                                                <button wire:click="$set('adjustmentType', 'credit')"
                                                    class="flex-1 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all cursor-pointer {{ $adjustmentType === 'credit' ? 'bg-success text-white shadow-xl shadow-success/20' : 'opacity-40 hover:opacity-60' }}">
                                                    Credit
                                                </button>
                                                <button wire:click="$set('adjustmentType', 'debit')"
                                                    class="flex-1 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all cursor-pointer {{ $adjustmentType === 'debit' ? 'bg-error text-white shadow-xl shadow-error/20' : 'opacity-40 hover:opacity-60' }}">
                                                    Debit
                                                </button>
                                            </div>
                                        </div>
                                        <div class="form-control">
                                            <label class="label mb-1"><span
                                                    class="label-text text-[10px] font-black uppercase tracking-widest opacity-40">Amount
                                                    (USD)</span></label>
                                            <div class="relative group">
                                                <input wire:model.live="adjustmentAmount" type="number" step="0.01"
                                                    placeholder="0.00"
                                                    class="input input-bordered h-14 w-full rounded-2xl bg-white pl-14 text-xl font-black focus:ring-8 focus:ring-primary/5 transition-all">
                                                <i data-lucide="dollar-sign"
                                                    class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 opacity-20 group-focus-within:opacity-100 transition-opacity"></i>
                                            </div>
                                            @if($this->cryptoEquivalent > 0)
                                                <div
                                                    class="mt-4 p-4 rounded-2xl bg-primary/5 border border-primary/10 flex items-center justify-between">
                                                    <p class="text-[8px] font-black uppercase tracking-widest opacity-40">Converted
                                                        Value</p>
                                                    <p class="text-xs font-black text-primary">
                                                        {{ number_format($this->cryptoEquivalent, 8) }}
                                                        {{ $this->assetSymbols[$selectedAssetId] ?? '' }}
                                                    </p>
                                                </div>
                                            @endif
                                            @error('adjustmentAmount') <span
                                            class="text-error text-xs mt-3 font-bold">{{ $message }}</span> @enderror
                                        </div>
                                        <button wire:click="applyWalletAdjustment"
                                            class="btn btn-primary h-14 w-full rounded-2xl font-black text-sm uppercase tracking-widest shadow-2xl shadow-primary/20">
                                            Update Balance
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex-1 overflow-y-auto p-10 bg-white custom-scrollbar">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                @php $transactions = $this->transactions; @endphp
                                @forelse($transactions as $transaction)
                                    <div
                                        class="group relative bg-white rounded-[2rem] p-6 border border-base-200/60 hover:border-primary/20 hover:shadow-2xl hover:shadow-primary/5 transition-all flex flex-col justify-between overflow-hidden min-h-[200px]">
                                        <div
                                            class="absolute left-0 top-0 bottom-0 w-1.5 {{ $transaction->type === 'receive' ? 'bg-success' : 'bg-warning' }} opacity-0 group-hover:opacity-100 transition-opacity">
                                        </div>
                                        <div>
                                            <div class="flex items-start justify-between mb-4">
                                                <div class="relative">
                                                    <div
                                                        class="w-14 h-14 rounded-[1.5rem] flex items-center justify-center transition-all duration-500 {{ $transaction->type === 'receive' ? 'bg-success/5 text-success' : 'bg-warning/5 text-warning' }} group-hover:scale-110">
                                                        <i data-lucide="{{ $transaction->type === 'receive' ? 'trending-up' : 'trending-down' }}"
                                                            class="w-7 h-7"></i>
                                                    </div>
                                                </div>
                                                <button wire:confirm="Are you sure you want to revert this transaction?"
                                                    wire:click="deleteTransaction({{ $transaction->id }})"
                                                    class="w-12 h-12 rounded-2xl bg-base-200/30 hover:bg-error/10 hover:text-error transition-all flex items-center justify-center cursor-pointer">
                                                    <i data-lucide="trash-2"
                                                        class="w-5 h-5 opacity-20 group-hover:opacity-100 transition-all"></i>
                                                </button>
                                            </div>
                                            <div class="space-y-3">
                                                <h4 class="text-sm font-black uppercase tracking-tight text-base-content truncate">
                                                    {{ $transaction->recipient_address }}
                                                </h4>
                                                <div class="flex items-center gap-4 border-t border-base-100 pt-3">
                                                    <div class="flex items-center gap-2 min-w-0">
                                                        <i data-lucide="hash" class="w-3.5 h-3.5 opacity-20 shrink-0"></i>
                                                        <p
                                                            class="text-[9px] font-bold opacity-40 uppercase tracking-widest truncate">
                                                            {{ substr($transaction->hash, 0, 10) }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-6 flex items-end justify-between">
                                            <div>
                                                <p class="text-[10px] font-black opacity-30 uppercase tracking-[0.2em] mb-1">
                                                    {{ $this->assetNames[$transaction->asset_id] ?? $transaction->asset_id }}
                                                </p>
                                                <span
                                                    class="text-xl font-black tracking-tighter {{ $transaction->type === 'receive' ? 'text-success' : 'text-warning' }}">
                                                    {{ $transaction->type === 'receive' ? '+' : '-' }}{{ number_format($transaction->amount, 8) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div
                                        class="col-span-full py-16 bg-base-100 rounded-[2.5rem] border-2 border-dashed border-base-200 flex flex-col items-center justify-center text-center">
                                        <i data-lucide="archive" class="w-14 h-14 opacity-10 mb-5"></i>
                                        <h4 class="text-base font-black opacity-40 uppercase tracking-widest">No transaction history
                                            found</h4>
                                    </div>
                                @endforelse
                            </div>

                            @if($transactions->hasPages())
                                <div class="flex justify-center mt-12">
                                    {{ $transactions->links() }}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Staking & Trading Consolidated Modal -->
    @if(in_array($manageTab, ['staking', 'trading']) && $managingUser = $this->managingUser)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4">
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeModal"></div>
            <div
                class="relative bg-white rounded-[1.5rem] lg:rounded-[2.5rem] shadow-2xl w-full max-w-3xl border border-base-300/30 animate-in zoom-in-95 fade-in duration-200 overflow-hidden flex flex-col h-[85vh] max-h-[90vh]">
                <div
                    class="bg-base-200/50 px-4 py-4 lg:px-8 lg:py-5 border-b border-base-300/20 flex items-center justify-between shrink-0">
                    <div class="flex items-center gap-3 lg:gap-4">
                        <div
                            class="w-10 h-10 lg:w-12 lg:h-12 {{ $manageTab === 'staking' ? 'bg-success shadow-success/20' : 'bg-warning shadow-warning/20' }} rounded-xl lg:rounded-2xl flex items-center justify-center shadow-lg text-white">
                            <i data-lucide="{{ $manageTab === 'staking' ? 'zap' : 'line-chart' }}"
                                class="w-5 h-5 lg:w-6 lg:h-6"></i>
                        </div>
                        <div>
                            <h3
                                class="text-sm lg:text-lg font-black tracking-tight text-base-content uppercase leading-tight">
                                Growth & Trading</h3>
                            <p
                                class="text-[8px] lg:text-[10px] font-bold opacity-30 uppercase tracking-widest leading-none mt-0.5">
                                {{ $managingUser->name }}
                            </p>
                        </div>
                    </div>
                    <button wire:click="closeModal"
                        class="btn btn-ghost btn-sm btn-circle bg-base-200/50 opacity-50 hover:opacity-100">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <!-- Tabs Navigation -->
                <div class="flex px-4 lg:px-8 bg-base-100 border-b border-base-200 shrink-0 overflow-x-auto no-scrollbar">
                    <button wire:click="$set('manageTab', 'staking')"
                        class="pb-3 px-4 lg:px-6 text-[9px] lg:text-[10px] font-black uppercase tracking-widest lg:tracking-[0.2em] transition-all relative cursor-pointer whitespace-nowrap {{ $manageTab === 'staking' ? 'text-primary' : 'opacity-30 hover:opacity-60' }}">
                        Staking Controls
                        @if($manageTab === 'staking')
                            <div class="absolute bottom-0 left-0 right-0 h-1 bg-primary rounded-t-full"></div>
                        @endif
                    </button>
                    <button wire:click="$set('manageTab', 'trading')"
                        class="pb-3 px-4 lg:px-6 text-[9px] lg:text-[10px] font-black uppercase tracking-widest lg:tracking-[0.2em] transition-all relative cursor-pointer whitespace-nowrap {{ $manageTab === 'trading' ? 'text-primary' : 'opacity-30 hover:opacity-60' }}">
                        Trading Accounts
                        @if($manageTab === 'trading')
                            <div class="absolute bottom-0 left-0 right-0 h-1 bg-primary rounded-t-full"></div>
                        @endif
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto custom-scrollbar">
                    @if($manageTab === 'staking')
                        <div class="p-8 space-y-6">
                            @forelse($managingUser->stakes()->where('status', 'active')->latest()->get() as $stake)
                                <div class="space-y-4">
                                    <div wire:click="{{ $selectedStakeId === $stake->id ? '$set(\'selectedStakeId\', null)' : '$set(\'selectedStakeId\', ' . $stake->id . ')' }}"
                                        class="bg-base-200/30 rounded-[1.5rem] p-6 border-2 transition-all cursor-pointer flex items-center justify-between group {{ $selectedStakeId === $stake->id ? 'border-primary bg-primary/5' : 'border-base-300/20 hover:border-primary/40' }}">
                                        <div class="flex items-center gap-6 text-left">
                                            <div
                                                class="bg-primary/10 text-primary rounded-2xl w-14 h-14 flex items-center justify-center group-hover:scale-110 transition-transform">
                                                <i data-lucide="zap" class="w-7 h-7"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-base font-black uppercase tracking-wider text-base-content">
                                                    {{ $this->assetNames[$stake->asset_id] ?? str($stake->asset_id)->replace('-', ' ') }}
                                                </h4>
                                                <div class="flex items-center gap-3 mt-2">
                                                    <span
                                                        class="text-[11px] font-bold opacity-50">{{ number_format($stake->amount, 4) }}
                                                        Staked</span>
                                                    <span class="w-1.5 h-1.5 rounded-full bg-base-300"></span>
                                                    <span class="text-[11px] font-black text-primary">{{ $stake->apy }}% APY</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span
                                                class="text-xs font-black text-success uppercase tracking-widest">+{{ number_format($stake->earned_rewards, 8) }}</span>
                                            <p class="text-[9px] font-bold opacity-20 uppercase tracking-[0.2em] mt-1">Rewards</p>
                                        </div>
                                    </div>

                                    @if($selectedStakeId === $stake->id)
                                        <div
                                            class="bg-white border-2 border-base-200 rounded-[1.5rem] p-6 shadow-xl animate-in slide-in-from-top-2 duration-300">
                                            <div class="flex items-center justify-between gap-6">
                                                <div
                                                    class="flex p-1 bg-base-100 rounded-2xl shadow-inner border border-base-200 shrink-0">
                                                    <button wire:click="$set('rewardAdjustmentMode', 'credit')"
                                                        class="px-6 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all cursor-pointer {{ $rewardAdjustmentMode === 'credit' ? 'bg-primary text-white shadow-md' : 'text-base-content/40' }}">
                                                        Credit
                                                    </button>
                                                    <button wire:click="$set('rewardAdjustmentMode', 'debit')"
                                                        class="px-6 py-2 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all cursor-pointer {{ $rewardAdjustmentMode === 'debit' ? 'bg-warning text-[#0A0C10] shadow-md' : 'text-base-content/40' }}">
                                                        Debit
                                                    </button>
                                                </div>
                                                <div class="flex-1 relative group">
                                                    <input wire:model.live="rewardAmount" type="number" step="0.01" placeholder="0.00"
                                                        class="w-full h-14 bg-base-100 border-base-200 rounded-2xl pl-10 pr-4 text-center text-base font-black focus:ring-4 focus:ring-primary/10 transition-all">
                                                    <i data-lucide="dollar-sign"
                                                        class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 opacity-20 group-focus-within:opacity-100 transition-opacity"></i>
                                                </div>
                                                <button wire:click="addStakeReward"
                                                    class="h-14 px-8 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl transition-all {{ $rewardAdjustmentMode === 'debit' ? 'bg-warning text-[#0A0C10]' : 'bg-primary text-white' }}">
                                                    Confirm
                                                </button>
                                            </div>
                                            @error('rewardAmount') <p
                                                class="text-error text-[10px] font-black uppercase tracking-widest mt-4 text-center">
                                                {{ $message }}
                                            </p> @enderror
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div
                                    class="py-16 bg-base-200/20 rounded-[3rem] border-2 border-dashed border-base-300/50 flex flex-col items-center justify-center text-center">
                                    <i data-lucide="shield-alert" class="w-16 h-16 opacity-10 mb-4"></i>
                                    <p class="text-xs font-black opacity-30 uppercase tracking-widest">No active staking detected
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    @else
                        <div class="p-6 lg:p-8 space-y-6">
                            <div class="bg-base-200/20 rounded-3xl p-6 border border-base-300/30 flex flex-col items-center justify-center text-center space-y-3 shadow-sm">
                                <div class="w-12 h-12 lg:w-14 lg:h-14 bg-white rounded-2xl flex items-center justify-center shadow-xl border border-base-200">
                                    <i data-lucide="bar-chart-2" class="w-6 h-6 lg:w-7 lg:h-7 text-primary"></i>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black uppercase tracking-[0.3em] text-primary/60 mb-1">Manual Trading Balance</p>
                                    <h4 class="text-2xl lg:text-3xl font-black tracking-tighter text-base-content tabular-nums">
                                        ${{ number_format($managingUser->tradingWallet->balance ?? 0, 2) }}
                                    </h4>
                                </div>
                            </div>

                            <div class="bg-base-200/20 rounded-3xl p-6 lg:p-8 border border-base-300/30 space-y-6">
                                <div class="form-control">
                                    <label class="label mb-1.5"><span class="label-text text-[10px] font-black uppercase tracking-widest opacity-40">Direction</span></label>
                                    <div class="flex bg-white rounded-2xl p-1.5 border border-base-300/50 h-13">
                                        <button wire:click="$set('tradingAdjustmentType', 'credit')"
                                            class="flex-1 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all cursor-pointer {{ $tradingAdjustmentType === 'credit' ? 'bg-primary text-white shadow-xl' : 'opacity-40 hover:opacity-60' }}">
                                            Credit
                                        </button>
                                        <button wire:click="$set('tradingAdjustmentType', 'debit')"
                                            class="flex-1 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all cursor-pointer {{ $tradingAdjustmentType === 'debit' ? 'bg-warning text-[#0A0C10] shadow-xl' : 'opacity-40 hover:opacity-60' }}">
                                            Debit
                                        </button>
                                    </div>
                                </div>
                                <div class="form-control">
                                    <label class="label mb-1.5"><span class="label-text text-[10px] font-black uppercase tracking-widest opacity-40">USD Amount</span></label>
                                    <div class="relative group">
                                        <input wire:model.live="tradingBalanceAmount" type="number" step="0.01" placeholder="0.00"
                                            class="input input-bordered h-14 w-full rounded-2xl bg-white pl-14 text-xl font-black focus:ring-8 focus:ring-primary/5 transition-all">
                                        <i data-lucide="dollar-sign" class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 opacity-20 group-focus-within:opacity-100 transition-opacity"></i>
                                    </div>
                                    @error('tradingBalanceAmount') <span class="text-error text-xs mt-3 font-bold">{{ $message }}</span> @enderror
                                </div>
                                <button wire:click="applyTradingAdjustment" class="btn btn-primary h-14 w-full rounded-2xl font-black text-sm uppercase tracking-widest shadow-2xl shadow-primary/20">
                                    Sync Trading Funds
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Contact Info Modal -->
    @if($contactUser = $this->contactUser)
            <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                <!-- Backdrop -->
                <div class="absolute inset-0 bg-black/60 backdrop-blur-md" wire:click="closeContactModal"></div>

                <!-- Modal Content -->
                <div class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md border border-base-300/30 animate-in zoom-in-95 fade-in duration-200 overflow-hidden">
                    <!-- Header -->
                    <div class="bg-base-200/50 px-8 py-6 border-b border-base-300/20 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                                <i data-lucide="shield-check" class="w-5 h-5 text-primary"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black tracking-tight text-base-content">Verified Contact</h3>
                                <p class="text-[10px] font-bold text-primary uppercase tracking-widest opacity-60">Identity
                                    Confirmed</p>
                            </div>
                        </div>
                        <button wire:click="closeContactModal"
                            class="btn btn-ghost btn-sm btn-circle bg-base-200/50 opacity-50 hover:opacity-100">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="p-8 space-y-6">
                        <!-- User Header -->
                        <div class="flex items-center gap-4 pb-6 border-b border-base-100">
                            <div class="avatar placeholder">
                                <div
                                    class="bg-gradient-to-br from-primary/10 to-primary/20 text-primary rounded-2xl w-12 h-12 flex items-center justify-center ring-4 ring-primary/5">
                                    <span
                                        class="text-sm font-black tracking-tighter">{{ substr($contactUser->name, 0, 1) }}{{ substr(explode(' ', $contactUser->name)[1] ?? '', 0, 1) }}</span>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-black text-base text-base-content tracking-tight">{{ $contactUser->name }}</h4>
                                <p class="text-[10px] font-bold opacity-40 uppercase tracking-widest">{{ $contactUser->email }}
                                </p>
                            </div>
                        </div>

                        <!-- Contact Details -->
                        <div class="space-y-4">
                            <div
                                class="bg-base-200/30 rounded-3xl p-5 border border-base-300/10 transition-all hover:border-primary/20 group">
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-10 h-10 rounded-2xl bg-white shadow-sm flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                        <i data-lucide="phone" class="w-4 h-4 text-primary"></i>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-[9px] font-black uppercase tracking-[0.2em] opacity-30 mb-1">Mobile Phone
                                        </p>
                                        <p class="text-sm font-bold text-base-content tracking-tight">
                                            {{ $contactUser->phone ?? 'Not provided' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="bg-base-200/30 rounded-3xl p-5 border border-base-300/10 transition-all hover:border-primary/20 group">
                                <div class="flex items-start gap-4">
                                    <div
                                        class="w-10 h-10 rounded-2xl bg-white shadow-sm flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                        <i data-lucide="map-pin" class="w-4 h-4 text-primary"></i>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-[9px] font-black uppercase tracking-[0.2em] opacity-30 mb-1">Residential
                                            Address</p>
                                        <p class="text-sm font-bold text-base-content leading-relaxed tracking-tight">
                                            {{ $contactUser->address ?? 'No address on file' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Security Footer -->
                        <div class="pt-4">
                            <div
                                class="bg-primary/5 rounded-2xl p-4 border border-primary/10 flex items-center justify-center gap-3">
                                <i data-lucide="lock" class="w-3.5 h-3.5 text-primary opacity-40"></i>
                                <p class="text-[8px] font-black uppercase tracking-[0.3em] text-primary">End-to-End Encrypted
                                    Data</p>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    @endif
</div>