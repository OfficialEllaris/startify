<div class="flex min-h-screen items-center justify-center px-3 py-6 sm:p-4 sm:py-12">
    <div class="w-full max-w-2xl">
        <!-- Logo Section -->
        <a href="{{ route('web.home') }}" class="flex items-center justify-center gap-4 sm:gap-4 mb-8 sm:mb-10 group/logo hover:opacity-80 transition-opacity">
            <div class="w-14 h-14 sm:w-14 sm:h-14 bg-primary rounded-2xl flex items-center justify-center text-primary-content shadow-2xl shadow-primary/30 rotate-3 transition-transform group-hover/logo:scale-110 group-hover/logo:rotate-6">
                <img src="{{ asset('favicon.ico') }}" alt="{{ config('app.name') }}" class="w-8 h-8 sm:w-8 sm:h-8 -rotate-3">
            </div>
            <div>
                <h1 class="text-3xl sm:text-3xl font-black tracking-tighter uppercase italic leading-none text-base-content">{{ config('app.name') }}</h1>
                <p class="text-[9px] sm:text-[10px] font-black opacity-30 uppercase tracking-[0.2em] mt-0.5">Business Formation</p>
            </div>
        </a>

        {{-- Step Indicator --}}
        @if($step < ($isAuthenticated ? 6 : 7))
            <div class="flex items-center justify-center gap-1 sm:gap-2 mb-6 sm:mb-10 px-2">
                @php
                    $steps = ['State', 'Details', 'Agent', 'Contact', 'Review'];
                    if (!$isAuthenticated) { $steps[] = 'Account'; }
                @endphp
                @foreach($steps as $i => $label)
                    @php $num = $i + 1; @endphp
                    <div class="flex items-center gap-1 sm:gap-2">
                        <div class="flex items-center gap-1.5 sm:gap-2 {{ $step >= $num ? 'opacity-100' : 'opacity-60' }} transition-opacity duration-300">
                            <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg sm:rounded-xl flex items-center justify-center text-[10px] sm:text-xs font-black transition-all duration-300
                                {{ $step === $num ? 'bg-primary text-primary-content shadow-lg shadow-primary/30 scale-110' : ($step > $num ? 'bg-primary/20 text-primary' : 'bg-base-300 text-base-content/60') }}">
                                @if($step > $num)
                                    <i data-lucide="check" class="w-3 h-3 sm:w-3.5 sm:h-3.5"></i>
                                @else
                                    {{ $num }}
                                @endif
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider hidden sm:inline {{ $step === $num ? 'text-base-content' : 'text-base-content/60' }}">{{ $label }}</span>
                        </div>
                        @if(!$loop->last)
                            <div class="w-3 sm:w-6 h-px {{ $step > $num ? 'bg-primary/30' : 'bg-base-300' }} transition-colors duration-300"></div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Main Card --}}
        <div class="bg-white rounded-[2rem] sm:rounded-[3rem] shadow-[0_30px_60px_rgba(0,0,0,0.05)] p-6 sm:p-10 lg:p-12 border border-base-300/30">

            {{-- Step Header --}}
            <div class="mb-6 sm:mb-10 text-center">
                <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-base-content mb-2">
                    @if($step === 1) Choose Your State
                    @elseif($step === 2) Business Details
                    @elseif($step === 3) Registered Agent
                    @elseif($step === 4) Contact Information
                    @elseif($step === 5) Review & Confirm
                    @elseif($step === 6) Secure Your Account
                    @elseif($step === 7) You're All Set!
                    @endif
                </h2>
                <p class="text-xs sm:text-sm font-medium text-base-content/60">
                    @if($step === 1) Select the state where your business will be officially formed.
                    @elseif($step === 2) Tell us about the entity you'd like to create.
                    @elseif($step === 3) Every business needs a registered agent for legal documents.
                    @elseif($step === 4) How can we reach you regarding your filing?
                    @elseif($step === 5) Please verify all details before submitting your application.
                    @elseif($step === 6) Create a password to finalize your application.
                    @elseif($step === 7) We've sent you a verification email.
                    @endif
                </p>
            </div>

            <form wire:submit.prevent="{{ ($isAuthenticated && $step === 5) || (!$isAuthenticated && $step === 6) ? 'submit' : 'nextStep' }}">

                {{-- Step 1: State --}}
                @if($step === 1)
                    <div class="space-y-4">
                        <div class="form-control w-full">
                            <label class="px-2 mb-2">
                                <span class="text-[10px] font-black uppercase tracking-widest opacity-40">State of Formation</span>
                            </label>
                            <div x-data="{
                                open: false,
                                search: '',
                                options: @js($this->stateOptions),
                                get filtered() {
                                    if (!this.search) return this.options;
                                    return this.options.filter(s => s.toLowerCase().includes(this.search.toLowerCase()));
                                }
                            }" class="relative">
                                <button type="button" @click="open = !open" class="flex items-center h-14 w-full bg-base-200/50 rounded-2xl pl-4 pr-4 font-medium transition-all hover:bg-base-200 focus:bg-base-200 focus:ring-4 focus:ring-primary/10 text-left gap-3">
                                    <span class="opacity-40"><i data-lucide="map-pin" class="w-4 h-4"></i></span>
                                    <span class="flex-1" :class="$wire.state ? 'text-base-content' : 'text-base-content/40'" x-text="$wire.state || 'Select a state'"></span>
                                    <i data-lucide="chevron-down" class="w-4 h-4 opacity-30 transition-transform duration-200" :class="open && 'rotate-180'"></i>
                                </button>
                                <div x-show="open" @click.outside="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                    class="absolute z-50 mt-2 w-full bg-white rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.12)] border border-base-300/30 overflow-hidden">
                                    <div class="p-3">
                                        <div class="relative">
                                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none opacity-40">
                                                <i data-lucide="search" class="w-3.5 h-3.5"></i>
                                            </span>
                                            <input type="text" x-model="search" @keydown.escape="open = false" x-ref="searchInput" x-effect="if(open) $nextTick(() => $refs.searchInput.focus())" placeholder="Search states..."
                                                class="input input-sm w-full bg-base-200/50 border-none rounded-xl pl-9 text-sm font-medium focus:ring-2 focus:ring-primary/10">
                                        </div>
                                    </div>
                                    <div class="max-h-52 overflow-y-auto px-2 pb-2">
                                        <template x-for="option in filtered" :key="option">
                                            <button type="button" @click="$wire.set('state', option); open = false; search = '';"
                                                class="w-full text-left px-3 py-2.5 rounded-xl text-sm font-medium transition-all hover:bg-primary/5 hover:text-primary flex items-center justify-between"
                                                :class="$wire.state === option ? 'bg-primary/10 text-primary font-bold' : 'text-base-content'">
                                                <span x-text="option"></span>
                                                <i x-show="$wire.state === option" data-lucide="check" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </template>
                                        <div x-show="filtered.length === 0" class="px-3 py-4 text-center text-sm text-base-content/40 font-medium">No states found</div>
                                    </div>
                                </div>
                            </div>
                            @error('state') <span class="text-error text-[10px] font-bold mt-2 px-2 uppercase tracking-wide">{{ $message }}</span> @enderror
                        </div>
                    </div>
                @endif

                {{-- Step 2: Business Details --}}
                @if($step === 2)
                    <div class="space-y-6">
                        <div class="form-control w-full">
                            <label class="px-2 mb-2">
                                <span class="text-[10px] font-black uppercase tracking-widest opacity-40">Business Name</span>
                            </label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none opacity-40 group-focus-within:opacity-100 transition-opacity">
                                    <i data-lucide="building-2" class="w-4 h-4"></i>
                                </span>
                                <input type="text" wire:model="business_name" placeholder="e.g. Acme Corporation LLC"
                                    class="input h-14 w-full bg-base-200/50 border-none rounded-2xl pl-12 focus:bg-base-200 focus:ring-4 focus:ring-primary/10 transition-all font-medium">
                            </div>
                            @error('business_name') <span class="text-error text-[10px] font-bold mt-2 px-2 uppercase tracking-wide">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-control w-full">
                            <label class="px-2 mb-2">
                                <span class="text-[10px] font-black uppercase tracking-widest opacity-40">Entity Type</span>
                            </label>
                            <div x-data="{ open: false, labels: @js(collect(\App\Enums\BusinessType::cases())->mapWithKeys(fn($case) => [$case->value => $case->label()])) }" class="relative">
                                <button type="button" @click="open = !open" class="flex items-center h-14 w-full bg-base-200/50 rounded-2xl pl-4 pr-4 font-medium transition-all hover:bg-base-200 focus:bg-base-200 focus:ring-4 focus:ring-primary/10 text-left gap-3">
                                    <span class="opacity-40"><i data-lucide="briefcase" class="w-4 h-4"></i></span>
                                    <span class="flex-1" :class="$wire.business_type ? 'text-base-content' : 'text-base-content/40'" x-text="$wire.business_type ? labels[$wire.business_type] : 'Select entity type'"></span>
                                    <i data-lucide="chevron-down" class="w-4 h-4 opacity-30 transition-transform duration-200" :class="open && 'rotate-180'"></i>
                                </button>
                                <div x-show="open" @click.outside="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                    class="absolute z-50 mt-2 w-full bg-white rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.12)] border border-base-300/30 overflow-hidden">
                                    <div class="px-2 py-2">
                                        @foreach(\App\Enums\BusinessType::cases() as $type)
                                            <button type="button" @click="$wire.set('business_type', '{{ $type->value }}'); open = false;"
                                                class="w-full text-left px-3 py-3 rounded-xl text-sm font-medium transition-all hover:bg-primary/5 hover:text-primary flex items-center justify-between"
                                                :class="$wire.business_type === '{{ $type->value }}' ? 'bg-primary/10 text-primary font-bold' : 'text-base-content'">
                                                <span>{{ $type->label() }}</span>
                                                <i x-show="$wire.business_type === '{{ $type->value }}'" data-lucide="check" class="w-3.5 h-3.5"></i>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @error('business_type') <span class="text-error text-[10px] font-bold mt-2 px-2 uppercase tracking-wide">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-control w-full">
                            <label class="px-2 mb-2">
                                <span class="text-[10px] font-black uppercase tracking-widest opacity-40">Business Purpose</span>
                            </label>
                            <div class="relative group">
                                <span class="absolute top-4 left-0 pl-4 flex items-start pointer-events-none opacity-40 group-focus-within:opacity-100 transition-opacity">
                                    <i data-lucide="file-text" class="w-4 h-4"></i>
                                </span>
                                <textarea wire:model="business_purpose" rows="3" placeholder="Briefly describe your business activities..."
                                    class="textarea w-full bg-base-200/50 border-none rounded-2xl pl-12 pt-4 focus:bg-base-200 focus:ring-4 focus:ring-primary/10 transition-all font-medium resize-none"></textarea>
                            </div>
                            @error('business_purpose') <span class="text-error text-[10px] font-bold mt-2 px-2 uppercase tracking-wide">{{ $message }}</span> @enderror
                        </div>
                    </div>
                @endif

                {{-- Step 3: Registered Agent --}}
                @if($step === 3)
                    <div class="space-y-6">
                        <label class="flex items-start gap-5 p-6 bg-primary/5 border-2 border-primary/20 rounded-3xl cursor-pointer hover:border-primary/40 transition-all group">
                            <input type="checkbox" wire:model.live="use_registrar_agent" class="checkbox checkbox-primary checkbox-md rounded-xl mt-0.5">
                            <div>
                                <span class="text-sm font-black text-base-content block">Use {{ config('app.name') }} as my Registered Agent</span>
                                <span class="text-xs font-medium text-base-content/40 mt-1 block leading-relaxed">We'll handle all legal correspondence, compliance reminders, and document forwarding on your behalf.</span>
                            </div>
                        </label>

                        @if(!$use_registrar_agent)
                            <div class="space-y-6 pt-2">
                                <div class="form-control w-full">
                                    <label class="px-2 mb-2">
                                        <span class="text-[10px] font-black uppercase tracking-widest opacity-40">Agent Name</span>
                                    </label>
                                    <div class="relative group">
                                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none opacity-40 group-focus-within:opacity-100 transition-opacity">
                                            <i data-lucide="user" class="w-4 h-4"></i>
                                        </span>
                                        <input type="text" wire:model="agent_name" placeholder="Full legal name of agent"
                                            class="input h-14 w-full bg-base-200/50 border-none rounded-2xl pl-12 focus:bg-base-200 focus:ring-4 focus:ring-primary/10 transition-all font-medium">
                                    </div>
                                    @error('agent_name') <span class="text-error text-[10px] font-bold mt-2 px-2 uppercase tracking-wide">{{ $message }}</span> @enderror
                                </div>
                                <div class="form-control w-full">
                                    <label class="px-2 mb-2">
                                        <span class="text-[10px] font-black uppercase tracking-widest opacity-40">Agent Address</span>
                                    </label>
                                    <div class="relative group">
                                        <span class="absolute top-4 left-0 pl-4 flex items-start pointer-events-none opacity-40 group-focus-within:opacity-100 transition-opacity">
                                            <i data-lucide="map" class="w-4 h-4"></i>
                                        </span>
                                        <textarea wire:model="agent_address" rows="3" placeholder="Agent's physical street address"
                                            class="textarea w-full bg-base-200/50 border-none rounded-2xl pl-12 pt-4 focus:bg-base-200 focus:ring-4 focus:ring-primary/10 transition-all font-medium resize-none"></textarea>
                                    </div>
                                    @error('agent_address') <span class="text-error text-[10px] font-bold mt-2 px-2 uppercase tracking-wide">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Step 4: Contact Details --}}
                @if($step === 4)
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="form-control w-full">
                                <label class="px-2 mb-2">
                                    <span class="text-[10px] font-black uppercase tracking-widest opacity-40">Full Name</span>
                                </label>
                                <div class="relative group">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none opacity-40 group-focus-within:opacity-100 transition-opacity">
                                        <i data-lucide="user" class="w-4 h-4"></i>
                                    </span>
                                    <input type="text" wire:model="user_name"
                                        class="input h-14 w-full bg-base-200/50 border-none rounded-2xl pl-12 focus:bg-base-200 focus:ring-4 focus:ring-primary/10 transition-all font-medium">
                                </div>
                                @error('user_name') <span class="text-error text-[10px] font-bold mt-2 px-2 uppercase tracking-wide">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-control w-full">
                                <label class="px-2 mb-2">
                                    <span class="text-[10px] font-black uppercase tracking-widest opacity-40">Email Address</span>
                                </label>
                                <div class="relative group">
                                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none opacity-40 group-focus-within:opacity-100 transition-opacity">
                                        <i data-lucide="mail" class="w-4 h-4"></i>
                                    </span>
                                    <input type="email" wire:model="user_email" {{ $isAuthenticated ? 'readonly' : '' }}
                                        class="input h-14 w-full bg-base-200/50 border-none rounded-2xl pl-12 focus:bg-base-200 focus:ring-4 focus:ring-primary/10 transition-all font-medium {{ $isAuthenticated ? 'opacity-60 cursor-not-allowed' : '' }}">
                                </div>
                                @error('user_email') <span class="text-error text-[10px] font-bold mt-2 px-2 uppercase tracking-wide">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-control w-full">
                            <label class="px-2 mb-2">
                                <span class="text-[10px] font-black uppercase tracking-widest opacity-40">Phone Number</span>
                            </label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none opacity-40 group-focus-within:opacity-100 transition-opacity">
                                    <i data-lucide="phone" class="w-4 h-4"></i>
                                </span>
                                <input type="tel" wire:model="user_phone" placeholder="+1 (555) 000-0000"
                                    class="input h-14 w-full bg-base-200/50 border-none rounded-2xl pl-12 focus:bg-base-200 focus:ring-4 focus:ring-primary/10 transition-all font-medium">
                            </div>
                            @error('user_phone') <span class="text-error text-[10px] font-bold mt-2 px-2 uppercase tracking-wide">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-control w-full">
                            <label class="px-2 mb-2">
                                <span class="text-[10px] font-black uppercase tracking-widest opacity-40">Physical Address</span>
                            </label>
                            <div class="relative group">
                                <span class="absolute top-4 left-0 pl-4 flex items-start pointer-events-none opacity-40 group-focus-within:opacity-100 transition-opacity">
                                    <i data-lucide="home" class="w-4 h-4"></i>
                                </span>
                                <textarea wire:model="user_address" rows="2" placeholder="Your current street address"
                                    class="textarea w-full bg-base-200/50 border-none rounded-2xl pl-12 pt-4 focus:bg-base-200 focus:ring-4 focus:ring-primary/10 transition-all font-medium resize-none"></textarea>
                            </div>
                            @error('user_address') <span class="text-error text-[10px] font-bold mt-2 px-2 uppercase tracking-wide">{{ $message }}</span> @enderror
                        </div>
                    </div>
                @endif

                {{-- Step 5: Review --}}
                @if($step === 5)
                    <div class="space-y-6">
                        <div class="bg-base-200/50 rounded-3xl p-6 lg:p-8 space-y-6">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest opacity-30 mb-3">Business Details</p>
                                <div class="grid grid-cols-2 gap-y-3 text-sm">
                                    <span class="font-bold text-base-content/50">Name</span>
                                    <span class="font-medium">{{ $business_name }}</span>
                                    <span class="font-bold text-base-content/50">Type</span>
                                    <span class="font-medium">{{ \App\Enums\BusinessType::tryFrom($business_type)?->label() }}</span>
                                    <span class="font-bold text-base-content/50">State</span>
                                    <span class="font-medium">{{ $state }}</span>
                                    <span class="font-bold text-base-content/50">Purpose</span>
                                    <span class="font-medium">{{ $business_purpose }}</span>
                                </div>
                            </div>
                            <div class="border-t border-base-300/50 pt-6">
                                <p class="text-[10px] font-black uppercase tracking-widest opacity-30 mb-3">Registered Agent</p>
                                @if($use_registrar_agent)
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-primary/10 rounded-xl flex items-center justify-center">
                                            <i data-lucide="shield-check" class="w-4 h-4 text-primary"></i>
                                        </div>
                                        <span class="text-sm font-bold text-primary">{{ config('app.name') }} Registered Agent Service</span>
                                    </div>
                                @else
                                    <div class="grid grid-cols-2 gap-y-3 text-sm">
                                        <span class="font-bold text-base-content/50">Name</span>
                                        <span class="font-medium">{{ $agent_name }}</span>
                                        <span class="font-bold text-base-content/50">Address</span>
                                        <span class="font-medium">{{ $agent_address }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="border-t border-base-300/50 pt-6">
                                <p class="text-[10px] font-black uppercase tracking-widest opacity-30 mb-3">Contact Information</p>
                                <div class="grid grid-cols-2 gap-y-3 text-sm">
                                    <span class="font-bold text-base-content/50">Name</span>
                                    <span class="font-medium">{{ $user_name }}</span>
                                    <span class="font-bold text-base-content/50">Email</span>
                                    <span class="font-medium">{{ $user_email }}</span>
                                    <span class="font-bold text-base-content/50">Phone</span>
                                    <span class="font-medium">{{ $user_phone }}</span>
                                    <span class="font-bold text-base-content/50">Address</span>
                                    <span class="font-medium">{{ $user_address }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Step 6: Create Account (Guests Only) --}}
                @if($step === 6 && !$isAuthenticated)
                    <div class="space-y-6">
                        <p class="text-xs sm:text-sm font-medium text-base-content/40 text-center">Create a password to secure your account and complete your filing.</p>
                        <div class="form-control w-full">
                            <label class="px-2 mb-2">
                                <span class="text-[10px] font-black uppercase tracking-widest opacity-40">Password</span>
                            </label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none opacity-40 group-focus-within:opacity-100 transition-opacity">
                                    <i data-lucide="lock" class="w-4 h-4"></i>
                                </span>
                                <input type="password" wire:model="password" placeholder="Minimum 8 characters"
                                    class="input h-14 w-full bg-base-200/50 border-none rounded-2xl pl-12 focus:bg-base-200 focus:ring-4 focus:ring-primary/10 transition-all font-medium">
                            </div>
                            @error('password') <span class="text-error text-[10px] font-bold mt-2 px-2 uppercase tracking-wide">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-control w-full">
                            <label class="px-2 mb-2">
                                <span class="text-[10px] font-black uppercase tracking-widest opacity-40">Confirm Password</span>
                            </label>
                            <div class="relative group">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none opacity-40 group-focus-within:opacity-100 transition-opacity">
                                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                                </span>
                                <input type="password" wire:model="password_confirmation" placeholder="Re-enter your password"
                                    class="input h-14 w-full bg-base-200/50 border-none rounded-2xl pl-12 focus:bg-base-200 focus:ring-4 focus:ring-primary/10 transition-all font-medium">
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Step 7: Success --}}
                @if($step === 7)
                    <div class="text-center py-6">
                        <div class="w-20 h-20 bg-success/10 rounded-[1.5rem] flex items-center justify-center mx-auto mb-6">
                            <i data-lucide="mail-check" class="w-10 h-10 text-success"></i>
                        </div>
                        <h3 class="text-xl font-black tracking-tight text-base-content mb-2">Check Your Inbox</h3>
                        <p class="text-sm font-medium text-base-content/40 max-w-sm mx-auto">We've sent a verification link to <strong class="text-base-content font-bold">{{ $user_email }}</strong>. Click it to complete your filing.</p>
                    </div>
                @endif

                {{-- Navigation --}}
                @if($step < ($isAuthenticated ? 6 : 7))
                    <div class="flex flex-col sm:flex-row {{ $step > 1 || $isAuthenticated ? 'sm:justify-between' : 'sm:justify-end' }} items-stretch sm:items-center mt-6 sm:mt-10 pt-6 sm:pt-8 border-t border-base-300/30 gap-3">
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                            @if($step > 1)
                                <button type="button" wire:click="previousStep" class="btn btn-ghost h-12 px-6 rounded-2xl font-black text-xs uppercase tracking-widest gap-2 group w-full sm:w-auto">
                                    <i data-lucide="arrow-left" class="w-4 h-4 group-hover:-translate-x-1 transition-transform"></i>
                                    Back
                                </button>
                            @endif

                            @if($isAuthenticated && $step === 1)
                                <a href="{{ route('app.dashboard') }}" wire:navigate class="btn h-12 px-6 rounded-2xl font-black text-xs uppercase tracking-widest gap-2 group w-full sm:w-auto">
                                    <i data-lucide="layout-dashboard" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                                    Dashboard
                                </a>
                            @endif
                        </div>

                        @if(($isAuthenticated && $step === 5) || (!$isAuthenticated && $step === 6))
                            <button type="submit" 
                                    wire:loading.attr="disabled"
                                    class="btn btn-primary h-12 rounded-2xl font-black text-xs uppercase tracking-widest shadow-2xl shadow-primary/30 gap-2 group px-8 w-full sm:w-auto disabled:opacity-70 disabled:cursor-not-allowed">
                                <span wire:loading.remove wire:target="submit, nextStep" class="flex items-center gap-2 justify-center">
                                    Submit Application
                                    <i data-lucide="check" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                                </span>
                                <span wire:loading wire:target="submit, nextStep" class="flex items-center gap-2 justify-center">
                                    Processing...
                                    <span class="loading loading-spinner loading-xs"></span>
                                </span>
                            </button>
                        @elseif($isAuthenticated && $step === 5)
                            {{-- This shouldn't be reached due to the previous condition, but for clarity --}}
                        @else
                            <button type="submit" 
                                    wire:loading.attr="disabled"
                                    class="btn btn-primary h-12 rounded-2xl font-black text-xs uppercase tracking-widest shadow-2xl shadow-primary/30 gap-2 group px-8 w-full sm:w-auto disabled:opacity-70 disabled:cursor-not-allowed">
                                <span wire:loading.remove wire:target="submit, nextStep" class="flex items-center gap-2 justify-center">
                                    Continue
                                    <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                                </span>
                                <span wire:loading wire:target="submit, nextStep" class="flex items-center gap-2 justify-center">
                                    Loading...
                                    <span class="loading loading-spinner loading-xs"></span>
                                </span>
                            </button>
                        @endif
                    </div>
                @endif
                
                @if(!$isAuthenticated && $step < 7)
                    <div class="text-center pt-2 border-t border-base-300/30 mt-6 pt-6">
                        <p class="text-xs font-bold text-base-content/50">
                            Already have an account? 
                            <a href="{{ route('app.login') }}" class="text-primary hover:text-primary-focus hover:underline transition-colors ml-1">Sign in to your dashboard</a>
                        </p>
                    </div>
                @endif
            </form>
        </div>

        <div class="mt-8 text-center">
            <p class="text-[10px] font-bold text-base-content/20 uppercase tracking-[0.2em]">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</div>