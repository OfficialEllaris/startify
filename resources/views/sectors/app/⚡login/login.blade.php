<div class="flex min-h-screen items-center justify-center px-4 py-8 sm:py-12">
    <div class="w-full max-w-md">
        <!-- Logo Section -->
        <a href="{{ route('web.home') }}" class="flex items-center justify-center gap-4 mb-12 group/logo hover:opacity-80 transition-opacity">
            <div class="w-12 h-12 bg-primary rounded-2xl flex items-center justify-center text-primary-content shadow-2xl shadow-primary/30 rotate-3 transition-transform group-hover/logo:scale-110 group-hover/logo:rotate-6">
                <img src="{{ asset('favicon.ico') }}" alt="{{ config('app.name') }}" class="w-7 h-7 -rotate-3">
            </div>
            <div>
                <h1 class="text-2xl font-black tracking-tighter uppercase italic leading-none text-base-content">{{ config('app.name') }}</h1>
                <p class="text-[9px] font-black opacity-30 uppercase tracking-[0.2em] mt-0.5">Enterprise Access</p>
            </div>
        </a>

        <div class="bg-white rounded-[3rem] shadow-[0_30px_60px_rgba(0,0,0,0.05)] p-10 lg:p-12 border border-base-300/30">
            <div class="mb-10">
                <h2 class="text-3xl font-black tracking-tight text-base-content mb-2 text-center">Welcome Back</h2>
                <p class="text-sm font-medium text-base-content/40 text-center">Enter your credentials to manage your formations.</p>
            </div>

            <form wire:submit="login" class="space-y-6">
                <div class="form-control w-full">
                    <label for="email" class="px-2 mb-2">
                        <span class="text-[10px] font-black uppercase tracking-widest opacity-40">Email Address</span>
                    </label>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none opacity-40 group-focus-within:opacity-100 transition-opacity">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </span>
                        <input type="email" 
                            wire:model="email" 
                            id="email" 
                            placeholder="name@company.com"
                            class="input h-14 w-full bg-base-200/50 border-none rounded-2xl pl-12 focus:bg-base-200 focus:ring-4 focus:ring-primary/10 transition-all font-medium" 
                            required autofocus>
                    </div>
                    @error('email') <span class="text-error text-[10px] font-bold mt-2 px-2 uppercase tracking-wide">{{ $message }}</span> @enderror
                </div>

                <div class="form-control w-full">
                    <div class="flex justify-between items-center px-2 mb-2">
                        <span class="text-[10px] font-black uppercase tracking-widest opacity-40">Security Key</span>
                        <a href="{{ route('app.forgot-password') }}" class="text-[10px] font-black uppercase tracking-widest text-primary hover:opacity-70 transition-opacity">Reset?</a>
                    </div>
                    <div class="relative group">
                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none opacity-40 group-focus-within:opacity-100 transition-opacity">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                        </span>
                        <input type="password" 
                            wire:model="password" 
                            id="password" 
                            placeholder="••••••••"
                            class="input h-14 w-full bg-base-200/50 border-none rounded-2xl pl-12 focus:bg-base-200 focus:ring-4 focus:ring-primary/10 transition-all font-medium" 
                            required>
                    </div>
                    @error('password') <span class="text-error text-[10px] font-bold mt-2 px-2 uppercase tracking-wide">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-between px-2 pt-2">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input type="checkbox" wire:model="remember" id="remember" class="checkbox checkbox-primary checkbox-sm rounded-lg">
                        <span class="text-xs font-bold text-base-content/50 group-hover:text-base-content transition-colors">Keep me active</span>
                    </label>
                </div>

                <div class="pt-4">
                    <button type="submit" class="btn btn-primary btn-block h-14 rounded-2xl font-black text-sm uppercase tracking-widest shadow-2xl shadow-primary/30 group flex-nowrap whitespace-nowrap">
                        <span wire:loading.remove wire:target="login" class="flex items-center justify-center gap-2 w-full">
                            Initialize Session
                            <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                        </span>
                        <span wire:loading wire:target="login" class="loading loading-spinner loading-sm"></span>
                    </button>
                </div>
                
                <div class="text-center pt-2 border-t border-base-300/30 mt-6 pt-6">
                    <p class="text-xs font-bold text-base-content/50">
                        Don't have an account? 
                        <a href="{{ route('app.onboarding') }}" class="text-primary hover:text-primary-focus hover:underline transition-colors ml-1">Start a new entity</a>
                    </p>
                </div>
            </form>
        </div>
        
        <div class="mt-8 text-center">
            <p class="text-[10px] font-bold text-base-content/30 uppercase tracking-[0.2em]">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</div>