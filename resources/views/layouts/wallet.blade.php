<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Crypto Wallet' }} | {{ config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
    @stack('styles')
</head>

<body class="bg-[#0A0C10] text-[#E0E0E0] antialiased selection:bg-primary/20 selection:text-primary overflow-x-hidden">

    <!-- Immersive Crypto Background -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden bg-[#0A0C10]">
        <!-- Ambient Glows -->
        <div class="absolute top-[-20%] left-[-10%] w-[70%] h-[70%] rounded-full bg-primary/10 blur-[120px] animate-pulse-slow"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[60%] h-[60%] rounded-full bg-secondary/10 blur-[100px] animate-pulse-slow-reverse"></div>
        
        <!-- Animated Primary Blob -->
        <div class="absolute top-[20%] right-[10%] w-[40%] h-[40%] rounded-full bg-primary/5 blur-[150px] animate-liquid"></div>

        <!-- Subtle Grid -->
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle, #ffffff 1px, transparent 1px); background-size: 40px 40px;"></div>

        <!-- Noise Texture -->
        <div class="absolute inset-0 opacity-[0.02] pointer-events-none"
            style="background-image: url('data:image/svg+xml,%3Csvg viewBox=\'0 0 200 200\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cfilter id=\'noiseFilter\'%3E%3CfeTurbulence type=\'fractalNoise\' baseFrequency=\'0.65\' numOctaves=\'3\' stitchTiles=\'stitch\'/%3E%3C/filter%3E%3Crect width=\'100%25\' height=\'100%25\' filter=\'url(%23noiseFilter)\'/%3E%3C/svg%3E');">
        </div>
    </div>

    <style>
        @keyframes pulse-slow {
            0%, 100% { opacity: 0.6; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.05); }
        }
        .animate-pulse-slow { animation: pulse-slow 15s infinite ease-in-out; }
        .animate-pulse-slow-reverse { animation: pulse-slow 18s infinite ease-in-out reverse; }
        
        @keyframes liquid {
            0% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(5%, 7%) scale(1.1); }
            66% { transform: translate(-4%, 5%) scale(0.9); }
            100% { transform: translate(0, 0) scale(1); }
        }
        .animate-liquid { animation: liquid 20s infinite ease-in-out; }

        /* Custom Scrollbar for Dark Mode */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.2); }
    </style>

    <!-- Content Stacking -->
    <div class="relative z-10 flex flex-col min-h-screen">
        {{ $slot }}
    </div>

    @livewireScripts
    @stack('scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        const initLucide = () => { 
            if (window.lucide) {
                window.lucide.createIcons(); 
            }
        };
        document.addEventListener('DOMContentLoaded', initLucide);
        document.addEventListener('livewire:navigated', initLucide);
        
        // Handle Livewire updates
        document.addEventListener('livewire:initialized', () => {
            Livewire.hook('request', ({ succeed }) => {
                succeed(() => {
                    setTimeout(initLucide, 0);
                });
            });
        });
    </script>
</body>

</html>
