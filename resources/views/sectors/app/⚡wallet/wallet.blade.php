@push('styles')
    <style>
        /* Glassmorphism Refinements */
        .backdrop-blur-md {
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .backdrop-blur-2xl {
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
        }

        /* Glass Noise Texture */
        .glass-noise {
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 180 180' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
            opacity: 0.15;
            mix-blend-mode: overlay;
            height: 100%;
        }

        /* Premium Animations */
        @keyframes soft-pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.8;
                transform: scale(0.98);
            }
        }

        .animate-soft-pulse {
            animation: soft-pulse 4s infinite ease-in-out;
        }

        /* Hide Scrollbar but keep functionality */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Stacking Cards Effect - More Visible */
        .card-stack {
            position: relative;
            height: 180px;
            margin-bottom: 60px;
        }

        .stack-item-1 {
            transform: translateY(0) scale(1);
            z-index: 30;
        }

        .stack-item-2 {
            transform: translateY(-20px) scale(0.92);
            z-index: 20;
            opacity: 0.5;
            border: 1px solid rgba(var(--color-primary), 0.2);
        }

        .stack-item-3 {
            transform: translateY(-40px) scale(0.84);
            z-index: 10;
            opacity: 0.2;
            border: 1px solid rgba(var(--color-primary), 0.1);
        }
        /* Global Pointer Cursor */
        button, 
        [role="button"], 
        .cursor-pointer,
        [wire\:click],
        [x-on\:click],
        [\@click] {
            cursor: pointer !important;
        }
    </style>
@endpush

<div class="flex flex-col min-h-screen bg-transparent relative">
    <!-- Main Scrollable Area -->
    <main class="flex-1 pb-40 overflow-y-auto no-scrollbar">
        <div class="max-w-md mx-auto p-6 lg:p-4 space-y-8">

            @if($view === 'overview')
                <div wire:key="view-overview" class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
                    <!-- Header / Balance with Stacking Effect -->
                    <div class="card-stack pt-12">
                        <!-- Background Stacks -->
                        <div
                            class="absolute inset-x-0 top-12 bg-white/5 border border-white/10 rounded-[2.5rem] h-40 stack-item-3">
                        </div>
                        <div
                            class="absolute inset-x-0 top-12 bg-white/10 border border-white/20 rounded-[2.5rem] h-40 stack-item-2 shadow-xl">
                        </div>

                        <div
                            class="absolute inset-x-0 top-12 bg-white/10 border-t border-l border-white/30 border-r border-b border-white/10 rounded-[2.5rem] p-8 flex flex-col items-center text-center space-y-2 backdrop-blur-3xl stack-item-1 shadow-2xl overflow-hidden ring-1 ring-inset ring-white/20">
                            <!-- Glass Noise Overlay: Fixed rounding alignment -->
                            <div class="absolute inset-0 glass-noise pointer-events-none rounded-[2.5rem]"></div>

                            <p class="relative z-10 text-[10px] font-black text-white/40 uppercase tracking-[0.2em]">Portfolio
                                Value</p>
                            <div class="relative z-10 flex items-baseline gap-2">
                                <span class="text-4xl font-black tracking-tighter text-white leading-none">${{ number_format($this->totalPortfolioValue, 2) }}</span>
                            </div>
                            @php
                                $portfolioChange = $this->totalPortfolioChange;
                                $isPositive = $portfolioChange >= 0;
                            @endphp
                            <div
                                class="relative z-10 inline-flex items-center gap-1.5 {{ $isPositive ? 'bg-success/20 text-success border-success/40' : 'bg-error/20 text-error border-error/40' }} px-3 py-1 rounded-full border backdrop-blur-md">
                                <i data-lucide="{{ $isPositive ? 'trending-up' : 'trending-down' }}" class="w-3 h-3"></i>
                                <span class="text-[10px] font-black">{{ $isPositive ? '+' : '' }}{{ number_format($portfolioChange, 2) }}% Today</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions Grid -->
                    <div class="grid grid-cols-4 gap-4">
                        <button wire:click="setView('send')" class="flex flex-col items-center gap-2 group">
                            <div
                                class="w-14 h-14 bg-white/5 rounded-2xl flex items-center justify-center border border-white/10 group-hover:bg-primary group-hover:border-primary/50 transition-all duration-300 shadow-xl group-hover:shadow-primary/30">
                                <i data-lucide="send" class="w-6 h-6 text-white group-hover:scale-110 transition-transform"></i>
                            </div>
                            <span
                                class="text-[10px] font-bold text-white/60 group-hover:text-white uppercase tracking-widest">Send</span>
                        </button>
                        <button wire:click="setView('receive')" class="flex flex-col items-center gap-2 group">
                            <div
                                class="w-14 h-14 bg-white/5 rounded-2xl flex items-center justify-center border border-white/10 group-hover:bg-primary group-hover:border-primary/50 transition-all duration-300 shadow-xl group-hover:shadow-primary/30">
                                <i data-lucide="qr-code"
                                    class="w-6 h-6 text-white group-hover:scale-110 transition-transform"></i>
                            </div>
                            <span
                                class="text-[10px] font-bold text-white/60 group-hover:text-white uppercase tracking-widest">Receive</span>
                        </button>
                        <button wire:click="setView('buy')" class="flex flex-col items-center gap-2 group">
                            <div
                                class="w-14 h-14 bg-white/5 rounded-2xl flex items-center justify-center border border-white/10 group-hover:bg-primary group-hover:border-primary/50 transition-all duration-300 shadow-xl group-hover:shadow-primary/30">
                                <i data-lucide="shopping-cart"
                                    class="w-6 h-6 text-white group-hover:scale-110 transition-transform"></i>
                            </div>
                            <span
                                class="text-[10px] font-bold text-white/60 group-hover:text-white uppercase tracking-widest">Buy</span>
                        </button>
                        <button wire:click="setView('swap')" class="flex flex-col items-center gap-2 group">
                            <div
                                class="w-14 h-14 bg-white/5 rounded-2xl flex items-center justify-center border border-white/10 group-hover:bg-primary group-hover:border-primary/50 transition-all duration-300 shadow-xl group-hover:shadow-primary/30">
                                <i data-lucide="repeat"
                                    class="w-6 h-6 text-white group-hover:scale-110 transition-transform"></i>
                            </div>
                            <span
                                class="text-[10px] font-bold text-white/60 group-hover:text-white uppercase tracking-widest">Swap</span>
                        </button>
                    </div>

                    <!-- Asset List -->
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xs font-black text-white/40 uppercase tracking-[0.2em]">Your Portfolio</h2>
                            <button wire:click="$toggle('showAllAssets')" class="text-[10px] font-bold text-primary hover:underline tracking-widest uppercase">
                                {{ $showAllAssets ? 'Show Less' : 'View All' }}
                            </button>
                        </div>

                        <div class="space-y-3">
                            @php
                                $displayAssets = $showAllAssets ? $this->assets : collect($this->assets)->take(5);
                            @endphp
                            @foreach($displayAssets as $asset)
                                <div class="relative group cursor-pointer" wire:click="selectAsset('{{ $asset['id'] }}')">
                                    <div class="absolute inset-0 bg-white/[0.03] border border-white/10 rounded-[1.5rem] backdrop-blur-xl group-hover:bg-white/[0.06] group-hover:border-white/20 transition-all duration-500 ring-1 ring-white/5 shadow-xl"
                                        style="box-shadow: 0 10px 30px -10px {{ $asset['color'] }}15"></div>

                                    <div class="relative z-10 p-4 flex items-center justify-between h-full">
                                        <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full blur-[40px] opacity-10 group-hover:opacity-15 transition-opacity duration-700"
                                            style="background-color: {{ $asset['color'] }}"></div>

                                        <div class="flex items-center gap-3">
                                            <div class="w-11 h-11 rounded-xl flex items-center justify-center shadow-xl relative overflow-hidden group-hover:scale-105 transition-transform duration-500 bg-[#0A0C10] border border-white/10">
                                                <div class="absolute inset-0 opacity-40 blur-md transition-opacity duration-500 group-hover:opacity-60"
                                                    style="background-color: {{ $asset['color'] }}"></div>
                                                <div class="absolute inset-0 bg-gradient-to-br from-white/30 to-transparent opacity-50"></div>
                                                <img src="{{ $asset['image'] }}" class="w-6 h-6 relative z-10 drop-shadow-[0_0_6px_{{ $asset['color'] }}60]" alt="{{ $asset['name'] }}">
                                            </div>

                                            <div class="flex flex-col">
                                                <span class="text-sm font-black text-white tracking-tight leading-tight">{{ $asset['name'] }}</span>
                                                <span class="text-[9px] font-bold text-white/30 uppercase tracking-[0.15em] mt-0.5">{{ $asset['balance'] }} {{ $asset['symbol'] }}</span>
                                            </div>
                                        </div>

                                        <div class="flex flex-col items-end gap-1">
                                            <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-white/5 border border-white/10 backdrop-blur-md">
                                                <i data-lucide="{{ str_contains($asset['change'], '+') ? 'trending-up' : 'trending-down' }}" class="w-2.5 h-2.5 {{ str_contains($asset['change'], '+') ? 'text-success' : 'text-error' }}"></i>
                                                <span class="text-[8px] font-black tracking-wider {{ str_contains($asset['change'], '+') ? 'text-success' : 'text-error' }}">
                                                    {{ str_replace(['+', '-'], '', $asset['change']) }}
                                                </span>
                                            </div>
                                            <span class="text-base font-black text-white tracking-tighter leading-none whitespace-nowrap">${{ $asset['usd'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Recent Transactions Section -->
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xs font-black text-white/40 uppercase tracking-[0.2em]">Recent Transactions</h2>
                            <button wire:click="setView('transactions')" class="text-[10px] font-bold text-primary hover:underline tracking-widest uppercase">View All</button>
                        </div>

                        <div class="space-y-3">
                            @forelse(collect($this->transactions)->take(3) as $transaction)
                                <div class="relative group cursor-pointer" wire:click="selectTransaction('{{ $transaction->id }}')">
                                    <div class="absolute inset-0 bg-white/[0.03] border border-white/10 rounded-[1.5rem] backdrop-blur-xl group-hover:bg-white/[0.06] group-hover:border-white/20 transition-all duration-500 ring-1 ring-white/5 shadow-xl"></div>

                                    <div class="relative z-10 p-4 flex items-center justify-between h-full">
                                        <div class="flex items-center gap-3">
                                            <div class="w-11 h-11 rounded-xl flex items-center justify-center shadow-xl relative overflow-hidden bg-[#0A0C10] border border-white/10">
                                                <div class="absolute inset-0 opacity-20 blur-md bg-primary"></div>
                                                <i data-lucide="{{ $transaction->type === 'send' ? 'arrow-up-right' : 'arrow-down-left' }}" class="w-5 h-5 text-white relative z-10"></i>
                                            </div>

                                            <div class="flex flex-col">
                                                <span class="text-sm font-black text-white tracking-tight leading-tight">
                                                    {{ $transaction->type === 'send' ? 'Sent' : 'Received' }} {{ strtoupper($transaction->asset_id) }}
                                                </span>
                                                <span class="text-[9px] font-bold text-white/30 uppercase tracking-[0.15em] mt-0.5">
                                                    {{ $transaction->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex flex-col items-end">
                                            <span class="text-base font-black text-white tracking-tighter leading-none whitespace-nowrap">
                                                {{ $transaction->type === 'send' ? '-' : '+' }}{{ number_format($transaction->amount, 4) }}
                                            </span>
                                            <span class="text-[8px] font-bold text-success uppercase tracking-widest mt-1">Completed</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="py-8 flex flex-col items-center justify-center text-center space-y-3 opacity-40">
                                    <i data-lucide="history" class="w-6 h-6"></i>
                                    <p class="text-[10px] font-bold uppercase tracking-widest">No recent history</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Stake Promo -->
                    <div
                        class="bg-gradient-to-br from-primary/20 to-primary/5 rounded-[2.5rem] p-6 border border-primary/20 relative overflow-hidden group">
                        <div
                            class="absolute -right-8 -bottom-8 w-32 h-32 bg-primary/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700">
                        </div>
                        <div class="relative z-10 flex flex-col space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                                    <i data-lucide="zap" class="w-5 h-5 text-primary"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="text-xs font-black text-white uppercase tracking-widest">Staking Rewards</span>
                                    <span class="text-[10px] font-bold text-white/40">Earn up to 12.5% APY</span>
                                </div>
                            </div>
                            <button wire:click="setView('stake')"
                                class="btn btn-primary btn-sm rounded-xl px-6 w-fit h-10 text-[10px] font-black uppercase tracking-[0.1em]">Stake
                                Now</button>
                        </div>
                    </div>
                </div>
            @elseif($view === 'send')
                <div wire:key="view-send" class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
                    <!-- Header -->
                    <div class="flex items-center justify-between">
                        <button wire:click="setView('overview')"
                            class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center hover:bg-white/10 transition-all">
                            <i data-lucide="chevron-left" class="w-5 h-5 text-white"></i>
                        </button>
                        <h2 class="text-lg font-black text-white tracking-tight">Send Assets</h2>
                        <div class="w-10"></div> <!-- Spacer -->
                    </div>

                    <!-- Asset Selector -->
                    <div class="space-y-3">
                        <p class="text-[10px] font-black text-white/30 uppercase tracking-[0.2em] ml-1">Select Asset</p>
                        <div x-data="{ 
                                                                                        isDown: false, 
                                                                                        startX: 0, 
                                                                                        scrollLeft: 0,
                                                                                        handleMouseDown(e) {
                                                                                            this.isDown = true;
                                                                                            this.startX = e.pageX - $el.offsetLeft;
                                                                                            this.scrollLeft = $el.scrollLeft;
                                                                                        },
                                                                                        handleMouseMove(e) {
                                                                                            if (!this.isDown) return;
                                                                                            e.preventDefault();
                                                                                            const x = e.pageX - $el.offsetLeft;
                                                                                            const walk = (x - this.startX) * 2;
                                                                                            $el.scrollLeft = this.scrollLeft - walk;
                                                                                        },
                                                                                        handleMouseUp() {
                                                                                            this.isDown = false;
                                                                                        }
                                                                                    }" @mousedown="handleMouseDown"
                            @mouseleave="handleMouseUp" @mouseup="handleMouseUp" @mousemove="handleMouseMove"
                            class="flex gap-3 overflow-x-auto p-4 no-scrollbar cursor-grab active:cursor-grabbing select-none">
                            @foreach($this->assets as $asset)
                                <button wire:click="selectAsset('{{ $asset['id'] }}')"
                                    class="flex-shrink-0 group relative p-1 rounded-2xl transition-all duration-300 ring {{ $selectedAssetId === $asset['id'] ? 'ring-white/40' : 'ring-transparent hover:ring-white/20' }} focus:outline-none">
                                    <div class="absolute inset-0 bg-white/10 rounded-2xl blur-md {{ $selectedAssetId === $asset['id'] ? 'opacity-100' : 'opacity-0' }} group-hover:opacity-100 transition-opacity"
                                        style="background-color: {{ $asset['color'] }}50"></div>
                                    <div
                                        class="relative w-16 h-16 rounded-2xl bg-[#0A0C10] border {{ $selectedAssetId === $asset['id'] ? 'border-white/40' : 'border-white/10' }} flex flex-col items-center justify-center gap-1 overflow-hidden transition-transform group-active:scale-95">
                                        <div class="absolute inset-0 {{ $selectedAssetId === $asset['id'] ? 'opacity-40' : 'opacity-20' }} blur-sm transition-opacity"
                                            style="background-color: {{ $asset['color'] }}"></div>
                                        <div class="absolute inset-0 bg-gradient-to-br from-white/10 to-transparent"></div>
                                        <img src="{{ $asset['image'] }}" class="w-6 h-6 relative z-10 pointer-events-none"
                                            alt="">
                                        <span
                                            class="text-[9px] font-black {{ $selectedAssetId === $asset['id'] ? 'text-white' : 'text-white/60' }} relative z-10 uppercase tracking-tighter">{{ $asset['symbol'] }}</span>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Amount Input -->
                    <div
                        class="relative bg-white/[0.03] border border-white/10 rounded-[2rem] p-8 flex flex-col items-center justify-center space-y-4 backdrop-blur-3xl overflow-hidden group shadow-2xl">
                        <p class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em] relative z-10">Enter
                            Amount</p>
                        <div class="flex flex-col items-center relative z-10">
                            <div class="flex items-baseline gap-2">
                                <span class="text-white/10 text-3xl font-black">$</span>
                                <input type="text" placeholder="0.00" wire:model.live="amount"
                                    class="bg-transparent border-none text-5xl font-black text-white p-0 w-48 text-center focus:ring-0 focus:outline-none outline-none placeholder:text-white/5 tracking-tighter transition-all focus:scale-105">
                            </div>
                                @error('amount') 
                                    <p class="text-error text-[10px] mt-1">{{ $message }}</p> 
                                @else
                                    @if($amount > 0)
                                        <p class="text-primary text-[10px] mt-1 font-bold">
                                            ≈ {{ number_format($this->amountInCrypto, 8) }} {{ $this->selectedAsset['symbol'] }}
                                        </p>
                                    @endif
                                @enderror
                                <p
                                    class="text-[10px] font-bold text-white/20 mt-3 uppercase tracking-widest bg-white/5 px-3 py-1 rounded-full border border-white/5">
                                    Available: {{ $this->selectedAsset['balance'] }} {{ $this->selectedAsset['symbol'] }}
                                </p>
                        </div>
                    </div>

                    <!-- Recipient Address -->
                    <div class="space-y-3">
                        <p class="text-[10px] font-black text-white/30 uppercase tracking-[0.2em] ml-1">Recipient Address
                        </p>
                        <div class="relative">
                            <input type="text" placeholder="Wallet address or ENS" wire:model="recipient"
                                class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-sm text-white placeholder:text-white/10 focus:ring-0 focus:outline-none outline-none transition-all">
                            @error('recipient') <p class="text-error text-[10px] mt-1">{{ $message }}</p> @enderror
                            <button @click="navigator.clipboard.readText().then(text => $wire.set('recipient', text))"
                                class="absolute right-3 top-1/2 -translate-y-1/2 px-3 py-1.5 rounded-xl bg-white/5 text-[10px] font-black text-white/40 hover:text-white transition-colors border border-white/10 uppercase tracking-wider">Paste</button>
                        </div>
                    </div>

                    <!-- Network Fee -->
                    <div class="flex items-center justify-between px-1">
                        <span class="text-[10px] font-black text-white/30 uppercase tracking-[0.2em]">Network Fee</span>
                        <div class="text-right">
                            <p class="text-[10px] font-black text-white/60 tracking-tight">
                                {{ number_format($this->networkFee, 8) }} {{ $this->selectedAsset['symbol'] }}
                            </p>
                            <p class="text-[8px] font-bold text-white/20 uppercase tracking-widest mt-0.5">
                                Estimated • 0 - 30 min
                            </p>
                        </div>
                    </div>

                    <!-- Review Button -->
                    <button wire:click="send"
                        class="w-full bg-primary text-white font-black py-4 rounded-2xl shadow-[0_0_30px_rgba(var(--color-primary),0.3)] hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2 group">
                        <span>Send Assets</span>
                        <i data-lucide="send" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </div>

            @elseif($view === 'transactions')
                <div wire:key="view-transactions" class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
                    <!-- Header -->
                    <div class="flex items-center justify-between">
                        <button wire:click="setView('overview')"
                            class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center hover:bg-white/10 transition-all">
                            <i data-lucide="chevron-left" class="w-5 h-5 text-white"></i>
                        </button>
                        <h2 class="text-lg font-black text-white tracking-tight">Transaction History</h2>
                        <div class="w-10"></div> <!-- Spacer -->
                    </div>

                    <div class="space-y-4">
                        @forelse($this->transactions as $transaction)
                            <div class="relative group cursor-pointer" wire:click="selectTransaction('{{ $transaction->id }}')">
                                <div class="absolute inset-0 bg-white/[0.03] border border-white/10 rounded-[1.5rem] backdrop-blur-xl group-hover:bg-white/[0.06] group-hover:border-white/20 transition-all duration-300 ring-1 ring-white/5 shadow-xl"></div>

                                <div class="relative z-10 p-5 flex items-center justify-between h-full">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-xl relative overflow-hidden bg-[#0A0C10] border border-white/10">
                                            <div class="absolute inset-0 opacity-20 blur-md bg-primary"></div>
                                            <i data-lucide="{{ $transaction->type === 'send' ? 'arrow-up-right' : 'arrow-down-left' }}" class="w-6 h-6 text-white relative z-10"></i>
                                        </div>

                                        <div class="flex flex-col">
                                            <span class="text-base font-black text-white tracking-tight leading-tight">
                                                {{ $transaction->type === 'send' ? 'Sent' : 'Received' }} {{ strtoupper($transaction->asset_id) }}
                                            </span>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-[10px] font-bold text-white/30 uppercase tracking-widest">
                                                    {{ $transaction->created_at->format('M d, Y • H:i') }}
                                                </span>
                                                <span class="w-1 h-1 rounded-full bg-white/10"></span>
                                                <span class="text-[10px] font-bold text-primary/60 uppercase tracking-widest">
                                                    {{ Str::limit($transaction->recipient_address, 8) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex flex-col items-end gap-1">
                                        <span class="text-xl font-black text-white tracking-tighter leading-none whitespace-nowrap">
                                            {{ $transaction->type === 'send' ? '-' : '+' }}{{ number_format($transaction->amount, 4) }}
                                        </span>
                                        <div class="flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-success/10 border border-success/20">
                                            <div class="w-1 h-1 rounded-full bg-success animate-pulse"></div>
                                            <span class="text-[8px] font-black text-success uppercase tracking-widest">Confirmed</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="py-20 flex flex-col items-center justify-center text-center space-y-6">
                                <div class="w-20 h-20 bg-white/5 rounded-3xl flex items-center justify-center border border-white/10 opacity-20">
                                    <i data-lucide="history" class="w-10 h-10"></i>
                                </div>
                                <div class="space-y-2">
                                    <p class="text-sm font-black text-white/40 uppercase tracking-[0.2em]">No History Found</p>
                                    <p class="text-[10px] font-bold text-white/20 max-w-[180px]">Your transaction history will appear here once you make your first transfer.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            @elseif($view === 'receive')
                <div wire:key="view-receive" x-data="{ showAssets: false }" class="space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-700">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-2">
                        <button wire:click="setView('overview')"
                            class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-white/5 transition-all">
                            <i data-lucide="chevron-left" class="w-5 h-5 text-white"></i>
                        </button>
                        <h2 class="text-base font-black text-white tracking-tight">Receive</h2>
                        <button class="w-10 h-10 rounded-full flex items-center justify-center hover:bg-white/5 transition-all">
                            <i data-lucide="info" class="w-5 h-5 text-white/40"></i>
                        </button>
                    </div>

                    <!-- Selected Asset Bar -->
                    <div class="relative group">
                        <div class="absolute inset-0 bg-white/5 rounded-2xl blur-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="relative bg-white/5 border border-white/10 rounded-2xl p-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-[#0A0C10] border border-white/10 flex items-center justify-center relative overflow-hidden">
                                    <div class="absolute inset-0 opacity-20 blur-md" style="background-color: {{ $this->selectedAsset['color'] }}"></div>
                                    <img src="{{ $this->selectedAsset['image'] }}" class="w-6 h-6 relative z-10" alt="">
                                </div>
                                <span class="text-sm font-black text-white">{{ $this->selectedAsset['name'] }}</span>
                            </div>
                            <button @click="showAssets = !showAssets" class="text-xs font-bold text-white/40 hover:text-white transition-colors">Change</button>
                        </div>
                    </div>

                    <!-- Collapsible Asset Selector -->
                    <div x-show="showAssets" x-collapse>
                        <div x-data="{ 
                                    isDown: false, 
                                    startX: 0, 
                                    scrollLeft: 0,
                                    handleMouseDown(e) {
                                        this.isDown = true;
                                        this.startX = e.pageX - $el.offsetLeft;
                                        this.scrollLeft = $el.scrollLeft;
                                    },
                                    handleMouseMove(e) {
                                        if (!this.isDown) return;
                                        e.preventDefault();
                                        const x = e.pageX - $el.offsetLeft;
                                        const walk = (x - this.startX) * 2;
                                        $el.scrollLeft = this.scrollLeft - walk;
                                    },
                                    handleMouseUp() {
                                        this.isDown = false;
                                    }
                                }" @mousedown="handleMouseDown"
                            @mouseleave="handleMouseUp" @mouseup="handleMouseUp" @mousemove="handleMouseMove"
                            class="flex gap-3 overflow-x-auto p-4 no-scrollbar cursor-grab active:cursor-grabbing select-none bg-white/[0.02] rounded-2xl border border-white/5">
                            @foreach($this->assets as $asset)
                                <button wire:click="selectAsset('{{ $asset['id'] }}')" @click="showAssets = false"
                                    class="flex-shrink-0 group relative p-1 rounded-2xl transition-all duration-300 ring {{ $selectedAssetId === $asset['id'] ? 'ring-white/40' : 'ring-transparent hover:ring-white/20' }} focus:outline-none">
                                    <div
                                        class="relative w-12 h-12 rounded-2xl bg-[#0A0C10] border {{ $selectedAssetId === $asset['id'] ? 'border-white/40' : 'border-white/10' }} flex items-center justify-center overflow-hidden">
                                        <div class="absolute inset-0 {{ $selectedAssetId === $asset['id'] ? 'opacity-40' : 'opacity-20' }} blur-sm"
                                            style="background-color: {{ $asset['color'] }}"></div>
                                        <img src="{{ $asset['image'] }}" class="w-5 h-5 relative z-10 pointer-events-none"
                                            alt="">
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Main QR Card -->
                    <div class="relative flex flex-col items-center">
                        <div class="w-full max-w-[320px] bg-white rounded-[2.5rem] p-6 flex flex-col items-center shadow-[0_0_50px_rgba(255,255,255,0.05)]">
                            <!-- QR Code Container -->
                            <div class="relative w-full aspect-square flex items-center justify-center p-2">
                                @if($this->adminAddress)
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($this->adminAddress) }}" 
                                         class="w-full h-full mix-blend-multiply" 
                                         alt="QR Code">
                                    
                                    <!-- Center Icon -->
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-xl border-4 border-white">
                                            <img src="{{ $this->selectedAsset['image'] }}" class="w-5 h-5" alt="">
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- QR Label -->
                            <div class="text-center mt-2 pb-2">
                                <p class="text-sm font-black text-black">Scan me</p>
                                <p class="text-[11px] font-bold text-black/40 font-mono tracking-tighter">
                                    {{ Str::limit($this->adminAddress, 24) }}
                                </p>
                            </div>
                        </div>

                        <!-- Asset Warning -->
                        <div class="mt-8 text-center max-w-[280px]">
                            <p class="text-xs font-medium text-white/40 leading-relaxed">
                                Send only <span class="text-white font-black">{{ $this->selectedAsset['name'] }} ({{ $this->selectedAsset['symbol'] }}) {{ $this->receiveAmount ? $this->receiveAmount . ' ' . $this->selectedAsset['symbol'] : '' }}</span> compatible tokens to this address
                            </p>
                        </div>
                    </div>

                    <!-- Set Amount Input (Relocated & Auto-focus) -->
                    <div x-show="$wire.isSettingAmount" x-collapse x-cloak>
                        <div class="bg-white/5 border border-white/10 rounded-2xl p-4 space-y-3 animate-in fade-in zoom-in-95 duration-300">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-black text-white/30 uppercase tracking-[0.2em]">Request Amount</span>
                                <button @click="$wire.isSettingAmount = false; $wire.receiveAmount = ''" class="text-[10px] font-bold text-primary">Clear</button>
                            </div>
                            <div class="relative">
                                <input type="number" 
                                    x-init="$watch('$wire.isSettingAmount', value => value && $nextTick(() => $el.focus()))"
                                    wire:model.live="receiveAmount" 
                                    placeholder="0.00"
                                    class="w-full bg-transparent border-none text-2xl font-black text-white placeholder:text-white/10 focus:outline-none outline-none p-0">
                                <div class="absolute right-0 top-1/2 -translate-y-1/2 text-sm font-black text-white/20 uppercase">{{ $this->selectedAsset['symbol'] }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Secondary Actions -->
                    <div class="grid grid-cols-3 gap-4 pt-4">
                        <div class="flex flex-col items-center gap-3" x-data="{ copied: false }">
                            <button 
                                @click="
                                    navigator.clipboard.writeText('{{ $this->adminAddress }}'); 
                                    copied = true; 
                                    setTimeout(() => copied = false, 2000);
                                    lucide.createIcons();
                                "
                                :class="copied ? 'bg-success border-success/50' : 'bg-white/5 border-white/10'"
                                class="w-14 h-14 rounded-full border flex items-center justify-center text-white hover:bg-primary hover:border-primary/50 transition-all duration-300 shadow-xl group">
                                <i x-show="!copied" data-lucide="copy" wire:key="icon-copy" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                                <i x-show="copied" data-lucide="check" wire:key="icon-check" class="w-5 h-5 animate-in zoom-in"></i>
                            </button>
                            <span class="text-[10px] font-bold uppercase tracking-widest transition-colors" :class="copied ? 'text-success' : 'text-white/40'">
                                <span x-text="copied ? 'Copied' : 'Copy'"></span>
                            </span>
                        </div>
                        <div class="flex flex-col items-center gap-3">
                            <button 
                                wire:click="$toggle('isSettingAmount')"
                                :class="$wire.isSettingAmount ? 'bg-primary border-primary/50 text-white' : 'bg-white/5 border-white/10 text-white/40'"
                                class="w-14 h-14 rounded-full border flex items-center justify-center hover:text-white transition-all duration-300 shadow-xl">
                                <i data-lucide="edit-3" class="w-5 h-5"></i>
                            </button>
                            <span class="text-[10px] font-bold text-white/40 uppercase tracking-widest">Set Amount</span>
                        </div>
                        <div class="flex flex-col items-center gap-3">
                            <button 
                                @click="
                                    const url = 'https://api.qrserver.com/v1/create-qr-code/?size=1000x1000&data={{ urlencode($this->adminAddress) }}';
                                    fetch(url).then(res => res.blob()).then(blob => {
                                        const a = document.createElement('a');
                                        a.href = URL.createObjectURL(blob);
                                        a.download = 'qr-code-{{ $this->selectedAsset['symbol'] }}.png';
                                        a.click();
                                    });
                                "
                                class="w-14 h-14 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-white/10 transition-all shadow-xl group">
                                <i data-lucide="download" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                            </button>
                            <span class="text-[10px] font-bold text-white/40 uppercase tracking-widest">Download</span>
                        </div>
                    </div>

                    <!-- Primary Share Action -->
                    <button 
                        @click="if (navigator.share) { navigator.share({ title: 'Deposit {{ $this->selectedAsset['name'] }}', text: 'My {{ $this->selectedAsset['name'] }} address is: {{ $this->adminAddress }}', url: '' }) }"
                        class="w-full bg-white/5 border border-white/10 text-white font-black py-4 rounded-2xl flex items-center justify-center gap-2 hover:bg-white/10 transition-all">
                        <i data-lucide="share-2" class="w-4 h-4"></i>
                        <span class="uppercase tracking-widest text-[11px]">Share Address</span>
                    </button>
                </div>
            @else
                <div wire:key="view-default" class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
                    <div class="flex items-center justify-between mb-8">
                        <button wire:click="setView('overview')" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center hover:bg-white/10 transition-all">
                            <i data-lucide="chevron-left" class="w-5 h-5 text-white"></i>
                        </button>
                        <h1 class="text-xs font-black text-white uppercase tracking-[0.3em]">{{ $view }}</h1>
                        <div class="w-10"></div> <!-- Spacer -->
                    </div>

                    <div class="flex flex-col items-center justify-center py-20 space-y-6 text-center">
                        <div
                            class="w-24 h-24 bg-white/5 rounded-full flex items-center justify-center border border-white/10 animate-pulse">
                            <i data-lucide="{{ $view === 'send' ? 'send' : ($view === 'receive' ? 'qr-code' : ($view === 'buy' ? 'shopping-cart' : ($view === 'swap' ? 'repeat' : ($view === 'stake' ? 'zap' : ($view === 'backup' ? 'shield-check' : 'credit-card'))))) }}"
                                class="w-10 h-10 text-white/20"></i>
                        </div>
                        <div class="space-y-2">
                            <h2 class="text-lg font-black text-white uppercase tracking-widest">{{ $view }} feature</h2>
                            <p class="text-sm font-medium text-white/30 max-w-[200px] mx-auto">This interface is currently under
                                construction. Check back soon!</p>
                        </div>
                        <button wire:click="setView('overview')"
                            class="btn btn-primary rounded-2xl px-8 h-12 text-xs font-black uppercase tracking-widest">Go
                            Back</button>
                    </div>
                </div>
            @endif

        </div>
    </main>

    <!-- Bottom Floating Navigation -->
    <nav class="fixed bottom-0 left-1/2 -translate-x-1/2 w-full max-w-md z-50 h-20 group">
        <!-- Fitted SVG Background (Extreme liquid wave fit) -->
        <div class="absolute inset-0 z-0">
            <svg width="100%" height="100%" viewBox="0 0 400 80" preserveAspectRatio="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M0,20 C0,8.95 8.95,0 20,0 H70 C120,0 167.9,0 167.9,12 A32.1,32.1 0 0,0 232.1,12 C232.1,0 280,0 330,0 H380 C391.05,0 400,8.95 400,20 V80 H0 V20Z"
                    fill="rgba(10, 12, 16, 0.95)" stroke="rgba(255,255,255,0.1)" stroke-width="1" />
            </svg>
            <div class="absolute inset-0 backdrop-blur-3xl -z-10 rounded-[2.5rem]"></div>
        </div>

        <!-- Navigation Content -->
        <div class="relative z-10 p-3 flex items-center justify-around h-full">
            <button wire:click="setView('overview')"
                class="flex flex-col items-center gap-1.5 p-2 transition-all {{ $view === 'overview' ? 'text-primary' : 'text-white/40 hover:text-white' }}">
                <i data-lucide="home" class="w-5 h-5 {{ $view === 'overview' ? 'fill-current opacity-20' : '' }}"></i>
                <span class="text-[8px] font-black uppercase tracking-widest">Home</span>
            </button>
            <button wire:click="setView('stake')"
                class="flex flex-col items-center gap-1.5 p-2 transition-all {{ $view === 'stake' ? 'text-primary' : 'text-white/40 hover:text-white' }}">
                <i data-lucide="zap" class="w-5 h-5 {{ $view === 'stake' ? 'fill-current opacity-20' : '' }}"></i>
                <span class="text-[8px] font-black uppercase tracking-widest">Stake</span>
            </button>

            <!-- Floating Center Button -->
            <div class="absolute left-1/2 -translate-x-1/2 -top-6 z-20">
                <button wire:click="setView('buy')"
                    class="w-16 h-16 bg-primary rounded-full flex items-center justify-center text-primary-content shadow-[0_10px_30px_rgba(var(--color-primary),0.4)] border-4 border-[#0A0C10] group-hover:scale-110 transition-all duration-500">
                    <i data-lucide="plus" class="w-8 h-8 group-hover:rotate-90 transition-transform duration-700"></i>
                </button>
            </div>

            <div class="w-16"></div> <!-- Perfect symmetry spacer -->

            <button wire:click="setView('card')"
                class="flex flex-col items-center gap-1.5 p-2 transition-all {{ $view === 'card' ? 'text-primary' : 'text-white/40 hover:text-white' }}">
                <i data-lucide="credit-card"
                    class="w-5 h-5 {{ $view === 'card' ? 'fill-current opacity-20' : '' }}"></i>
                <span class="text-[8px] font-black uppercase tracking-widest">Card</span>
            </button>
            <button wire:click="setView('backup')"
                class="flex flex-col items-center gap-1.5 p-2 transition-all {{ $view === 'backup' ? 'text-primary' : 'text-white/40 hover:text-white' }}">
                <i data-lucide="shield-check"
                    class="w-5 h-5 {{ $view === 'backup' ? 'fill-current opacity-20' : '' }}"></i>
                <span class="text-[8px] font-black uppercase tracking-widest">Backup</span>
            </button>
        </div>
    </nav>

    <!-- Transaction Detail Bottom Modal -->
    <div x-data="{ open: @entangle('selectedTransactionId') }" 
         x-show="open" 
         x-cloak
         class="fixed inset-0 z-[100] flex flex-col justify-end pointer-events-none"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-y-full"
         x-transition:enter-end="translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="translate-y-0"
         x-transition:leave-end="translate-y-full">
        
        <!-- Backdrop -->
        <div x-show="open" 
             @click="open = null"
             class="absolute inset-0 bg-black/60 backdrop-blur-sm pointer-events-auto"
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-500"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"></div>

        <!-- Modal Content -->
        @if($this->selectedTransaction)
            @php
                $asset = collect($this->assets)->firstWhere('id', $this->selectedTransaction->asset_id);
                $color = $asset['color'] ?? '#F7931A';
            @endphp
            <div class="relative w-full max-w-md mx-auto bg-[#0A0C10] rounded-t-[3rem] p-8 pb-12 pointer-events-auto border-t border-white/10 shadow-[0_-20px_50px_rgba(0,0,0,0.5)] overflow-hidden">
                <!-- Branding Glow: Focused vibrant atmospheric lighting -->
                <div class="absolute -top-40 -right-40 w-[120%] h-[500px] blur-[100px] opacity-[0.4] pointer-events-none"
                     style="background: radial-gradient(circle at 100% 0%, {{ $color }} 0%, transparent 70%); z-index: 1;"></div>
                <div class="absolute top-0 inset-x-0 h-48 pointer-events-none opacity-[0.2]"
                     style="background: linear-gradient(135deg, {{ $color }}40 0%, transparent 60%); z-index: 2;"></div>
                <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-white/50 to-transparent" style="z-index: 3;"></div>
                
                <!-- Content Layer -->
                <div class="relative z-10">
                    <!-- Pull Bar -->
                    <div class="w-12 h-1.5 bg-white/10 rounded-full mx-auto mb-8 cursor-pointer" @click="open = null"></div>

                    <div class="flex flex-col items-center text-center space-y-6">
                        <!-- Asset Icon -->
                        <div class="relative">
                            <div class="w-20 h-20 rounded-3xl bg-[#0A0C10] border-2 border-white/10 flex items-center justify-center relative overflow-hidden shadow-2xl">
                                <div class="absolute inset-0 opacity-40 blur-xl" style="background-color: {{ $color }}"></div>
                                <img src="{{ $asset['image'] }}" class="w-10 h-10 relative z-10" alt="">
                            </div>
                            <div class="absolute -bottom-2 -right-2 w-8 h-8 rounded-full bg-success flex items-center justify-center border-4 border-[#0A0C10] shadow-xl">
                                <i data-lucide="{{ $this->selectedTransaction->type === 'send' ? 'arrow-up' : 'arrow-down' }}" class="w-4 h-4 text-[#0A0C10] stroke-[3px]"></i>
                            </div>
                        </div>

                        <!-- Title & Date -->
                        <div class="space-y-1">
                            <h3 class="text-xl font-black text-white tracking-tight">
                                {{ $this->selectedTransaction->type === 'send' ? 'Sent' : 'Received' }} {{ $asset['name'] }}
                            </h3>
                            <p class="text-[11px] font-bold text-white/30 uppercase tracking-[0.2em]">
                                {{ $this->selectedTransaction->created_at->format('F d, Y • h:i A') }}
                            </p>
                        </div>

                        <!-- Amount -->
                        <div class="space-y-2">
                            <div class="flex items-baseline gap-2">
                                <span class="text-5xl font-black text-white tracking-tighter">{{ number_format($this->selectedTransaction->amount, 4) }}</span>
                                <span class="text-xl font-black text-white/40">{{ $asset['symbol'] }}</span>
                            </div>
                            <p class="text-xs font-bold text-white/20 uppercase tracking-widest flex items-center justify-center gap-2">
                                <span>~ ${{ number_format($this->selectedTransaction->amount * (float)str_replace(',', '', $asset['usd']), 2) }} USD</span>
                                <i data-lucide="arrow-up-down" class="w-3 h-3"></i>
                            </p>
                        </div>

                        <!-- Details Table -->
                        <div class="w-full bg-white/[0.02] border border-white/10 rounded-[2rem] p-6 mt-4 space-y-5 backdrop-blur-xl">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-black text-white/30 uppercase tracking-widest">
                                    {{ $this->selectedTransaction->type === 'send' ? 'To' : 'From' }}
                                </span>
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 rounded-full overflow-hidden bg-primary/20">
                                        <img src="https://ui-avatars.com/api/?name=User&background=random" class="w-full h-full object-cover" alt="">
                                    </div>
                                    <span class="text-[11px] font-bold text-white tracking-tight">{{ Str::limit($this->selectedTransaction->recipient_address, 16) }}</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-black text-white/30 uppercase tracking-widest">Status</span>
                                <span class="text-[11px] font-black text-success uppercase tracking-widest">{{ ucfirst($this->selectedTransaction->status) }}</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-black text-white/30 uppercase tracking-widest">Network fee</span>
                                <span class="text-[11px] font-bold text-white tracking-tight">${{ $this->selectedTransaction->network_fee ?? '3.54' }}</span>
                            </div>

                            <div class="flex items-center justify-between group cursor-pointer" @click="navigator.clipboard.writeText('{{ $this->selectedTransaction->hash }}')">
                                <span class="text-[10px] font-black text-white/30 uppercase tracking-widest">Transaction ID</span>
                                <div class="flex items-center gap-1.5">
                                    <span class="text-[11px] font-bold text-white tracking-tight">{{ Str::limit($this->selectedTransaction->hash, 16) }}</span>
                                    <i data-lucide="external-link" class="w-3 h-3 text-white/40 group-hover:text-white transition-colors"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Processing Overlay -->
    <div wire:loading.flex wire:target="send"
         class="fixed inset-0 z-[200] flex items-center justify-center bg-[#0A0C10]/90 backdrop-blur-xl">
        <div class="flex flex-col items-center space-y-6">
            <div class="relative">
                <div class="w-24 h-24 rounded-full border-4 border-primary/20 border-t-primary animate-spin"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <i data-lucide="send" class="w-8 h-8 text-primary animate-pulse"></i>
                </div>
            </div>
            <div class="space-y-1 text-center">
                <h3 class="text-xl font-black text-white tracking-tighter">Processing Transaction</h3>
                <p class="text-[10px] font-bold text-white/40 uppercase tracking-[0.2em]">Securing on blockchain...</p>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div x-data="{ show: @entangle('showSuccessModal') }" x-show="show" x-cloak
         class="fixed inset-0 z-[200] flex items-center justify-center p-6 bg-black/60 backdrop-blur-md"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100">
        <div class="bg-[#0A0C10] w-full max-w-sm rounded-[3rem] p-8 border border-white/10 shadow-2xl relative overflow-hidden text-center space-y-8">
            <!-- Glow background -->
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-64 h-64 bg-success/20 blur-[100px] -z-10"></div>
            
            <div class="relative inline-flex">
                <div class="w-24 h-24 rounded-full bg-success/10 border-4 border-success/20 flex items-center justify-center relative">
                    <div class="absolute inset-0 bg-success/20 rounded-full animate-ping opacity-20"></div>
                    <i data-lucide="check-circle" class="w-12 h-12 text-success"></i>
                </div>
                <!-- Confetti particles (simplified dots) -->
                <div class="absolute -top-4 -right-4 w-2 h-2 rounded-full bg-success/40 animate-bounce"></div>
                <div class="absolute -bottom-2 -left-6 w-3 h-3 rounded-full bg-primary/40 animate-bounce delay-75"></div>
            </div>

            <div class="space-y-2">
                <h2 class="text-3xl font-black text-white tracking-tighter">Transfer Successful!</h2>
                <p class="text-[11px] font-bold text-white/40 leading-relaxed max-w-[240px] mx-auto uppercase tracking-wider">
                    Your assets have been sent successfully. They are now being confirmed on the network.
                </p>
            </div>

            <div class="flex flex-col gap-3">
                <button wire:click="closeSuccessModal" 
                        class="w-full bg-white text-[#0A0C10] font-black py-4 rounded-2xl hover:scale-[1.02] transition-transform active:scale-95 shadow-xl">
                    Done
                </button>
                <button wire:click="setView('transactions')" @click="show = false"
                        class="w-full bg-white/5 text-white font-black py-4 rounded-2xl border border-white/10 hover:bg-white/10 transition-all">
                    View Transaction History
                </button>
            </div>
        </div>
    </div>
</div>
