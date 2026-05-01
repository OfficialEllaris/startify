@push('styles')
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .dark-mode-container {
            background: #0A0C10;
            color: #E2E8F0;
        }
    </style>
@endpush

<div class="max-w-[1400px] mx-auto min-h-screen py-8 px-4 lg:px-8 space-y-12">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <h1 class="text-3xl font-black tracking-tighter text-base-content mb-1 uppercase">Copy Trading</h1>
            <p class="text-xs font-medium opacity-40 uppercase tracking-widest">Mirror elite strategies and grow your portfolio</p>
        </div>
        
        <div class="flex items-center gap-3">
            <button wire:click="$set('isWithdrawing', true)" class="btn btn-primary h-12 px-6 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-primary/20 group">
                <i data-lucide="wallet-cards" class="w-3.5 h-3.5 mr-2 group-hover:rotate-12 transition-transform"></i>
                Claim Profits
            </button>
        </div>
    </div>

    <!-- User Stats Header -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-primary/5 border border-primary/10 rounded-[1.5rem] p-6 flex flex-col items-center justify-center text-center space-y-2 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 opacity-5 group-hover:scale-110 transition-transform duration-700">
                <i data-lucide="wallet" class="w-24 h-24 text-primary"></i>
            </div>
            <p class="text-[9px] font-black uppercase tracking-[0.3em] text-primary/60">Trading Balance</p>
            <h4 class="text-2xl lg:text-3xl font-black tracking-tighter tabular-nums text-base-content">
                ${{ number_format($this->wallet->balance, 2) }}
            </h4>
        </div>
        <div class="bg-success/5 border border-success/10 rounded-[1.5rem] p-6 flex flex-col items-center justify-center text-center space-y-2 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 opacity-5 group-hover:scale-110 transition-transform duration-700">
                <i data-lucide="trending-up" class="w-24 h-24 text-success"></i>
            </div>
            <p class="text-[9px] font-black uppercase tracking-[0.3em] text-success/60">Total Profit</p>
            <h4 class="text-2xl lg:text-3xl font-black tracking-tighter tabular-nums {{ $this->totalProfit >= 0 ? 'text-success' : 'text-error' }}">
                {{ $this->totalProfit >= 0 ? '+' : '-' }}${{ number_format(abs($this->totalProfit), 2) }}
            </h4>
        </div>
        <div class="bg-base-200/50 border border-base-300/20 rounded-[1.5rem] p-6 flex flex-col items-center justify-center text-center space-y-2 relative overflow-hidden group">
            <div class="absolute -right-4 -top-4 opacity-5 group-hover:scale-110 transition-transform duration-700">
                <i data-lucide="users" class="w-24 h-24 opacity-20"></i>
            </div>
            <p class="text-[9px] font-black uppercase tracking-[0.3em] opacity-40">Active Copies</p>
            <h4 class="text-2xl lg:text-3xl font-black tracking-tighter tabular-nums text-base-content">
                {{ $this->activeCopies->count() }}
            </h4>
        </div>
    </div>

    <!-- Claim Modal -->
    @if($isWithdrawing)
        @teleport('body')
            <div class="fixed inset-0 z-[100] flex items-center justify-center p-4"
                 x-init="lucide.createIcons()">
                <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" wire:click="$set('isWithdrawing', false)"></div>
                <div class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-sm border border-base-300/30 overflow-hidden animate-in zoom-in-95 fade-in duration-200">
                    <div class="px-8 py-5 bg-base-200/50 border-b border-base-300/20 flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-black tracking-tight text-base-content uppercase">Claim Profits</h3>
                            <p class="text-[8px] font-bold opacity-30 uppercase tracking-widest mt-0.5 text-primary">To USDT Wallet</p>
                        </div>
                        <button wire:click="$set('isWithdrawing', false)" class="opacity-30 hover:opacity-100 transition-opacity">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="p-5 bg-primary/5 rounded-2xl border border-primary/10 flex items-center justify-between">
                            <div class="flex flex-col">
                                <span class="text-[8px] font-black uppercase tracking-widest opacity-40 mb-1">Available to Claim</span>
                                <span class="text-xl font-black tracking-tighter text-primary">${{ number_format($this->totalProfit, 2) }}</span>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                                <i data-lucide="coins" class="w-5 h-5"></i>
                            </div>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text text-[9px] font-black uppercase tracking-widest opacity-40">Amount (USDT)</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 font-black text-base-content/20 text-lg">$</span>
                                <input wire:model="withdrawAmount" type="number" step="0.01" 
                                    class="input input-bordered h-14 w-full pl-10 rounded-xl bg-base-100 border-base-300/50 font-black text-xl tabular-nums focus:border-primary transition-all">
                            </div>
                            @error('withdrawAmount') <span class="text-error text-[9px] font-bold mt-1.5 ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex flex-col gap-3">
                            <button wire:click="withdrawProfits" class="btn btn-primary w-full h-14 rounded-xl font-black text-xs uppercase tracking-widest shadow-xl shadow-primary/20">
                                Confirm Claim
                            </button>
                            <button wire:click="$set('withdrawAmount', {{ $this->totalProfit }})" class="btn btn-ghost btn-sm h-10 rounded-lg text-[9px] font-black uppercase tracking-widest opacity-40 hover:opacity-100">
                                Claim Max Profit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <script>lucide.createIcons();</script>
        @endteleport
    @endif

    @if($this->activeCopies->count() > 0)
        <!-- Active Copies Section -->
        <section class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-black tracking-tight text-base-content uppercase">Your Active Strategies</h2>
                    <p class="text-xs font-medium opacity-40 mt-1 uppercase tracking-widest">Strategies you are currently following</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($this->activeCopies as $copy)
                    <div class="bg-white border border-base-300/30 rounded-[2rem] p-6 shadow-sm hover:shadow-xl transition-all group overflow-hidden relative">
                        <div class="absolute top-0 right-0 p-4">
                            <span class="bg-success/10 text-success text-[8px] font-black px-2 py-0.5 rounded uppercase tracking-widest">Active</span>
                        </div>
                        <div class="flex items-center gap-4 mb-6">
                            <div class="avatar">
                                <div class="w-14 h-14 rounded-2xl ring-4 ring-primary/5">
                                    @if($copy->trader->avatar)
                                        <img src="{{ asset('storage/' . $copy->trader->avatar) }}" alt="{{ $copy->trader->name }}" />
                                    @else
                                        <div class="bg-primary text-white flex items-center justify-center h-full text-lg font-black">{{ substr($copy->trader->name, 0, 1) }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base font-black tracking-tight text-base-content truncate whitespace-nowrap max-w-[100px]">{{ $copy->trader->name }}</h3>
                                <p class="text-[9px] font-black text-primary uppercase tracking-widest truncate">{{ $copy->trader->strategy }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-4 mb-6">
                            <div class="bg-base-100 rounded-xl p-3 border border-base-200">
                                <p class="text-[8px] font-black opacity-30 uppercase tracking-widest mb-1">Current Strategy Performance</p>
                                <p class="text-sm font-black text-success">+{{ number_format($copy->trader->profit_percentage, 1) }}% ROI</p>
                            </div>
                        </div>
                        <button wire:click="stopCopying({{ $copy->id }})" wire:confirm="Are you sure you want to stop copying this trader?" 
                                class="btn btn-ghost w-full h-12 rounded-xl text-[10px] font-black uppercase tracking-widest border border-base-200 hover:bg-error/5 hover:text-error hover:border-error/20 transition-all">
                            Stop Copying
                        </button>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <!-- Browse Traders Section -->
    <section class="space-y-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h2 class="text-2xl lg:text-3xl font-black tracking-tighter text-base-content uppercase">Marketplace</h2>
                <p class="text-sm font-medium opacity-40 mt-1">Select from our top-performing algorithmic and professional traders.</p>
            </div>
            <div class="relative w-full md:w-80">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 opacity-20"></i>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search strategies..." 
                    class="input input-bordered h-12 w-full rounded-2xl bg-white pl-12 text-xs font-bold border-base-300/50 focus:ring-8 focus:ring-primary/5 transition-all">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($this->traders as $trader)
                <div class="bg-white border border-base-300/30 rounded-[2.5rem] p-6 flex flex-col hover:shadow-2xl hover:shadow-primary/5 transition-all group border-b-4 border-b-base-300/10 hover:border-b-primary/40">
                    <div class="flex items-start justify-between mb-6">
                        <div class="avatar">
                            <div class="w-16 h-16 rounded-2xl ring-8 ring-primary/5 shadow-xl transition-transform group-hover:scale-110 duration-500">
                                @if($trader->avatar)
                                    <img src="{{ asset('storage/' . $trader->avatar) }}" alt="{{ $trader->name }}" />
                                @else
                                    <div class="bg-primary text-white flex items-center justify-center h-full text-xl font-black">{{ substr($trader->name, 0, 1) }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-success font-black text-lg tabular-nums">+{{ number_format($trader->profit_percentage, 1) }}%</div>
                            <div class="text-[8px] font-black opacity-30 uppercase tracking-widest">Performance</div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-black tracking-tight text-base-content">{{ $trader->name }}</h3>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-[9px] font-black text-primary uppercase tracking-widest">{{ $trader->strategy }}</span>
                            <span class="w-1 h-1 rounded-full bg-base-300"></span>
                            <span class="text-[9px] font-black {{ $trader->risk_level === 'High' ? 'text-error' : ($trader->risk_level === 'Medium' ? 'text-warning' : 'text-success') }} uppercase tracking-widest">{{ $trader->risk_level }} Risk</span>
                        </div>
                    </div>

                    <div class="space-y-3 mb-8">
                        <div class="flex items-center justify-between text-[10px] font-bold">
                            <span class="opacity-40 uppercase tracking-widest">Win Rate</span>
                            <span class="text-base-content font-black">{{ number_format($trader->win_rate, 1) }}%</span>
                        </div>
                        <div class="flex items-center justify-between text-[10px] font-bold">
                            <span class="opacity-40 uppercase tracking-widest">Total Copiers</span>
                            <span class="text-base-content font-black">{{ number_format($trader->total_copiers) }}+</span>
                        </div>
                    </div>

                    <button wire:click="copyTrader({{ $trader->id }})" wire:confirm="Are you sure you want to copy {{ $trader->name }}'s trading strategy?"
                            class="btn btn-primary w-full h-14 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-primary/20 mt-auto group-hover:scale-[1.02] transition-transform active:scale-[0.98]">
                        Copy Trade
                    </button>
                </div>
            @empty
                <div class="col-span-full py-20 flex flex-col items-center justify-center opacity-30 text-center">
                    <i data-lucide="layers" class="w-16 h-16 mb-4" stroke-width="1"></i>
                    <p class="text-xl font-black tracking-tight uppercase">No Strategies Available</p>
                    <p class="text-sm font-medium">Check back later for new trading opportunities.</p>
                </div>
            @endforelse
        </div>

        @if($this->traders->hasPages())
            <div class="mt-12 flex justify-center">
                {{ $this->traders->links() }}
            </div>
        @endif
    </section>
</div>