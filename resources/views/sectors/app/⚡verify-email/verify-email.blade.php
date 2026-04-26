<div class="flex min-h-screen items-center justify-center px-4 py-8 sm:py-12">
    <div class="w-full max-w-md">
        <!-- Logo Section -->
        <div class="flex items-center justify-center gap-4 mb-12">
            <div class="w-12 h-12 bg-primary rounded-2xl flex items-center justify-center text-primary-content shadow-2xl shadow-primary/30 rotate-3">
                <i data-lucide="zap" class="w-7 h-7 -rotate-3"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black tracking-tighter uppercase italic leading-none">{{ config('app.name') }}</h1>
                <p class="text-[9px] font-black opacity-30 uppercase tracking-[0.2em] mt-0.5">Verification</p>
            </div>
        </div>

        <div class="bg-white rounded-[3rem] shadow-[0_30px_60px_rgba(0,0,0,0.05)] p-10 lg:p-12 border border-base-300/30">
            <div class="flex flex-col items-center text-center">
                @if ($status === 'verifying')
                    <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center mb-6">
                        <span class="loading loading-spinner loading-lg text-primary"></span>
                    </div>
                    <h2 class="text-2xl font-black tracking-tight text-base-content mb-2">Verifying Email</h2>
                    <p class="text-sm font-medium text-base-content/40">Please wait while we set up your account...</p>
                @elseif ($status === 'success')
                    <div class="w-16 h-16 bg-success/10 rounded-2xl flex items-center justify-center mb-6">
                        <i data-lucide="check-circle-2" class="w-9 h-9 text-success"></i>
                    </div>
                    <h2 class="text-2xl font-black tracking-tight text-success mb-2">Verified!</h2>
                    <p class="text-sm font-medium text-base-content/40">Your email has been confirmed. Redirecting to dashboard...</p>
                    <div class="mt-6 flex items-center gap-2 text-xs font-bold text-base-content/30">
                        <span class="loading loading-dots loading-xs"></span>
                        Redirecting
                    </div>
                @else
                    <div class="w-16 h-16 bg-error/10 rounded-2xl flex items-center justify-center mb-6">
                        <i data-lucide="shield-x" class="w-9 h-9 text-error"></i>
                    </div>
                    <h2 class="text-2xl font-black tracking-tight text-error mb-2">Invalid Link</h2>
                    <p class="text-sm font-medium text-base-content/40 mb-8">This verification link is invalid or has expired.</p>
                    <a href="{{ route('app.login') }}" class="btn btn-primary w-full h-14 rounded-2xl text-sm font-black uppercase tracking-widest shadow-xl shadow-primary/20">
                        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Login
                    </a>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-[10px] font-bold opacity-20 uppercase tracking-[0.2em] mt-10">{{ config('app.name') }} Legal Technology v1.0.4</p>
    </div>
</div>
