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
                                <button wire:click="manageUser({{ $client->id }})" wire:loading.attr="disabled"
                                    class="btn btn-ghost btn-sm h-8 px-3 rounded-xl opacity-40 hover:opacity-100 hover:bg-primary/10 hover:text-primary transition-all flex items-center gap-1.5 group/btn">
                                    <div wire:loading.remove wire:target="manageUser({{ $client->id }})">
                                        <i data-lucide="cog"
                                            class="w-3.5 h-3.5 group-hover/btn:rotate-90 transition-transform duration-500"></i>
                                    </div>
                                    <div wire:loading wire:target="manageUser({{ $client->id }})">
                                        <span class="loading loading-spinner w-3 h-3 text-primary"></span>
                                    </div>
                                    <span class="text-[9px] font-black uppercase tracking-widest">Manage</span>
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

    <!-- Manage User Modal -->
    @if($managingUser = $this->managingUser)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeModal"></div>

            <!-- Modal Content -->
            <div
                class="relative bg-white rounded-[1.5rem] lg:rounded-[2.5rem] shadow-2xl w-full max-w-5xl h-full lg:max-h-[85vh] flex flex-col border border-base-300/30 animate-in zoom-in-95 fade-in duration-200 overflow-hidden">
                <!-- Header -->
                <div
                    class="bg-base-200/50 px-4 lg:px-8 py-3 lg:py-5 flex items-center justify-between border-b border-base-300/20 shrink-0">
                    <div class="flex items-center gap-3 lg:gap-5">
                        <div class="avatar placeholder">
                            <div
                                class="bg-primary text-white rounded-[0.85rem] lg:rounded-[1.25rem] w-9 lg:w-13 h-9 lg:h-13 flex items-center justify-center shadow-xl shadow-primary/20 ring-4 ring-primary/10">
                                <span
                                    class="text-[10px] lg:text-sm font-black tracking-tighter">{{ substr($managingUser->name, 0, 1) }}{{ substr(explode(' ', $managingUser->name)[1] ?? '', 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm lg:text-lg font-black tracking-tight text-base-content truncate">
                                {{ $managingUser->name }}</h3>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span
                                    class="text-[9px] lg:text-[10px] font-bold uppercase tracking-widest opacity-40 truncate max-w-[120px] lg:max-w-none">{{ $managingUser->email }}</span>
                                <span class="hidden sm:inline w-1 h-1 rounded-full bg-base-300"></span>
                                <span
                                    class="hidden sm:inline text-[9px] lg:text-[10px] font-bold text-primary uppercase tracking-widest">Client
                                    Account</span>
                            </div>
                        </div>
                    </div>
                    <button wire:click="closeModal"
                        class="btn btn-ghost btn-xs lg:btn-sm btn-circle opacity-50 hover:opacity-100 bg-base-200/50">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <!-- Tabs -->
                <div class="flex px-4 lg:px-8 bg-base-100 border-b border-base-200 shrink-0">
                    <button wire:click="$set('manageTab', 'wallet')"
                        class="pb-2.5 lg:pb-3.5 px-4 lg:px-6 text-[9px] lg:text-[10px] font-black uppercase tracking-[0.1em] lg:tracking-[0.2em] transition-all relative cursor-pointer {{ $manageTab === 'wallet' ? 'text-primary' : 'opacity-30 hover:opacity-60' }}">
                        Wallet Management
                        @if($manageTab === 'wallet')
                            <div class="absolute bottom-0 left-0 right-0 h-1 bg-primary rounded-t-full"></div>
                        @endif
                    </button>
                    <button wire:click="$set('manageTab', 'staking')"
                        class="pb-2.5 lg:pb-3.5 px-4 lg:px-6 text-[9px] lg:text-[10px] font-black uppercase tracking-[0.1em] lg:tracking-[0.2em] transition-all relative cursor-pointer {{ $manageTab === 'staking' ? 'text-primary' : 'opacity-30 hover:opacity-60' }}">
                        Staking Controls
                        @if($manageTab === 'staking')
                            <div class="absolute bottom-0 left-0 right-0 h-1 bg-primary rounded-t-full"></div>
                        @endif
                    </button>
                    <button wire:click="$set('manageTab', 'activity')"
                        class="pb-2.5 lg:pb-3.5 px-4 lg:px-6 text-[9px] lg:text-[10px] font-black uppercase tracking-[0.1em] lg:tracking-[0.2em] transition-all relative cursor-pointer {{ $manageTab === 'activity' ? 'text-primary' : 'opacity-30 hover:opacity-60' }}">
                        Activity Log
                        @if($manageTab === 'activity')
                            <div class="absolute bottom-0 left-0 right-0 h-1 bg-primary rounded-t-full"></div>
                        @endif
                    </button>
                </div>


                <!-- Body -->
                <div class="flex-1 overflow-hidden flex flex-col h-full relative">
                    <!-- Tab Transition Loader -->
                    <div wire:loading.flex wire:target="manageTab"
                        class="absolute inset-0 bg-white z-[60] items-center justify-center animate-in fade-in duration-300">

                        <div class="flex flex-col items-center gap-4">
                            <div class="relative">
                                <div
                                    class="w-12 h-12 rounded-full border-4 border-primary/10 border-t-primary animate-spin">
                                </div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-2 h-2 bg-primary rounded-full animate-pulse"></div>
                                </div>
                            </div>
                            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-primary/40 animate-pulse">
                                Synchronizing Interface</p>
                        </div>
                    </div>

                    @if($manageTab === 'wallet')

                        <div class="flex flex-col lg:flex-row h-full">
                            <!-- Left: Vertical Asset Slider -->
                            <div
                                class="w-full lg:w-64 border-b lg:border-b-0 lg:border-r border-base-200 bg-base-200/10 flex flex-col h-[140px] lg:h-full shrink-0">
                                <div class="p-2.5 lg:p-4 border-b border-base-200 flex justify-between items-center">
                                    <p class="text-[8px] lg:text-[9px] font-black uppercase tracking-widest opacity-30">
                                        Assets</p>
                                    <span class="lg:hidden text-[7px] font-bold opacity-30 uppercase tracking-widest">Select</span>
                                </div>
                                <div class="flex-1 overflow-y-auto p-1.5 lg:p-2 space-y-1 lg:space-y-1.5 custom-scrollbar">
                                    @php $userBalances = $managingUser->wallet->balances ?? []; @endphp
                                    @foreach($this->availableAssets as $assetId)
                                        @php $hasBalance = (float) ($userBalances[$assetId] ?? 0) > 0; @endphp
                                        <button wire:click="$set('selectedAssetId', '{{ $assetId }}')"
                                            class="w-full flex items-center justify-between p-2 lg:p-3 rounded-lg lg:rounded-xl transition-all group cursor-pointer {{ $selectedAssetId === $assetId ? 'bg-white shadow-lg shadow-base-300/20 border border-base-300/50' : 'hover:bg-white/50' }}">
                                            <div class="flex items-center gap-2 lg:gap-3">
                                                <div
                                                    class="w-8 lg:w-10 h-8 lg:h-10 rounded-lg lg:rounded-xl bg-primary/5 flex items-center justify-center group-hover:scale-110 transition-transform">
                                                    <i data-lucide="coins"
                                                        class="w-4 lg:w-5 h-4 lg:h-5 {{ $selectedAssetId === $assetId ? 'text-primary' : 'opacity-20' }}"></i>
                                                </div>
                                                <div class="text-left">
                                                    <p
                                                        class="text-[9px] lg:text-[10px] font-black uppercase tracking-wider {{ $selectedAssetId === $assetId ? 'text-base-content' : 'opacity-40' }}">
                                                        {{ $this->assetNames[$assetId] ?? str($assetId)->replace('-', ' ') }}</p>
                                                    @if($hasBalance)
                                                        <p
                                                            class="text-[7px] lg:text-[8px] font-bold text-success uppercase tracking-widest mt-0.5">
                                                            Funded</p>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($selectedAssetId === $assetId)
                                                <div class="w-1 lg:w-1.5 h-4 lg:h-6 bg-primary rounded-full"></div>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Right: Detailed Adjustment View -->
                            <div class="flex-1 overflow-y-auto p-5 lg:p-8 bg-white">
                                <div class="max-w-xl mx-auto space-y-4 lg:space-y-6">
                                    <!-- Asset Title & Balance -->
                                    <!-- Asset Title & Balance -->
                                    <div class="bg-base-200/20 rounded-[1.5rem] lg:rounded-[2rem] p-4 lg:p-6 border border-base-300/30 flex flex-col sm:flex-row items-start sm:items-center justify-between shadow-sm relative overflow-hidden gap-3 lg:gap-4">
                                        <div class="flex items-center gap-3 lg:gap-4">
                                            <div class="w-10 lg:w-12 h-10 lg:h-12 bg-white rounded-xl flex items-center justify-center shadow-sm border border-base-200 relative shrink-0">
                                                <div class="absolute inset-0 bg-primary/5 blur-lg rounded-full"></div>
                                                <i data-lucide="wallet-2" class="w-5 lg:w-6 h-5 lg:h-6 text-primary relative"></i>
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-1.5 mb-0.5">
                                                    <span class="w-1 h-1 rounded-full bg-primary/40"></span>
                                                    <span class="text-[8px] font-black uppercase tracking-[0.1em] text-primary/60">Current Balance</span>
                                                </div>
                                                <h4 class="text-sm lg:text-base font-black tracking-tight text-base-content uppercase">
                                                    {{ $this->assetNames[$selectedAssetId] ?? str($selectedAssetId)->replace('-', ' ') }}
                                                </h4>
                                            </div>
                                        </div>
                                        
                                        <div class="text-left sm:text-right w-full sm:w-auto flex flex-col sm:items-end">
                                            <div class="text-xl lg:text-2xl font-black tracking-tighter text-base-content tabular-nums flex items-baseline gap-1">
                                                {{ number_format((float) ($userBalances[$selectedAssetId] ?? 0), 8) }}
                                                <span class="text-[9px] lg:text-[10px] opacity-20 uppercase font-black tracking-widest">{{ $this->assetSymbols[$selectedAssetId] ?? '' }}</span>
                                            </div>
                                            <p class="text-[11px] font-black text-base-content/30 uppercase tracking-widest tabular-nums mt-0.5">
                                                ≈ ${{ number_format((float)($userBalances[$selectedAssetId] ?? 0) * (float)($this->prices[$selectedAssetId] ?? 0), 2) }} USD
                                            </p>
                                        </div>
                                    </div>


                                    <!-- Adjustment Form -->
                                    <div
                                        class="bg-base-200/20 rounded-[1.5rem] lg:rounded-[2.5rem] p-6 lg:p-10 border border-base-300/30 space-y-6 lg:space-y-8">
                                        <div class="form-control">
                                            <label class="label mb-1"><span
                                                    class="label-text text-[9px] lg:text-[10px] font-black uppercase tracking-widest opacity-40">Direction</span></label>
                                            <div
                                                class="flex bg-white rounded-xl lg:rounded-2xl p-1 lg:p-1.5 border border-base-300/50 h-11 lg:h-13">
                                                <button wire:click="$set('adjustmentType', 'credit')"
                                                    class="flex-1 rounded-lg lg:rounded-xl text-[9px] lg:text-[10px] font-black uppercase tracking-widest transition-all cursor-pointer {{ $adjustmentType === 'credit' ? 'bg-success text-white shadow-lg lg:shadow-xl shadow-success/20' : 'opacity-40 hover:opacity-60' }}">
                                                    <i data-lucide="plus-circle"
                                                        class="w-3 lg:w-4 h-3 lg:h-4 inline-block mr-1 lg:mr-2"></i> Credit
                                                </button>
                                                <button wire:click="$set('adjustmentType', 'debit')"
                                                    class="flex-1 rounded-lg lg:rounded-xl text-[9px] lg:text-[10px] font-black uppercase tracking-widest transition-all cursor-pointer {{ $adjustmentType === 'debit' ? 'bg-error text-white shadow-lg lg:shadow-xl shadow-error/20' : 'opacity-40 hover:opacity-60' }}">
                                                    <i data-lucide="minus-circle"
                                                        class="w-3 lg:w-4 h-3 lg:h-4 inline-block mr-1 lg:mr-2"></i> Debit
                                                </button>
                                            </div>
                                        </div>

                                        <div class="form-control">
                                            <label class="label mb-1"><span
                                                    class="label-text text-[9px] lg:text-[10px] font-black uppercase tracking-widest opacity-40">Amount
                                                    to {{ $adjustmentType }} (USD)</span></label>
                                            <div class="relative group">
                                                <input wire:model.live="adjustmentAmount" type="number" step="0.01"
                                                    placeholder="0.00"
                                                    class="input input-bordered h-12 lg:h-14 w-full rounded-xl lg:rounded-2xl bg-white pl-12 lg:pl-14 text-lg lg:text-xl font-black focus:ring-4 lg:focus:ring-8 focus:ring-primary/5 transition-all placeholder:opacity-10">
                                                <i data-lucide="dollar-sign"
                                                    class="absolute left-4 lg:left-5 top-1/2 -translate-y-1/2 w-4 lg:w-5 h-4 lg:h-5 opacity-20 group-focus-within:opacity-100 transition-opacity"></i>
                                            </div>
                                            @if($this->cryptoEquivalent > 0)
                                                <div
                                                    class="mt-4 p-4 rounded-2xl bg-primary/5 border border-primary/10 flex items-center justify-between animate-in fade-in slide-in-from-top-2">
                                                    <div class="flex items-center gap-3">
                                                        <div
                                                            class="w-8 h-8 rounded-lg bg-primary text-white flex items-center justify-center">
                                                            <i data-lucide="refresh-cw" class="w-4 h-4 animate-spin-slow"></i>
                                                        </div>
                                                        <div>
                                                            <p class="text-[8px] font-black uppercase tracking-widest opacity-40">
                                                                Calculated Value</p>
                                                            <p class="text-xs font-black text-primary">
                                                                {{ number_format($this->cryptoEquivalent, 8) }}
                                                                {{ $this->assetSymbols[$selectedAssetId] ?? '' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <p class="text-[7px] font-bold opacity-30 uppercase tracking-widest">Rate
                                                        </p>
                                                        <p class="text-[9px] font-bold text-base-content/60">
                                                            ${{ number_format((float) ($this->prices[$selectedAssetId] ?? 0), 2) }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                            @error('adjustmentAmount') <span
                                                class="text-error text-[10px] lg:text-xs mt-2 lg:mt-3 font-bold ml-1">{{ $message }}</span>
                                            @enderror
                                        </div>


                                        <button wire:click="applyWalletAdjustment"
                                            class="btn btn-primary h-12 lg:h-14 w-full rounded-xl lg:rounded-2xl font-black text-xs lg:text-sm uppercase tracking-widest shadow-xl lg:shadow-2xl shadow-primary/20 group/submit mt-2 lg:mt-4"
                                            wire:loading.attr="disabled">
                                            <span wire:loading.remove wire:target="applyWalletAdjustment"
                                                class="flex items-center gap-2 lg:gap-3">
                                                Update Balance
                                                <i data-lucide="chevron-right"
                                                    class="w-4 lg:w-5 h-4 lg:h-5 group-hover:translate-x-1 transition-transform"></i>
                                            </span>
                                            <div wire:loading wire:target="applyWalletAdjustment"
                                                class="flex items-center gap-2 lg:gap-3">
                                                <span class="loading loading-spinner loading-sm lg:loading-md"></span>
                                                <span class="text-[10px] lg:text-xs">Synchronizing...</span>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($manageTab === 'staking')
                        <!-- Staking Controls Tab -->
                        <div class="p-6 lg:p-8 space-y-6 lg:space-y-8 h-full overflow-y-auto custom-scrollbar">
                            <!-- Active Stakes -->
                            <div class="space-y-4">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div class="flex flex-col sm:flex-row sm:items-center gap-2 lg:gap-3">
                                        <p class="text-[9px] lg:text-[10px] font-black uppercase tracking-[0.15em] opacity-30">
                                            Active Staking Positions</p>
                                        <div
                                            class="flex items-center justify-center sm:justify-start gap-1.5 px-3 py-1.5 sm:py-0.5 bg-primary/5 rounded-xl sm:rounded-full border border-primary/10 w-full sm:w-auto">
                                            <div class="w-1 h-1 rounded-full bg-primary"></div>
                                            <span
                                                class="text-[7px] lg:text-[8px] font-black text-primary uppercase tracking-widest">Select
                                                position to adjust rewards</span>
                                        </div>
                                    </div>
                                    <span
                                        class="text-[8px] lg:text-[9px] font-black text-primary/50 uppercase tracking-widest self-end sm:self-auto">{{ $managingUser->stakes()->where('status', 'active')->count() }}
                                        Active</span>
                                </div>
                                <div class="grid grid-cols-1 gap-3">
                                    @forelse($managingUser->stakes()->where('status', 'active')->latest()->get() as $stake)
                                        <div class="space-y-3">
                                            <!-- Stake Item -->
                                            <div wire:click="{{ $selectedStakeId === $stake->id ? '$set(\'selectedStakeId\', null)' : '$set(\'selectedStakeId\', ' . $stake->id . ')' }}"
                                                class="bg-base-200/30 rounded-[1.25rem] lg:rounded-[1.5rem] p-4 lg:p-6 border-2 transition-all cursor-pointer flex items-center justify-between group {{ $selectedStakeId === $stake->id ? 'border-base-content/10 bg-primary/5' : 'border-base-300/20 hover:border-primary/40' }}">
                                                <div class="flex items-center gap-4 lg:gap-6">
                                                    <div
                                                        class="bg-primary/10 text-primary rounded-xl lg:rounded-2xl w-10 lg:w-14 h-10 lg:h-14 flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                                                        <i data-lucide="zap" class="w-5 lg:w-7 h-5 lg:h-7"></i>
                                                    </div>
                                                    <div>
                                                        <h4
                                                            class="text-sm lg:text-base font-black uppercase tracking-wider text-base-content">
                                                            {{ $this->assetNames[$stake->asset_id] ?? str($stake->asset_id)->replace('-', ' ') }}
                                                        </h4>
                                                        <div
                                                            class="flex items-center gap-2 mt-0.5 opacity-50 group-hover:opacity-100 transition-opacity">
                                                            <i data-lucide="shield-check" class="w-3 h-3 text-primary"></i>
                                                            <span
                                                                class="text-[8px] lg:text-[10px] font-black uppercase tracking-widest">{{ collect($this->validators)->firstWhere('id', $stake->validator_id)['name'] ?? 'Core Node' }}</span>
                                                        </div>
                                                        <div class="flex items-center gap-2 lg:gap-3 mt-1.5 lg:mt-2">
                                                            <span
                                                                class="text-[9px] lg:text-[11px] font-bold opacity-50 whitespace-nowrap">{{ number_format($stake->amount, 4) }}
                                                                Staked</span>
                                                            <span class="w-1 h-1 lg:w-1.5 lg:h-1.5 rounded-full bg-base-300"></span>
                                                            <span
                                                                class="text-[9px] lg:text-[11px] font-black text-primary whitespace-nowrap">{{ $stake->apy }}%
                                                                APY</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-4 lg:gap-8">
                                                    <div class="text-right">
                                                        <div class="flex flex-col items-end">
                                                            <span
                                                                class="text-[10px] lg:text-xs font-black text-success uppercase tracking-widest whitespace-nowrap">+{{ number_format($stake->earned_rewards, 8) }}</span>
                                                            <span
                                                                class="text-[7px] lg:text-[9px] font-bold opacity-20 uppercase tracking-[0.2em] mt-1 whitespace-nowrap">Rewards</span>
                                                        </div>
                                                    </div>

                                                    <!-- Management Hint -->
                                                    <div
                                                        class="hidden sm:flex items-center justify-center w-10 h-10 rounded-xl bg-primary text-white shadow-lg shadow-primary/20 opacity-30 group-hover:opacity-100 transition-all duration-300">
                                                        <i data-lucide="settings-2" class="w-5 h-5"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Compact Inline Adjustment Form -->
                                            @if($selectedStakeId === $stake->id)
                                                <div
                                                    class="bg-white border-2 border-base-200 rounded-[1.5rem] p-4 lg:p-6 shadow-xl animate-in slide-in-from-top-2 duration-300 relative overflow-hidden">
                                                    <div class="flex flex-col lg:flex-row items-center justify-between gap-4 lg:gap-6">
                                                        <!-- Label (Mobile) -->
                                                        <div class="lg:hidden text-center">
                                                            <p class="text-[8px] font-black uppercase tracking-widest opacity-30">Manual
                                                                Balance Correction</p>
                                                        </div>

                                                        <!-- Mode Toggle -->
                                                        <div
                                                            class="flex p-1 bg-base-100 rounded-xl lg:rounded-2xl shadow-inner border border-base-200 w-full lg:w-auto shrink-0">
                                                            <button wire:click="$set('rewardAdjustmentMode', 'credit')"
                                                                class="flex-1 lg:px-6 py-2 rounded-lg lg:rounded-xl text-[9px] font-black uppercase tracking-widest transition-all cursor-pointer {{ $rewardAdjustmentMode === 'credit' ? 'bg-primary text-white shadow-md' : 'text-base-content/40' }}">
                                                                Credit
                                                            </button>
                                                            <button wire:click="$set('rewardAdjustmentMode', 'debit')"
                                                                class="flex-1 lg:px-6 py-2 rounded-lg lg:rounded-xl text-[9px] font-black uppercase tracking-widest transition-all cursor-pointer {{ $rewardAdjustmentMode === 'debit' ? 'bg-warning text-[#0A0C10] shadow-md' : 'text-base-content/40' }}">
                                                                Debit
                                                            </button>
                                                        </div>

                                                        <!-- Input Area -->
                                                        <div class="flex-1 relative w-full lg:mx-4 group">
                                                            <input wire:model.live="rewardAmount" type="number" step="0.01"
                                                                placeholder="0.00"
                                                                class="w-full h-12 lg:h-14 bg-base-100 border-base-200 rounded-xl lg:rounded-2xl pl-10 pr-4 text-center text-sm lg:text-base font-black focus:ring-4 focus:ring-primary/10 focus:border-primary transition-all selection:bg-primary selection:text-white">
                                                            <i data-lucide="dollar-sign"
                                                                class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 opacity-20 group-focus-within:opacity-100 transition-opacity"></i>

                                                            @if($this->stakingCryptoEquivalent > 0)
                                                                <div
                                                                    class="absolute -bottom-2 left-1/2 -translate-x-1/2 px-3 py-0.5 bg-[#0A0C10] text-white text-[7px] font-black uppercase tracking-widest rounded-full shadow-lg animate-in zoom-in duration-200">
                                                                    ≈ {{ number_format($this->stakingCryptoEquivalent, 8) }}
                                                                    {{ $this->assetSymbols[$stake->asset_id] ?? 'UNIT' }}
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <!-- Buttons Container -->
                                                        <div class="flex items-center gap-3 w-full lg:w-auto shrink-0">
                                                            <!-- Action Button -->
                                                            <button wire:click="addStakeReward"
                                                                class="flex-1 lg:flex-none h-12 lg:h-14 px-8 rounded-xl lg:rounded-2xl font-black text-[10px] lg:text-xs uppercase tracking-widest shadow-xl transition-all active:scale-[0.95] group/btn {{ $rewardAdjustmentMode === 'debit' ? 'bg-warning text-[#0A0C10]' : 'bg-primary text-white' }}"
                                                                wire:loading.attr="disabled">
                                                                <span wire:loading.remove wire:target="addStakeReward"
                                                                    class="flex items-center justify-center gap-3">
                                                                    Confirm
                                                                    {{ $rewardAdjustmentMode === 'credit' ? 'Credit' : 'Debit' }}
                                                                    <i data-lucide="{{ $rewardAdjustmentMode === 'credit' ? 'plus-circle' : 'minus-circle' }}"
                                                                        class="w-4 h-4"></i>
                                                                </span>
                                                                <div wire:loading wire:target="addStakeReward"
                                                                    class="w-5 h-5 border-2 border-white/20 border-t-white rounded-full animate-spin mx-auto">
                                                                </div>
                                                            </button>

                                                            <!-- Close Button -->
                                                            <button wire:click="$set('selectedStakeId', null)"
                                                                class="w-12 h-12 lg:h-14 rounded-xl bg-base-200/50 flex items-center justify-center hover:bg-error/10 hover:text-error transition-all group/close">
                                                                <i data-lucide="x"
                                                                    class="w-5 h-5 group-hover/close:rotate-90 transition-transform"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    @error('rewardAmount')
                                                        <p
                                                            class="text-error text-[8px] lg:text-[10px] font-black uppercase tracking-widest mt-4 text-center">
                                                            {{ $message }}</p>
                                                    @enderror
                                                </div>
                                            @endif
                                        </div>
                                    @empty
                                        <div
                                            class="py-12 lg:py-20 bg-base-200/20 rounded-[2rem] lg:rounded-[3rem] border-2 border-dashed border-base-300/50 flex flex-col items-center justify-center text-center">
                                            <div
                                                class="w-12 lg:w-16 h-12 lg:h-16 bg-base-200 rounded-xl lg:rounded-[2rem] flex items-center justify-center mb-4 lg:mb-5">
                                                <i data-lucide="shield-alert" class="w-6 lg:w-8 h-6 lg:h-8 opacity-20"></i>
                                            </div>
                                            <p class="text-[10px] lg:text-[11px] font-black opacity-30 uppercase tracking-widest">No
                                                active staking detected</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                        </div>
                    @elseif($manageTab === 'activity')
                        <div class="flex-1 overflow-y-auto p-6 lg:p-10 bg-white h-full custom-scrollbar">
                            <style>
                                .custom-scrollbar::-webkit-scrollbar {
                                    width: 4px;
                                }

                                .custom-scrollbar::-webkit-scrollbar-track {
                                    background: transparent;
                                }

                                .custom-scrollbar::-webkit-scrollbar-thumb {
                                    background: rgba(0, 0, 0, 0.05);
                                    border-radius: 10px;
                                }

                                .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                                    background: rgba(0, 0, 0, 0.1);
                                }
                            </style>

                            <div class="max-w-4xl mx-auto space-y-6 lg:space-y-8">
                                <div class="flex items-center justify-between">

                                    <div>
                                        <h3 class="text-base lg:text-lg font-black tracking-tight text-base-content uppercase">
                                            User Activity Log</h3>
                                        <p
                                            class="text-[9px] lg:text-[10px] font-medium opacity-30 uppercase tracking-widest mt-1">
                                            Review and reconcile recent transactions</p>
                                    </div>
                                    <div
                                        class="flex items-center gap-2 bg-base-200/50 px-3 py-1.5 rounded-xl border border-base-300/30">
                                        <div class="w-1.5 h-1.5 rounded-full bg-success"></div>
                                        <span class="text-[8px] font-black uppercase tracking-widest opacity-60">System
                                            Synchronized</span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                                    @php $transactions = $this->transactions; @endphp
                                    @forelse($transactions as $transaction)
                                        <div
                                            class="group relative bg-white rounded-[2rem] p-5 lg:p-6 border border-base-200/60 hover:border-primary/20 hover:shadow-2xl hover:shadow-primary/5 transition-all flex flex-col justify-between overflow-hidden min-h-[180px] lg:min-h-[200px]">
                                            <!-- Indicator Line -->
                                            <div
                                                class="absolute left-0 top-0 bottom-0 w-1.5 {{ $transaction->type === 'receive' ? 'bg-success' : 'bg-warning' }} opacity-0 group-hover:opacity-100 transition-opacity">
                                            </div>

                                            <div>
                                                <div class="flex items-start justify-between mb-4">
                                                    <!-- Status Icon -->
                                                    <div class="relative">
                                                        <div
                                                            class="w-12 h-12 lg:w-14 lg:h-14 rounded-2xl lg:rounded-[1.5rem] flex items-center justify-center transition-all duration-500 {{ $transaction->type === 'receive' ? 'bg-success/5 text-success' : 'bg-warning/5 text-warning' }} group-hover:scale-110">
                                                            <i data-lucide="{{ $transaction->type === 'receive' ? 'trending-up' : 'trending-down' }}"
                                                                class="w-6 h-6 lg:w-7 lg:h-7"></i>
                                                        </div>
                                                        <div
                                                            class="absolute -right-1 -bottom-1 w-5 h-5 lg:w-6 lg:h-6 rounded-full bg-white border-2 border-base-100 flex items-center justify-center shadow-sm">
                                                            <i data-lucide="{{ $transaction->type === 'receive' ? 'plus' : 'minus' }}"
                                                                class="w-3 h-3 lg:w-3.5 lg:h-3.5 {{ $transaction->type === 'receive' ? 'text-success' : 'text-warning' }}"></i>
                                                        </div>
                                                    </div>

                                                    <!-- Advanced Delete Button -->
                                                    <button
                                                        wire:confirm="Are you sure you want to revert this transaction and reconcile the balance?"
                                                        wire:click="deleteTransaction({{ $transaction->id }})"
                                                        class="group/del relative w-10 h-10 lg:w-12 lg:h-12 rounded-xl lg:rounded-2xl bg-base-200/30 hover:bg-base-300 hover:shadow-lg transition-all duration-300 flex items-center justify-center overflow-hidden cursor-pointer">
                                                        <i data-lucide="trash-2"
                                                            class="w-4 lg:w-5 h-4 lg:h-5 text-base-content/20 group-hover/del:text-base-content transition-all"></i>
                                                    </button>




                                                </div>

                                                <!-- Transaction Details -->
                                                <div class="space-y-3">
                                                    <div class="min-w-0">
                                                        <div class="flex items-center justify-between gap-2 mb-1">
                                                            <h4
                                                                class="text-xs lg:text-sm font-black uppercase tracking-tight text-base-content truncate">
                                                                {{ $transaction->recipient_address }}</h4>
                                                            @if(str_starts_with($transaction->hash, 'ADMIN-'))
                                                                <div
                                                                    class="inline-flex items-center gap-1.5 bg-primary/5 text-primary px-2 py-0.5 rounded-full border border-primary/10 shrink-0">
                                                                    <div class="w-1 h-1 rounded-full bg-primary animate-pulse"></div>
                                                                    <span class="text-[8px] font-black uppercase tracking-widest">Admin
                                                                        Mod</span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>


                                                    <div class="flex items-center gap-4 border-t border-base-100 pt-3">
                                                        <div class="flex items-center gap-2 min-w-0">
                                                            <i data-lucide="hash" class="w-3.5 h-3.5 opacity-20 shrink-0"></i>
                                                            <p
                                                                class="text-[9px] lg:text-xs font-bold opacity-40 uppercase tracking-widest truncate">
                                                                {{ substr($transaction->hash, 0, 10) }}</p>
                                                        </div>
                                                        <div class="flex items-center gap-2 shrink-0">
                                                            <i data-lucide="calendar" class="w-3.5 h-3.5 opacity-20 shrink-0"></i>
                                                            <p
                                                                class="text-[9px] lg:text-xs font-bold opacity-40 uppercase tracking-widest whitespace-nowrap">
                                                                {{ $transaction->created_at->format('M d, Y') }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Amount & Asset -->
                                            <div class="mt-6 flex items-end justify-between">
                                                <div>
                                                    <p
                                                        class="text-[10px] lg:text-xs font-black opacity-30 uppercase tracking-[0.2em] mb-1">
                                                        {{ $this->assetNames[$transaction->asset_id] ?? $transaction->asset_id }}
                                                    </p>
                                                    <span
                                                        class="text-lg lg:text-xl font-black tracking-tighter {{ $transaction->type === 'receive' ? 'text-success' : 'text-warning' }}">
                                                        {{ $transaction->type === 'receive' ? '+' : '-' }}{{ number_format($transaction->amount, 8) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div
                                            class="col-span-full py-24 lg:py-40 bg-base-100 rounded-[2.5rem] border-2 border-dashed border-base-200 flex flex-col items-center justify-center text-center">
                                            <div
                                                class="w-20 lg:w-28 h-20 lg:h-28 bg-base-200/50 rounded-[2.5rem] flex items-center justify-center mb-8 relative">
                                                <i data-lucide="archive" class="w-10 lg:w-14 h-10 lg:h-14 opacity-10"></i>
                                                <div
                                                    class="absolute inset-0 border-4 border-base-300/20 rounded-[2.5rem] animate-ping opacity-20">
                                                </div>
                                            </div>
                                            <h4 class="text-sm lg:text-base font-black opacity-40 uppercase tracking-widest">No
                                                transaction history found</h4>
                                            <p class="text-xs font-medium opacity-20 uppercase tracking-[0.2em] mt-3">Ready to log
                                                your first adjustment</p>
                                        </div>
                                    @endforelse




                                    @if($transactions->hasPages())
                                        <div class="col-span-full flex flex-col sm:flex-row justify-between items-center pt-8 border-t border-base-200/50 mt-8 gap-4">
                                            <div class="flex flex-col text-center sm:text-left">
                                                <p class="text-[9px] font-black text-base-content/20 uppercase tracking-[0.2em] mb-0.5">Log Navigation</p>
                                                <p class="text-[11px] font-bold text-base-content/40 tracking-tight">
                                                    Page <span class="text-base-content font-black">{{ $transactions->currentPage() }}</span> of {{ $transactions->lastPage() }}
                                                </p>
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <button wire:click="previousPage('historyPage')" {{ $transactions->onFirstPage() ? 'disabled' : '' }}
                                                    class="btn btn-ghost h-10 px-5 rounded-xl border border-base-300/30 bg-white shadow-sm hover:bg-primary hover:text-white hover:border-primary disabled:opacity-40 transition-all group">
                                                    <i data-lucide="arrow-left" class="w-3.5 h-3.5 mr-2 group-hover:-translate-x-1 transition-transform"></i>
                                                    <span class="text-[9px] font-black uppercase tracking-widest">Older</span>
                                                </button>

                                                <button wire:click="nextPage('historyPage')" {{ !$transactions->hasMorePages() ? 'disabled' : '' }}
                                                    class="btn btn-ghost h-10 px-5 rounded-xl border border-base-300/30 bg-white shadow-sm hover:bg-primary hover:text-white hover:border-primary disabled:opacity-40 transition-all group">
                                                    <span class="text-[9px] font-black uppercase tracking-widest">Newer</span>
                                                    <i data-lucide="arrow-right" class="w-3.5 h-3.5 ml-2 group-hover:translate-x-1 transition-transform"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>

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
            <div
                class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md border border-base-300/30 animate-in zoom-in-95 fade-in duration-200 overflow-hidden">
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
                                        {{ $contactUser->phone ?? 'Not provided' }}</p>
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
                                        {{ $contactUser->address ?? 'No address on file' }}</p>
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