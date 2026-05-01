@push('styles')
    <style>
        /* Global Pointer Cursor */
        button,
        [role="button"],
        .cursor-pointer,
        [wire\:click],
        [x-on\:click],
        [\@click] {
            cursor: pointer !important;
        }

        /* Active View Transition */
        .view-transition {
            animation: view-fade-in 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes view-fade-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Custom Scrollbar */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .tab-active::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            right: 0;
            height: 2px;
            background: #F5C542;
            border-radius: 2px;
        }

        .icon-svg {
            width: 24px;
            height: 24px;
        }

        .nav-icon-svg {
            width: 22px;
            height: 22px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('open-new-tab', (event) => {
                const url = event.url || (event[0] && event[0].url) || event;
                if (url) window.open(url, '_blank');
            });
        });
    </script>
@endpush

<div class="flex flex-col min-h-screen bg-[#0A0C10] text-white font-sans selection:bg-[#F5C542] selection:text-black">
    <!-- Main Scrollable Area -->
    <main class="flex-1 pb-24 overflow-y-auto no-scrollbar">
        <div class="max-w-md mx-auto space-y-8" x-data
            x-init="$watch('$wire.view', () => { window.scrollTo({ top: 0, behavior: 'smooth' }); })">

            @php
                $viewTitles = [
                    'overview' => 'Assets',
                    'send' => 'Send Crypto',
                    'receive' => 'Receive Crypto',
                    'buy' => 'Buy Crypto',
                    'history' => 'Transaction History',
                    'swap' => 'Swap Crypto',
                    'card' => 'Virtual Cards',
                    'stake' => 'Stake Crypto',
                    'backup' => 'LINK WALLET'
                ];
            @endphp
            <!-- Top Header -->
            <div
                class="flex items-center justify-between py-4 px-4 border-b border-[#1A2635] sticky top-0 bg-[#0A0C10]/95 backdrop-blur-xl z-50">
                @if($view !== 'overview')
                    <button wire:click="setView('overview')"
                        class="w-10 h-10 flex items-center justify-start text-white/60 hover:text-white transition-colors">
                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 12H5M12 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                @else
                    <a href="{{ route('app.dashboard') }}" wire:navigate
                        class="w-10 h-10 flex items-center justify-start text-white/60 hover:text-white transition-colors">
                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 12H5M12 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                @endif
                <h1 class="text-[22px] font-bold tracking-tight">
                    {{ $view === 'card' ? 'Cards' : ($viewTitles[$view] ?? 'Assets') }}
                </h1>
                @if($view === 'card')
                    <button wire:click="$set('isApplyingForCard', true)"
                        class="w-10 h-10 bg-white/5 hover:bg-white/10 transition-colors rounded-[12px] flex items-center justify-center">
                        <svg class="w-5 h-5 text-white/60" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M12 5v14m-7-7h14"></path>
                        </svg>
                    </button>
                @else
                    <div class="w-10"></div>
                @endif
            </div>

            @if($view === 'overview')
                <div wire:key="view-overview" class="space-y-4 view-transition">
                    <!-- Welcome Card -->
                    <div
                        class="bg-[#0F141B] rounded-[16px] p-[16px] mx-[16px] mb-[29px] flex gap-4 border border-[#1A2635]">
                        <div class="w-8 h-8 flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-[#F5C542]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </div>
                        <div class="space-y-1">
                            <h2 class="text-[16px] font-bold tracking-tight">Welcome back, {{ auth()->user()->name }}!
                            </h2>
                            <p class="text-[14px] text-[#bababa] leading-tight">
                                Track your digital assets, and explore more opportunities
                            </p>
                        </div>
                    </div>

                    <!-- Balance Section -->
                    <div class="flex items-center gap-4 px-4">
                        <span class="text-[36px] font-bold">${{ number_format($this->totalPortfolioValue, 2) }}</span>
                        @php $portfolioChange = $this->totalPortfolioChange; @endphp
                        <div
                            class="flex items-center gap-1 {{ $portfolioChange >= 0 ? 'bg-[rgba(34,197,94,0.15)] text-[#22C55E]' : 'bg-[rgba(239,68,68,0.15)] text-[#EF4444]' }} px-[10px] py-[4px] rounded-[8px]">
                            <svg class="w-4 h-4 {{ $portfolioChange < 0 ? 'rotate-180' : '' }}" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="3">
                                <path d="M18 15l-6-6-6 6"></path>
                            </svg>
                            <span
                                class="text-[16px] font-bold">{{ $portfolioChange >= 0 ? '+' : '' }}{{ number_format($portfolioChange, 2) }}%</span>
                        </div>
                    </div>

                    <!-- Quick Actions Grid (Gap added, Casing updated) -->
                    <div class="px-4 space-y-2">
                        <!-- Row 1: 4 Columns -->
                        <div class="grid grid-cols-4 gap-2">
                            <!-- Send -->
                            <button wire:click="setView('send')"
                                class="w-full aspect-square bg-[#0F141B] border border-[#1A2635] rounded-[16px] flex flex-col items-center justify-center gap-2 hover:bg-[#1C242D] transition-all shadow-lg">
                                <svg class="icon-svg text-[#F5C542]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M12 19V5M19 12l-7-7-7 7"></path>
                                </svg>
                                <span class="text-[12px] font-semibold text-white tracking-tight">Send</span>
                            </button>
                            <!-- Receive -->
                            <button wire:click="setView('receive')"
                                class="w-full aspect-square bg-[#0F141B] border border-[#1A2635] rounded-[16px] flex flex-col items-center justify-center gap-2 hover:bg-[#1C242D] transition-all shadow-lg">
                                <svg class="icon-svg text-[#F5C542]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M12 5v14M5 12l7 7 7-7"></path>
                                </svg>
                                <span class="text-[12px] font-semibold text-white tracking-tight">Receive</span>
                            </button>
                            <!-- Buy -->
                            <button wire:click="setView('buy')"
                                class="w-full aspect-square bg-[#0F141B] border border-[#1A2635] rounded-[16px] flex flex-col items-center justify-center gap-2 hover:bg-[#1C242D] transition-all shadow-lg">
                                <svg class="icon-svg text-[#F5C542]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M7 7h10v10H7z"></path>
                                    <path d="M9 9h6v6H9z"></path>
                                </svg>
                                <span class="text-[12px] font-semibold text-white tracking-tight">Buy</span>
                            </button>
                            <!-- Cards -->
                            <button wire:click="setView('card')"
                                class="w-full aspect-square bg-[#0F141B] border border-[#1A2635] rounded-[16px] flex flex-col items-center justify-center gap-2 hover:bg-[#1C242D] transition-all shadow-lg">
                                <svg class="icon-svg text-[#F5C542]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <rect x="1" y="4" width="22" height="16" rx="2"></rect>
                                    <line x1="1" y1="10" x2="23" y2="10"></line>
                                </svg>
                                <span class="text-[12px] font-semibold text-white tracking-tight">Cards</span>
                            </button>
                        </div>

                        <!-- Row 2: 3 Columns (Full Width) -->
                        <div class="grid grid-cols-3 gap-2">
                            <!-- Swap -->
                            <button wire:click="setView('swap')"
                                class="w-full h-[80px] bg-[#0F141B] border border-[#1A2635] rounded-[16px] flex flex-col items-center justify-center gap-2 hover:bg-[#1C242D] transition-all shadow-lg">
                                <svg class="icon-svg text-[#F5C542]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M7 16L17 6M17 6H9M17 6V14"></path>
                                    <path d="M17 8L7 18M7 18H15M7 18V10"></path>
                                </svg>
                                <span class="text-[12px] font-semibold text-white tracking-tight">Swap</span>
                            </button>
                            <!-- Stake -->
                            <button wire:click="setView('stake')"
                                class="w-full h-[80px] bg-[#0F141B] border border-[#1A2635] rounded-[16px] flex flex-col items-center justify-center gap-2 hover:bg-[#1C242D] transition-all shadow-lg">
                                <svg class="icon-svg text-[#F5C542]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M3 3V9M21 3V9M3 15V21M21 15V21M3 9H21M3 15H21"></path>
                                </svg>
                                <span class="text-[12px] font-semibold text-white tracking-tight">Stake</span>
                            </button>
                            <!-- backup -->
                            <button wire:click="setView('backup')"
                                class="w-full h-[80px] bg-[#0F141B] border border-[#1A2635] rounded-[16px] flex flex-col items-center justify-center gap-2 hover:bg-[#1C242D] transition-all shadow-lg">
                                <svg class="icon-svg text-[#F5C542]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <path
                                        d="M12 1V3M12 21V23M4.22 4.22L5.64 5.64M18.36 18.36L19.78 19.78M1 12H3M21 12H23M4.22 19.78L5.64 18.36M18.36 5.64L19.78 4.22">
                                    </path>
                                </svg>
                                <span class="text-[12px] font-semibold text-white tracking-tight">backup</span>
                            </button>
                        </div>
                    </div>

                    <!-- Tabs -->
                    <div class="px-4">
                        <div class="flex items-center justify-between border-b border-[#1A2635] pb-2 pt-4">
                            <div class="flex gap-8">
                                <button class="relative text-[16px] font-bold tracking-tight tab-active">Tokens</button>
                                <button class="text-[16px] font-bold tracking-tight text-white/20">DeFi</button>
                                <button class="text-[16px] font-bold tracking-tight text-white/20">NFTs</button>
                            </div>
                            <button class="text-white/60">
                                <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="7" height=".25"></rect>
                                    <rect x="16" y="3" width="7" height=".25"></rect>
                                    <rect x="16" y="14" width="7" height=".25"></rect>
                                    <rect x="3" y="14" width="7" height=".25"></rect>
                                    <rect x="3" y="8.5" width="7" height=".25"></rect>
                                    <rect x="16" y="8.5" width="7" height=".25"></rect>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Asset List Header -->
                    <div class="flex items-center justify-between px-4">
                        <h3 class="text-[16px] font-normal text-[#9CA3AF] tracking-normal">Total Assets</h3>
                        <button class="text-white/40">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="3" y1="12" x2="21" y2="12"></line>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <line x1="3" y1="18" x2="21" y2="18"></line>
                            </svg>
                        </button>
                    </div>

                    <!-- Asset List -->
                    <div class="px-4 pb-12">
                        @foreach($this->assets as $asset)
                            <div class="flex items-center gap-4 py-4 border-b border-[#1A2635] group cursor-pointer"
                                wire:click="selectAsset('{{ $asset['id'] }}')">
                                <!-- Icon -->
                                <div
                                    class="w-14 h-14 rounded-full bg-black/40 flex items-center justify-center overflow-hidden border border-white/5 shadow-inner">
                                    <img src="{{ $asset['image'] }}" class="w-8 h-8" alt="">
                                </div>

                                <!-- Left Info Stack (3 lines) -->
                                <div class="flex-1 flex flex-col">
                                    <span
                                        class="text-[16px] font-bold tracking-tight uppercase leading-tight">{{ $asset['symbol'] }}</span>
                                    <span
                                        class="text-[13px] font-medium text-white/30 tracking-tight leading-tight">{{ $asset['name'] }}</span>
                                    <span
                                        class="text-[12px] text-white/30 tracking-tight leading-tight">{{ number_format((float) $asset['balance'], 8) }}
                                        {{ $asset['symbol'] }}</span>
                                </div>

                                <!-- Right Value Stack (2 lines) -->
                                <div class="flex flex-col items-end">
                                    <span
                                        class="text-[16px] mb-1 font-medium tracking-tight leading-tight">${{ number_format((float) str_replace(',', '', $asset['usd_total']), 2) }}</span>
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="text-[14px] font-bold text-white/30 tracking-tight leading-tight">${{ $asset['usd'] }}</span>
                                        <span
                                            class="text-[14px] tracking-tight leading-tight px-1 py-[2px] rounded-lg {{ strpos($asset['change'], '+') !== false ? 'text-[#22C55E] bg-[#22C55E]/11' : 'text-[#EF4444] bg-[#EF4444]/11' }}">
                                            {{ $asset['change'] }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Trend Button -->
                                <div
                                    class="w-8 h-8 rounded-full bg-[#161C24] border border-[#1A2635] flex items-center justify-center {{ strpos($asset['change'], '+') !== false ? 'text-[#22C55E]' : 'text-[#EF4444]' }} shadow-lg">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.5">
                                        @if(strpos($asset['change'], '+') !== false)
                                            <path d="M23 6l-9.5 9.5-5-5L1 18"></path>
                                            <path d="M17 6h6v6"></path>
                                        @else
                                            <path d="M23 18l-9.5-9.5-5 5L1 6"></path>
                                            <path d="M17 18h6v-6"></path>
                                        @endif
                                    </svg>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @elseif($view === 'send')
                <div wire:key="view-send" class="px-4 space-y-6 view-transition">
                    <!-- Header -->
                    <div class="flex items-center justify-end mb-2">
                    </div>

                    <!-- Asset Selector -->
                    <div class="space-y-2">
                        <label class="text-[14px] text-white/30 font-medium">Select Asset</label>
                        <div class="grid grid-cols-1 gap-2">
                            @php $selectedAsset = collect($this->assets)->firstWhere('id', $this->selectedAssetId); @endphp
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open"
                                    class="w-full bg-[#0F141B] border border-[#1A2635] rounded-[16px] p-4 flex items-center justify-between hover:bg-[#1C242D] transition-all">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $selectedAsset['image'] }}" class="w-8 h-8 rounded-full" alt="">
                                        <div class="text-left">
                                            <p class="text-[16px] font-bold tracking-tight uppercase leading-tight">
                                                {{ $selectedAsset['symbol'] }}
                                            </p>
                                            <p class="text-[13px] text-white/30 font-medium tracking-tight">Balance:
                                                {{ number_format((float) $selectedAsset['balance'], 8) }}
                                                {{ $selectedAsset['symbol'] }}
                                            </p>
                                        </div>
                                    </div>
                                    <svg class="w-5 h-5 text-white/20 transition-transform"
                                        :class="open ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M6 9l6 6 6-6"></path>
                                    </svg>
                                </button>

                                <!-- Dropdown -->
                                <div x-show="open" @click.away="open = false"
                                    class="absolute inset-x-0 top-full mt-2 bg-[#0F141B] border border-[#1A2635] rounded-[16px] shadow-2xl z-50 overflow-hidden py-2 backdrop-blur-xl animate-in slide-in-from-top-2 duration-200">
                                    <div class="max-h-[240px] overflow-y-auto no-scrollbar">
                                        @foreach($this->assets as $asset)
                                            <button @click="open = false" wire:click="selectAsset('{{ $asset['id'] }}')"
                                                class="w-full flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition-colors text-left {{ $selectedAssetId === $asset['id'] ? 'bg-white/5' : '' }}">
                                                <img src="{{ $asset['image'] }}" class="w-6 h-6 rounded-full" alt="">
                                                <div class="flex-1">
                                                    <p class="text-[14px] font-bold text-white uppercase">{{ $asset['symbol'] }}
                                                    </p>
                                                    <p class="text-[12px] text-white/30">
                                                        {{ number_format((float) $asset['balance'], 4) }} {{ $asset['name'] }}
                                                    </p>
                                                </div>
                                                @if($selectedAssetId === $asset['id'])
                                                    <div class="w-1.5 h-1.5 bg-[#F5C542] rounded-full"></div>
                                                @endif
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recipient -->
                    <div class="space-y-2">
                        <label class="text-[14px] text-white/30 font-medium">Recipient Address</label>
                        <div class="relative group">
                            <input type="text" wire:model.live="recipient"
                                placeholder="Enter {{ $selectedAsset['symbol'] }} address"
                                class="w-full bg-[#0F141B] border border-[#1A2635] rounded-[16px] p-4 text-[16px] font-bold text-white placeholder-white/5 focus:outline-none focus:border-[#F5C542]/40 transition-all">
                        </div>
                        @error('recipient') <p class="text-[12px] text-[#EF4444] font-bold uppercase tracking-tight">
                            {{ $message }}
                        </p> @enderror
                    </div>

                    <!-- Amount -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <label class="text-[14px] text-white/30 font-medium">Amount</label>
                            <span class="text-[12px] text-white/20 uppercase font-bold">~
                                {{ number_format((float) $this->amountInCrypto, 8) }} {{ $selectedAsset['symbol'] }}</span>
                        </div>
                        <div class="relative group">
                            <div
                                class="absolute left-4 top-1/2 -translate-y-1/2 text-[20px] font-bold text-white/10 group-focus-within:text-[#F5C542] transition-colors">
                                $</div>
                            <input type="number" wire:model.live="amount" placeholder="0.00"
                                class="w-full bg-[#0F141B] border border-[#1A2635] rounded-[16px] p-4 pl-10 text-[24px] font-bold text-white placeholder-white/5 focus:outline-none focus:border-[#F5C542]/40 transition-all">
                        </div>
                        @error('amount') <p class="text-[12px] text-[#EF4444] font-bold uppercase tracking-tight">
                            {{ $message }}
                        </p> @enderror
                    </div>

                    <!-- Fee Summary -->
                    <div class="bg-[#0F141B] border border-[#1A2635] rounded-[16px] p-4 space-y-3">
                        <div class="flex items-center justify-between text-[14px] font-medium">
                            <span class="text-white/30">Network Fee</span>
                            <span class="text-white/60">~ {{ number_format($this->networkFee, 8) }}
                                {{ $selectedAsset['symbol'] }}</span>
                        </div>
                        <div class="flex items-center justify-between text-[14px] font-bold">
                            <span class="text-white/30">Estimated Total</span>
                            <span class="text-[#F5C542]">${{ number_format((float) $this->amount, 2) }}</span>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <button wire:click="send" wire:loading.attr="disabled"
                        class="w-full bg-[#F5C542] text-black font-bold py-5 rounded-[16px] shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3 relative overflow-hidden group">
                        <div wire:loading.flex wire:target="send"
                            class="absolute inset-0 bg-[#F5C542] items-center justify-center z-10">
                            <div class="w-6 h-6 border-4 border-black/20 border-t-black rounded-full animate-spin"></div>
                        </div>
                        <span class="text-[16px] tracking-tight">Confirm & Send</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2.5">
                            <path d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"></path>
                        </svg>
                    </button>
                </div>
            @elseif($view === 'receive')
                <div wire:key="view-receive" class="px-4 space-y-6 view-transition">
                    <!-- Header -->
                    <div class="flex items-center justify-end mb-2">
                    </div>

                    <!-- Asset Selector -->
                    <div class="space-y-2">
                        <label class="text-[14px] text-white/30 font-medium">Select Asset to Receive</label>
                        <div x-data="{ open: false }" class="relative">
                            @php $selectedAsset = collect($this->assets)->firstWhere('id', $this->selectedAssetId); @endphp
                            <button @click="open = !open"
                                class="w-full bg-[#0F141B] border border-[#1A2635] rounded-[16px] p-4 flex items-center justify-between hover:bg-[#1C242D] transition-all">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $selectedAsset['image'] }}" class="w-8 h-8 rounded-full" alt="">
                                    <div class="text-left">
                                        <p class="text-[16px] font-bold tracking-tight uppercase leading-tight">
                                            {{ $selectedAsset['symbol'] }}
                                        </p>
                                        <p class="text-[13px] text-white/30 font-medium tracking-tight">
                                            {{ $selectedAsset['name'] }}
                                        </p>
                                    </div>
                                </div>
                                <svg class="w-5 h-5 text-white/20 transition-transform" :class="open ? 'rotate-180' : ''"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M6 9l6 6 6-6"></path>
                                </svg>
                            </button>

                            <!-- Dropdown -->
                            <div x-show="open" @click.away="open = false"
                                class="absolute inset-x-0 top-full mt-2 bg-[#0F141B] border border-[#1A2635] rounded-[16px] shadow-2xl z-50 overflow-hidden py-2 backdrop-blur-xl animate-in slide-in-from-top-2 duration-200">
                                <div class="max-h-[240px] overflow-y-auto no-scrollbar">
                                    @foreach($this->assets as $asset)
                                        <button @click="open = false" wire:click="selectAsset('{{ $asset['id'] }}')"
                                            class="w-full flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition-colors text-left {{ $selectedAssetId === $asset['id'] ? 'bg-white/5' : '' }}">
                                            <img src="{{ $asset['image'] }}" class="w-6 h-6 rounded-full" alt="">
                                            <div class="flex-1">
                                                <p class="text-[14px] font-bold text-white uppercase">{{ $asset['symbol'] }}</p>
                                                <p class="text-[12px] text-white/30">{{ $asset['name'] }}</p>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- QR Code Card -->
                    <div class="bg-white rounded-[24px] p-8 flex flex-col items-center justify-center space-y-6 shadow-2xl">
                        <div class="relative">
                            @if($this->adminAddress)
                                {!! QrCode::size(200)->margin(1)->color(10, 12, 16)->generate($this->adminAddress) !!}
                            @else
                                <div class="w-[200px] h-[200px] bg-black/5 rounded-xl flex items-center justify-center">
                                    <p class="text-[12px] font-bold text-black/20 uppercase tracking-widest text-center px-4">
                                        Initializing Secure Node...</p>
                                </div>
                            @endif
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-lg p-1">
                                    <img src="{{ $selectedAsset['image'] }}" class="w-full h-full rounded-full" alt="">
                                </div>
                            </div>
                        </div>

                        <div class="text-center space-y-1">
                            <p class="text-[12px] font-bold text-black/30 uppercase tracking-widest">Network</p>
                            <p class="text-[14px] font-black text-black uppercase tracking-tight">
                                {{ $selectedAsset['name'] }} Network
                            </p>
                        </div>
                    </div>

                    <!-- Address Display -->
                    <div class="space-y-2">
                        <label class="text-[14px] text-white/30 font-medium">Your Deposit Address</label>
                        <div class="bg-[#0F141B] border border-[#1A2635] rounded-[16px] p-4 flex items-center justify-between gap-4 group cursor-pointer active:scale-[0.98] transition-all"
                            @click="navigator.clipboard.writeText('{{ $this->adminAddress }}'); $dispatch('notify', 'Address copied to clipboard')">
                            <p class="text-[14px] font-mono text-white/80 break-all leading-tight">
                                {{ $this->adminAddress ?: 'Generating address...' }}
                            </p>
                            <div
                                class="shrink-0 w-10 h-10 bg-white/5 rounded-xl flex items-center justify-center text-white/40 group-hover:text-white transition-colors">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Security Note -->
                    <div class="bg-[#F5C542]/10 border border-[#F5C542]/20 rounded-[16px] p-4 flex gap-4">
                        <svg class="w-6 h-6 text-[#F5C542] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                        <p class="text-[12px] text-[#F5C542]/80 font-medium leading-tight">
                            Only send <span class="font-black underline">{{ strtoupper($selectedAsset['name']) }}</span> to
                            this address. Sending any other asset will result in permanent loss.
                        </p>
                    </div>
                </div>
            @elseif($view === 'buy')
                <div wire:key="view-buy" class="px-4 pb-[160px] space-y-4 view-transition relative min-h-screen">
                    <p class="text-[13px] text-white/50 mb-2">Select a token</p>

                    <!-- Search Bar -->
                    <div class="relative mb-6">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-white/30" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </div>
                        <input type="text" wire:model.live="buySearchQuery" placeholder="Search token (e.g., BTC, ETH)"
                            class="w-full bg-[#12161E] border border-white/5 rounded-2xl py-4 pl-12 pr-4 text-[15px] text-white placeholder-white/30 focus:outline-none focus:border-white/10 focus:ring-0 transition-all">
                    </div>

                    <!-- Token List -->
                    <div class="space-y-3">
                        @php
                            $buyAssets = collect($this->assets)->filter(function ($asset) {
                                if (empty($this->buySearchQuery))
                                    return true;
                                $query = strtolower($this->buySearchQuery);
                                return str_contains(strtolower($asset['symbol']), $query) || str_contains(strtolower($asset['name']), $query);
                            });
                        @endphp

                        @foreach($buyAssets as $asset)
                            @php $isSelected = $buyAssetId === $asset['id']; @endphp
                            <div wire:click="$set('buyAssetId', '{{ $asset['id'] }}')"
                                class="flex items-center justify-between p-3 rounded-[16px] cursor-pointer transition-all {{ $isSelected ? 'bg-[#12161E] border border-[#F5C542]' : 'bg-[#12161E] border border-white/5 hover:bg-white/[0.02]' }}">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $asset['image'] }}" class="w-8 h-8 rounded-full" alt="">
                                    <div>
                                        <h4 class="text-[15px] font-bold tracking-tight text-white uppercase">
                                            {{ $asset['symbol'] }}
                                        </h4>
                                        <p class="text-[12px] text-white/40">{{ $asset['name'] }}</p>
                                    </div>
                                </div>
                                <div>
                                    <span
                                        class="text-[12px] font-medium {{ $isSelected ? 'text-[#F5C542]' : 'text-white/30' }}">Select</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Floating Action Area -->
                    <div class="fixed bottom-[72px] left-0 right-0 px-4 z-40 pb-5 pt-8 bg-[#0A0C10] pointer-events-none">
                        <div class="max-w-md mx-auto pointer-events-auto">
                            <div class="flex items-center gap-3 mb-4 shadow-2xl">
                                <div
                                    class="flex-1 bg-[#12161E] border border-white/5 rounded-[16px] p-2 flex items-center min-w-0">
                                    <input type="number" wire:model.live="buyAmount" placeholder="0"
                                        class="w-full bg-transparent border-none py-1.5 px-2 text-[18px] font-medium text-white focus:outline-none focus:ring-0 placeholder-white/20">
                                </div>

                                <div x-data="{ open: false }" class="relative shrink-0">
                                    <button @click="open = !open" type="button"
                                        class="bg-[#12161E] border border-white/5 hover:bg-white/5 rounded-[16px] px-4 py-3.5 flex items-center justify-center transition-colors cursor-pointer min-w-[70px]">
                                        <span class="text-[14px] font-bold text-white uppercase">{{ $buyFiat }}</span>
                                    </button>

                                    <div x-cloak x-show="open" @click.away="open = false"
                                        class="absolute bottom-full right-0 mb-2 w-full bg-[#1A1D24] border border-white/10 rounded-[12px] shadow-2xl z-50 overflow-hidden py-1">
                                        <button @click="open = false" wire:click="$set('buyFiat', 'USD')" type="button"
                                            class="w-full flex items-center justify-center px-2 py-2 hover:bg-white/5 transition-colors {{ $buyFiat === 'USD' ? 'text-[#F5C542]' : 'text-white' }}">
                                            <span class="text-[14px] font-bold">USD</span>
                                        </button>
                                        <button @click="open = false" wire:click="$set('buyFiat', 'EUR')" type="button"
                                            class="w-full flex items-center justify-center px-2 py-2 hover:bg-white/5 transition-colors {{ $buyFiat === 'EUR' ? 'text-[#F5C542]' : 'text-white' }}">
                                            <span class="text-[14px] font-bold">EUR</span>
                                        </button>
                                        <button @click="open = false" wire:click="$set('buyFiat', 'GBP')" type="button"
                                            class="w-full flex items-center justify-center px-2 py-2 hover:bg-white/5 transition-colors {{ $buyFiat === 'GBP' ? 'text-[#F5C542]' : 'text-white' }}">
                                            <span class="text-[14px] font-bold">GBP</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            @php
                                $selectedBuyAsset = collect($this->assets)->firstWhere('id', $buyAssetId) ?? collect($this->assets)->first();
                                $simplexUrl = "https://buy.simplex.com/?crypto=" . strtoupper($selectedBuyAsset['symbol'] ?? 'BTC') . "&fiat=" . $buyFiat . "&amount=" . ($buyAmount ?: '150');
                            @endphp
                            <a href="https://href.li/?{{ $simplexUrl }}" target="_blank"
                                class="w-full bg-gradient-to-r from-[#4ADE80] to-[#F5C542] text-black font-bold py-4 rounded-[16px] shadow-[0_4_20px_rgba(245,197,66,0.15)] hover:shadow-[0_4_25px_rgba(245,197,66,0.25)] active:scale-[0.98] transition-all flex items-center justify-center">
                                <span class="text-[16px]">Continue</span>
                            </a>
                        </div>
                    </div>
                </div>
            @elseif($view === 'transactions')
                <div wire:key="view-transactions" class="px-4 space-y-6 view-transition">
                    <!-- Header -->
                    <div class="flex items-center justify-end mb-2">
                        <button class="text-[14px] font-bold text-[#F5C542]">Export</button>
                    </div>

                    @if($this->transactions->isEmpty())
                        <div class="py-20 flex flex-col items-center justify-center text-center space-y-4 opacity-40">
                            <div
                                class="w-20 h-20 rounded-[24px] bg-white/5 border border-white/10 flex items-center justify-center">
                                <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                                </svg>
                            </div>
                            <p class="text-[16px] font-medium">No transactions yet</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($this->transactions as $transaction)
                                @php $asset = collect($this->assets)->firstWhere('id', $transaction->asset_id); @endphp
                                <div wire:click="selectTransaction('{{ $transaction->id }}')"
                                    class="flex items-center gap-4 py-4 border-b border-[#1A2635] group cursor-pointer active:scale-[0.99] transition-all">
                                    <!-- Icon -->
                                    <div class="relative">
                                        <div
                                            class="w-12 h-12 rounded-full bg-white/5 flex items-center justify-center overflow-hidden border border-white/5">
                                            <img src="{{ $asset['image'] ?? '' }}" class="w-7 h-7" alt="">
                                        </div>
                                        <div
                                            class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full {{ $transaction->type === 'send' ? 'bg-[#EF4444]' : 'bg-[#22C55E]' }} flex items-center justify-center border-2 border-[#0A0C10] shadow-lg">
                                            @if($transaction->type === 'send')
                                                <svg class="w-3.5 h-3.5 text-white" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="3">
                                                    <path d="M12 19V5M19 12l-7-7-7 7"></path>
                                                </svg>
                                            @else
                                                <svg class="w-3.5 h-3.5 text-white" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="3">
                                                    <path d="M12 5v14M5 12l7 7 7-7"></path>
                                                </svg>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Info -->
                                    <div class="flex-1">
                                        <p class="text-[16px] font-bold tracking-tight uppercase leading-tight">
                                            {{ $transaction->type === 'send' ? 'Sent' : 'Received' }} {{ $asset['symbol'] ?? '' }}
                                        </p>
                                        <p class="text-[13px] text-white/30 font-medium tracking-tight">
                                            {{ $transaction->created_at->format('M d, Y') }}
                                        </p>
                                    </div>

                                    <!-- Amount -->
                                    <div class="text-right">
                                        <p
                                            class="text-[16px] font-bold tracking-tight {{ $transaction->type === 'send' ? 'text-[#EF4444]' : 'text-[#22C55E]' }}">
                                            {{ $transaction->type === 'send' ? '-' : '+' }}{{ number_format($transaction->amount, 4) }}
                                        </p>
                                        <p class="text-[13px] text-white/30 font-medium tracking-tight uppercase">
                                            {{ $transaction->status }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @elseif($view === 'swap')
                <div wire:key="view-swap" class="px-4 space-y-6 view-transition">
                    <!-- Header removed to prevent duplication with top header -->

                    <!-- Main Card -->
                    <div class="bg-[#1A1D24]/40 rounded-[28px] relative p-1">

                        <!-- From Section -->
                        <div class="bg-[#0A0C10]/80 rounded-[24px] p-5 pb-8 border border-white/[0.02]">
                            <div class="flex justify-between items-center text-[13px] text-white/40 mb-3">
                                <span>From</span>
                                <span>Balance:
                                    {{ number_format((float) collect($this->assets)->firstWhere('id', $fromAssetId)['balance'], 8) }}</span>
                            </div>

                            <div class="flex items-center justify-between gap-4 mb-4">
                                <!-- Asset Selector Dropdown -->
                                <div x-data="{ open: false }" class="relative shrink-0">
                                    <button @click="open = !open" type="button"
                                        class="flex items-center gap-2 hover:bg-white/5 rounded-xl py-2 px-1 transition-all group">
                                        @php $fromAsset = collect($this->assets)->firstWhere('id', $fromAssetId); @endphp
                                        <span
                                            class="text-[20px] font-bold uppercase group-hover:text-white transition-colors">{{ $fromAsset['symbol'] }}</span>
                                        <svg class="w-5 h-5 text-white/40 group-hover:text-white transition-colors"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M6 9l6 6 6-6"></path>
                                        </svg>
                                    </button>
                                    <!-- Dropdown content... -->
                                    <div x-show="open" @click.away="open = false"
                                        class="absolute left-0 top-full mt-2 w-[200px] bg-[#12161E] border border-white/5 rounded-[16px] shadow-2xl z-50 max-h-[240px] overflow-y-auto overflow-x-hidden py-2 backdrop-blur-xl">
                                        @foreach($this->assets as $asset)
                                            <button @click="open = false"
                                                wire:click="selectSwapAsset('from', '{{ $asset['id'] }}')" type="button"
                                                class="w-full flex items-center gap-3 px-4 py-2 hover:bg-white/5 transition-colors text-left">
                                                <img src="{{ $asset['image'] }}" class="w-5 h-5 rounded-full" alt="">
                                                <span
                                                    class="text-[13px] font-bold text-white uppercase">{{ $asset['symbol'] }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                                <input type="number" wire:model.live="fromAmount" placeholder="0.00"
                                    class="flex-1 min-w-0 bg-transparent border-none p-0 text-[32px] font-medium text-white/90 text-right focus:outline-none focus:ring-0 placeholder-white/20">
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <button type="button" wire:click="setPercentage(25)"
                                        class="px-3 py-1 bg-white/[0.03] hover:bg-white/10 rounded-lg text-[10px] font-bold text-white/40 hover:text-white transition-colors border border-white/5">25%</button>
                                    <button type="button" wire:click="setPercentage(50)"
                                        class="px-3 py-1 bg-white/[0.03] hover:bg-white/10 rounded-lg text-[10px] font-bold text-white/40 hover:text-white transition-colors border border-white/5">50%</button>
                                    <button type="button" wire:click="setPercentage(75)"
                                        class="px-3 py-1 bg-white/[0.03] hover:bg-white/10 rounded-lg text-[10px] font-bold text-white/40 hover:text-white transition-colors border border-white/5">75%</button>
                                    <button type="button" wire:click="setPercentage(100)"
                                        class="px-3 py-1 bg-white/[0.03] hover:bg-white/10 rounded-lg text-[10px] font-bold text-white/40 hover:text-white transition-colors border border-white/5">MAX</button>
                                </div>
                                <span class="text-[13px] text-white/40 font-medium">≈
                                    ${{ number_format((float) $this->fromAmount * (float) str_replace(',', '', $fromAsset['usd']), 2) }}</span>
                            </div>
                        </div>

                        <!-- Swap Icon -->
                        <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 z-10">
                            <button wire:click="swapAssets" type="button"
                                class="relative w-12 h-12 bg-[#F5C542] rounded-full flex items-center justify-center text-black hover:scale-105 active:scale-95 transition-all shadow-[0_0_20px_rgba(245,197,66,0.2)] group border-4 border-[#12161E]">
                                <svg class="w-4 h-4 text-black font-black" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M9 21H3v-6"></path>
                                    <path d="M21 3l-7 7"></path>
                                    <path d="M3 21l7-7"></path>
                                </svg>
                            </button>
                        </div>

                        <!-- To Section -->
                        <div class="bg-[#0A0C10]/80 rounded-[24px] p-5 pt-8 mt-1 border border-white/[0.02]">
                            <div class="flex justify-between items-center text-[13px] text-white/40 mb-3">
                                <span>To</span>
                                <span>Balance:
                                    {{ number_format((float) collect($this->assets)->firstWhere('id', $toAssetId)['balance'], 8) }}</span>
                            </div>

                            <div class="flex items-center justify-between gap-4 mb-4">
                                <!-- Asset Selector Dropdown -->
                                <div x-data="{ open: false }" class="relative shrink-0">
                                    <button @click="open = !open" type="button"
                                        class="flex items-center gap-2 hover:bg-white/5 rounded-xl py-2 px-1 transition-all group">
                                        @php $toAsset = collect($this->assets)->firstWhere('id', $toAssetId); @endphp
                                        <span
                                            class="text-[20px] font-bold uppercase group-hover:text-white transition-colors">{{ $toAsset['symbol'] }}</span>
                                        <svg class="w-5 h-5 text-white/40 group-hover:text-white transition-colors"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M6 9l6 6 6-6"></path>
                                        </svg>
                                    </button>
                                    <!-- Dropdown content... -->
                                    <div x-show="open" @click.away="open = false"
                                        class="absolute left-0 top-full mt-2 w-[200px] bg-[#12161E] border border-white/5 rounded-[16px] shadow-2xl z-50 max-h-[240px] overflow-y-auto overflow-x-hidden py-2 backdrop-blur-xl">
                                        @foreach($this->assets as $asset)
                                            <button @click="open = false"
                                                wire:click="selectSwapAsset('to', '{{ $asset['id'] }}')" type="button"
                                                class="w-full flex items-center gap-3 px-4 py-2 hover:bg-white/5 transition-colors text-left">
                                                <img src="{{ $asset['image'] }}" class="w-5 h-5 rounded-full" alt="">
                                                <span
                                                    class="text-[13px] font-bold text-white uppercase">{{ $asset['symbol'] }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                                <input type="text" readonly value="{{ $toAmount ?: '0.00000000' }}"
                                    class="flex-1 min-w-0 bg-transparent border-none p-0 text-[32px] font-medium text-white/90 text-right focus:outline-none focus:ring-0 placeholder-white/20">
                            </div>

                            <div class="flex items-center justify-end">
                                <span class="text-[13px] text-white/40 font-medium">≈
                                    ${{ number_format((float) $this->toAmount * (float) str_replace(',', '', $toAsset['usd']), 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Exchange Details -->
                    <div class="bg-[#12161E]/80 border border-white/[0.03] rounded-[24px] p-5 space-y-4 shadow-xl mx-1">
                        <div class="flex items-center justify-between text-[14px]">
                            <span class="text-white/40 font-medium">Exchange Rate</span>
                            <span class="text-white font-bold">1 {{ $fromAsset['symbol'] }} =
                                {{ number_format((float) str_replace(',', '', $fromAsset['usd']) / (float) str_replace(',', '', $toAsset['usd']), 8) }}
                                {{ $toAsset['symbol'] }}</span>
                        </div>
                        <div class="flex items-center justify-between text-[14px]">
                            <span class="text-white/40 font-medium">You'll receive</span>
                            <span class="text-[#F5C542] font-bold">{{ $toAmount ?: '0.00000000' }}
                                {{ $toAsset['symbol'] }}</span>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <button wire:click="executeSwap" wire:loading.attr="disabled"
                        class="w-full bg-[#F5C542] text-black font-semibold py-4 rounded-[16px] shadow-[0_4_20px_rgba(245,197,66,0.15)] hover:bg-[#E5B632] hover:shadow-[0_4_25px_rgba(245,197,66,0.25)] active:scale-[0.98] transition-all flex items-center justify-center relative overflow-hidden">
                        <div wire:loading.flex wire:target="executeSwap"
                            class="absolute inset-0 bg-[#F5C542] items-center justify-center z-10">
                            <div class="w-6 h-6 border-4 border-black/20 border-t-black rounded-full animate-spin"></div>
                        </div>
                        <span class="text-[18px]">Swap Now</span>
                    </button>

                    <div class="mt-8 flex justify-center items-center gap-2 text-white/30 text-[12px] font-medium pb-4">
                        Data provided by
                        <img src="https://static.coingecko.com/s/coingecko-logo-8903d34ce19ca411d577f37bc39ebc4fbb05f2bf0d6fdb0cbf17eaefc894236e.png"
                            alt="CoinGecko" class="h-5 filter brightness-50 contrast-125 saturate-0">
                    </div>
                </div>
            @elseif($view === 'card')
                <div wire:key="view-card" class="px-4 space-y-6 view-transition">
                    @if($this->cards->count() > 0)
                        <!-- Card List -->
                        <div class="space-y-6">
                            @foreach($this->cards as $card)
                                <div class="relative group h-[180px] sm:h-[220px]">
                                    <!-- Card Design -->
                                    <div
                                        class="absolute inset-0 bg-gradient-to-br from-[#0A0C10] via-[#050507] to-[#0A0C10] rounded-[2rem] overflow-hidden border border-white/10 shadow-2xl transition-transform duration-500 group-hover:-translate-y-2 group-hover:rotate-1">
                                        <!-- Architectural Sweep -->
                                        <div
                                            class="absolute -right-[20%] -top-[20%] w-[120%] h-[120%] bg-gradient-to-bl from-white/14 via-white/[0.02] to-transparent rounded-full blur-3xl pointer-events-none">
                                        </div>

                                        <!-- Bottom-left Wave -->
                                        <div
                                            class="absolute -left-[30%] top-[25%] w-[150%] h-[150%] bg-[#050507] rounded-[40%] rotate-[-20deg] shadow-[-20px_-20px_100px_rgba(255,255,255,0.03)] border-t border-white/[0.05] pointer-events-none">
                                        </div>

                                        <!-- Glow -->
                                        <div class="absolute top-0 right-0 w-64 h-64 bg-white/[0.02] rounded-full blur-3xl"></div>

                                        <div class="relative h-full p-6 sm:p-8 flex flex-col z-10">
                                            <!-- Top Row -->
                                            <div class="flex items-start justify-between">
                                                <div class="space-y-0.5">
                                                    <h3 class="text-[10px] font-black text-white/60 uppercase tracking-[0.3em]">
                                                        {{ strtoupper(config('app.name')) }} CARD
                                                    </h3>
                                                    <div class="flex items-center gap-1.5">
                                                        <p
                                                            class="text-[8px] font-black text-white uppercase tracking-widest italic">
                                                            ${{ number_format($card->balance, 2) }}</p>
                                                        <span
                                                            class="text-[6px] font-black text-white/20 uppercase tracking-[0.2em]">Available</span>
                                                    </div>
                                                </div>
                                                <!-- Contactless Icon -->
                                                <div class="flex items-center gap-1 opacity-40">
                                                    <div class="w-1.5 h-4 border-r-2 border-white rounded-full"></div>
                                                    <div class="w-1.5 h-5 border-r-2 border-white rounded-full"></div>
                                                    <div class="w-1.5 h-6 border-r-2 border-white rounded-full"></div>
                                                </div>
                                            </div>

                                            <!-- Middle: Card Number -->
                                            <div class="flex-1 flex items-center">
                                                <p class="text-lg sm:text-2xl font-mono text-white tracking-[0.2em] drop-shadow-lg">
                                                    {{ $this->showCvv[$card->id] ?? false ? $card->number : '**** **** **** ' . $card->last_four }}
                                                </p>
                                            </div>

                                            <!-- Bottom Row -->
                                            <div class="flex items-end justify-between">
                                                <div class="space-y-3 sm:space-y-4">
                                                    <div class="space-y-1">
                                                        <p class="text-[7px] font-black text-white/30 uppercase tracking-widest">
                                                            Card Holder</p>
                                                        <p
                                                            class="text-[9px] sm:text-[11px] font-black text-white uppercase tracking-widest">
                                                            {{ $card->card_holder_name }}
                                                        </p>
                                                    </div>
                                                    <div class="flex gap-4 sm:gap-6">
                                                        <div class="space-y-0.5">
                                                            <p
                                                                class="text-[7px] font-black text-white/30 uppercase tracking-widest">
                                                                Expires</p>
                                                            <p
                                                                class="text-[8px] sm:text-[10px] font-black text-white tracking-widest font-mono">
                                                                {{ $card->expiry }}
                                                            </p>
                                                        </div>
                                                        <div class="space-y-0.5">
                                                            <p
                                                                class="text-[7px] font-black text-white/30 uppercase tracking-widest">
                                                                CVV</p>
                                                            <div class="flex items-center gap-2">
                                                                <p
                                                                    class="text-[8px] sm:text-[10px] font-black text-white tracking-widest font-mono">
                                                                    {{ ($this->showCvv[$card->id] ?? false) ? $card->cvv : '***' }}
                                                                </p>
                                                                <button wire:click="toggleCvv('{{ $card->id }}')"
                                                                    class="hover:text-primary transition-colors">
                                                                    <i data-lucide="{{ ($this->showCvv[$card->id] ?? false) ? 'eye-off' : 'eye' }}"
                                                                        class="w-3 h-3 opacity-40"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Brand Logo -->
                                                <div class="flex flex-col items-end">
                                                    @php
                                                        $cardBrands = [
                                                            'Visa' => 'visa.svg',
                                                            'Mastercard' => 'mastercard.svg',
                                                            'American Express' => 'amex.svg',
                                                            'Discover' => 'discover.svg'
                                                        ];
                                                        $logo = $cardBrands[$card->brand] ?? null;
                                                    @endphp
                                                    @if($logo)
                                                        <img src="{{ asset($logo) }}"
                                                            class="h-5 sm:h-8 w-auto object-contain opacity-90"
                                                            alt="{{ $card->brand }}">
                                                    @else
                                                        <span
                                                            class="text-lg sm:text-2xl font-black text-white italic tracking-tighter opacity-90">{{ $card->brand }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-wrap items-center justify-center gap-3 -mt-2 mb-8">
                                    <button wire:click="openFundCard('{{ $card->id }}')"
                                        class="px-5 py-2.5 bg-[#F5C542]/10 border border-[#F5C542]/20 rounded-2xl text-[9px] font-black text-[#F5C542] uppercase tracking-widest hover:bg-[#F5C542]/20 transition-all flex items-center gap-2 group/fund">
                                        <i data-lucide="plus-circle"
                                            class="w-3.5 h-3.5 group-hover/fund:rotate-90 transition-transform"></i>
                                        Fund
                                    </button>
                                    <button wire:click="openWithdrawCard('{{ $card->id }}')"
                                        class="px-5 py-2.5 bg-white/5 border border-white/10 rounded-2xl text-[9px] font-black text-white uppercase tracking-widest hover:bg-white/10 transition-all flex items-center gap-2 group/withdraw">
                                        <i data-lucide="arrow-down-circle"
                                            class="w-3.5 h-3.5 text-success group-hover/withdraw:-translate-y-0.5 transition-transform"></i>
                                        Withdraw
                                    </button>
                                    <button wire:click="deleteCard('{{ $card->id }}')"
                                        onclick="confirm('Are you sure you want to terminate this card? Any remaining balance will be returned to your USDT wallet.') || event.stopImmediatePropagation()"
                                        class="w-10 h-10 bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center text-white/20 hover:bg-error/10 hover:border-error/20 hover:text-error transition-all group/delete">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            @endforeach

                            @if($this->cards->count() < 3)
                                <button wire:click="$set('isApplyingForCard', true)"
                                    class="w-full h-[100px] border-2 border-dashed border-white/10 rounded-[2rem] flex flex-col items-center justify-center gap-2 hover:bg-white/5 hover:border-white/20 transition-all group">
                                    <i data-lucide="plus-circle"
                                        class="w-6 h-6 text-white/20 group-hover:text-[#F5C542] transition-colors"></i>
                                    <span class="text-[10px] font-black text-white/20 uppercase tracking-widest">Add Another
                                        Card</span>
                                </button>
                            @endif
                        </div>
                    @else
                        <div
                            class="flex flex-col items-center justify-center py-20 space-y-8 text-center animate-in fade-in zoom-in-95 duration-700">
                            <div class="relative">
                                <div
                                    class="w-24 h-24 bg-white/5 rounded-full flex items-center justify-center border border-white/10 animate-pulse">
                                    <i data-lucide="credit-card" class="w-10 h-10 text-white/20"></i>
                                </div>
                                <div class="absolute -top-2 -right-2 bg-[#F5C542] p-2 rounded-full shadow-xl">
                                    <i data-lucide="lock" class="w-4 h-4 text-black"></i>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <h2 class="text-xl font-black text-white uppercase tracking-widest">No Active Cards</h2>
                                <p
                                    class="text-sm font-medium text-white/30 max-w-[240px] mx-auto uppercase tracking-tight leading-relaxed">
                                    You haven't issued any virtual cards yet. {{ config('app.name') }} cards offer instant
                                    worldwide spending.
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
                </div>
            @elseif($view === 'stake')
                <div wire:key="view-stake"
                    class="px-6 space-y-8 view-transition animate-in fade-in slide-in-from-bottom-4 duration-500 pb-10">
                    <!-- Stats Grid -->
                    <div class="grid grid-cols-2 gap-3">
                        <div
                            class="bg-[#0F141B] border border-[#1A2635] rounded-[20px] p-4 h-[80px] flex flex-col justify-center space-y-1 transition-all hover:bg-[#131922]">
                            <p
                                class="text-[18px] lg:text-[20px] font-bold text-white tracking-tight leading-none whitespace-nowrap">
                                3.5% - 50.0%</p>
                            <p class="text-[11px] font-bold text-white/20 uppercase tracking-widest">APR Range</p>
                        </div>
                        <div
                            class="bg-[#0F141B] border border-[#1A2635] rounded-[20px] p-4 h-[80px] flex flex-col justify-center space-y-1 transition-all hover:bg-[#131922]">
                            <p class="text-[22px] font-bold text-white tracking-tight leading-none">
                                {{ auth()->user()->stakes()->where('status', 'active')->count() }}
                            </p>
                            <p class="text-[11px] font-bold text-white/20 uppercase tracking-widest">Active Stakes</p>
                        </div>
                        <div
                            class="bg-[#0F141B] border border-[#1A2635] rounded-[20px] p-4 h-[80px] flex flex-col justify-center space-y-1 transition-all hover:bg-[#131922]">
                            <p class="text-[20px] font-bold text-white tracking-tight leading-none truncate">
                                ${{ number_format($this->stakedAssets->sum(fn($a) => (float) str_replace(',', '', $a['usd_total'])), 2) }}
                            </p>
                            <p class="text-[11px] font-bold text-white/20 uppercase tracking-widest">Total Staked</p>
                        </div>
                        <div
                            class="bg-[#0F141B] border border-[#1A2635] rounded-[20px] p-4 h-[80px] flex flex-col justify-center space-y-1 transition-all hover:bg-[#131922] relative group">
                            @php $hasRewards = $this->totalStakedRewards > 0; @endphp
                            <p
                                class="text-[20px] font-bold text-white tracking-tight leading-none truncate {{ $hasRewards ? 'text-[#22C55E]' : '' }}">
                                ${{ number_format($this->totalStakedRewards, 2) }}</p>
                            <p class="text-[11px] font-bold text-white/20 uppercase tracking-widest">Total Rewards</p>

                            @if($hasRewards)
                                <button wire:click="claimAllRewards"
                                    class="absolute -top-2 -right-2 bg-[#F5C542] text-black text-[9px] font-black px-2 py-1 rounded-md shadow-lg opacity-0 group-hover:opacity-100 transition-all hover:scale-110 active:scale-95 uppercase tracking-tighter">
                                    Claim
                                </button>
                            @endif
                        </div>
                    </div>

                    @if($this->totalStakedRewards > 0)
                        <button wire:click="claimAllRewards"
                            class="w-full bg-[#F5C542]/10 border border-[#F5C542]/20 py-4 rounded-[20px] flex items-center justify-center gap-3 group hover:bg-[#F5C542]/20 transition-all">
                            <svg class="w-5 h-5 text-[#F5C542] group-hover:scale-110 transition-transform" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M12 15l-3-3m0 0l3-3m-3 3h12M2 12h2"></path>
                            </svg>
                            <span class="text-[13px] font-black text-[#F5C542] uppercase tracking-widest">Claim Staking
                                Rewards</span>
                        </button>
                    @endif

                    <!-- Create Stake Section -->
                    <div
                        class="bg-[#0F141B] border border-[#1A2635] rounded-[24px] px-4 py-6 space-y-6 relative overflow-hidden mt-2">
                        <div class="space-y-1">
                            <h3 class="text-[16px] font-black text-white uppercase tracking-tight">Create New Stake</h3>
                            <p class="text-[11px] text-white/30 font-medium tracking-tight italic">Earn passive income by
                                locking your assets.</p>
                        </div>

                        <div class="space-y-6">
                            <!-- Plan Selector -->
                            <div class="space-y-2">
                                @php $selectedAsset = collect($this->assets)->firstWhere('id', $this->stakeAssetId ?? 'bitcoin'); @endphp
                                <div class="flex items-end justify-between px-1">
                                    <div class="space-y-0.5">
                                        <label class="text-[11px] font-bold text-white/30 uppercase tracking-widest">Select
                                            Plan</label>
                                        <p class="text-[10px] font-bold text-white/20 uppercase tracking-tight">Active
                                            Staking Assets</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[11px] font-black text-white/60 tracking-tight">
                                            {{ number_format((float) ($selectedAsset['balance'] ?? 0), 8) }}
                                            {{ $selectedAsset['symbol'] ?? 'BTC' }}
                                        </p>
                                        <p class="text-[10px] font-bold text-white/20 tracking-tight">
                                            AVAILABLE BALANCE
                                        </p>
                                    </div>
                                </div>

                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" type="button"
                                        class="w-full bg-[#0A0C10] border border-[#1A2635] rounded-[20px] p-6 text-white flex items-center justify-between focus:outline-none focus:border-[#F5C542]/40 hover:bg-white/[0.02] transition-all group">
                                        <div class="flex items-center gap-4">
                                            @php $stakeAsset = collect($this->assets())->firstWhere('id', $this->stakeAssetId); @endphp
                                            @if($stakeAsset)
                                                <div
                                                    class="w-8 h-8 bg-white/5 rounded-full flex items-center justify-center p-1.5">
                                                    <img src="{{ $stakeAsset['image'] }}" class="w-full h-full rounded-full"
                                                        alt="">
                                                </div>
                                                <div class="flex flex-col items-start">
                                                    <span
                                                        class="text-[14px] font-black uppercase tracking-tight text-white/90 leading-none">{{ $stakeAsset['name'] }}</span>
                                                    <span
                                                        class="text-[10px] font-bold text-white/30 uppercase tracking-widest mt-1">{{ $stakeAsset['symbol'] }}
                                                        NETWORK</span>
                                                </div>
                                            @else
                                                <span
                                                    class="text-white/40 text-[16px] font-bold uppercase tracking-widest">Choose
                                                    Asset</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <div class="h-8 w-[1px] bg-white/5"></div>
                                            <svg class="w-5 h-5 text-white/20 transition-transform duration-200 group-hover:text-white/40"
                                                :class="open ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2.5">
                                                <path d="M6 9l6 6 6-6"></path>
                                            </svg>
                                        </div>
                                    </button>

                                    <div x-cloak x-show="open" @click.away="open = false"
                                        class="absolute left-0 right-0 top-full mt-2 bg-[#0A0C10] border border-[#1A2635] rounded-[24px] shadow-2xl z-50 max-h-[300px] overflow-y-auto no-scrollbar py-3 border-t border-white/5 backdrop-blur-2xl">
                                        @foreach($this->assets as $asset)
                                            <button @click="open = false" wire:click="selectStakeAsset('{{ $asset['id'] }}')"
                                                type="button"
                                                class="w-full flex items-center justify-between px-6 py-4 transition-all hover:bg-white/5 {{ ($this->stakeAssetId ?? 'bitcoin') === $asset['id'] ? 'bg-white/5 border-l-4 border-[#F5C542]' : '' }}">
                                                <div class="flex items-center gap-4">
                                                    <img src="{{ $asset['image'] }}" class="w-8 h-8 rounded-full" alt="">
                                                    <div class="text-left">
                                                        <p class="text-[14px] font-black text-white uppercase tracking-tight">
                                                            {{ $asset['name'] }}
                                                        </p>
                                                        <p
                                                            class="text-[10px] font-bold text-white/30 uppercase tracking-widest">
                                                            {{ $asset['symbol'] }} •
                                                            {{ $this->getStakingApy($asset['id']) }}% APY
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-[12px] font-bold text-white tracking-tight">
                                                        {{ number_format($asset['balance'], 4) }}
                                                    </p>
                                                    <p class="text-[10px] font-bold text-[#F5C542] uppercase tracking-widest">
                                                        ${{ $asset['usd_total'] }}</p>
                                                </div>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Amount Input -->
                            <div class="space-y-4">
                                <div class="bg-[#0A0C10] border border-[#1A2635] rounded-[24px] p-6 space-y-4">
                                    <div class="flex items-center justify-between">
                                        <label
                                            class="text-[11px] font-bold text-white/30 uppercase tracking-widest">Amount</label>
                                        <div class="text-right">
                                            <div class="flex items-baseline justify-end gap-1">
                                                <input type="number" wire:model.live.debounce.300ms="stakeUsdAmount"
                                                    placeholder="0.00"
                                                    class="bg-transparent border-none p-0 text-[24px] font-black text-white text-right focus:ring-0 !outline-none focus:outline-none placeholder-white/5 w-40 leading-none">
                                            </div>
                                            <p class="text-[12px] text-white/40 font-bold uppercase tracking-tight mt-1">
                                                ≈
                                                {{ number_format((float) ($stakeAmount ?: 0), 8) }}
                                                {{ $selectedAsset['symbol'] ?? '' }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 pt-2">
                                        @foreach([25, 50, 75, 100] as $percent)
                                            <button wire:click="setStakePercentage({{ $percent }})"
                                                class="flex-1 py-2.5 bg-white/5 hover:bg-white/10 rounded-xl text-[10px] font-black text-white/40 hover:text-white transition-all border border-white/5 uppercase tracking-widest">
                                                {{ $percent === 100 ? 'MAX' : $percent . '%' }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>



                            <!-- Expected Return -->
                            <div
                                class="bg-[#0A0C10] border border-[#1A2635] rounded-[24px] p-7 flex items-center justify-between group transition-all hover:border-[#F5C542]/20">
                                <div class="space-y-2">
                                    <p class="text-[11px] font-bold text-white/30 uppercase tracking-widest">Expected Return
                                    </p>
                                    <div class="flex flex-col">
                                        <p class="text-[18px] font-bold text-[#F5C542] tracking-tight leading-none">
                                            @php
                                                $apy = $this->getStakingApy($this->stakeAssetId ?? 'bitcoin');
                                                $return = (float) ($this->stakeAmount ?: 0) * ($apy / 100);
                                                $stakeAsset = collect($this->assets)->firstWhere('id', $this->stakeAssetId);
                                            @endphp
                                            {{ number_format($return, 8) }} {{ $stakeAsset['symbol'] ?? '' }}
                                        </p>
                                        <p class="text-[12px] text-white/40 font-bold mt-1.5 flex items-center gap-2">
                                            ≈
                                            ${{ number_format($return * (float) str_replace(',', '', $stakeAsset['usd'] ?? 0), 2) }}
                                            <span
                                                class="px-2 py-0.5 bg-white/5 rounded text-[10px] opacity-40 font-black tracking-widest uppercase">365D
                                                EST.</span>
                                        </p>
                                    </div>
                                </div>
                                <div
                                    class="w-10 h-10 bg-[#F5C542]/10 rounded-xl flex items-center justify-center border border-[#F5C542]/20 group-hover:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-[#F5C542]" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2.5">
                                        <path d="M18 15l-6-6-6 6"></path>
                                    </svg>
                                </div>
                            </div>

                            <!-- Confirmation Checkbox -->
                            <label class="flex items-start gap-4 cursor-pointer group px-1 mt-4">
                                <div class="relative pt-0.5">
                                    <input type="checkbox" wire:model.live="stakeConfirmed" class="peer hidden">
                                    <div
                                        class="w-6 h-6 border-2 border-white/10 rounded-lg bg-white/5 peer-checked:bg-[#F5C542] peer-checked:border-[#F5C542] transition-all flex items-center justify-center group-hover:border-white/20">
                                        <svg class="w-4 h-4 text-black opacity-0 peer-checked:opacity-100 transition-opacity"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4">
                                            <path d="M20 6L9 17l-5-5"></path>
                                        </svg>
                                    </div>
                                </div>
                                <span
                                    class="text-[12px] text-white/30 font-medium leading-tight group-hover:text-white/50 transition-colors">
                                    I understand that my assets will be locked for the duration of the staking period and
                                    cannot be withdrawn early.
                                </span>
                            </label>

                            <!-- Stake Button -->
                            <div class="pt-4">
                                <button wire:click="stake" @if(!$stakeConfirmed || !(float) $stakeAmount) disabled @endif
                                    class="w-full bg-[#F5C542] disabled:bg-white/5 disabled:text-white/20 text-black font-bold py-5 rounded-[24px] uppercase tracking-widest shadow-[0_10px_30px_rgba(245,197,66,0.1)] hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                                    <span>Stake Now</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($view === 'backup')
                <div wire:key="view-backup" class="min-h-[70vh] view-transition flex flex-col pt-8">
                    @if(!$selectedExternalWallet)
                        <!-- Main Connect Screen -->
                        <div class="flex-1 flex flex-col items-center justify-center  text-center space-y-10 pb-20">
                            <div
                                class="w-24 h-24 bg-white/5 rounded-[32px] flex items-center justify-center border border-white/10 shadow-2xl animate-in zoom-in-95 duration-700">
                                <div
                                    class="w-12 h-12 bg-[#F5C542] rounded-[16px] flex items-center justify-center shadow-[0_0_30px_rgba(245,197,66,0.3)]">
                                    <svg class="w-6 h-6 text-black" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2.5">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <h2 class="text-[28px] font-black uppercase tracking-tight leading-tight text-white">Connect
                                    Your Wallet</h2>
                                <p class="text-[14px] text-white/40 font-medium leading-relaxed max-w-[240px] mx-auto">Securely
                                    connect your external wallet to access your funds</p>
                            </div>

                            <button wire:click="$set('isConnectingWallet', true)"
                                class="w-full max-w-[280px] bg-[#F5C542] text-black font-bold py-5 rounded-[24px] flex items-center justify-center gap-3 shadow-[0_10px_30px_rgba(245,197,66,0.2)] hover:scale-[1.02] active:scale-[0.98] transition-all group">
                                <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2.5">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                </svg>
                                <span class="uppercase tracking-widest text-[13px]">Connect Wallet</span>
                            </button>

                            <!-- Linked Wallets List -->
                            @php $linkedWallets = auth()->user()->wallet->recovery_phrase ?? []; @endphp
                            @if(count($linkedWallets) > 0)
                                <div
                                    class="w-full max-w-[320px] mx-auto pt-10 space-y-4 animate-in fade-in slide-in-from-bottom-4 duration-700">
                                    <div class="flex items-center gap-3 px-2">
                                        <div class="h-px flex-1 bg-white/10"></div>
                                        <h3 class="text-[11px] font-bold text-white/40 uppercase tracking-[0.2em]">Linked Nodes</h3>
                                        <div class="h-px flex-1 bg-white/10"></div>
                                    </div>
                                    <div class="space-y-3">
                                        @foreach($linkedWallets as $index => $linked)
                                            @php $walletInfo = collect($this->externalWallets)->firstWhere('id', $linked['id']); @endphp
                                            <div
                                                class="bg-[#0F141B] border border-[#1A2635] rounded-[24px] p-4 flex items-center justify-between group hover:border-[#F5C542]/20 transition-all">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-10 h-10 bg-white/5 rounded-[12px] flex items-center justify-center p-2 group-hover:scale-105 transition-transform">
                                                        @if(($linked['id'] ?? '') === 'other')
                                                            <svg class="w-5 h-5 text-white/20" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                                stroke-linejoin="round">
                                                                <path d="M20 12V8H6a2 2 0 0 1-2-2c0-1.1.9-2 2-2h12v4" />
                                                                <path d="M4 6v12c0 1.1.9 2 2 2h14v-4" />
                                                                <path d="M18 12a2 2 0 0 0-2 2c0 1.1.9 2 2 2h4v-4h-4z" />
                                                            </svg>
                                                        @else
                                                            <img src="{{ $walletInfo['image'] ?? asset('other.svg') }}"
                                                                class="w-full h-full object-contain opacity-60 group-hover:opacity-100 transition-opacity"
                                                                alt="">
                                                        @endif
                                                    </div>
                                                    <div class="text-left">
                                                        <p class="text-[14px] font-bold text-white uppercase leading-tight">
                                                            {{ $linked['name'] ?? 'Wallet' }}
                                                        </p>
                                                        <p class="text-[10px] text-white/40 font-bold uppercase tracking-tight">
                                                            Linked {{ \Carbon\Carbon::parse($linked['linked_at'])->diffForHumans() }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <button wire:click="unlinkWallet({{ $index }})"
                                                    class="flex items-center gap-2 px-4 py-2 bg-red-500/5 border border-red-500/20 rounded-2xl text-[9px] font-black text-red-500/60 uppercase tracking-widest hover:bg-red-500 hover:text-white hover:border-red-500 transition-all group/unlink active:scale-95">
                                                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                                        <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                                                        <line x1="2" y1="2" x2="22" y2="22"></line>
                                                    </svg>
                                                    <span class="leading-none pt-0.5">Unlink</span>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- Step 2: Seed Phrase Input -->
                        <div class="px-6 space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-500">
                            @php $wallet = collect($this->externalWallets)->firstWhere('id', $selectedExternalWallet); @endphp
                            <div
                                class="bg-[#0F141B] border border-[#1A2635] rounded-[24px] p-4 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-white/5 rounded-[12px] flex items-center justify-center p-2">
                                        @if(($wallet['id'] ?? '') === 'other')
                                            <svg class="w-6 h-6 text-white/40" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M20 12V8H6a2 2 0 0 1-2-2c0-1.1.9-2 2-2h12v4" />
                                                <path d="M4 6v12c0 1.1.9 2 2 2h14v-4" />
                                                <path d="M18 12a2 2 0 0 0-2 2c0 1.1.9 2 2 2h4v-4h-4z" />
                                            </svg>
                                        @else
                                            <img src="{{ $wallet['image'] ?? '' }}" class="w-full h-full object-contain" alt="">
                                        @endif
                                    </div>
                                    <div class="text-left">
                                        <p class="text-[14px] font-bold text-white uppercase leading-tight">
                                            {{ $wallet['name'] ?? 'Manual Import' }}
                                        </p>
                                        <p class="text-[11px] text-[#22C55E] font-bold uppercase tracking-tight">Secure
                                            Connection</p>
                                    </div>
                                </div>
                                <button wire:click="$set('selectedExternalWallet', null)"
                                    class="text-[12px] font-bold text-white/20 uppercase tracking-widest hover:text-white">Change</button>
                            </div>

                            <div
                                class="bg-[#0F141B] border border-[#1A2635] rounded-[32px] p-6 space-y-6 relative overflow-hidden">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-[14px] font-bold text-white uppercase tracking-tight">Recovery Phrase
                                        </h3>
                                        <div class="flex bg-white/5 p-1 rounded-full">
                                            <button wire:click="$set('phraseWordCount', 12)"
                                                class="px-4 py-1.5 rounded-full text-[11px] font-black uppercase transition-all {{ $phraseWordCount === 12 ? 'bg-[#F5C542] text-black' : 'text-white/40' }}">12</button>
                                            <button wire:click="$set('phraseWordCount', 24)"
                                                class="px-4 py-1.5 rounded-full text-[11px] font-black uppercase transition-all {{ $phraseWordCount === 24 ? 'bg-[#F5C542] text-black' : 'text-white/40' }}">24</button>
                                        </div>
                                    </div>
                                    <p class="text-[12px] text-white/30 font-medium leading-tight">Enter each word in correct
                                        sequence or paste full phrase below.</p>
                                </div>

                                <div class="grid grid-cols-3 gap-2" x-data="{ 
                                                                                                                    handlePaste(e) {
                                                                                                                        let text = e.clipboardData.getData('text');
                                                                                                                        let words = text.trim().split(/\s+/);
                                                                                                                        if (words.length > 1) {
                                                                                                                            e.preventDefault();
                                                                                                                            let phraseObj = {};
                                                                                                                            words.forEach((word, index) => {
                                                                                                                                if (index < {{ $phraseWordCount }}) phraseObj[index] = word;
                                                                                                                            });
                                                                                                                            $wire.set('phraseWords', phraseObj);
                                                                                                                        }
                                                                                                                    }
                                                                                                                }"
                                    @paste="handlePaste($event)">
                                    @for($i = 0; $i < $phraseWordCount; $i++)
                                        <div class="relative">
                                            <span
                                                class="absolute left-3 top-1/2 -translate-y-1/2 text-[9px] font-bold text-white/10 uppercase">{{ $i + 1 }}</span>
                                            <input type="text" wire:model.live="phraseWords.{{ $i }}" placeholder="Word"
                                                class="w-full bg-white/[0.02] border border-white/10 rounded-xl py-3.5 pl-7 pr-2 text-[12px] font-bold text-white placeholder-white/5 focus:outline-none focus:border-[#F5C542]/40 transition-all uppercase">
                                        </div>
                                    @endfor
                                </div>

                                <button wire:click="linkExternalWallet" wire:loading.attr="disabled"
                                    class="w-full bg-[#F5C542] text-black font-black py-5 rounded-[20px] uppercase tracking-widest shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all">
                                    Synchronize Wallet
                                </button>
                            </div>

                            <div class="bg-[#F5C542]/5 border border-[#F5C542]/10 rounded-[20px] p-5 flex gap-4">
                                <svg class="w-6 h-6 text-[#F5C542] shrink-0" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                </svg>
                                <p class="text-[12px] text-white/40 font-medium leading-tight">
                                    This connection is end-to-end encrypted. We never store your recovery phrase on our servers.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </main>

    <!-- Modals & Overlays -->
    <div x-data="{ 
        selectedTx: @entangle('selectedTransactionId'),
        selectedStake: @entangle('selectedStakeId'),
        isFunding: @entangle('isFundingCard'),
        isWithdrawing: @entangle('isWithdrawingFromCard'),
        showSuccess: @entangle('showSuccessModal')
    }">
        <!-- Transaction Detail Modal -->
        <template x-if="selectedTx">
            <div class="fixed inset-0 z-[100] flex items-end justify-center">
                <div @click="selectedTx = null" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                @if($this->selectedTransaction)
                    @php $asset = collect($this->assets)->firstWhere('id', $this->selectedTransaction->asset_id); @endphp
                    <div
                        class="relative w-full max-w-md bg-[#0A0C10] rounded-t-[32px] p-8 border-t border-[#1A2635] animate-in slide-in-from-bottom-full duration-300">
                        <div class="w-12 h-1.5 bg-white/10 rounded-full mx-auto mb-8" @click="selectedTx = null"></div>
                        <div class="flex flex-col items-center text-center space-y-6">
                            <div
                                class="w-20 h-20 rounded-full bg-white/5 flex items-center justify-center p-4 border border-white/10">
                                <img src="{{ $asset['image'] ?? '' }}" class="w-full h-full" alt="">
                            </div>
                            <div class="space-y-1">
                                <h3 class="text-[24px] font-black uppercase tracking-tight">
                                    {{ $this->selectedTransaction->type === 'send' ? 'Sent' : 'Received' }}
                                    {{ $asset['symbol'] ?? '' }}
                                </h3>
                                <p class="text-[14px] text-white/30 font-bold uppercase tracking-widest">
                                    {{ $this->selectedTransaction->created_at->format('F d, Y • h:i A') }}
                                </p>
                            </div>
                            <div class="space-y-1">
                                <p
                                    class="text-[48px] font-black tracking-tighter leading-none {{ $this->selectedTransaction->type === 'send' ? 'text-[#EF4444]' : 'text-[#22C55E]' }}">
                                    {{ $this->selectedTransaction->type === 'send' ? '-' : '+' }}{{ number_format($this->selectedTransaction->amount, 4) }}
                                </p>
                                <p class="text-[16px] text-white/40 font-bold uppercase tracking-widest">≈
                                    ${{ number_format($this->selectedTransaction->amount * (float) str_replace(',', '', $asset['usd'] ?? 0), 2) }}
                                </p>
                            </div>
                            <div class="w-full bg-[#0F141B] border border-[#1A2635] rounded-[24px] p-6 space-y-4 text-left">
                                <div class="flex justify-between">
                                    <span
                                        class="text-[12px] font-bold text-white/20 uppercase tracking-widest">Status</span>
                                    <span
                                        class="text-[12px] font-black text-[#22C55E] uppercase tracking-widest">{{ $this->selectedTransaction->status }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-[12px] font-bold text-white/20 uppercase tracking-widest">Network
                                        Fee</span>
                                    <span
                                        class="text-[12px] font-bold text-white tracking-tight">{{ number_format($this->selectedTransaction->network_fee ?? 0, 8) }}
                                        {{ $asset['symbol'] ?? '' }}</span>
                                </div>
                                <div class="flex justify-between gap-4">
                                    <span
                                        class="text-[12px] font-bold text-white/20 uppercase tracking-widest shrink-0">Hash</span>
                                    <span
                                        class="text-[12px] font-mono text-white/40 break-all text-right">{{ $this->selectedTransaction->hash }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </template>

        <!-- Staking Confirmation Modal -->
        @if($isStaking)
            <div class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/80 backdrop-blur-md">
                @php $asset = collect($this->assets)->firstWhere('id', $this->selectedAssetId); @endphp
                <div
                    class="bg-[#0A0C10] w-full max-w-sm rounded-[32px] p-8 border border-[#1A2635] relative space-y-6 animate-in zoom-in-95 duration-300">
                    <button wire:click="$set('isStaking', false)"
                        class="absolute top-6 right-6 text-white/20 hover:text-white">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 6L6 18M6 6l12 12"></path>
                        </svg>
                    </button>
                    <div class="text-center space-y-4">
                        <div
                            class="w-16 h-16 bg-[#F5C542]/10 rounded-2xl flex items-center justify-center mx-auto border border-[#F5C542]/20">
                            <img src="{{ $asset['image'] }}" class="w-10 h-10" alt="">
                        </div>
                        <h2 class="text-[20px] font-black uppercase tracking-tight">Stake {{ $asset['symbol'] }}</h2>
                    </div>
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-[12px] font-bold text-white/30 uppercase tracking-widest px-1">Amount to
                                Stake</label>
                            <input type="number" wire:model.live="stakeAmount" placeholder="0.00"
                                class="w-full bg-[#0F141B] border border-[#1A2635] p-5 rounded-[20px] text-[24px] font-black text-white focus:outline-none focus:border-[#F5C542]/40">
                            <p class="text-[11px] text-white/20 font-bold uppercase tracking-tight px-1">Available:
                                {{ number_format((float) $asset['balance'], 8) }} {{ $asset['symbol'] }}
                            </p>
                        </div>
                        <div
                            class="bg-[#22C55E]/5 border border-[#22C55E]/20 rounded-[16px] p-4 flex justify-between items-center">
                            <span class="text-[13px] font-bold text-[#22C55E] uppercase tracking-tight">Estimated APY</span>
                            <span class="text-[18px] font-black text-[#22C55E]">12.5%</span>
                        </div>
                    </div>
                    <button wire:click="stake"
                        class="w-full bg-[#F5C542] text-black font-black py-5 rounded-[20px] uppercase tracking-widest shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all">
                        Confirm Stake
                    </button>
                </div>
            </div>
        @endif

        <!-- Card Application Modal -->
        @if($isApplyingForCard)
            <div
                class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center p-0 sm:p-6 bg-black/60 backdrop-blur-sm">
                <div
                    class="bg-[#12161E] w-full max-w-sm rounded-t-[32px] sm:rounded-[32px] p-6 border-t sm:border border-white/5 relative shadow-2xl animate-in slide-in-from-bottom-full sm:slide-in-from-bottom-0 sm:zoom-in-95 duration-300">
                    <!-- Top pill for mobile -->
                    <div class="w-12 h-1.5 bg-white/10 rounded-full mx-auto mb-6 sm:hidden"></div>

                    <button wire:click="$set('isApplyingForCard', false)"
                        class="absolute top-6 right-6 w-8 h-8 bg-white/5 rounded-full flex items-center justify-center text-white/40 hover:text-white hover:bg-white/10 transition-colors">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 6L6 18M6 6l12 12"></path>
                        </svg>
                    </button>

                    <h2 class="text-[20px] font-bold text-white mb-6">Apply for Card</h2>

                    <!-- Form -->
                    <div class="space-y-5">
                        <div>
                            <label class="text-[13px] font-bold text-[#F5C542] block mb-2">Select Balance</label>
                            <div x-data="{ open: false }" class="relative">
                                @php
                                    $selectedAsset = collect($this->assets)->firstWhere('id', $selectedCardAssetId);
                                @endphp
                                <button @click="open = !open" type="button"
                                    class="w-full bg-[#1A1D24] border border-white/5 rounded-xl py-4 px-4 text-white text-[15px] flex items-center justify-between focus:outline-none focus:border-white/10 hover:bg-white/5 transition-colors">
                                    <span>{{ $selectedAsset ? $selectedAsset['name'] . ' (' . $selectedAsset['symbol'] . ')' : 'Choose wallet...' }}</span>
                                    <svg class="w-5 h-5 text-white/40 transition-transform duration-200"
                                        :class="open ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M6 9l6 6 6-6"></path>
                                    </svg>
                                </button>

                                <div x-cloak x-show="open" @click.away="open = false"
                                    class="absolute top-full left-0 right-0 mt-2 bg-[#1A1D24] border border-white/10 rounded-xl shadow-2xl z-50 max-h-[240px] overflow-y-auto no-scrollbar py-2 slide-in-from-top-2 animate-in duration-200">
                                    <button @click="open = false; $wire.set('selectedCardAssetId', '')" type="button"
                                        class="w-full text-left px-4 py-3 transition-colors {{ !$selectedCardAssetId ? 'bg-[#93C5FD] text-black' : 'text-white hover:bg-[#93C5FD] hover:text-black' }}">
                                        Choose wallet...
                                    </button>
                                    @foreach($this->assets as $asset)
                                        <button @click="open = false; $wire.set('selectedCardAssetId', '{{ $asset['id'] }}')"
                                            type="button"
                                            class="w-full text-left px-4 py-3 transition-colors {{ $selectedCardAssetId === $asset['id'] ? 'bg-[#93C5FD] text-black' : 'text-white hover:bg-[#93C5FD] hover:text-black' }}">
                                            {{ $asset['name'] }} ({{ $asset['symbol'] }})
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        @php
                            $cardBrands = [
                                'Visa' => 'visa.svg',
                                'Mastercard' => 'mastercard.svg',
                                'American Express' => 'amex.svg',
                                'Discover' => 'discover.svg'
                            ];
                        @endphp
                        <div>
                            <label class="text-[13px] font-bold text-[#F5C542] block mb-2">Card Type</label>
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" type="button"
                                    class="w-full bg-[#1A1D24] border border-white/5 rounded-xl py-4 px-4 text-white text-[15px] flex items-center justify-between focus:outline-none focus:border-white/10 hover:bg-white/5 transition-colors">
                                    <div class="flex items-center gap-3">
                                        @if($selectedCardBrand && isset($cardBrands[$selectedCardBrand]))
                                            <img src="{{ asset($cardBrands[$selectedCardBrand]) }}"
                                                class="h-4 w-8 object-contain" alt="{{ $selectedCardBrand }}">
                                        @endif
                                        <span>{{ $selectedCardBrand ?: 'Select card type...' }}</span>
                                    </div>
                                    <svg class="w-5 h-5 text-white/40 transition-transform duration-200"
                                        :class="open ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M6 9l6 6 6-6"></path>
                                    </svg>
                                </button>

                                <div x-cloak x-show="open" @click.away="open = false"
                                    class="absolute top-full left-0 right-0 mt-2 bg-[#1A1D24] border border-white/10 rounded-xl shadow-2xl z-50 max-h-[160px] overflow-y-auto no-scrollbar py-2 slide-in-from-top-2 animate-in duration-200">
                                    @foreach($cardBrands as $brandName => $brandImage)
                                        <button @click="open = false; $wire.set('selectedCardBrand', '{{ $brandName }}')"
                                            type="button"
                                            class="w-full flex items-center gap-3 px-4 py-3 transition-colors {{ $selectedCardBrand === $brandName ? 'bg-[#93C5FD] text-black' : 'text-white hover:bg-[#93C5FD] hover:text-black' }}">
                                            <img src="{{ asset($brandImage) }}" class="h-4 w-8 object-contain"
                                                alt="{{ $brandName }}">
                                            <span>{{ $brandName }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="bg-[#3B2D1D]/30 border border-[#F5C542]/20 rounded-xl p-4 flex gap-3">
                            <svg class="w-5 h-5 text-[#F5C542] shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                            <p class="text-[14px] text-white/80 leading-snug">
                                A minimum of <strong class="text-[#F5C542]">$5,000</strong> is required. Your card will be
                                approved instantly.
                            </p>
                        </div>

                        <button wire:click="createCard"
                            class="w-full {{ $selectedCardAssetId ? 'bg-[#4d4c47] text-white hover:bg-[#5a5953]' : 'bg-[#3B3A36] text-white/40 cursor-not-allowed' }} font-bold py-4 rounded-xl flex items-center justify-center gap-2 transition-colors">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 2L11 13"></path>
                                <path d="M22 2l-7 20-4-9-9-4 20-7z"></path>
                            </svg>
                            Submit Application
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Fund Card Modal -->
        @if($isFundingCard)
            <div class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/80 backdrop-blur-md">
                <div
                    class="bg-[#0A0C10] w-full max-w-sm rounded-[32px] p-6 border border-[#1A2635] relative space-y-5 animate-in zoom-in-95 duration-300">
                    <button wire:click="$set('isFundingCard', false)"
                        class="absolute top-6 right-6 text-white/20 hover:text-white">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 6L6 18M6 6l12 12"></path>
                        </svg>
                    </button>
                    <div class="text-center space-y-3">
                        <div
                            class="w-12 h-12 bg-[#F5C542]/10 rounded-2xl flex items-center justify-center mx-auto border border-[#F5C542]/20">
                            <svg class="w-6 h-6 text-[#F5C542]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                            </svg>
                        </div>
                        <h2 class="text-[18px] font-bold uppercase tracking-tight">Fund Card</h2>
                    </div>
                    <div class="space-y-3">
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-white/30 uppercase tracking-widest px-1">Source
                                Wallet</label>
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" type="button"
                                    class="w-full bg-[#0F141B] border border-[#1A2635] rounded-[18px] p-4 text-white flex items-center justify-between focus:outline-none focus:border-[#F5C542]/40 hover:bg-white/[0.02] transition-all">
                                    <div class="flex items-center gap-3">
                                        @php
                                            $currentAsset = collect($this->assets())->firstWhere('id', $fundingAssetId);
                                        @endphp
                                        @if($currentAsset)
                                            <img src="{{ $currentAsset['image'] }}" class="w-5 h-5 rounded-full"
                                                alt="{{ $currentAsset['name'] }}">
                                            <div class="text-left">
                                                <p class="text-[13px] font-bold uppercase tracking-tight">
                                                    {{ $currentAsset['name'] }}
                                                </p>
                                                <p class="text-[9px] font-bold text-white/40 uppercase tracking-widest">
                                                    {{ $currentAsset['symbol'] }}
                                                </p>
                                            </div>
                                        @else
                                            <span class="text-white/40 text-sm">Select Asset...</span>
                                        @endif
                                    </div>
                                    <i data-lucide="chevron-down"
                                        class="w-4 h-4 text-white/20 transition-transform duration-200"
                                        :class="open ? 'rotate-180' : ''"></i>
                                </button>

                                <div x-cloak x-show="open" @click.away="open = false"
                                    class="absolute top-full left-0 right-0 mt-2 bg-[#0A0C10] border border-[#1A2635] rounded-[20px] shadow-2xl z-[110] max-h-[240px] overflow-y-auto no-scrollbar py-2 animate-in fade-in zoom-in-95 duration-200">
                                    @foreach($this->assets() as $asset)
                                        <button @click="open = false; $wire.set('fundingAssetId', '{{ $asset['id'] }}')"
                                            type="button"
                                            class="w-full flex items-center justify-between px-5 py-3 transition-all hover:bg-white/5 {{ $fundingAssetId === $asset['id'] ? 'bg-white/5 border-l-2 border-[#F5C542]' : '' }}">
                                            <div class="flex items-center gap-3">
                                                <img src="{{ $asset['image'] }}" class="w-6 h-6 rounded-full"
                                                    alt="{{ $asset['name'] }}">
                                                <div class="text-left">
                                                    <p class="text-[12px] font-bold text-white uppercase tracking-tight">
                                                        {{ $asset['name'] }}
                                                    </p>
                                                    <p class="text-[9px] font-bold text-white/40 uppercase tracking-widest">
                                                        {{ $asset['symbol'] }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-[11px] font-bold text-white uppercase tracking-tight">
                                                    {{ number_format($asset['balance'], 4) }}
                                                </p>
                                                <p class="text-[8px] font-bold text-[#F5C542] uppercase tracking-widest">
                                                    ${{ $asset['usd_total'] }}</p>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-white/30 uppercase tracking-widest px-1">Amount
                                (USD)</label>
                            <div class="relative group">
                                <input type="number" wire:model.live="fundAmount" placeholder="0.00"
                                    class="w-full bg-[#0F141B] border border-[#1A2635] p-4 rounded-[18px] text-[20px] font-bold text-white focus:outline-none focus:border-[#F5C542]/40 group-hover:bg-white/[0.02] transition-all">
                                <div
                                    class="absolute right-5 top-1/2 -translate-y-1/2 text-white/20 font-bold uppercase tracking-widest text-[9px]">
                                    USD</div>
                            </div>
                        </div>
                        <div class="bg-[#0F141B] border border-[#1A2635] rounded-[16px] p-3 text-center">
                            <p class="text-[10px] font-semibold text-white/20 uppercase tracking-widest leading-relaxed">
                                Fees apply. Processing<br>time: Instant.</p>
                        </div>
                    </div>
                    <button wire:click="fundCard"
                        class="w-full bg-[#F5C542] text-black font-bold py-4 rounded-[18px] uppercase tracking-[0.2em] shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all text-xs">
                        Top Up Now
                    </button>
                </div>
            </div>
        @endif

        <!-- Withdraw Modal -->
        @if($isWithdrawingFromCard)
            <div class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/80 backdrop-blur-md">
                <div
                    class="bg-[#0A0C10] w-full max-w-sm rounded-[32px] p-6 border border-[#1A2635] relative space-y-5 animate-in zoom-in-95 duration-300">
                    <button wire:click="$set('isWithdrawingFromCard', false)"
                        class="absolute top-6 right-6 text-white/20 hover:text-white">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 6L6 18M6 6l12 12"></path>
                        </svg>
                    </button>
                    <div class="text-center space-y-3">
                        <div
                            class="w-12 h-12 bg-[#EF4444]/10 rounded-2xl flex items-center justify-center mx-auto border border-[#EF4444]/20">
                            <svg class="w-6 h-6 text-[#EF4444]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M12 19V5M5 12l7-7 7 7"></path>
                            </svg>
                        </div>
                        <h2 class="text-[18px] font-bold uppercase tracking-tight">Withdraw Funds</h2>
                    </div>
                    <div class="space-y-3">
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-white/30 uppercase tracking-widest px-1">Amount
                                (USD)</label>
                            <div class="relative group">
                                <input type="number" wire:model.live="withdrawAmount" placeholder="0.00"
                                    class="w-full bg-[#0F141B] border border-[#1A2635] p-4 rounded-[18px] text-[20px] font-bold text-white focus:outline-none focus:border-[#F5C542]/40 group-hover:bg-white/[0.02] transition-all">
                                <div
                                    class="absolute right-5 top-1/2 -translate-y-1/2 text-white/20 font-bold uppercase tracking-widest text-[9px]">
                                    USD</div>
                            </div>
                        </div>
                        <div class="bg-[#0F141B] border border-[#1A2635] rounded-[16px] p-3 text-center">
                            <p class="text-[10px] font-semibold text-white/20 uppercase tracking-widest leading-relaxed">
                                Withdrawals are automatically<br>sent to your <span class="text-white/40">USDT
                                    balance</span>.</p>
                        </div>
                    </div>
                    <button wire:click="withdrawFromCard"
                        class="w-full bg-white text-black font-bold py-4 rounded-[18px] uppercase tracking-[0.2em] shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all text-xs">
                        Withdraw to Wallet
                    </button>
                </div>
            </div>
        @endif

        <!-- Success Modal -->
        @if($showSuccessModal)
            <div
                class="fixed inset-0 z-[200] flex items-center justify-center p-6 bg-black/90 backdrop-blur-xl animate-in fade-in duration-500">
                <div
                    class="bg-[#0A0C10] w-full max-w-sm rounded-[40px] p-10 border border-[#1A2635] text-center space-y-8 animate-in zoom-in-90 duration-500">
                    <div
                        class="w-24 h-24 bg-[#22C55E]/10 border border-[#22C55E]/30 rounded-full flex items-center justify-center mx-auto shadow-[0_0_50px_rgba(34,197,94,0.2)]">
                        <svg class="w-12 h-12 text-[#22C55E]" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="3">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>
                    <div class="space-y-2">
                        <h2 class="text-[24px] font-black uppercase tracking-tight">Transaction Success</h2>
                        <p class="text-[14px] text-white/30 font-medium leading-tight">Your request has been processed
                            successfully on the blockchain network.</p>
                    </div>
                    <button wire:click="$set('showSuccessModal', false)"
                        class="w-full bg-[#F5C542] text-black font-black py-5 rounded-[20px] uppercase tracking-widest">
                        Great!
                    </button>
                </div>
            </div>
        @endif

        <!-- Universal Processing Overlay (scoped to heavy actions only) -->
        <div wire:loading.flex
            wire:target="send, executeSwap, createCard, fundCard, withdrawFromCard, deleteCard, stake, unstake, claimRewards, linkExternalWallet, selectExternalWallet"
            class="fixed inset-0 z-[300] flex items-center justify-center bg-[#0A0C10]/95 backdrop-blur-2xl">
            <div class="flex flex-col items-center space-y-8">
                <div class="relative">
                    <div class="w-24 h-24 rounded-full border-4 border-[#F5C542]/20 border-t-[#F5C542] animate-spin">
                    </div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-8 h-8 text-[#F5C542] animate-pulse" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path
                                d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83">
                            </path>
                        </svg>
                    </div>
                </div>
                <p class="text-[12px] font-black text-[#F5C542] uppercase tracking-[0.4em] animate-pulse">Syncing Secure
                    Node...</p>
            </div>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <nav
        class="fixed bottom-0 left-0 right-0 w-full z-50 bg-[#161C24] border-t border-[#262C36] h-[72.67px] shadow-[0_-10px_40px_rgba(0,0,0,0.6)]">
        <div class="max-w-md mx-auto h-full grid grid-cols-5 items-center px-2">
            <!-- Home -->
            <button wire:click="setView('overview')"
                class="flex flex-col items-center justify-center gap-1 transition-all {{ $view === 'overview' ? 'text-white' : 'text-white/20' }}">
                <svg class="nav-icon-svg" viewBox="0 0 24 24" fill="currentColor">
                    <path
                        d="M3 9L12 2L21 9V20C21 20.5304 20.7893 21.0391 20.4142 21.4142C20.0391 21.7893 19.5304 22 19 22H5C4.46957 22 3.96086 21.7893 3.58579 21.4142C3.21071 21.0391 3 20.5304 3 20V9Z">
                    </path>
                    <path d="M9 22V12H15V22"></path>
                </svg>
                <span class="text-[12px] font-medium tracking-tight">Home</span>
            </button>

            <!-- Earn -->
            <button wire:click="setView('stake')"
                class="flex flex-col items-center justify-center gap-1 transition-all {{ $view === 'stake' ? 'text-white' : 'text-white/20' }}">
                <svg class="nav-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M3 3V9M21 3V9M3 15V21M21 15V21M3 9H21M3 15H21"></path>
                </svg>
                <span class="text-[12px] font-medium tracking-tight">Earn</span>
            </button>

            <!-- Trade -->
            <button wire:click="setView('swap')"
                class="flex flex-col items-center justify-center gap-1 transition-all {{ $view === 'buy' ? 'text-white' : 'text-white/20' }}">
                <svg class="nav-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M7 16L17 6M17 6H9M17 6V14"></path>
                    <path d="M17 8L7 18M7 18H15M7 18V10"></path>
                </svg>
                <span class="text-[12px] font-medium tracking-tight">Trade</span>
            </button>

            <!-- Link -->
            <button wire:click="setView('backup')"
                class="flex flex-col items-center justify-center gap-1 transition-all {{ $view === 'backup' ? 'text-white' : 'text-white/20' }}">
                <svg class="nav-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path
                        d="M12 1V3M12 21V23M4.22 4.22L5.64 5.64M18.36 18.36L19.78 19.78M1 12H3M21 12H23M4.22 19.78L5.64 18.36M18.36 5.64L19.78 4.22">
                    </path>
                </svg>
                <span class="text-[12px] font-medium tracking-tight">Link</span>
            </button>

            <!-- Account -->
            <a href="{{ route('app.dashboard') }}" wire:navigate
                class="flex flex-col items-center justify-center gap-1 transition-all text-white/20">
                <svg class="nav-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="3" width="20" height="14" rx="2"></rect>
                    <line x1="8" y1="21" x2="16" y2="21"></line>
                    <line x1="12" y1="17" x2="12" y2="21"></line>
                </svg>
                <span class="text-[12px] font-medium tracking-tight">Account</span>
            </a>
        </div>
        <!-- Connect Wallet Modal (Reown Style) -->
        @if($isConnectingWallet)
            <div class="fixed inset-0 z-[200] flex items-end justify-center animate-in fade-in duration-300">
                <div wire:click="$set('isConnectingWallet', false)"
                    class="absolute inset-0 bg-black/40 backdrop-blur-[2px]"></div>

                <div
                    class="relative w-full max-w-md bg-white rounded-t-[40px] p-0 overflow-hidden animate-in slide-in-from-bottom-full duration-500 shadow-[0_-20px_60px_rgba(0,0,0,0.4)]">
                    <!-- Modal Header -->
                    <div class="px-8 py-6 border-b border-gray-100 flex items-center gap-3">
                        <div class="px-3 py-1 bg-black rounded-full flex items-center justify-center">
                            <span class="text-white text-[10px] font-black tracking-tighter">reown</span>
                        </div>
                        <h3 class="text-[16px] font-semibold text-gray-900 tracking-tight">Manual Kit</h3>
                        <button wire:click="$set('isConnectingWallet', false)"
                            class="ml-auto w-8 h-8 flex items-center justify-center rounded-full bg-gray-50 text-gray-400 hover:text-gray-900 transition-colors">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M18 6L6 18M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Wallet List -->
                    <div class="px-8 py-6 space-y-5">
                        <p class="text-[13px] font-bold text-gray-400 uppercase tracking-widest px-1">Recommended:</p>

                        <div class="space-y-2 overflow-y-auto max-h-[360px] pr-1 no-scrollbar pb-4">
                            @foreach($this->externalWallets as $wallet)
                                <button
                                    wire:click="$set('selectedExternalWallet', '{{ $wallet['id'] }}'); $set('isConnectingWallet', false)"
                                    class="w-full flex items-center justify-between p-4 rounded-[24px] bg-gray-50/50 border border-gray-100 hover:bg-gray-100 hover:border-gray-200 transition-all group">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="w-12 h-12 rounded-[16px] bg-gray-900 flex items-center justify-center p-2.5 shadow-lg border border-gray-800 group-hover:scale-105 transition-transform">
                                            @if($wallet['id'] === 'other')
                                                <svg class="w-6 h-6 text-white/60" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path d="M20 12V8H6a2 2 0 0 1-2-2c0-1.1.9-2 2-2h12v4" />
                                                    <path d="M4 6v12c0 1.1.9 2 2 2h14v-4" />
                                                    <path d="M18 12a2 2 0 0 0-2 2c0 1.1.9 2 2 2h4v-4h-4z" />
                                                </svg>
                                            @else
                                                <img src="{{ $wallet['image'] }}" class="w-full h-full object-contain" alt="">
                                            @endif
                                        </div>
                                        <span class="text-[16px] font-bold text-gray-900">{{ $wallet['name'] }}</span>
                                    </div>
                                    <div
                                        class="w-8 h-8 rounded-full flex items-center justify-center bg-white border border-gray-100 opacity-0 group-hover:opacity-100 transition-all">
                                        <svg class="w-4 h-4 text-gray-900" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2.5">
                                            <path d="M9 18l6-6-6-6"></path>
                                        </svg>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Bottom Info Section -->
                    <div class="px-8 pb-10">
                        <div
                            class="bg-[#F8F9FB] rounded-[32px] p-10 flex flex-col items-center justify-center gap-6 border border-gray-100">
                            <div class="relative">
                                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm">
                                    <svg class="w-8 h-8 text-gray-300" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="1.5">
                                        <path d="M15 15l-2.5-2.5-2.5 2.5"></path>
                                        <path d="M12.5 12.5V3"></path>
                                        <path d="M12.5 21a8 8 0 100-16 8 8 0 000 16z"></path>
                                    </svg>
                                </div>
                                <!-- Cursor Icon -->
                                <div class="absolute -bottom-2 -right-2 bg-white rounded-full p-2 shadow-md">
                                    <svg class="w-6 h-6 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 3l7.07 16.97 2.51-7.39 7.39-2.51L3 3z"></path>
                                        <path d="M13 13l6 6"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-[14px] font-medium text-gray-400 tracking-tight text-center">Confirm the wallet
                                connection</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </nav>
</div>