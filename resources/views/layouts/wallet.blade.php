<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ config('app.name') }} | {{ $title ?? 'Crypto Wallet' }} </title>

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
        <div
            class="absolute top-[-20%] left-[-10%] w-[70%] h-[70%] rounded-full bg-primary/10 blur-[120px] animate-pulse-slow">
        </div>
        <div
            class="absolute bottom-[-20%] right-[-10%] w-[60%] h-[60%] rounded-full bg-secondary/10 blur-[100px] animate-pulse-slow-reverse">
        </div>

        <!-- Animated Primary Blob -->
        <div
            class="absolute top-[20%] right-[10%] w-[40%] h-[40%] rounded-full bg-primary/5 blur-[150px] animate-liquid">
        </div>

        <!-- Subtle Grid -->
        <div class="absolute inset-0 opacity-[0.03]"
            style="background-image: radial-gradient(circle, #ffffff 1px, transparent 1px); background-size: 40px 40px;">
        </div>

        <!-- Noise Texture -->
        <div class="absolute inset-0 opacity-[0.02] pointer-events-none"
            style="background-image: url('data:image/svg+xml,%3Csvg viewBox=\'0 0 200 200\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cfilter id=\'noiseFilter\'%3E%3CfeTurbulence type=\'fractalNoise\' baseFrequency=\'0.65\' numOctaves=\'3\' stitchTiles=\'stitch\'/%3E%3C/filter%3E%3Crect width=\'100%25\' height=\'100%25\' filter=\'url(%23noiseFilter)\'/%3E%3C/svg%3E');">
        </div>
    </div>

    <style>
        @keyframes pulse-slow {

            0%,
            100% {
                opacity: 0.6;
                transform: scale(1);
            }

            50% {
                opacity: 1;
                transform: scale(1.05);
            }
        }

        .animate-pulse-slow {
            animation: pulse-slow 15s infinite ease-in-out;
        }

        .animate-pulse-slow-reverse {
            animation: pulse-slow 18s infinite ease-in-out reverse;
        }

        @keyframes liquid {
            0% {
                transform: translate(0, 0) scale(1);
            }

            33% {
                transform: translate(5%, 7%) scale(1.1);
            }

            66% {
                transform: translate(-4%, 5%) scale(0.9);
            }

            100% {
                transform: translate(0, 0) scale(1);
            }
        }

        .animate-liquid {
            animation: liquid 20s infinite ease-in-out;
        }

        /* Custom Scrollbar for Dark Mode */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
        }
    </style>

    <!-- Content Stacking -->
    <div class="relative z-10 flex flex-col min-h-screen">
        {{ $slot }}
    </div>

    <!-- Global Notifications -->
    <div x-data="{ notification: null, showNotification: false, type: 'success' }" x-on:notify.window="
             let detail = $event.detail;
             notification = typeof detail === 'string' ? detail : (detail.message || (detail[0] && detail[0].message) || (detail.detail && detail.detail.message));
             type = detail.type || (detail[0] && detail[0].type) || (detail.detail && detail.detail.type) || 'success';
             showNotification = true;
             setTimeout(() => showNotification = false, 4000)
         " class="fixed top-6 left-1/2 -translate-x-1/2 z-[300] w-full max-w-[320px]" x-cloak>
        <div x-show="showNotification" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 -translate-y-4 scale-95" class="relative group">

            <div
                class="absolute inset-0 bg-primary/20 blur-2xl rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500">
            </div>

            <div
                class="relative bg-[#0A0C10] rounded-[1.5rem] p-4 shadow-2xl border border-white/10 flex items-center gap-4 overflow-hidden backdrop-blur-3xl">
                <!-- Status indicator line -->
                <div class="absolute left-0 top-0 bottom-0 w-1.5 transition-colors duration-500"
                    :class="{ 'bg-primary': type === 'success', 'bg-error': type === 'error', 'bg-warning': type === 'warning' }">
                </div>

                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors duration-500"
                    :class="{ 'bg-primary/10 text-primary': type === 'success', 'bg-error/10 text-error': type === 'error', 'bg-warning/10 text-warning': type === 'warning' }">
                    <div class="relative">
                        <div class="absolute inset-0 animate-ping opacity-20 rounded-full"
                            :class="{ 'bg-primary': type === 'success', 'bg-error': type === 'error', 'bg-warning': type === 'warning' }">
                        </div>
                        <span class="relative flex h-2.5 w-2.5 rounded-full"
                            :class="{ 'bg-primary': type === 'success', 'bg-error': type === 'error', 'bg-warning': type === 'warning' }"></span>
                    </div>
                </div>

                <div class="flex-1 min-w-0">
                    <p class="text-[9px] font-black uppercase tracking-[0.2em] text-white/30 mb-0.5"
                        x-text="type === 'success' ? 'Operation Success' : 'Attention Required'"></p>
                    <p class="text-[11px] font-bold text-white tracking-tight leading-tight" x-text="notification"></p>
                </div>

                <button @click="showNotification = false"
                    class="text-white/20 hover:text-white transition-colors p-2 -mr-2">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
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