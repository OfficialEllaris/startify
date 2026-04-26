<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="bulveria">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body
    class="bg-[#FDFDFF] text-base-content antialiased selection:bg-primary/10 selection:text-primary overflow-x-hidden">

    <!-- Premium Interactive Background -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden bg-[#FDFDFF]">
        <!-- Dynamic Mesh Gradient -->
        <div class="absolute inset-0 opacity-[0.45]"
            style="background: radial-gradient(at 0% 0%, color-mix(in oklch, var(--color-primary), transparent 90%) 0%, transparent 50%), 
                         radial-gradient(at 100% 0%, color-mix(in oklch, var(--color-secondary), transparent 90%) 0%, transparent 50%),
                         radial-gradient(at 100% 100%, color-mix(in oklch, var(--color-accent), transparent 90%) 0%, transparent 50%),
                         radial-gradient(at 0% 100%, color-mix(in oklch, var(--color-primary), transparent 90%) 0%, transparent 50%);">
        </div>

        <!-- Animated Floating Blobs -->
        <div
            class="absolute top-[-15%] left-[-5%] w-[60%] h-[60%] rounded-full bg-primary/15 blur-[120px] animate-liquid">
        </div>
        <div
            class="absolute bottom-[-15%] right-[-5%] w-[50%] h-[50%] rounded-full bg-secondary/15 blur-[100px] animate-liquid-reverse">
        </div>

        <!-- Elegant Grid Pattern -->
        <div class="absolute inset-0 bg-grid-pattern opacity-[0.6]"></div>

        <!-- Grain Texture Overlay (The "Premium" Secret) -->
        <div class="absolute inset-0 opacity-[0.03] contrast-150 brightness-100 pointer-events-none"
            style="background-image: url('data:image/svg+xml,%3Csvg viewBox=\'0 0 200 200\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cfilter id=\'noiseFilter\'%3E%3CfeTurbulence type=\'fractalNoise\' baseFrequency=\'0.65\' numOctaves=\'3\' stitchTiles=\'stitch\'/%3E%3C/filter%3E%3Crect width=\'100%25\' height=\'100%25\' filter=\'url(%23noiseFilter)\'/%3E%3C/svg%3E');">
        </div>
    </div>

    <style>
        .bg-grid-pattern {
            background-image: radial-gradient(color-mix(in oklch, var(--color-base-content), transparent 85%) 1.5px, transparent 1.5px);
            background-size: 32px 32px;
        }

        @keyframes liquid {
            0% {
                transform: translate(0, 0) rotate(0deg) scale(1);
            }

            33% {
                transform: translate(4%, 6%) rotate(8deg) scale(1.05);
            }

            66% {
                transform: translate(-3%, 8%) rotate(-4deg) scale(0.95);
            }

            100% {
                transform: translate(0, 0) rotate(0deg) scale(1);
            }
        }

        .animate-liquid {
            animation: liquid 25s infinite ease-in-out;
        }

        .animate-liquid-reverse {
            animation: liquid 30s infinite ease-in-out reverse;
        }

        /* Smooth page transitions support */
        [x-cloak] {
            display: none !important;
        }
    </style>

    <!-- Main Content Stacking Context -->
    <div class="relative z-10 flex flex-col min-h-screen">
        {{ $slot }}
    </div>

    @livewireScripts
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        const initLucide = () => { if (window.lucide) window.lucide.createIcons(); };
        document.addEventListener('DOMContentLoaded', initLucide);
        document.addEventListener('livewire:navigated', initLucide);
        Livewire.hook('morph.updated', initLucide);
        Livewire.hook('morph.added', initLucide);
    </script>
</body>

</html>