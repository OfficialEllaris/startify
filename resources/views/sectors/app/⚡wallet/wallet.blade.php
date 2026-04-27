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
    </style>
@endpush

<div class="flex flex-col min-h-screen bg-transparent relative">
    <!-- Main Scrollable Area -->
    <main class="flex-1 pb-40 overflow-y-auto no-scrollbar">
        <div class="max-w-md mx-auto p-6 lg:p-4 space-y-8">

            @if($view === 'overview')
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
                            <span class="text-4xl font-black tracking-tighter text-white leading-none">$87,521.42</span>
                        </div>
                        <div
                            class="relative z-10 inline-flex items-center gap-1.5 bg-success/20 text-success px-3 py-1 rounded-full border border-success/40 backdrop-blur-md">
                            <i data-lucide="trending-up" class="w-3 h-3"></i>
                            <span class="text-[10px] font-black">+4.25% Today</span>
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

                <!-- Asset List with Stacking & Notches -->
                <div class="space-y-12">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xs font-black text-white/40 uppercase tracking-[0.2em]">Your Portfolio</h2>
                        <button class="text-[10px] font-bold text-primary hover:underline tracking-widest uppercase">View
                            All</button>
                    </div>

                    <div class="space-y-3">
                        @foreach($this->assets as $index => $asset)
                            <div class="relative group cursor-pointer">
                                <!-- Premium Glass Container -->
                                <div class="absolute inset-0 bg-white/[0.03] border border-white/10 rounded-[1.5rem] backdrop-blur-xl group-hover:bg-white/[0.06] group-hover:border-white/20 transition-all duration-500 ring-1 ring-white/5 shadow-xl"
                                    style="box-shadow: 0 10px 30px -10px {{ $asset['color'] }}15"></div>

                                <div class="relative z-10 p-4 flex items-center justify-between h-full">
                                    <!-- Dynamic Brand Glow -->
                                    <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full blur-[40px] opacity-10 group-hover:opacity-15 transition-opacity duration-700"
                                        style="background-color: {{ $asset['color'] }}"></div>

                                    <div class="flex items-center gap-3">
                                        <!-- Real Asset Icon with Glowing Squircle -->
                                        <div
                                            class="w-11 h-11 rounded-xl flex items-center justify-center shadow-xl relative overflow-hidden group-hover:scale-105 transition-transform duration-500 bg-[#0A0C10] border border-white/10">
                                            <!-- Inner brand glow -->
                                            <div class="absolute inset-0 opacity-40 blur-md transition-opacity duration-500 group-hover:opacity-60"
                                                style="background-color: {{ $asset['color'] }}"></div>
                                            <div
                                                class="absolute inset-0 bg-gradient-to-br from-white/30 to-transparent opacity-50">
                                            </div>
                                            @if(!empty($asset['image']))
                                                <img src="{{ $asset['image'] }}"
                                                    class="w-6 h-6 relative z-10 drop-shadow-[0_0_6px_{{ $asset['color'] }}60]"
                                                    alt="{{ $asset['name'] }}">
                                            @else
                                                <span
                                                    class="relative z-10 text-sm font-black text-white">{{ substr($asset['symbol'], 0, 1) }}</span>
                                            @endif
                                        </div>

                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-black text-white tracking-tight leading-tight">{{ $asset['name'] }}</span>
                                            <span
                                                class="text-[9px] font-bold text-white/30 uppercase tracking-[0.15em] mt-0.5">{{ $asset['balance'] }}
                                                {{ $asset['symbol'] }}</span>
                                        </div>
                                    </div>

                                    <div class="flex flex-col items-end gap-1">
                                        <!-- Percentage Badge with Trend Icon -->
                                        <div
                                            class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-white/5 border border-white/10 backdrop-blur-md">
                                            @if(str_contains($asset['change'], '+'))
                                                <i data-lucide="trending-up" class="w-2.5 h-2.5 text-success"></i>
                                            @elseif(str_contains($asset['change'], '-'))
                                                <i data-lucide="trending-down" class="w-2.5 h-2.5 text-error"></i>
                                            @endif
                                            <span
                                                class="text-[8px] font-black tracking-wider {{ str_contains($asset['change'], '+') ? 'text-success' : (str_contains($asset['change'], '-') ? 'text-error' : 'text-white/30') }}">
                                                {{ str_replace(['+', '-'], '', $asset['change']) }}
                                            </span>
                                        </div>

                                        <div class="flex flex-col items-end">
                                            <span
                                                class="text-base font-black text-white tracking-tighter leading-none">${{ $asset['usd'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
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
            @elseif($view === 'send')
                <!-- Send View -->
                <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
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
                                <input type="text" placeholder="0.00"
                                    class="bg-transparent border-none text-5xl font-black text-white p-0 w-48 text-center focus:ring-0 placeholder:text-white/5 tracking-tighter transition-all focus:scale-105">
                            </div>
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
                            <input type="text" placeholder="Wallet address or ENS"
                                class="w-full bg-white/5 border border-white/10 rounded-2xl p-4 text-sm text-white placeholder:text-white/10 focus:ring-1 focus:ring-white/20 transition-all">
                            <button
                                class="absolute right-3 top-1/2 -translate-y-1/2 px-3 py-1.5 rounded-xl bg-white/5 text-[10px] font-black text-white/40 hover:text-white transition-colors border border-white/10 uppercase tracking-wider">Paste</button>
                        </div>
                    </div>

                    <!-- Review Button -->
                    <button
                        class="w-full bg-white text-black font-black py-4 rounded-2xl shadow-[0_0_30px_rgba(255,255,255,0.2)] hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                        <span>Review Transfer</span>
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </button>
                </div>

            @else
                <!-- Detail View Header -->
                <div class="flex items-center justify-between mb-8">
                    <button wire:click="setView('overview')" class="btn btn-ghost btn-circle bg-white/5 border-white/10">
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
</div>