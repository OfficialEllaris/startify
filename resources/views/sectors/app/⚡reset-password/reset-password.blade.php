<div class="flex min-h-screen items-center justify-center px-4 py-8 sm:py-12">
    <div class="w-full max-w-md">
        <!-- Logo Section -->
        <div class="flex items-center justify-center gap-4 mb-12">
            <div class="w-12 h-12 bg-primary rounded-2xl flex items-center justify-center text-primary-content shadow-2xl shadow-primary/30 rotate-3">
                <i data-lucide="zap" class="w-7 h-7 -rotate-3"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black tracking-tighter uppercase italic leading-none">{{ config('app.name') }}</h1>
                <p class="text-[9px] font-black opacity-30 uppercase tracking-[0.2em] mt-0.5">Account Recovery</p>
            </div>
        </div>

        <div class="bg-white rounded-[3rem] shadow-[0_30px_60px_rgba(0,0,0,0.05)] p-10 lg:p-12 border border-base-300/30">
            @if ($isValidToken)
                <div class="mb-10 text-center">
                    <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <i data-lucide="shield-check" class="w-7 h-7 text-primary"></i>
                    </div>
                    <h2 class="text-3xl font-black tracking-tight text-base-content mb-2">Reset Password</h2>
                    <p class="text-sm font-medium text-base-content/40">Choose a strong new password for your account.</p>
                </div>

                @if (session('status'))
                    <div class="bg-success/10 border border-success/20 rounded-2xl p-4 mb-6 flex items-center gap-3">
                        <i data-lucide="check-circle" class="w-5 h-5 text-success flex-shrink-0"></i>
                        <span class="text-sm font-medium text-success">{{ session('status') }}</span>
                    </div>
                @endif

                <form wire:submit="resetPassword" class="space-y-6">
                    <input type="hidden" wire:model="email">

                    <div class="form-control w-full">
                        <label for="password" class="px-2 mb-2">
                            <span class="text-[10px] font-black uppercase tracking-widest opacity-40">New Password</span>
                        </label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none opacity-40 group-focus-within:opacity-100 transition-opacity">
                                <i data-lucide="lock" class="w-4 h-4"></i>
                            </span>
                            <input type="password" wire:model="password" id="password" placeholder="••••••••"
                                class="input h-14 w-full bg-base-200/30 border-none rounded-2xl pl-12 focus:bg-white focus:ring-4 focus:ring-primary/5 transition-all font-medium" required>
                        </div>
                        @error('password') <span class="text-error text-xs mt-1 px-2 font-medium">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-control w-full">
                        <label for="password_confirmation" class="px-2 mb-2">
                            <span class="text-[10px] font-black uppercase tracking-widest opacity-40">Confirm Password</span>
                        </label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none opacity-40 group-focus-within:opacity-100 transition-opacity">
                                <i data-lucide="lock-keyhole" class="w-4 h-4"></i>
                            </span>
                            <input type="password" wire:model="password_confirmation" id="password_confirmation" placeholder="••••••••"
                                class="input h-14 w-full bg-base-200/30 border-none rounded-2xl pl-12 focus:bg-white focus:ring-4 focus:ring-primary/5 transition-all font-medium" required>
                        </div>
                        @error('password_confirmation') <span class="text-error text-xs mt-1 px-2 font-medium">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-full h-14 rounded-2xl text-sm font-black uppercase tracking-widest shadow-xl shadow-primary/20">
                        <span wire:loading.remove wire:target="resetPassword" class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4"></i> Reset Password
                        </span>
                        <span wire:loading wire:target="resetPassword" class="loading loading-spinner loading-sm"></span>
                    </button>

                    <div class="text-center">
                        <a href="{{ route('app.login') }}" class="inline-flex items-center gap-2 text-xs font-bold text-base-content/40 hover:text-primary transition-colors">
                            <i data-lucide="arrow-left" class="w-3 h-3"></i> Back to login
                        </a>
                    </div>
                </form>
            @else
                <div class="flex flex-col items-center text-center">
                    <div class="w-16 h-16 bg-error/10 rounded-2xl flex items-center justify-center mb-6">
                        <i data-lucide="shield-x" class="w-9 h-9 text-error"></i>
                    </div>
                    <h2 class="text-2xl font-black tracking-tight text-error mb-2">Invalid Link</h2>
                    <p class="text-sm font-medium text-base-content/40 mb-8">This password reset link is invalid or has expired.</p>
                    <a href="{{ route('app.forgot-password') }}" class="btn btn-primary w-full h-14 rounded-2xl text-sm font-black uppercase tracking-widest shadow-xl shadow-primary/20 flex items-center justify-center">
                        Request New Link
                    </a>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <p class="text-center text-[10px] font-bold opacity-20 uppercase tracking-[0.2em] mt-10">{{ config('app.name') }} Legal Technology v1.0.4</p>
    </div>
</div>