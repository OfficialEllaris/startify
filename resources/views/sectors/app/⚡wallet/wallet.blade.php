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
        .ripple-container {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 160px;
            height: 160px;
        }
        .ripple-ring {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%) scale(1);
            width: 80px;
            height: 80px;
            border: 2px solid var(--color-primary);
            border-radius: 50%;
            opacity: 0;
            animation: ripple 3s cubic-bezier(0, 0.2, 0.8, 1) infinite;
        }
        @keyframes ripple {
            0% { transform: translate(-50%, -50%) scale(1); opacity: 0; }
            50% { opacity: 0.3; }
            100% { transform: translate(-50%, -50%) scale(2.8); opacity: 0; }
        }
        .ripple-1 { animation-delay: 0s; }
        .ripple-2 { animation-delay: 0.75s; }
        .ripple-3 { animation-delay: 1.5s; }
        .ripple-4 { animation-delay: 2.25s; }
    </style>
@endpush

<div class="flex flex-col min-h-screen bg-transparent relative">
    <!-- Main Scrollable Area -->
    <main class="flex-1 pb-30 overflow-y-auto no-scrollbar">
        <div class="max-w-md mx-auto p-6 lg:p-4 space-y-8" 
             x-data
             x-init="$watch('$wire.view', () => { window.scrollTo({ top: 0, behavior: 'smooth' }); })">

            <!-- Navigation Header -->
            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('app.dashboard') }}" wire:navigate
                   class="group flex items-center gap-3 px-5 py-2.5 rounded-2xl bg-white/5 border border-white/10 hover:bg-white/10 transition-all active:scale-95 shadow-xl">
                    <i data-lucide="chevron-left" class="w-4 h-4 text-white group-hover:-translate-x-1 transition-transform"></i>
                    <span class="text-[10px] font-black text-white uppercase tracking-[0.2em]">Dashboard</span>
                </a>
                
                <div class="flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-white/5 border border-white/10 shadow-xl">
                    <div class="w-1.5 h-1.5 rounded-full bg-success animate-pulse"></div>
                    <span class="text-[8px] font-black text-white/40 uppercase tracking-widest">Network Live</span>
                </div>
            </div>

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
                                <div class="py-4">
                                    <div class="border-2 border-dashed border-white/5 rounded-[2.5rem] p-12 flex flex-col items-center justify-center text-center space-y-4 transition-all duration-500 hover:border-white/10 hover:bg-white/[0.01]">
                                        <div class="w-16 h-16 rounded-3xl bg-white/5 flex items-center justify-center border border-white/10 shadow-2xl">
                                            <i data-lucide="history" class="w-7 h-7 text-white/10"></i>
                                        </div>
                                        <div class="space-y-1.5">
                                            <h4 class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em]">No Recent History</h4>
                                            <p class="text-[8px] font-bold text-white/20 uppercase tracking-widest leading-relaxed max-w-[200px] mx-auto">Your transaction history will automatically synchronize here after your first network interaction.</p>
                                        </div>
                                    </div>
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
                                    class="bg-transparent border-none text-5xl font-black text-white p-0 w-48 text-center focus:ring-0 focus:outline-none outline-none placeholder:text-white/5 tracking-tighter transition-all focus:scale-105 selection:bg-primary selection:text-white">
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
                                class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-sm text-white placeholder:text-white/10 focus:ring-0 focus:outline-none outline-none transition-all selection:bg-primary selection:text-white">
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
                                            <div class="absolute inset-0 opacity-20 blur-md {{ $transaction->type === 'swap' ? 'bg-primary' : ($transaction->type === 'send' ? 'bg-error' : 'bg-success') }}"></div>
                                            <i data-lucide="{{ $transaction->type === 'swap' ? 'repeat' : ($transaction->type === 'send' ? 'arrow-up-right' : 'arrow-down-left') }}" class="w-6 h-6 text-white relative z-10"></i>
                                        </div>

                                        <div class="flex flex-col">
                                            <span class="text-base font-black text-white tracking-tight leading-tight">
                                                {{ $transaction->type === 'swap' ? 'Swapped' : ($transaction->type === 'send' ? 'Sent' : 'Received') }} {{ strtoupper($transaction->asset_id) }}
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
                                    class="w-full bg-transparent border-none text-2xl font-black text-white placeholder:text-white/10 focus:outline-none focus:ring-0 outline-none p-0 selection:bg-primary selection:text-white">
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
            @elseif($view === 'buy')
                <div wire:key="view-buy" class="max-w-[420px] mx-auto space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700 py-4">
                    <!-- Top Navigation -->
                    <div class="flex items-center justify-between mb-2">
                        <button wire:click="setView('overview')" 
                                class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center hover:bg-white/10 transition-all group/back">
                            <i data-lucide="chevron-left" class="w-5 h-5 text-white group-hover/back:-translate-x-0.5 transition-transform"></i>
                        </button>
                        
                        <div class="flex items-center gap-2 bg-[#0A0C10]/80 border border-success/20 px-4 py-1.5 rounded-full backdrop-blur-md scale-90">
                            <div class="w-1.5 h-1.5 bg-success rounded-full shadow-[0_0_8px_rgba(34,197,94,0.6)]"></div>
                            <span class="text-[8px] font-black text-success uppercase tracking-[0.2em]">Secure Gateway</span>
                        </div>
                        
                        <div class="w-10"></div> <!-- Spacer -->
                    </div>

                    <!-- Refined Header -->
                    <div class="text-center space-y-2 relative z-10">
                        <h2 class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-b from-white to-white/60 tracking-[-0.04em] uppercase leading-none">
                            External Purchase
                        </h2>
                        <div class="flex items-center justify-center gap-4">
                            <div class="h-[1px] w-8 bg-gradient-to-r from-transparent to-white/10"></div>
                            <p class="text-[9px] font-black text-white/20 uppercase tracking-[0.3em]">Direct Redirect</p>
                            <div class="h-[1px] w-8 bg-gradient-to-l from-transparent to-white/10"></div>
                        </div>
                    </div>

                    <!-- Compact Platform Card -->
                    <div class="relative group">
                        <div class="relative bg-gradient-to-b from-white/[0.04] to-transparent border border-white/[0.06] rounded-[2.5rem] p-8 text-center space-y-8 backdrop-blur-3xl shadow-2xl">
                            <div class="relative space-y-8">
                                <div class="flex justify-center transform group-hover:scale-105 transition-transform duration-500">
                                    <img src="/changelly-logo.png" class="h-8 object-contain brightness-125 opacity-80 group-hover:opacity-100" alt="Changelly">
                                </div>
                                
                                <div class="flex items-center justify-center gap-4">
                                    <div class="h-[1px] bg-gradient-to-r from-transparent via-white/5 to-transparent w-full"></div>
                                </div>

                                <div class="space-y-2">
                                    <p class="text-[10px] font-black text-white/80 uppercase tracking-[0.15em]">Official Partner Gateway</p>
                                    <p class="text-[8px] font-bold text-white/20 uppercase tracking-[0.1em] leading-relaxed px-4">
                                        Trusted by millions worldwide for fast, secure and private financial operations.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <button wire:click="buy" 
                                class="w-full bg-primary text-white font-black py-4.5 rounded-[1.5rem] shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2 group">
                            <span class="uppercase tracking-[0.15em] text-[10px]">CONTINUE TO CHANGELLY</span>
                            <i data-lucide="arrow-up-right" class="w-4 h-4 group-hover:-translate-y-0.5 group-hover:translate-x-0.5 transition-all"></i>
                        </button>
                        
                        <button wire:click="setView('overview')" 
                                class="w-full bg-white/[0.02] text-white/40 font-black py-4 rounded-[1.5rem] border border-white/5 hover:bg-white/5 transition-all text-[8px] uppercase tracking-[0.2em]">
                            Go Back to Wallet
                        </button>
                    </div>

                    <!-- Enhanced Safety Note -->
                    <div class="flex items-start gap-4 px-5 py-5 bg-white/[0.03] border border-white/10 rounded-2xl">
                        <i data-lucide="info" class="w-5 h-5 text-white/40 flex-shrink-0"></i>
                        <p class="text-[10px] font-bold text-white/30 leading-relaxed uppercase tracking-tight">
                            Important: <span class="text-white/60">{{ config('app.name') }}</span> is not a licensed financial provider. All purchases are handled securely by <span class="text-white/60">Changelly</span>. Please ensure your details are correct.
                        </p>
                    </div>
                </div>
            @elseif($view === 'swap')
                <div wire:key="view-swap" class="max-w-[440px] mx-auto space-y-4 animate-in fade-in slide-in-from-bottom-4 duration-700 py-4">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-2">
                        <button wire:click="setView('overview')" 
                                class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center hover:bg-white/10 transition-all group/back">
                            <i data-lucide="chevron-left" class="w-5 h-5 text-white group-hover/back:-translate-x-0.5 transition-transform"></i>
                        </button>
                        <h1 class="text-xs font-black text-white uppercase tracking-[0.3em]">Exchange</h1>
                        <div class="w-10"></div>
                    </div>

                    <div class="space-y-3 relative">
                        @php
                            $fromAsset = collect($this->assets())->firstWhere('id', $this->fromAssetId);
                            $toAsset = collect($this->assets())->firstWhere('id', $this->toAssetId);
                        @endphp

                        <!-- From Card -->
                        <div class="relative group" x-data="{ open: false }" :class="{ 'z-30': open, 'z-10': !open }">
                            <div class="bg-gradient-to-br from-white/[0.08] via-[#0A0C10] to-primary/5 border border-white/[0.08] rounded-[2.5rem] p-6 space-y-4 backdrop-blur-3xl shadow-xl transition-all duration-500 group-hover:border-white/20">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-black text-white/20 uppercase tracking-widest">From</span>
                                    <div class="flex items-center gap-1.5 bg-white/5 px-2.5 py-1 rounded-full border border-white/5">
                                        <div class="w-1 h-1 bg-success rounded-full animate-pulse"></div>
                                        <span class="text-[8px] font-bold text-success uppercase tracking-widest">Best Rate</span>
                                    </div>
                                </div>

                                <div class="flex items-end justify-between gap-4">
                                    <div class="space-y-1 flex-1 min-w-0">
                                        <input type="number" 
                                               wire:model.live="fromAmount"
                                               placeholder="0.00"
                                               class="w-full bg-transparent border-none p-0 text-2xl sm:text-4xl font-black text-white placeholder:text-white/5 focus:ring-0 focus:outline-none outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none selection:bg-primary selection:text-white truncate">
                                        <p class="text-[9px] font-bold text-white/20 uppercase tracking-widest truncate">
                                            Available: {{ number_format((float)($fromAsset['balance'] ?? 0), 4) }} {{ $fromAsset['symbol'] ?? '' }}
                                        </p>
                                    </div>

                                    <div class="relative flex-shrink-0">
                                        <button @click="open = !open" 
                                                class="flex items-center gap-2 bg-white/5 hover:bg-white/10 border border-white/10 p-2 pr-4 rounded-2xl transition-all">
                                            <img src="{{ $fromAsset['image'] ?? '' }}" class="w-6 h-6 rounded-full" alt="">
                                            <span class="text-xs font-black text-white">{{ $fromAsset['symbol'] ?? '' }}</span>
                                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-white/20"></i>
                                        </button>
                                        
                                        <!-- Mini Dropdown -->
                                        <div x-show="open" @click.away="open = false" 
                                             class="absolute right-0 mt-2 w-48 bg-[#0A0C10] border border-white/10 rounded-2xl shadow-2xl z-50 overflow-hidden py-2 backdrop-blur-xl animate-in fade-in zoom-in-95 duration-200">
                                            <div class="max-h-[240px] overflow-y-auto custom-scrollbar">
                                                @foreach($this->assets() as $asset)
                                                    <button @click="open = false" 
                                                            wire:click="selectSwapAsset('from', '{{ $asset['id'] }}')"
                                                            class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-white/5 transition-colors text-left group/item">
                                                        <img src="{{ $asset['image'] }}" class="w-5 h-5 rounded-full opacity-60 group-hover/item:opacity-100" alt="">
                                                        <div class="flex-1">
                                                            <p class="text-[10px] font-black text-white tracking-wide uppercase">{{ $asset['symbol'] }}</p>
                                                            <p class="text-[8px] font-bold text-white/20 uppercase">{{ number_format((float)$asset['balance'], 4) }} {{ $asset['name'] }}</p>
                                                        </div>
                                                        @if($fromAssetId === $asset['id'])
                                                            <div class="w-1 h-1 bg-primary rounded-full"></div>
                                                        @endif
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Interchange Button -->
                        <div class="absolute left-1/2 -translate-x-1/2 top-1/2 -translate-y-1/2 z-20">
                            <button wire:click="swapAssets" 
                                    class="w-12 h-12 bg-primary text-white rounded-2xl flex items-center justify-center border-4 border-[#020205] shadow-2xl hover:rotate-180 transition-all duration-500 group">
                                <i data-lucide="arrow-down-up" class="w-5 h-5 group-hover:scale-110"></i>
                            </button>
                        </div>

                        <!-- To Card -->
                        <div class="relative group" x-data="{ open: false }" :class="{ 'z-30': open, 'z-10': !open }">
                            <div class="bg-gradient-to-br from-white/[0.04] via-[#0A0C10] to-white/[0.02] border border-white/[0.06] rounded-[2.5rem] p-6 space-y-4 backdrop-blur-3xl shadow-xl transition-all duration-500 group-hover:border-white/10">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-black text-white/20 uppercase tracking-widest">To</span>
                                    <p class="text-[8px] font-bold text-white/20 uppercase tracking-widest">Calculated Price</p>
                                </div>

                                <div class="flex items-end justify-between gap-4">
                                    <div class="space-y-1 flex-1 min-w-0">
                                        <div class="text-2xl sm:text-4xl font-black text-white/40 tracking-tight h-[40px] flex items-end overflow-x-auto no-scrollbar whitespace-nowrap">
                                            {{ $toAmount ?: '0.00' }}
                                        </div>
                                        <p class="text-[9px] font-bold text-white/20 uppercase tracking-widest truncate">
                                            Balance: {{ number_format((float)($toAsset['balance'] ?? 0), 4) }} {{ $toAsset['symbol'] ?? '' }}
                                        </p>
                                    </div>

                                    <div class="relative flex-shrink-0">
                                        <button @click="open = !open" 
                                                class="flex items-center gap-2 bg-white/5 hover:bg-white/10 border border-white/10 p-2 pr-4 rounded-2xl transition-all">
                                            <img src="{{ $toAsset['image'] ?? '' }}" class="w-6 h-6 rounded-full" alt="">
                                            <span class="text-xs font-black text-white">{{ $toAsset['symbol'] ?? '' }}</span>
                                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-white/20"></i>
                                        </button>
                                        
                                        <!-- Mini Dropdown -->
                                        <div x-show="open" @click.away="open = false" 
                                             class="absolute right-0 mt-2 w-48 bg-[#0A0C10] border border-white/10 rounded-2xl shadow-2xl z-50 overflow-hidden py-2 backdrop-blur-xl animate-in fade-in zoom-in-95 duration-200">
                                            <div class="max-h-[240px] overflow-y-auto custom-scrollbar">
                                                @foreach($this->assets() as $asset)
                                                    <button @click="open = false" 
                                                            wire:click="selectSwapAsset('to', '{{ $asset['id'] }}')"
                                                            class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-white/5 transition-colors text-left group/item">
                                                        <img src="{{ $asset['image'] }}" class="w-5 h-5 rounded-full opacity-60 group-hover/item:opacity-100" alt="">
                                                        <div class="flex-1">
                                                            <p class="text-[10px] font-black text-white tracking-wide uppercase">{{ $asset['symbol'] }}</p>
                                                            <p class="text-[8px] font-bold text-white/20 uppercase">{{ number_format((float)$asset['balance'], 4) }} {{ $asset['name'] }}</p>
                                                        </div>
                                                        @if($toAssetId === $asset['id'])
                                                            <div class="w-1 h-1 bg-primary rounded-full"></div>
                                                        @endif
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Swap Info -->
                    <div class="px-6 py-2">
                        <div class="flex items-center justify-between text-[9px] font-bold uppercase tracking-widest text-white/20">
                            <span>Exchange Fee</span>
                            <span class="text-white/40">Free</span>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <div class="pt-2">
                        <button wire:click="executeSwap" 
                                wire:loading.attr="disabled"
                                class="w-full bg-primary text-white font-black py-5 rounded-[2rem] shadow-[0_20px_40px_rgba(var(--color-primary),0.3)] hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3 group relative overflow-hidden">
                            <div wire:loading.flex wire:target="executeSwap" class="absolute inset-0 bg-primary items-center justify-center z-10">
                                <div class="w-6 h-6 border-4 border-white/20 border-t-white rounded-full animate-spin"></div>
                            </div>
                            
                            <span wire:loading.remove wire:target="executeSwap" class="uppercase tracking-[0.2em] text-xs">SWAP {{ $fromAsset['symbol'] ?? '' }}</span>
                            <i wire:loading.remove wire:target="executeSwap" data-lucide="repeat" class="w-5 h-5 group-hover:rotate-180 transition-all duration-700"></i>
                        </button>
                    </div>

                    <!-- Safety Note -->
                    <div class="flex items-start gap-4 px-6 py-4 bg-white/[0.02] border border-white/5 rounded-3xl">
                        <i data-lucide="shield-check" class="w-5 h-5 text-success/40 flex-shrink-0"></i>
                        <p class="text-[9px] font-bold text-white/20 leading-relaxed uppercase tracking-tight">
                            Internal swaps are processed instantly. Ensure you have selected the correct assets before proceeding.
                        </p>
                    </div>
                </div>
            @elseif($view === 'card')
                <div wire:key="view-card" class="max-w-[440px] mx-auto space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-700 py-4">
                    <!-- Header -->
                    <div class="flex items-center justify-between px-2 mb-6">
                        <button wire:click="setView('overview')" class="w-10 h-10 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-white/10 transition-all group">
                            <i data-lucide="chevron-left" class="w-5 h-5 text-white/40 group-hover:text-white transition-colors"></i>
                        </button>
                        <h2 class="text-xs font-black text-white uppercase tracking-[0.3em]">Virtual Cards</h2>
                        <button wire:click="$toggle('isApplyingForCard')" 
                                class="w-10 h-10 rounded-2xl bg-primary/10 border border-primary/20 flex items-center justify-center hover:bg-primary/20 transition-all text-primary">
                            <i data-lucide="{{ $isApplyingForCard ? 'x' : 'plus' }}" class="w-5 h-5"></i>
                        </button>
                    </div>

                    @if(auth()->user()->cards()->count() >= 3)
                        <div class="bg-warning/10 border border-warning/20 p-4 rounded-3xl flex items-start gap-4 animate-in fade-in slide-in-from-top-2">
                            <i data-lucide="alert-triangle" class="w-5 h-5 text-warning flex-shrink-0"></i>
                            <div class="space-y-1">
                                <p class="text-[10px] font-black text-white uppercase tracking-widest">Card Limit Reached</p>
                                <p class="text-[8px] font-bold text-white/40 leading-relaxed uppercase tracking-tight">
                                    You have reached the maximum of 3 virtual cards. Delete an existing card or contact support to increase your limit.
                                </p>
                            </div>
                        </div>
                    @endif

                    @if($this->isApplyingForCard)
                        <!-- Apply for Card Flow -->
                        <div class="space-y-6 animate-in fade-in zoom-in-95 duration-500">
                            <div class="bg-white/5 border border-white/10 rounded-[2.5rem] p-8 space-y-8 backdrop-blur-3xl">
                                <div class="text-center space-y-2">
                                    <h2 class="text-xl font-black text-white uppercase tracking-widest">New Virtual Card</h2>
                                    <p class="text-[10px] font-bold text-white/20 uppercase tracking-widest">Instant issuance & Worldwide usage</p>
                                </div>

                                <!-- Step 1: Select Brand -->
                                <div class="space-y-4">
                                    <label class="text-[10px] font-black text-white/30 uppercase tracking-[0.2em] px-2">1. Select Provider</label>
                                    <div class="grid grid-cols-2 gap-3">
                                        @foreach(['Visa', 'Mastercard', 'American Express', 'Discover'] as $brand)
                                            @php
                                                $logo = match($brand) {
                                                    'Visa' => 'visa.svg',
                                                    'Mastercard' => 'mastercard.svg',
                                                    'American Express' => 'amex.svg',
                                                    'Discover' => 'discover.svg',
                                                    default => null
                                                };
                                            @endphp
                                            <button wire:click="selectCardBrand('{{ $brand }}')"
                                                    class="p-4 rounded-2xl border transition-all flex flex-col items-center justify-center min-h-[80px] gap-3 {{ $selectedCardBrand === $brand ? 'bg-primary/10 border-primary shadow-[0_0_20px_rgba(var(--color-primary),0.2)]' : 'bg-white/5 border-white/10 grayscale opacity-60 hover:opacity-100 hover:grayscale-0' }}">
                                                @if($logo)
                                                    <img src="{{ asset($logo) }}" class="h-5 w-auto object-contain" alt="{{ $brand }}">
                                                @else
                                                    <span class="text-[10px] font-black text-white uppercase">{{ $brand }}</span>
                                                @endif
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Step 2: Select Payment Asset -->
                                <div class="space-y-4">
                                    <label class="text-[10px] font-black text-white/30 uppercase tracking-[0.2em] px-2">2. Verification Asset</label>
                                    <div x-data="{ open: false }" class="relative">
                                        @php $payAsset = collect($this->assets())->firstWhere('id', $this->selectedCardAssetId); @endphp
                                        <button @click="open = !open" 
                                                class="w-full bg-white/5 border border-white/10 p-4 rounded-2xl flex items-center justify-between hover:bg-white/10 transition-all">
                                            <div class="flex items-center gap-3">
                                                <img src="{{ $payAsset['image'] }}" class="w-6 h-6 rounded-full" alt="">
                                                <div class="text-left">
                                                    <p class="text-[10px] font-black text-white uppercase">{{ $payAsset['name'] }}</p>
                                                    <p class="text-[8px] font-bold text-white/40 uppercase">Balance: {{ $payAsset['usd_total'] }} USD</p>
                                                </div>
                                            </div>
                                            <i data-lucide="chevron-down" class="w-4 h-4 text-white/20"></i>
                                        </button>

                                        <div x-show="open" @click.away="open = false" 
                                             class="absolute inset-x-0 bottom-full mb-2 bg-[#0A0C10] border border-white/10 rounded-2xl shadow-2xl z-50 overflow-hidden py-2 backdrop-blur-xl animate-in slide-in-from-bottom-2 duration-300">
                                            <div class="max-h-[200px] overflow-y-auto custom-scrollbar">
                                                @foreach($this->assets() as $asset)
                                                    <button @click="open = false" 
                                                            wire:click="selectCardAsset('{{ $asset['id'] }}')"
                                                            class="w-full flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition-colors text-left group/item">
                                                        <img src="{{ $asset['image'] }}" class="w-5 h-5 rounded-full opacity-60" alt="">
                                                        <div class="flex-1">
                                                            <p class="text-[10px] font-black text-white tracking-wide uppercase">{{ $asset['name'] }}</p>
                                                            <p class="text-[8px] font-bold text-white/20 uppercase">{{ $asset['usd_total'] }} USD</p>
                                                        </div>
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @error('card_payment')
                                        <div class="bg-error/10 border border-error/20 p-4 rounded-2xl flex items-center gap-3 animate-shake">
                                            <i data-lucide="alert-circle" class="w-4 h-4 text-error"></i>
                                            <p class="text-[10px] font-bold text-error uppercase tracking-tight">{{ $message }}</p>
                                        </div>
                                    @enderror
                                </div>

                                @php $isAtLimit = auth()->user()->cards()->count() >= 3; @endphp
                                <button wire:click="createCard" 
                                        wire:loading.attr="disabled"
                                        @if($isAtLimit) disabled @endif
                                        class="w-full {{ $isAtLimit ? 'bg-white/5 text-white/20 border border-white/5 cursor-not-allowed' : 'bg-primary text-white' }} font-black py-5 rounded-2xl shadow-xl hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3 relative overflow-hidden group">
                                    <div wire:loading.flex wire:target="createCard" class="absolute inset-0 bg-primary items-center justify-center z-10">
                                        <div class="w-6 h-6 border-4 border-white/20 border-t-white rounded-full animate-spin"></div>
                                    </div>
                                    <span wire:loading.remove wire:target="createCard" class="uppercase tracking-[0.2em] text-xs">
                                        {{ $isAtLimit ? 'Limit Reached' : 'Confirm & Issue Card' }}
                                    </span>
                                    @if(!$isAtLimit)
                                        <i wire:loading.remove wire:target="createCard" data-lucide="shield-check" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                                    @endif
                                </button>
                            </div>
                        </div>
                    @else
                        <!-- Card List / Empty State -->
                        @if($this->cards->count() > 0)
                            <div class="space-y-6">
                                @foreach($this->cards as $card)
                                    <div class="relative group h-[180px] sm:h-[220px]">
                                        <!-- Card Design -->
                                        <div class="absolute inset-0 bg-gradient-to-br from-[#0A0C10] via-[#050507] to-[#0A0C10] rounded-[2rem] overflow-hidden border border-white/10 shadow-2xl transition-transform duration-500 group-hover:-translate-y-2 group-hover:rotate-1">
                                            <!-- Architectural Sweep (High Visibility) -->
                                            <!-- Top-right Light Sweep -->
                                            <div class="absolute -right-[20%] -top-[20%] w-[120%] h-[120%] bg-gradient-to-bl from-white/14 via-white/[0.02] to-transparent rounded-full blur-3xl pointer-events-none"></div>
                                            
                                            <!-- Bottom-left Wave Definition -->
                                            <div class="absolute -left-[30%] top-[25%] w-[150%] h-[150%] bg-[#050507] rounded-[40%] rotate-[-20deg] shadow-[-20px_-20px_100px_rgba(255,255,255,0.03)] border-t border-white/[0.05] pointer-events-none"></div>
                                            
                                            <!-- Subtle Atmospheric Glow -->
                                            <div class="absolute top-0 right-0 w-64 h-64 bg-white/[0.02] rounded-full blur-3xl"></div>
                                            
                                            <div class="relative h-full p-6 sm:p-8 flex flex-col z-10">
                                                <!-- Top Row -->
                                                <div class="flex items-start justify-between">
                                                    <div class="space-y-0.5">
                                                        <h3 class="text-[10px] font-black text-white/60 uppercase tracking-[0.3em]">{{ strtoupper(config('app.name')) }} CARD</h3>
                                                        <div class="flex items-center gap-1.5">
                                                            <p class="text-[8px] font-black text-white uppercase tracking-widest italic">${{ number_format($card->balance, 2) }}</p>
                                                            <span class="text-[6px] font-black text-white/20 uppercase tracking-[0.2em]">Available</span>
                                                        </div>
                                                    </div>
                                                    <!-- Contactless Icon -->
                                                    <div class="flex items-center gap-1 opacity-40">
                                                        <div class="w-1.5 h-4 border-r-2 border-white rounded-full"></div>
                                                        <div class="w-1.5 h-5 border-r-2 border-white rounded-full"></div>
                                                        <div class="w-1.5 h-6 border-r-2 border-white rounded-full"></div>
                                                    </div>
                                                </div>

                                                <!-- Middle: Centered Card Number -->
                                                <div class="flex-1 flex items-center">
                                                    <p class="text-lg sm:text-2xl font-mono text-white tracking-[0.2em] drop-shadow-lg">{{ $card->number }}</p>
                                                </div>

                                                <!-- Bottom Row -->
                                                <div class="flex items-end justify-between">
                                                    <div class="space-y-3 sm:space-y-4">
                                                        <div class="space-y-1">
                                                            <p class="text-[7px] font-black text-white/30 uppercase tracking-widest">Card Holder</p>
                                                            <p class="text-[9px] sm:text-[11px] font-black text-white uppercase tracking-widest">{{ $card->card_holder_name }}</p>
                                                        </div>
                                                        <div class="flex gap-4 sm:gap-6">
                                                            <div class="space-y-0.5">
                                                                <p class="text-[7px] font-black text-white/30 uppercase tracking-widest">Expires</p>
                                                                <p class="text-[8px] sm:text-[10px] font-black text-white tracking-widest font-mono">{{ $card->expiry }}</p>
                                                            </div>
                                                            <div class="space-y-0.5">
                                                                <p class="text-[7px] font-black text-white/30 uppercase tracking-widest">CVV</p>
                                                                <div class="flex items-center gap-2">
                                                                    <p class="text-[8px] sm:text-[10px] font-black text-white tracking-widest font-mono">
                                                                        {{ ($this->showCvv[$card->id] ?? false) ? $card->cvv : '***' }}
                                                                    </p>
                                                                    <button wire:click="toggleCvv({{ $card->id }})" class="hover:text-primary transition-colors">
                                                                        <i data-lucide="{{ ($this->showCvv[$card->id] ?? false) ? 'eye-off' : 'eye' }}" class="w-3 h-3 opacity-40"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Brand Logo -->
                                                    <div class="flex flex-col items-end">
                                                        @php
                                                            $logo = match($card->brand) {
                                                                'Visa' => 'visa.svg',
                                                                'Mastercard' => 'mastercard.svg',
                                                                'American Express' => 'amex.svg',
                                                                'Discover' => 'discover.svg',
                                                                default => null
                                                            };
                                                        @endphp
                                                        @if($logo)
                                                            <img src="{{ asset($logo) }}" class="h-5 sm:h-8 w-auto object-contain opacity-90" alt="{{ $card->brand }}">
                                                        @else
                                                            <span class="text-lg sm:text-2xl font-black text-white italic tracking-tighter opacity-90">{{ $card->brand }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex flex-wrap items-center justify-center gap-3 -mt-2 mb-8">
                                        <button wire:click="openFundCard({{ $card->id }})" 
                                                class="px-5 py-2.5 bg-primary/10 border border-primary/20 rounded-2xl text-[9px] font-black text-primary uppercase tracking-widest hover:bg-primary/20 transition-all flex items-center gap-2 group/fund">
                                            <i data-lucide="plus-circle" class="w-3.5 h-3.5 group-hover/fund:rotate-90 transition-transform"></i>
                                            Fund
                                        </button>
                                        <button wire:click="openWithdrawCard({{ $card->id }})" 
                                                class="px-5 py-2.5 bg-white/5 border border-white/10 rounded-2xl text-[9px] font-black text-white uppercase tracking-widest hover:bg-white/10 transition-all flex items-center gap-2 group/withdraw">
                                            <i data-lucide="arrow-down-circle" class="w-3.5 h-3.5 text-success group-hover/withdraw:-translate-y-0.5 transition-transform"></i>
                                            Withdraw
                                        </button>
                                        <button wire:confirm="Are you sure you want to delete this card? Any remaining funds will be automatically moved to your wallet."
                                                wire:click="deleteCard({{ $card->id }})" 
                                                class="w-10 h-10 bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center text-white/20 hover:bg-error/10 hover:border-error/20 hover:text-error transition-all group/delete">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                @endforeach
                                
                                @if($this->cards->count() < 3)
                                    <button wire:click="$set('isApplyingForCard', true)" 
                                            class="w-full h-[100px] border-2 border-dashed border-white/10 rounded-[2rem] flex flex-col items-center justify-center gap-2 hover:bg-white/5 hover:border-white/20 transition-all group">
                                        <i data-lucide="plus-circle" class="w-6 h-6 text-white/20 group-hover:text-primary transition-colors"></i>
                                        <span class="text-[10px] font-black text-white/20 uppercase tracking-widest">Add Another Card</span>
                                    </button>
                                @endif
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-20 space-y-8 text-center animate-in fade-in zoom-in-95 duration-700">
                                <div class="relative">
                                    <div class="w-24 h-24 bg-white/5 rounded-full flex items-center justify-center border border-white/10 animate-pulse">
                                        <i data-lucide="credit-card" class="w-10 h-10 text-white/20"></i>
                                    </div>
                                    <div class="absolute -top-2 -right-2 bg-primary p-2 rounded-full shadow-xl">
                                        <i data-lucide="lock" class="w-4 h-4 text-white"></i>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <h2 class="text-xl font-black text-white uppercase tracking-widest">No Active Cards</h2>
                                    <p class="text-sm font-medium text-white/30 max-w-[240px] mx-auto uppercase tracking-tight leading-relaxed">
                                        You haven't issued any virtual cards yet. {{ config('app.name') }} cards offer instant worldwide spending.
                                    </p>
                                </div>
                                <button wire:click="$set('isApplyingForCard', true)" 
                                        class="bg-white text-[#0A0C10] text-[10px] font-black uppercase tracking-[0.2em] px-10 py-5 rounded-2xl shadow-2xl hover:scale-105 active:scale-95 transition-all">
                                    Apply For Card
                                </button>
                                
                                <div class="flex items-center gap-6 pt-4 opacity-20 grayscale">
                                    <span class="text-[10px] font-black text-white tracking-tighter italic">Visa</span>
                                    <span class="text-[10px] font-black text-white tracking-tighter italic">Mastercard</span>
                                    <span class="text-[10px] font-black text-white tracking-tighter italic">Amex</span>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            @elseif($view === 'stake')
                <div wire:key="view-stake" class="max-w-[440px] mx-auto space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-700 py-4">
                    <!-- Header -->
                    <div class="flex items-center justify-between px-2">
                        <button wire:click="setView('overview')" class="w-10 h-10 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-white/10 transition-all group">
                            <i data-lucide="chevron-left" class="w-5 h-5 text-white/40 group-hover:text-white transition-colors"></i>
                        </button>
                        <h2 class="text-xs font-black text-white uppercase tracking-[0.3em]">Staking Engine</h2>
                        <div class="w-10"></div> <!-- Spacer -->
                    </div>

                    @if($stakeAssetId)
                        @php $stakeAsset = collect($this->assets())->firstWhere('id', $stakeAssetId); @endphp
                        <!-- Staking Detail View -->
                        <div class="space-y-6 animate-in fade-in slide-in-from-right-4 duration-500">
                            <!-- Detail Header -->
                            <div class="flex items-center justify-between px-2">
                                <button wire:click="$set('stakeAssetId', null)" class="flex items-center gap-2 text-[10px] font-black text-white/40 uppercase tracking-widest hover:text-white transition-colors group">
                                    <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i>
                                    Back to Hub
                                </button>
                                <div class="bg-primary/10 px-3 py-1 rounded-full border border-primary/20 flex items-center justify-center">
                                    <span class="text-[8px] font-black text-primary uppercase leading-none">Step 1 of 2</span>
                                </div>
                            </div>

                            <!-- Asset Hero Card -->
                            <div class="bg-gradient-to-br from-white/[0.05] via-[#0A0C10] to-white/[0.02] border border-white/10 rounded-[2.5rem] p-8 space-y-6 relative overflow-hidden">
                                <div class="absolute -right-4 -bottom-4 w-32 h-32 bg-primary/5 rounded-full blur-3xl"></div>
                                <div class="flex items-center gap-5">
                                    <div class="w-16 h-16 rounded-3xl bg-white/5 flex items-center justify-center border border-white/10 shadow-2xl">
                                        <img src="{{ $stakeAsset['image'] }}" class="w-10 h-10 rounded-full" alt="">
                                    </div>
                                    <div>
                                        <h2 class="text-2xl font-black text-white tracking-tighter uppercase">{{ $stakeAsset['name'] }}</h2>
                                        <p class="text-xs font-bold text-success uppercase tracking-widest">{{ $this->getStakingApy($stakeAssetId) }}% Estimated APY</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-white/5">
                                    <div class="space-y-1">
                                        <p class="text-[8px] font-bold text-white/20 uppercase tracking-widest">Liquid Balance</p>
                                        <p class="text-xs font-black text-white">{{ number_format((float)str_replace(',', '', $stakeAsset['balance']), 4) }} {{ $stakeAsset['symbol'] }}</p>
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-[8px] font-bold text-white/20 uppercase tracking-widest">Staking Status</p>
                                        <p class="text-xs font-black text-success uppercase tracking-tighter italic">Optimized</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Validator Selection -->
                            <div class="space-y-4">
                                <h3 class="text-[10px] font-black text-white/20 uppercase tracking-[0.2em] px-2 flex items-center justify-between">
                                    Select Validator
                                    <span class="text-[8px] normal-case text-white/10 font-medium">Higher uptime = More rewards</span>
                                </h3>
                                <div class="grid gap-3">
                                    @foreach($this->validators as $validator)
                                        <button wire:click="$set('selectedValidatorId', '{{ $validator['id'] }}')" 
                                                class="w-full bg-white/[0.03] border {{ $selectedValidatorId === $validator['id'] ? 'border-primary shadow-[0_0_20px_rgba(var(--color-primary),0.2)]' : 'border-white/10' }} rounded-3xl p-4 flex items-center justify-between group hover:bg-white/[0.05] transition-all text-left">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-2xl {{ $selectedValidatorId === $validator['id'] ? 'bg-primary/20' : 'bg-white/5' }} flex items-center justify-center border border-white/10">
                                                    <i data-lucide="server" class="w-5 h-5 {{ $selectedValidatorId === $validator['id'] ? 'text-primary' : 'text-white/40' }}"></i>
                                                </div>
                                                <div>
                                                    <p class="text-xs font-black text-white uppercase tracking-wider">{{ $validator['name'] }}</p>
                                                    <p class="text-[9px] font-bold text-white/20 uppercase">Commission: {{ $validator['commission'] }}</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-[10px] font-black text-success uppercase tracking-tighter">{{ $validator['reliability'] }}</p>
                                                <p class="text-[8px] font-bold text-white/20 uppercase">{{ $validator['uptime'] }} Uptime</p>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Amount Input -->
                            <div class="bg-white/[0.03] border border-white/10 rounded-[2.5rem] p-8 space-y-4 text-center group transition-all">
                                <p class="text-[10px] font-black text-white/20 uppercase tracking-[0.2em]">Enter Stake Amount</p>
                                <div class="flex flex-col items-center gap-1">
                                    <input type="number" 
                                           wire:model.live="stakeAmount"
                                           placeholder="0.00"
                                           class="bg-transparent border-none text-5xl font-black text-white p-0 w-full text-center focus:ring-0 focus:outline-none outline-none placeholder:text-white/5 tracking-tighter">
                                    <div class="flex items-center gap-2 pt-2">
                                        <button wire:click="$set('stakeAmount', '{{ round((float)str_replace(',', '', $stakeAsset['balance']) * 0.25, 4) }}')" class="px-3 py-1 rounded-full bg-white/5 border border-white/10 text-[8px] font-black text-white/40 uppercase hover:text-white hover:bg-white/10 transition-all">25%</button>
                                        <button wire:click="$set('stakeAmount', '{{ round((float)str_replace(',', '', $stakeAsset['balance']) * 0.5, 4) }}')" class="px-3 py-1 rounded-full bg-white/5 border border-white/10 text-[8px] font-black text-white/40 uppercase hover:text-white hover:bg-white/10 transition-all">50%</button>
                                        <button wire:click="$set('stakeAmount', '{{ round((float)str_replace(',', '', $stakeAsset['balance']) * 0.75, 4) }}')" class="px-3 py-1 rounded-full bg-white/5 border border-white/10 text-[8px] font-black text-white/40 uppercase hover:text-white hover:bg-white/10 transition-all">75%</button>
                                        <button wire:click="$set('stakeAmount', '{{ (float)str_replace(',', '', $stakeAsset['balance']) }}')" class="px-3 py-1 rounded-full bg-white/5 border border-white/10 text-[8px] font-black text-white/40 uppercase hover:text-white hover:bg-white/10 transition-all">Max</button>

                                    </div>
                                </div>
                                @error('stakeAmount')
                                    <p class="text-error text-[10px] font-bold uppercase pt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Summary Card -->
                            <div class="bg-primary/5 border border-primary/20 rounded-3xl p-6 space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-black text-white/40 uppercase tracking-widest">Est. Monthly Rewards</span>
                                    <span class="text-xs font-black text-success">
                                        +{{ number_format(((float)$stakeAmount * ($this->getStakingApy($stakeAssetId)/100)) / 12, 6) }} {{ $stakeAsset['symbol'] }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-black text-white/40 uppercase tracking-widest">Unstaking Period</span>
                                    <span class="text-[10px] font-black text-white uppercase italic tracking-tighter">Instant Withdrawal</span>
                                </div>
                            </div>

                            <div class="pt-4 pb-12">
                                <button wire:click="stake" 
                                        wire:loading.attr="disabled"
                                        class="w-full bg-primary text-white font-black py-5 rounded-2xl shadow-[0_10px_30px_rgba(var(--color-primary),0.4)] hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-3 group relative overflow-hidden">
                                    <div wire:loading.flex wire:target="stake" class="absolute inset-0 bg-primary items-center justify-center z-10">
                                        <div class="w-5 h-5 border-3 border-white/20 border-t-white rounded-full animate-spin"></div>
                                    </div>
                                    <span class="uppercase tracking-[0.2em] text-[10px] font-black">Initiate Staking</span>
                                    <i data-lucide="zap" class="w-4 h-4 group-hover:scale-125 transition-transform"></i>
                                </button>
                            </div>
                        </div>
                    @else
                            <div class="relative bg-gradient-to-br from-white/[0.08] to-transparent border border-white/10 rounded-[2.5rem] p-8 overflow-hidden group">
                                <!-- Design Accents -->
                                <div class="absolute -right-20 -top-20 w-64 h-64 bg-primary/10 blur-[100px] rounded-full group-hover:bg-primary/20 transition-all duration-1000"></div>
                                <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-white/5 blur-[50px] rounded-full"></div>
                                
                                <div class="relative z-10 flex flex-col h-full justify-between space-y-8">
                                    <div class="flex items-center justify-between">
                                        <div class="space-y-1">
                                            <span class="text-[10px] font-black text-primary uppercase tracking-[0.3em]">Staking Engine</span>
                                            <div class="flex items-center gap-2">
                                                <div class="w-1.5 h-1.5 bg-success rounded-full animate-pulse"></div>
                                                <p class="text-[8px] font-black text-white/40 uppercase tracking-widest">{{ $this->stakedAssets->count() }} Assets Staked</p>
                                            </div>
                                        </div>
                                        <div class="bg-primary/10 border border-primary/20 rounded-full px-4 py-1.5">
                                            <span class="text-[8px] font-black text-primary uppercase tracking-widest">Active</span>
                                        </div>
                                    </div>

                                    <div>
                                        <h2 class="text-4xl font-black text-white tracking-tighter mb-1">
                                            ${{ number_format($this->stakedAssets->sum(fn($a) => (float)str_replace(',', '', $a['usd_total'])), 2) }}
                                        </h2>
                                        <p class="text-[10px] font-black text-white/20 uppercase tracking-[0.2em]">Total Value Staked</p>
                                    </div>

                                    <div class="grid grid-cols-2 gap-6 pt-6 border-t border-white/5">
                                        <div class="space-y-1">
                                            <p class="text-[8px] font-black text-white/30 uppercase tracking-widest">Total Rewards</p>
                                            <div class="flex items-baseline gap-1">
                                                <span class="text-lg font-black text-white tracking-tight text-primary">+${{ number_format($this->totalStakedRewards, 2) }}</span>
                                            </div>
                                        </div>
                                        <div class="space-y-1 text-right">
                                            <p class="text-[8px] font-black text-white/30 uppercase tracking-widest">Est. Monthly</p>
                                            <p class="text-lg font-black text-white tracking-tight text-success">+${{ number_format($this->estimatedMonthlyStakingReturn, 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <!-- Your Stakes -->
                        @if($this->stakedAssets->isNotEmpty())
                            <div class="space-y-4">
                                <h3 class="text-[10px] font-black text-white/20 uppercase tracking-[0.2em] px-2">Active Stakes</h3>
                                <div class="grid gap-3">
                                    @foreach($this->stakedAssets as $asset)
                                        <div @click="$wire.selectStake({{ $asset['stake_id'] }})"
                                             class="bg-white/[0.03] border border-white/10 rounded-3xl p-4 flex items-center justify-between group hover:bg-white/[0.05] transition-all cursor-pointer">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-2xl bg-white/5 flex items-center justify-center border border-white/10 group-hover:scale-110 transition-transform">
                                                    <img src="{{ $asset['image'] }}" class="w-6 h-6 rounded-full" alt="">
                                                </div>
                                                <div>
                                                    <p class="text-xs font-black text-white uppercase tracking-wider">{{ $asset['name'] }}</p>
                                                    <p class="text-[10px] font-bold text-success uppercase">{{ $asset['apy'] }}% APY</p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-xs font-black text-white">{{ number_format($asset['staked_balance'], 4) }} {{ $asset['symbol'] }}</p>
                                                <div class="flex items-center justify-end gap-1.5 mt-1">
                                                    <span class="text-[8px] font-black text-primary uppercase tracking-widest group-hover:mr-1 transition-all">View Stake Data</span>
                                                    <i data-lucide="chevron-right" class="w-2.5 h-2.5 text-primary group-hover:translate-x-0.5 transition-all"></i>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Available to Stake -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between px-2">
                                <h3 class="text-[10px] font-black text-white/20 uppercase tracking-[0.2em]">Earn Rewards</h3>
                                <span class="text-[8px] font-bold text-white/10 uppercase">{{ $this->posAssets->count() }} Assets Available</span>
                            </div>

                            <!-- Search Input -->
                            <div class="px-0">
                                <div class="relative group">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-white/20 group-focus-within:text-primary transition-colors">
                                        <i data-lucide="search" class="w-4 h-4"></i>
                                    </div>
                                    <input type="text" 
                                           wire:model.live="stakingSearch"
                                           placeholder="Search stakeable assets..."
                                           class="w-full bg-white/[0.03] border border-white/10 rounded-2xl py-3.5 pl-11 pr-4 text-[10px] font-bold text-white placeholder-white/10 focus:outline-none focus:border-primary/40 focus:bg-primary/5 transition-all">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($this->posAssets as $asset)
                                    <button wire:click="selectStakeAsset('{{ $asset['id'] }}')" 
                                            class="bg-gradient-to-br from-white/[0.04] to-transparent border border-white/10 rounded-3xl p-6 text-left group hover:bg-white/[0.07] transition-all relative overflow-hidden flex flex-col justify-between min-h-[220px]">
                                        <!-- Asset Accent Blur -->
                                        <div class="absolute -right-4 -top-4 w-20 h-20 opacity-20 group-hover:opacity-40 transition-opacity blur-2xl rounded-full" style="background-color: {{ $asset['color'] }}"></div>
                                        
                                        <div class="space-y-4 relative z-10">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center border border-white/10 group-hover:scale-110 transition-transform flex-shrink-0">
                                                    <img src="{{ $asset['image'] }}" class="w-7 h-7 rounded-full shadow-2xl" alt="">
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-sm font-black text-white uppercase tracking-wider truncate">{{ $asset['symbol'] }}</p>
                                                    <p class="text-[9px] font-bold text-white/20 uppercase tracking-widest truncate">{{ $asset['name'] }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="space-y-5 relative z-10 mt-6">
                                            <div class="space-y-1">
                                                <p class="text-4xl font-black text-primary tracking-tighter">{{ number_format($this->getStakingApy($asset['id']) / 12, 3) }}%</p>
                                                <p class="text-[10px] font-black text-white/20 uppercase tracking-[0.2em]">Monthly Yield</p>
                                            </div>

                                            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-white/10">
                                                <div class="space-y-1 min-w-0">
                                                    <p class="text-[9px] font-black text-white/20 uppercase tracking-widest truncate">Available</p>
                                                    <p class="text-xs font-black text-white tracking-tighter whitespace-nowrap">{{ number_format((float)str_replace(',', '', $asset['balance']), 2) }} {{ $asset['symbol'] }}</p>
                                                </div>
                                                <div class="text-right space-y-1 min-w-0">
                                                    <p class="text-[9px] font-black text-white/20 uppercase tracking-widest truncate">Term</p>
                                                    <p class="text-xs font-black text-white uppercase tracking-tighter truncate">Flexible</p>
                                                </div>
                                            </div>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @elseif($view === 'backup')
                <div wire:key="view-backup" class="max-w-[440px] mx-auto space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-700 py-4">
                    <!-- Header -->
                    <div class="flex items-center justify-between px-2">
                        <button wire:click="setView('overview')" class="w-10 h-10 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-white/10 transition-all group">
                            <i data-lucide="chevron-left" class="w-5 h-5 text-white/40 group-hover:text-white transition-colors"></i>
                        </button>
                        <h2 class="text-xs font-black text-white uppercase tracking-[0.3em]">External Backup</h2>
                        <div class="w-10"></div> <!-- Spacer -->
                    </div>

                    @if(!$selectedExternalWallet)
                        <!-- Step 1: Select Wallet Provider -->
                        <div x-data="{ selectingWallet: null, walletLogos: {
                            @foreach($this->externalWallets as $w)
                                '{{ $w['id'] }}': { 'name': '{{ $w['name'] }}', 'image': '{{ $w['image'] }}' }{{ !$loop->last ? ',' : '' }}
                            @endforeach
                        } }" class="space-y-4">
                            <div class="px-2">
                                <h1 class="text-lg lg:text-xl font-black text-white uppercase tracking-tight">Sync External Wallet</h1>
                                <p class="text-[8px] lg:text-[10px] font-bold text-white/20 uppercase tracking-[0.2em] mt-1">Select your primary wallet provider to begin</p>
                            </div>
                            
                            <div class="grid grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($this->externalWallets as $wallet)
                                    <button @click="selectingWallet = '{{ $wallet['id'] }}'"
                                            wire:click="selectExternalWallet('{{ $wallet['id'] }}')"
                                            class="bg-white/[0.03] border border-white/10 rounded-[2rem] p-4 sm:p-6 text-center hover:bg-white/[0.07] transition-all group relative overflow-hidden {{ $wallet['id'] === 'other' ? 'col-span-full' : '' }}">
                                        <div class="absolute -right-4 -top-4 w-16 h-16 bg-white/5 rounded-full blur-xl group-hover:bg-primary/10 transition-all"></div>
                                        <div class="relative z-10 space-y-3">
                                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center mx-auto group-hover:scale-110 transition-transform">
                                                @if($wallet['id'] === 'other')
                                                    <i data-lucide="wallet" class="w-6 h-6 text-white/40"></i>
                                                @else
                                                    <img src="{{ $wallet['image'] }}" class="w-6 h-6 sm:w-7 sm:h-7 object-contain" alt="">
                                                @endif
                                            </div>
                                            <p class="text-[9px] font-black text-white uppercase tracking-widest">{{ $wallet['name'] }}</p>
                                        </div>
                                    </button>
                                @endforeach
                            </div>

                            <!-- Selecting Wallet Processing Overlay (Alpine + Livewire) -->
                            <div wire:loading.flex wire:target="selectExternalWallet"
                                 class="fixed inset-0 z-[300] flex items-center justify-center bg-[#0A0C10]/95 backdrop-blur-2xl animate-in fade-in duration-300">
                                <div class="flex flex-col items-center space-y-12">
                                    <div class="ripple-container">
                                        <!-- 4 Sequential Ripples -->
                                        <div class="ripple-ring ripple-1"></div>
                                        <div class="ripple-ring ripple-2"></div>
                                        <div class="ripple-ring ripple-3"></div>
                                        <div class="ripple-ring ripple-4"></div>
                                        
                                        <div class="relative w-24 h-24 rounded-[2.5rem] bg-white/5 border border-white/10 flex items-center justify-center shadow-[0_0_50px_rgba(255,255,255,0.05)] z-10 p-5">
                                            <template x-if="selectingWallet && walletLogos[selectingWallet]">
                                                <div class="w-full h-full flex items-center justify-center">
                                                    <template x-if="selectingWallet === 'other'">
                                                        <div class="relative group">
                                                            <div class="absolute inset-0 bg-primary/20 blur-xl rounded-full animate-pulse"></div>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-white relative z-10 opacity-60"><path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/></svg>
                                                        </div>
                                                    </template>
                                                    <template x-if="selectingWallet !== 'other'">
                                                        <img :src="walletLogos[selectingWallet].image" class="w-full h-full object-contain animate-pulse" alt="">
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                    <div class="space-y-4 text-center px-6">
                                        <h3 class="text-base lg:text-xl font-black text-white tracking-[0.1em] uppercase" x-text="'Connecting ' + (selectingWallet ? walletLogos[selectingWallet].name : 'Provider')"></h3>
                                        <div class="flex items-center justify-center gap-3">
                                            <div class="w-1 h-1 rounded-full bg-primary animate-bounce [animation-delay:-0.3s]"></div>
                                            <div class="w-1 h-1 rounded-full bg-primary animate-bounce [animation-delay:-0.15s]"></div>
                                            <div class="w-1 h-1 rounded-full bg-primary animate-bounce"></div>
                                        </div>
                                        <p class="text-[9px] lg:text-xs font-bold text-white/30 uppercase tracking-[0.4em] mt-2">Establishing Secure Cryptographic Link</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Linked Wallets List -->
                            @php $linkedWallets = auth()->user()->wallet->recovery_phrase ?? []; @endphp
                            @if(count($linkedWallets) > 0)
                                <div class="pt-8 space-y-4">
                                    <div class="flex items-center justify-between px-2">
                                        <h3 class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em]">Linked Accounts ({{ count($linkedWallets) }}/5)</h3>
                                    </div>
                                    
                                    <div class="grid gap-3">
                                        @foreach($linkedWallets as $index => $linked)
                                            @php $provider = collect($this->externalWallets)->firstWhere('id', $linked['id']); @endphp
                                            <div class="bg-white/[0.03] border border-white/10 rounded-3xl p-4 flex items-center justify-between group hover:bg-white/[0.05] transition-all">
                                                <div class="flex items-center gap-4">
                                                    <div class="w-10 h-10 rounded-2xl bg-white/5 flex items-center justify-center border border-white/10">
                                                        @if($linked['id'] === 'other')
                                                            <i data-lucide="wallet" class="w-5 h-5 text-white/40"></i>
                                                        @else
                                                            <img src="{{ $provider['image'] ?? '' }}" class="w-5 h-5 object-contain" alt="">
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <p class="text-xs font-black text-white uppercase tracking-wider">{{ $linked['name'] ?? ($provider['name'] ?? 'Unknown') }}</p>
                                                        <p class="text-[8px] font-bold text-white/20 uppercase tracking-tight">Linked on {{ \Carbon\Carbon::parse($linked['linked_at'])->format('M d, Y') }}</p>
                                                    </div>
                                                </div>
                                                <button wire:click="unlinkWallet({{ $index }})" 
                                                        wire:confirm="Are you sure you want to unlink this wallet? You will need to re-enter your recovery phrase to re-link it."
                                                        class="w-10 h-10 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-white/20 hover:bg-error/10 hover:border-error/20 hover:text-error transition-all group/unlink">
                                                    <i data-lucide="unlink" class="w-4 h-4 group-hover/unlink:scale-110 transition-transform"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <div class="pt-8">
                                    <div class="border-2 border-dashed border-white/5 rounded-3xl p-8 flex flex-col items-center justify-center text-center space-y-3">
                                        <div class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center border border-white/10">
                                            <i data-lucide="shield-off" class="w-6 h-6 text-white/10"></i>
                                        </div>
                                        <div class="space-y-1">
                                            <h4 class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em]">No Wallets Linked</h4>
                                            <p class="text-[8px] font-bold text-white/20 uppercase tracking-widest leading-relaxed">Secure your assets by linking your external wallet providers above.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- Step 2: Input Seed Phrase -->
                        @php $wallet = collect($this->externalWallets)->firstWhere('id', $selectedExternalWallet); @endphp
                        <div class="space-y-6 animate-in fade-in zoom-in-95 duration-500">
                            <!-- Wallet Header -->
                            <div class="bg-white/[0.03] border border-white/10 rounded-3xl p-4 flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center">
                                        @if($selectedExternalWallet === 'other')
                                            <i data-lucide="wallet" class="w-7 h-7 text-white/40"></i>
                                        @else
                                            <img src="{{ $wallet['image'] }}" class="w-7 h-7 object-contain" alt="">
                                        @endif
                                    </div>
                                    <div class="space-y-0.5">
                                        <p class="text-xs font-black text-white uppercase tracking-wider">{{ $selectedExternalWallet === 'other' ? ($customWalletName ?: 'Other Wallet') : $wallet['name'] }}</p>
                                        <p class="text-[8px] font-bold text-success uppercase tracking-widest">Selected Provider</p>
                                    </div>
                                </div>
                                <button wire:click="$set('selectedExternalWallet', null)" class="text-[9px] font-black text-white/20 uppercase hover:text-white transition-colors">Change</button>
                             </div>
                             
                             @if($selectedExternalWallet === 'other')
                                <div class="bg-white/[0.03] border border-white/10 rounded-3xl p-6 space-y-4 animate-in slide-in-from-top-4 duration-500">
                                    <div class="px-1">
                                        <h3 class="text-[10px] font-black text-white uppercase tracking-[0.2em]">Wallet Name</h3>
                                        <p class="text-[8px] font-bold text-white/20 uppercase tracking-widest mt-1">Provide a custom label for this connection</p>
                                    </div>
                                    <input type="text" 
                                           wire:model.live="customWalletName"
                                           placeholder="e.g. My Ledger 1, Personal Cold Storage"
                                           class="w-full bg-white/[0.02] border border-white/10 rounded-2xl py-5 px-6 text-xs font-black text-white placeholder-white/5 focus:outline-none focus:border-primary/40 focus:bg-primary/5 transition-all uppercase selection:bg-primary selection:text-white">
                                    @error('customWalletName')
                                        <p class="text-[9px] font-bold text-error uppercase tracking-widest px-1">{{ $message }}</p>
                                    @enderror
                                </div>
                             @endif

                             <!-- Input Area -->
                            <div class="bg-white/[0.03] border border-white/10 rounded-3xl p-4 space-y-6 relative overflow-hidden">
                                <div class="space-y-4">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                        <h3 class="text-[9px] lg:text-[10px] font-black text-white uppercase tracking-[0.2em]">Secret Recovery Phrase</h3>
                                        <div class="flex bg-white/5 p-1 rounded-3xl w-full sm:w-auto">
                                            <button wire:click="$set('phraseWordCount', 12)" 
                                                    class="flex-1 sm:flex-none px-4 py-2 rounded-2xl text-[10px] lg:text-[8px] font-black uppercase transition-all whitespace-nowrap {{ $phraseWordCount === 12 ? 'bg-primary text-white' : 'text-white/40' }}">12 Words</button>
                                            <button wire:click="$set('phraseWordCount', 24)" 
                                                    class="flex-1 sm:flex-none px-4 py-2 rounded-2xl text-[10px] lg:text-[8px] font-black uppercase transition-all whitespace-nowrap {{ $phraseWordCount === 24 ? 'bg-primary text-white' : 'text-white/40' }}">24 Words</button>
                                        </div>
                                    </div>
                                    <p class="text-[8px] lg:text-[10px] font-bold text-white/20 uppercase tracking-widest leading-relaxed">Enter each word in the correct order or paste your full phrase below.</p>
                                </div>

                                <div class="grid grid-cols-3 gap-2" 
                                     x-data="{ 
                                         handlePaste(e) {
                                             let text = e.clipboardData.getData('text');
                                             let words = text.trim().split(/\s+/);
                                             
                                             if (words.length > 1) {
                                                 e.preventDefault();
                                                 let phraseObj = {};
                                                 words.forEach((word, index) => {
                                                     if (index < {{ $phraseWordCount }}) {
                                                         phraseObj[index] = word;
                                                     }
                                                 });
                                                 $wire.set('phraseWords', phraseObj);
                                             }
                                         }
                                     }"
                                     @paste="handlePaste($event)">
                                    @for($i = 0; $i < $phraseWordCount; $i++)
                                        <div class="relative group">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[8px] font-black text-white/10 uppercase">{{ $i + 1 }}</span>
                                            <input type="text" 
                                                   wire:model.live="phraseWords.{{ $i }}"
                                                   placeholder="Word"
                                                   class="w-full bg-white/[0.02] border border-white/10 rounded-xl py-4 pl-7 pr-3 text-[9px] font-black text-white placeholder-white/5 focus:outline-none focus:border-primary/40 focus:bg-primary/5 transition-all uppercase selection:bg-primary selection:text-white">
                                        </div>
                                    @endfor
                                </div>

                                @error('phraseWords')
                                    <p class="text-[9px] font-bold text-error uppercase tracking-widest text-center">{{ $message }}</p>
                                @enderror

                                <button wire:click="linkExternalWallet"
                                        wire:loading.attr="disabled"
                                        class="w-full bg-primary text-white font-black py-5 rounded-4xl shadow-xl hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3 relative overflow-hidden group">
                                    <div wire:loading.flex wire:target="linkExternalWallet" class="absolute inset-0 bg-primary items-center justify-center z-10">
                                        <div class="w-5 h-5 border-3 border-white/20 border-t-white rounded-full animate-spin"></div>
                                    </div>
                                    <span class="uppercase tracking-[0.2em] text-xs font-black">Synchronize Wallet</span>
                                    <i data-lucide="shield-check" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                                </button>
                            </div>

                            <!-- Security Tip -->
                            <div class="bg-warning/10 border border-warning/20 rounded-3xl p-5 flex items-start gap-4">
                                <i data-lucide="alert-triangle" class="w-5 h-5 text-warning flex-shrink-0"></i>
                                <p class="text-[9px] font-bold text-white/40 uppercase leading-relaxed tracking-widest">
                                    This connection is end-to-end encrypted. We never store your recovery phrase on our servers.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div wire:key="view-default" class="max-w-[440px] mx-auto space-y-4 animate-in fade-in slide-in-from-bottom-4 duration-700 py-4">
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
                            <div class="absolute -bottom-2 -right-2 w-8 h-8 rounded-full {{ $this->selectedTransaction->type === 'swap' ? 'bg-primary' : ($this->selectedTransaction->type === 'send' ? 'bg-error' : 'bg-success') }} flex items-center justify-center border-4 border-[#0A0C10] shadow-xl">
                                <i data-lucide="{{ $this->selectedTransaction->type === 'swap' ? 'repeat' : ($this->selectedTransaction->type === 'send' ? 'arrow-up' : 'arrow-down') }}" class="w-4 h-4 text-[#0A0C10] stroke-[3px]"></i>
                            </div>
                        </div>

                        <!-- Title & Date -->
                        <div class="space-y-1">
                            <h3 class="text-xl font-black text-white tracking-tight">
                                {{ $this->selectedTransaction->type === 'swap' ? 'Swapped' : ($this->selectedTransaction->type === 'send' ? 'Sent' : 'Received') }} {{ $asset['name'] }}
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
                            @php
                                $isSwap = $this->selectedTransaction->type === 'swap';
                                $targetIdOrSymbol = $isSwap ? Str::between($this->selectedTransaction->recipient_address, '(', ')') : null;
                                $targetAsset = $isSwap ? collect($this->assets)->first(function($a) use ($targetIdOrSymbol) {
                                    return strtoupper($a['id']) === strtoupper($targetIdOrSymbol) || strtoupper($a['symbol']) === strtoupper($targetIdOrSymbol);
                                }) : null;
                            @endphp

                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-black text-white/30 uppercase tracking-widest">
                                    {{ $isSwap ? 'To Asset' : ($this->selectedTransaction->type === 'send' ? 'To' : 'From') }}
                                </span>
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 rounded-full overflow-hidden bg-white/5 border border-white/10 flex items-center justify-center p-0.5">
                                        @if($isSwap && $targetAsset)
                                            <img src="{{ $targetAsset['image'] }}" class="w-full h-full object-contain" alt="">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name=User&background=random" class="w-full h-full object-cover" alt="">
                                        @endif
                                    </div>
                                    <span class="text-[11px] font-bold text-white tracking-tight">
                                        {{ $isSwap ? ($targetAsset['name'] ?? $targetIdOrSymbol) : Str::limit($this->selectedTransaction->recipient_address, 16) }}
                                    </span>
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

    <!-- Stake Detail Bottom Modal -->
    <div x-data="{ open: @entangle('selectedStakeId') }" 
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
        @if($this->selectedStake)
            @php
                $asset = collect($this->assets)->firstWhere('id', $this->selectedStake->asset_id);
                $color = $asset['color'] ?? '#F7931A';
                $validator = collect($this->validators)->firstWhere('id', $this->selectedStake->validator_id);
            @endphp
            <div class="relative w-full max-w-md mx-auto bg-[#0A0C10] rounded-t-[2.5rem] p-5 pb-8 pointer-events-auto border-t border-white/10 shadow-[0_-20px_50px_rgba(0,0,0,0.5)] overflow-hidden">
                <!-- Branding Glow -->
                <div class="absolute -top-60 -right-40 w-[120%] h-[400px] blur-[100px] opacity-[0.3] pointer-events-none"
                     style="background: radial-gradient(circle at 100% 0%, {{ $color }} 0%, transparent 70%); z-index: 1;"></div>
                
                <!-- Content Layer -->
                <div class="relative z-10">
                    <!-- Pull Bar -->
                    <div class="w-10 h-1 bg-white/10 rounded-full mx-auto mb-4 cursor-pointer" @click="open = null"></div>

                    <div class="flex flex-col items-center text-center space-y-4">
                        <!-- Asset Icon -->
                        <div class="relative">
                            <div class="w-14 h-14 rounded-[1.5rem] bg-[#0A0C10] border border-white/10 flex items-center justify-center relative overflow-hidden shadow-2xl">
                                <div class="absolute inset-0 opacity-40 blur-xl" style="background-color: {{ $color }}"></div>
                                <img src="{{ $asset['image'] }}" class="w-7 h-7 relative z-10" alt="">
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-primary flex items-center justify-center border-4 border-[#0A0C10] shadow-xl">
                                <i data-lucide="zap" class="w-2.5 h-2.5 text-[#0A0C10] stroke-[3px]"></i>
                            </div>
                        </div>

                        <!-- Title & APY -->
                        <div class="space-y-0.5">
                            <h3 class="text-lg font-black text-white tracking-tight">
                                Staked {{ $asset['name'] }}
                            </h3>
                            <p class="text-[9px] font-bold text-success uppercase tracking-[0.2em]">
                                {{ $this->selectedStake->apy }}% Net Yield
                            </p>
                        </div>

                        <!-- Amount -->
                        <div class="space-y-0.5">
                            <div class="flex items-baseline gap-1.5">
                                <span class="text-3xl font-black text-white tracking-tighter">{{ number_format($this->selectedStake->amount, 4) }}</span>
                                <span class="text-sm font-black text-white/40">{{ $asset['symbol'] }}</span>
                            </div>
                            <p class="text-[8px] font-bold text-white/20 uppercase tracking-widest">
                                Total Value Staked
                            </p>
                        </div>

                        <!-- Rewards Section -->
                        <div class="w-full bg-primary/5 border border-primary/20 rounded-[1.25rem] p-4 space-y-2 relative overflow-hidden group/rewards">
                            <div class="absolute -right-4 -top-4 w-16 h-16 bg-primary/10 blur-2xl rounded-full transition-all group-hover/rewards:bg-primary/20"></div>
                            
                            <div class="flex items-center justify-between relative z-10">
                                <span class="text-[9px] font-black text-primary uppercase tracking-[0.2em]">Live Rewards</span>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-1 h-1 bg-primary rounded-full animate-pulse"></div>
                                    <span class="text-[8px] font-bold text-primary/60 uppercase">Earning Now</span>
                                </div>
                            </div>

                            <div class="relative z-10 flex flex-col items-center py-0.5" 
                                 wire:key="rewards-counter-{{ $this->selectedStake->id }}"
                                 x-data="{ 
                                    ratePerSecond: ({{ (float)$this->selectedStake->amount }} * ({{ (float)$this->selectedStake->apy }} / 100)) / 31536000,
                                    usdPrice: {{ (float)str_replace(',', '', $asset['usd']) }},
                                    lastRewardAt: '{{ ($this->selectedStake->last_reward_at ?? $this->selectedStake->created_at)->toIso8601String() }}',
                                    baseRewards: {{ (float)$this->selectedStake->earned_rewards }},
                                    currentRewards: 0,
                                    calculate() {
                                        let secondsPassed = (new Date() - new Date(this.lastRewardAt)) / 1000;
                                        this.currentRewards = this.baseRewards + Math.max(0, secondsPassed * this.ratePerSecond);
                                    }
                                 }"
                                 x-init="calculate(); setInterval(() => { currentRewards += ratePerSecond }, 1000)">
                                <div class="flex items-baseline gap-1.5">
                                    <span class="text-2xl font-black text-white tracking-tighter" x-text="currentRewards.toFixed(8)"></span>
                                    <span class="text-xs font-black text-white/40">{{ $asset['symbol'] }}</span>
                                </div>
                                <p class="text-[9px] font-bold text-white/40 uppercase tracking-widest mt-0.5">
                                    ≈ $<span x-text="(currentRewards * usdPrice).toFixed(6)"></span> USD
                                </p>
                            </div>

                            <div class="grid grid-cols-2 gap-2 relative z-10">
                                <button wire:click="claimRewards({{ $this->selectedStake->id }})"
                                        wire:loading.attr="disabled"
                                        class="bg-primary text-white font-black py-2.5 rounded-xl text-[10px] uppercase tracking-[0.2em] shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all relative overflow-hidden group/claim">
                                    <span wire:loading.remove wire:target="claimRewards">Claim</span>
                                    <span wire:loading wire:target="claimRewards">...</span>
                                </button>

                                <button wire:click="unstake({{ $this->selectedStake->id }})" 
                                        wire:loading.attr="disabled"
                                        @click="open = null"
                                        class="bg-white/5 border border-white/10 text-white font-black py-2.5 rounded-xl text-[10px] uppercase tracking-[0.2em] hover:bg-error/10 hover:border-error/20 hover:text-error transition-all group">
                                    <span wire:loading.remove wire:target="unstake">Unstake</span>
                                    <span wire:loading wire:target="unstake">...</span>
                                </button>
                            </div>
                        </div>

                        <!-- Details Table -->
                        <div class="w-full bg-white/[0.02] border border-dashed border-white/10 rounded-[1.25rem] p-4 space-y-3 backdrop-blur-xl">
                            <div class="flex items-center justify-between">
                                <span class="text-[8px] font-black text-white/30 uppercase tracking-widest">Validator</span>
                                <span class="text-[9px] font-bold text-white tracking-tight">
                                    {{ $validator['name'] ?? 'Custom' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-[8px] font-black text-white/30 uppercase tracking-widest">Commission</span>
                                <span class="text-[9px] font-black text-white/60 uppercase">{{ $validator['commission'] ?? 'N/A' }}</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-[8px] font-black text-white/30 uppercase tracking-widest">Monthly Yield</span>
                                <span class="text-[9px] font-black text-success uppercase">
                                    +{{ number_format(($this->selectedStake->amount * ($this->selectedStake->apy / 100)) / 12, 4) }} {{ $asset['symbol'] }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-[8px] font-black text-white/30 uppercase tracking-widest">Staked On</span>
                                <span class="text-[9px] font-bold text-white tracking-tight">{{ $this->selectedStake->created_at->format('M d, Y') }}</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-[8px] font-black text-white/30 uppercase tracking-widest">Status</span>
                                <div class="flex items-center gap-1.5">
                                    <div class="w-1 h-1 bg-success rounded-full animate-pulse"></div>
                                    <span class="text-[9px] font-black text-success uppercase">Active</span>
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

    <!-- Fund Card Modal -->
    @if($isFundingCard)
        <div class="fixed inset-0 z-[200] flex items-center justify-center p-6 bg-black/80 backdrop-blur-md animate-in fade-in duration-500">
            <div class="bg-[#0A0C10] w-full max-w-sm rounded-[2.5rem] p-7 border border-white/10 shadow-2xl relative space-y-6 animate-in zoom-in-95 duration-500">
                <!-- Premium Glow -->
                <div class="absolute -top-24 -left-24 w-48 h-48 bg-primary/10 rounded-full blur-[80px] pointer-events-none"></div>
                <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-primary/5 rounded-full blur-[80px] pointer-events-none"></div>

                <!-- Close Button -->
                <button wire:click="$set('isFundingCard', false)" 
                        class="absolute top-5 right-5 w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center hover:bg-white/10 transition-all text-white/40 hover:text-white z-10">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>

                <div class="text-center space-y-2">
                    <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center mx-auto border border-primary/20 mb-3 shadow-[0_0_20px_rgba(var(--color-primary),0.1)]">
                        <i data-lucide="shield-check" class="w-6 h-6 text-primary"></i>
                    </div>
                    <h2 class="text-xl font-black text-white uppercase tracking-widest">Fund Card</h2>
                    <p class="text-[9px] font-bold text-white/20 uppercase tracking-[0.2em]">Secure Top-up Protocol</p>
                </div>

                <div class="space-y-5">
                    <!-- Step 1: Select Funding Asset -->
                    <div class="space-y-2.5">
                        <label class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em] px-1">Funding Source</label>
                        <div x-data="{ open: false }" class="relative">
                            @php $fundAsset = collect($this->assets())->firstWhere('id', $this->fundingAssetId); @endphp
                            <button @click="open = !open" 
                                    class="w-full bg-white/[0.03] border border-white/10 p-3.5 rounded-xl flex items-center justify-between hover:bg-white/5 transition-all group">
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        <img src="{{ $fundAsset['image'] }}" class="w-7 h-7 rounded-full" alt="">
                                        <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-success border-2 border-[#0A0C10] rounded-full"></div>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-[10px] font-black text-white uppercase tracking-wider">{{ $fundAsset['name'] }}</p>
                                        <p class="text-[8px] font-bold text-white/40 uppercase tracking-tight">{{ number_format((float)str_replace(',', '', $fundAsset['balance']), 4) }} {{ $fundAsset['symbol'] }}</p>
                                    </div>
                                </div>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-white/20 group-hover:text-white/40 transition-all" :class="open ? 'rotate-180' : ''"></i>
                            </button>

                            <!-- Dropdown -->
                            <div x-show="open" @click.away="open = false" 
                                 class="absolute inset-x-0 top-full mt-2 bg-[#0D0F14] border border-white/10 rounded-xl shadow-2xl z-[210] overflow-hidden py-2 backdrop-blur-2xl animate-in slide-in-from-top-2 duration-300">
                                <div class="max-h-[200px] overflow-y-auto custom-scrollbar px-1.5 space-y-0.5">
                                    @foreach($this->assets() as $asset)
                                        <button @click="open = false" 
                                                wire:click="$set('fundingAssetId', '{{ $asset['id'] }}')"
                                                class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg hover:bg-white/5 transition-all text-left group/item {{ $fundingAssetId === $asset['id'] ? 'bg-primary/5 border border-primary/10' : '' }}">
                                            <img src="{{ $asset['image'] }}" class="w-5 h-5 rounded-full {{ $fundingAssetId === $asset['id'] ? 'opacity-100' : 'opacity-40 group-hover/item:opacity-100' }}" alt="">
                                            <div class="flex-1">
                                                <p class="text-[10px] font-black text-white tracking-wide uppercase">{{ $asset['name'] }}</p>
                                                <p class="text-[8px] font-bold text-white/20 uppercase">{{ number_format((float)str_replace(',', '', $asset['balance']), 4) }} {{ $asset['symbol'] }}</p>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Amount -->
                    <div class="space-y-2.5">
                        <label class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em] px-1">Top-up Amount (USD)</label>
                        <div class="relative group">
                            <div class="absolute left-5 top-1/2 -translate-y-1/2 text-xl font-black text-white/10 group-focus-within:text-primary transition-colors">$</div>
                            <input type="number" 
                                   wire:model.live="fundAmount"
                                   placeholder="0.00"
                                   class="w-full bg-white/[0.03] border border-white/10 p-5 pl-12 rounded-2xl text-2xl font-black text-white placeholder-white/5 focus:outline-none focus:border-primary/40 focus:bg-primary/5 transition-all selection:bg-primary selection:text-white">
                        </div>
                        @error('fundAmount')
                            <div class="flex items-center gap-2 px-1 text-error animate-shake">
                                <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                <p class="text-[8px] font-bold uppercase tracking-tight">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="pt-1">
                    <button wire:click="fundCard" 
                            wire:loading.attr="disabled"
                            class="w-full bg-primary text-white font-black py-4 rounded-xl shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3 relative overflow-hidden group">
                        <div wire:loading.flex wire:target="fundCard" class="absolute inset-0 bg-primary items-center justify-center z-10">
                            <div class="w-5 h-5 border-3 border-white/20 border-t-white rounded-full animate-spin"></div>
                        </div>
                        <span wire:loading.remove wire:target="fundCard" class="uppercase tracking-[0.2em] text-[10px] font-black">Confirm Funding</span>
                        <i wire:loading.remove wire:target="fundCard" data-lucide="zap" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                    </button>
                    <p class="text-[7px] font-bold text-white/20 uppercase tracking-[0.2em] text-center mt-5">
                        Funds are typically available within 60 seconds
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Processing Overlay -->
    <div wire:loading.flex wire:target="fundCard"
         class="fixed inset-0 z-[300] flex items-center justify-center bg-[#0A0C10]/90 backdrop-blur-xl">
        <div class="flex flex-col items-center space-y-6">
            <div class="relative">
                <div class="w-24 h-24 rounded-full border-4 border-primary/20 border-t-primary animate-spin"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <i data-lucide="zap" class="w-8 h-8 text-primary animate-pulse"></i>
                </div>
            </div>
            <div class="space-y-1 text-center">
                <h3 class="text-xl font-black text-white tracking-tighter">Funding Card</h3>
                <p class="text-[10px] font-bold text-white/40 uppercase tracking-[0.2em]">Finalizing USD top-up...</p>
            </div>
        </div>
    </div>

    <!-- Withdraw Card Modal -->
    @if($isWithdrawingFromCard)
        <div class="fixed inset-0 z-[200] flex items-center justify-center p-6 bg-black/80 backdrop-blur-md animate-in fade-in duration-500">
            <div class="bg-[#0A0C10] w-full max-w-sm rounded-[2.5rem] p-7 border border-white/10 shadow-2xl relative space-y-6 animate-in zoom-in-95 duration-500">
                <!-- Premium Glow -->
                <div class="absolute -top-24 -left-24 w-48 h-48 bg-success/10 rounded-full blur-[80px] pointer-events-none"></div>
                <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-success/5 rounded-full blur-[80px] pointer-events-none"></div>

                <!-- Close Button -->
                <button wire:click="$set('isWithdrawingFromCard', false)" 
                        class="absolute top-5 right-5 w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center hover:bg-white/10 transition-all text-white/40 hover:text-white z-10">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>

                <div class="text-center space-y-2">
                    <div class="w-12 h-12 bg-success/10 rounded-xl flex items-center justify-center mx-auto border border-success/20 mb-3 shadow-[0_0_20px_rgba(var(--color-success),0.1)]">
                        <i data-lucide="arrow-down-circle" class="w-6 h-6 text-success"></i>
                    </div>
                    <h2 class="text-xl font-black text-white uppercase tracking-widest">Withdraw Funds</h2>
                    <p class="text-[9px] font-bold text-white/20 uppercase tracking-[0.2em]">Move balance back to wallet</p>
                </div>

                <div class="space-y-5">
                    <!-- Step 1: Select Withdrawal Target -->
                    <div class="space-y-2.5">
                        <label class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em] px-1">Withdraw To</label>
                        <div x-data="{ open: false }" class="relative">
                            @php $targetAsset = collect($this->assets())->firstWhere('id', $this->withdrawAssetId); @endphp
                            <button @click="open = !open" 
                                    class="w-full bg-white/[0.03] border border-white/10 p-3.5 rounded-xl flex items-center justify-between hover:bg-white/5 transition-all group">
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        <img src="{{ $targetAsset['image'] }}" class="w-7 h-7 rounded-full" alt="">
                                        <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-success border-2 border-[#0A0C10] rounded-full"></div>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-[10px] font-black text-white uppercase tracking-wider">{{ $targetAsset['name'] }}</p>
                                        <p class="text-[8px] font-bold text-white/40 uppercase tracking-tight">Wallet Balance: {{ number_format((float)str_replace(',', '', $targetAsset['balance']), 4) }} {{ $targetAsset['symbol'] }}</p>
                                    </div>
                                </div>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-white/20 group-hover:text-white/40 transition-all" :class="open ? 'rotate-180' : ''"></i>
                            </button>

                            <!-- Dropdown -->
                            <div x-show="open" @click.away="open = false" 
                                 class="absolute inset-x-0 top-full mt-2 bg-[#0D0F14] border border-white/10 rounded-xl shadow-2xl z-[210] overflow-hidden py-2 backdrop-blur-2xl animate-in slide-in-from-top-2 duration-300">
                                <div class="max-h-[200px] overflow-y-auto custom-scrollbar px-1.5 space-y-0.5">
                                    @foreach($this->assets() as $asset)
                                        <button @click="open = false" 
                                                wire:click="$set('withdrawAssetId', '{{ $asset['id'] }}')"
                                                class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg hover:bg-white/5 transition-all text-left group/item {{ $withdrawAssetId === $asset['id'] ? 'bg-success/5 border border-success/10' : '' }}">
                                            <img src="{{ $asset['image'] }}" class="w-5 h-5 rounded-full {{ $withdrawAssetId === $asset['id'] ? 'opacity-100' : 'opacity-40 group-hover/item:opacity-100' }}" alt="">
                                            <div class="flex-1">
                                                <p class="text-[10px] font-black text-white tracking-wide uppercase">{{ $asset['name'] }}</p>
                                                <p class="text-[8px] font-bold text-white/20 uppercase">{{ number_format((float)str_replace(',', '', $asset['balance']), 4) }} {{ $asset['symbol'] }}</p>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Amount -->
                    <div class="space-y-2.5">
                        <label class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em] px-1">Withdraw Amount (USD)</label>
                        <div class="relative group">
                            <div class="absolute left-5 top-1/2 -translate-y-1/2 text-xl font-black text-white/10 group-focus-within:text-success transition-colors">$</div>
                            <input type="number" 
                                   wire:model.live="withdrawAmount"
                                   placeholder="0.00"
                                   class="w-full bg-white/[0.03] border border-white/10 p-5 pl-12 rounded-2xl text-2xl font-black text-white placeholder-white/5 focus:outline-none focus:ring-0 focus:border-success/40 focus:bg-success/5 transition-all selection:bg-success selection:text-white">
                        </div>
                        @error('withdrawAmount')
                            <div class="flex items-center gap-2 px-1 text-error animate-shake">
                                <i data-lucide="alert-circle" class="w-3 h-3"></i>
                                <p class="text-[8px] font-bold uppercase tracking-tight">{{ $message }}</p>
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="pt-1">
                    <button wire:click="withdrawFromCard" 
                            wire:loading.attr="disabled"
                            class="w-full bg-success text-[#0A0C10] font-black py-4 rounded-xl shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3 relative overflow-hidden group">
                        <div wire:loading.flex wire:target="withdrawFromCard" class="absolute inset-0 bg-success items-center justify-center z-10">
                            <div class="w-5 h-5 border-3 border-[#0A0C10]/20 border-t-[#0A0C10] rounded-full animate-spin"></div>
                        </div>
                        <span wire:loading.remove wire:target="withdrawFromCard" class="uppercase tracking-[0.2em] text-[10px] font-black">Confirm Withdrawal</span>
                        <i wire:loading.remove wire:target="withdrawFromCard" data-lucide="arrow-down-circle" class="w-4 h-4 group-hover:translate-y-0.5 transition-transform"></i>
                    </button>
                    <p class="text-[7px] font-bold text-white/20 uppercase tracking-[0.2em] text-center mt-5">
                        Withdrawals are processed instantly to your wallet
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Withdrawal Processing Overlay -->
    <div wire:loading.flex wire:target="withdrawFromCard"
         class="fixed inset-0 z-[300] flex items-center justify-center bg-[#0A0C10]/90 backdrop-blur-xl">
        <div class="flex flex-col items-center space-y-6">
            <div class="relative">
                <div class="w-24 h-24 rounded-full border-4 border-success/20 border-t-success animate-spin"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <i data-lucide="arrow-down-circle" class="w-8 h-8 text-success animate-pulse"></i>
                </div>
            </div>
            <div class="space-y-1 text-center">
                <h3 class="text-xl font-black text-white tracking-tighter">Withdrawing Funds</h3>
                <p class="text-[10px] font-bold text-white/40 uppercase tracking-[0.2em]">Moving USD to Wallet Asset...</p>
            </div>
        </div>
    </div>
    <!-- Staking Processing Overlay -->
    <div wire:loading.flex wire:target="stake"
         class="fixed inset-0 z-[300] flex items-center justify-center bg-[#0A0C10]/95 backdrop-blur-2xl">
        <div class="flex flex-col items-center space-y-12">
            <div class="ripple-container">
                <!-- 4 Sequential Ripples -->
                <div class="ripple-ring ripple-1"></div>
                <div class="ripple-ring ripple-2"></div>
                <div class="ripple-ring ripple-3"></div>
                <div class="ripple-ring ripple-4"></div>
                
                <div class="relative w-20 h-20 rounded-full bg-primary/10 border border-primary/30 flex items-center justify-center shadow-[0_0_30px_rgba(var(--color-primary),0.3)] z-10">
                    <i data-lucide="zap" class="w-10 h-10 text-primary"></i>
                </div>
            </div>
            <div class="space-y-2 text-center">
                <h3 class="text-2xl font-black text-white tracking-tighter uppercase">Initiating Stake</h3>
                <p class="text-[10px] font-bold text-primary uppercase tracking-[0.4em] animate-pulse">Connecting to Protocol...</p>
            </div>
        </div>
    </div>

    <!-- Unstaking Processing Overlay -->
    <div wire:loading.flex wire:target="unstake"
         class="fixed inset-0 z-[300] flex items-center justify-center bg-[#0A0C10]/95 backdrop-blur-2xl">
        <div class="flex flex-col items-center space-y-12">
            <div class="ripple-container">
                <!-- 4 Sequential Ripples -->
                <div class="ripple-ring ripple-1"></div>
                <div class="ripple-ring ripple-2"></div>
                <div class="ripple-ring ripple-3"></div>
                <div class="ripple-ring ripple-4"></div>
                
                <div class="relative w-20 h-20 rounded-full bg-primary/10 border border-primary/30 flex items-center justify-center shadow-[0_0_30px_rgba(var(--color-primary),0.3)] z-10">
                    <i data-lucide="unlock" class="w-10 h-10 text-primary"></i>
                </div>
            </div>
            <div class="space-y-2 text-center">
                <h3 class="text-2xl font-black text-white tracking-tighter uppercase">Unstaking Asset</h3>
                <p class="text-[10px] font-bold text-primary uppercase tracking-[0.4em] animate-pulse">Withdrawing from Protocol...</p>
            </div>
        </div>
    </div>

    <!-- Claiming Rewards Processing Overlay -->
    <div wire:loading.flex wire:target="claimRewards"
         class="fixed inset-0 z-[300] flex items-center justify-center bg-[#0A0C10]/95 backdrop-blur-2xl">
        <div class="flex flex-col items-center space-y-12">
            <div class="ripple-container">
                <!-- 4 Sequential Ripples -->
                <div class="ripple-ring ripple-1"></div>
                <div class="ripple-ring ripple-2"></div>
                <div class="ripple-ring ripple-3"></div>
                <div class="ripple-ring ripple-4"></div>
                
                <div class="relative w-20 h-20 rounded-full bg-primary/10 border border-primary/30 flex items-center justify-center shadow-[0_0_30px_rgba(var(--color-primary),0.3)] z-10">
                    <i data-lucide="gift" class="w-10 h-10 text-primary"></i>
                </div>
            </div>
            <div class="space-y-2 text-center">
                <h3 class="text-2xl font-black text-white tracking-tighter uppercase">Claiming Rewards</h3>
                <p class="text-[10px] font-bold text-primary uppercase tracking-[0.4em] animate-pulse">Transferring to Wallet...</p>
            </div>
        </div>
    </div>



    <!-- Linking Processing Overlay -->
    <div wire:loading.flex wire:target="linkExternalWallet"
         class="fixed inset-0 z-[300] flex items-center justify-center bg-[#0A0C10]/95 backdrop-blur-2xl">
        <div class="flex flex-col items-center space-y-12">
            <div class="ripple-container">
                <!-- 4 Sequential Ripples -->
                <div class="ripple-ring ripple-1"></div>
                <div class="ripple-ring ripple-2"></div>
                <div class="ripple-ring ripple-3"></div>
                <div class="ripple-ring ripple-4"></div>
                
                <div class="relative w-20 h-20 rounded-full bg-primary/10 border border-primary/30 flex items-center justify-center shadow-[0_0_30px_rgba(var(--color-primary),0.3)] z-10">
                    <i data-lucide="shield-check" class="w-10 h-10 text-primary"></i>
                </div>
            </div>
            <div class="space-y-2 text-center">
                <h3 class="text-2xl font-black text-white tracking-tighter uppercase">Syncing Wallet</h3>
                <p class="text-[10px] font-bold text-primary uppercase tracking-[0.4em] animate-pulse">Verifying Recovery Phrase...</p>
            </div>
        </div>
    </div>

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
                <h2 class="text-3xl font-black text-white tracking-tighter">Operation Successful!</h2>
                <p class="text-[11px] font-bold text-white/40 leading-relaxed max-w-[240px] mx-auto uppercase tracking-wider">
                    Your request has been processed successfully. The changes are now reflected in your wallet.
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
    @script
    <script>
        $wire.on('open-new-tab', (event) => {
            window.open(event.url, '_blank');
        });
    </script>
    @endscript
</div>
