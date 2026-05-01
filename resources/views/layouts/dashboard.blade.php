<x-layouts::app :title="$title ?? null">
    <div class="drawer lg:drawer-open min-h-screen bg-base-200/30">
        <input id="dashboard-drawer" type="checkbox" class="drawer-toggle" />

        <div class="drawer-content flex flex-col">
            <!-- Header -->
            <header
                class="h-20 lg:h-24 flex items-center px-6 lg:px-10 sticky top-0 z-20 bg-base-200/30 backdrop-blur-md">
                <div class="flex-1 flex items-center justify-between">
                    <div class="flex items-center gap-4 lg:hidden">
                        <label for="dashboard-drawer" class="btn btn-ghost btn-circle">
                            <i data-lucide="menu" class="w-5 h-5"></i>
                        </label>
                        <h1 class="text-xl font-black italic tracking-tighter uppercase">{{ config('app.name') }}</h1>
                    </div>

                    <div class="hidden lg:flex flex-1 max-w-xl relative group">
                        <span
                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none opacity-40 group-focus-within:opacity-100 transition-opacity">
                            <i data-lucide="search" class="w-4 h-4"></i>
                        </span>
                        <input type="text" x-data
                            x-on:input.debounce.300ms="Livewire.dispatch('searchUpdated', { search: $event.target.value })"
                            placeholder="Search applications..."
                            class="input h-11 w-full bg-white border border-base-300/50 rounded-2xl pl-12 focus:border-primary/30 focus:ring-4 focus:ring-primary/5 transition-all font-medium text-sm shadow-sm">
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center gap-2">
                            <kbd class="kbd kbd-xs bg-base-100 border-base-300 opacity-40">⌘K</kbd>
                        </div>
                    </div>

                    <!-- User Profile Group -->
                    <div class="dropdown dropdown-end pl-4 border-l border-base-300/50">
                        <div tabindex="0" role="button"
                            class="flex items-center gap-3 cursor-pointer hover:opacity-80 transition-opacity">
                            <div
                                class="bg-primary text-primary-content rounded-xl w-10 h-10 flex items-center justify-center shadow-lg shadow-primary/20 ring-2 ring-primary ring-offset-2 ring-offset-base-100">
                                <span
                                    class="text-sm font-black leading-none">{{ auth()->check() ? strtoupper(substr(auth()->user()->name, 0, 1) . substr(strrchr(auth()->user()->name, ' ') ?: auth()->user()->name, 1, 1)) : '??' }}</span>
                            </div>
                            <div class="hidden md:flex flex-col">
                                <span
                                    class="text-sm font-bold text-base-content leading-tight">{{ auth()->user()?->name ?? 'Guest User' }}</span>
                                <span
                                    class="text-[10px] lowercase font-bold text-base-content/40 tracking-tight mt-0.5">
                                    {{ auth()->check() ? str(auth()->user()->email)->limit(20) : 'Awaiting Setup' }}
                                </span>
                            </div>
                        </div>
                        @if(auth()->check())
                            <ul tabindex="0"
                                class="mt-4 z-[1] p-3 shadow-2xl menu menu-md dropdown-content bg-base-100 rounded-3xl w-64 border border-base-300/50 backdrop-blur-xl">
                                <li class="menu-title px-4 py-2 text-xs font-bold uppercase tracking-widest opacity-40">
                                    Account Settings</li>
                                <li>
                                    <button x-data @click="$dispatch('open-profile-modal', { tab: 'profile' })"
                                        class="py-3 rounded-2xl flex items-center gap-3">
                                        <i data-lucide="user" class="w-4 h-4"></i>
                                        <span class="font-medium">My Profile</span>
                                    </button>
                                </li>
                                <li>
                                    <button x-data @click="$dispatch('open-profile-modal', { tab: 'security' })"
                                        class="py-3 rounded-2xl flex items-center gap-3">
                                        <i data-lucide="shield" class="w-4 h-4"></i>
                                        <span class="font-medium">Security</span>
                                    </button>
                                </li>
                                <div class="divider my-1 opacity-10"></div>
                                <li>
                                    <livewire:sectors::app.logout />
                                </li>
                            </ul>
                        @endif
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 p-2 lg:p-10 pt-0 overflow-y-auto">
                {{ $slot }}
            </main>
        </div>

        <!-- Sidebar Drawer -->
        <div class="drawer-side z-30">
            <label for="dashboard-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
            <aside
                class="bg-base-100 min-h-screen w-72 lg:w-60 border-r border-base-300/50 flex flex-col shadow-2xl shadow-base-300/10 rounded-r-4xl">

                <!-- Logo Section -->
                <div class="p-5 pb-6">
                    <a href="{{ route('web.home') }}" class="flex items-center gap-3 group/logo">
                        <div
                            class="w-9 h-9 bg-primary rounded-xl flex items-center justify-center text-primary-content shadow-lg shadow-primary/30 rotate-3 transition-transform group-hover/logo:scale-110 group-hover/logo:rotate-6">
                            <img src="{{ asset('favicon.ico') }}" alt="{{ config('app.name') }}"
                                class="w-6 h-6 -rotate-3">
                        </div>
                        <div class="flex flex-col">
                            <span
                                class="text-lg font-black tracking-tight leading-none uppercase text-base-content">{{ config('app.name') }}</span>
                            <span
                                class="text-[8px] font-bold opacity-30 uppercase tracking-[0.2em] mt-0.5">Platform</span>
                        </div>
                    </a>
                </div>

                <!-- Menu Content -->
                <div class="flex-1 px-3 py-3 overflow-y-auto space-y-6">
                    <div>
                        <p class="text-[9px] font-bold text-base-content/30 uppercase tracking-[0.15em] mb-2 px-3">
                            Workspace</p>
                        <ul class="menu menu-sm gap-1 p-0 w-full">
                            <li>
                                <a href="{{ route('app.dashboard') }}"
                                    class="flex gap-3 p-3 rounded-xl group transition-all duration-300 text-sm {{ request()->routeIs('app.dashboard') ? 'bg-primary text-primary-content shadow-lg shadow-primary/20' : 'hover:bg-base-200' }}">
                                    <i data-lucide="layout-dashboard"
                                        class="w-5 h-5 {{ request()->routeIs('app.dashboard') ? '' : 'text-base-content/40 group-hover:text-primary' }}"></i>
                                    <span class="font-bold tracking-tight">Applications</span>
                                </a>
                            </li>
                            @if(auth()->check() && !auth()->user()->isManager())
                                <li>
                                    <a href="{{ route('app.onboarding') }}" wire:navigate
                                        class="flex gap-3 p-3 rounded-xl group transition-all duration-300 text-sm {{ request()->routeIs('app.onboarding') ? 'bg-primary text-primary-content shadow-lg shadow-primary/20' : 'hover:bg-base-200' }}">
                                        <i data-lucide="plus-circle"
                                            class="w-5 h-5 {{ request()->routeIs('app.onboarding') ? '' : 'text-base-content/40 group-hover:text-primary' }}"></i>
                                        <span class="font-bold tracking-tight">New Filing</span>
                                    </a>
                                </li>
                            @endif
                            @if(auth()->check() && !auth()->user()->isManager())
                                <li>
                                    <a href="{{ route('app.wallet') }}" wire:navigate
                                        class="flex gap-3 p-3 rounded-xl group transition-all duration-300 text-sm {{ request()->routeIs('app.wallet') ? 'bg-primary text-primary-content shadow-lg shadow-primary/20' : 'hover:bg-base-200' }}">
                                        <i data-lucide="wallet"
                                            class="w-5 h-5 {{ request()->routeIs('app.wallet') ? '' : 'text-base-content/40 group-hover:text-primary' }}"></i>
                                        <span class="font-bold tracking-tight">Crypto Assets</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('app.copy-trading') }}" wire:navigate
                                        class="flex gap-3 p-3 rounded-xl group transition-all duration-300 text-sm {{ request()->routeIs('app.copy-trading') ? 'bg-primary text-primary-content shadow-lg shadow-primary/20' : 'hover:bg-base-200' }}">
                                        <i data-lucide="layers"
                                            class="w-5 h-5 {{ request()->routeIs('app.copy-trading') ? '' : 'text-base-content/40 group-hover:text-primary' }}"></i>
                                        <span class="font-bold tracking-tight">Copy Experts</span>
                                    </a>
                                </li>
                            @elseif(auth()->check() && auth()->user()->isManager())
                                <li>
                                    <a href="{{ route('app.clients') }}" wire:navigate
                                        class="flex gap-3 p-3 rounded-xl group transition-all duration-300 text-sm {{ request()->routeIs('app.clients') ? 'bg-primary text-primary-content shadow-lg shadow-primary/20' : 'hover:bg-base-200' }}">
                                        <i data-lucide="users"
                                            class="w-5 h-5 {{ request()->routeIs('app.clients') ? '' : 'text-base-content/40 group-hover:text-primary' }}"></i>
                                        <span class="font-bold tracking-tight">Manage Clients</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('app.wallets') }}" wire:navigate
                                        class="flex gap-3 p-3 rounded-xl group transition-all duration-300 text-sm {{ request()->routeIs('app.wallets') ? 'bg-primary text-primary-content shadow-lg shadow-primary/20' : 'hover:bg-base-200' }}">
                                        <i data-lucide="wallet"
                                            class="w-5 h-5 {{ request()->routeIs('app.wallets') ? '' : 'text-base-content/40 group-hover:text-primary' }}"></i>
                                        <span class="font-bold tracking-tight">Manage Wallets</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('app.traders') }}" wire:navigate
                                        class="flex gap-3 p-3 rounded-xl group transition-all duration-300 text-sm {{ request()->routeIs('app.traders') ? 'bg-primary text-primary-content shadow-lg shadow-primary/20' : 'hover:bg-base-200' }}">
                                        <i data-lucide="user-plus"
                                            class="w-5 h-5 {{ request()->routeIs('app.traders') ? '' : 'text-base-content/40 group-hover:text-primary' }}"></i>
                                        <span class="font-bold tracking-tight">Manage Traders</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('app.manage-trades') }}" wire:navigate
                                        class="flex gap-3 p-3 rounded-xl group transition-all duration-300 text-sm {{ request()->routeIs('app.manage-trades') ? 'bg-primary text-primary-content shadow-lg shadow-primary/20' : 'hover:bg-base-200' }}">
                                        <i data-lucide="activity"
                                            class="w-5 h-5 {{ request()->routeIs('app.manage-trades') ? '' : 'text-base-content/40 group-hover:text-primary' }}"></i>
                                        <span class="font-bold tracking-tight">Manage Trades</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>

                    <div>
                        <p class="text-[9px] font-bold text-base-content/30 uppercase tracking-[0.15em] mb-2 px-3">
                            Account</p>
                        <ul class="menu menu-sm gap-1 p-0 w-full">
                            <li>
                                <button x-data @click="$dispatch('open-profile-modal', { tab: 'profile' })"
                                    class="flex gap-3 p-3 rounded-xl group transition-all duration-300 text-sm hover:bg-base-200">
                                    <i data-lucide="user-circle"
                                        class="w-5 h-5 text-base-content/40 group-hover:text-primary"></i>
                                    <span class="font-bold tracking-tight">My Profile</span>
                                </button>
                            </li>
                            <li>
                                <button x-data @click="$dispatch('open-profile-modal', { tab: 'security' })"
                                    class="flex gap-3 p-3 rounded-xl group transition-all duration-300 text-sm hover:bg-base-200">
                                    <i data-lucide="shield"
                                        class="w-5 h-5 text-base-content/40 group-hover:text-primary"></i>
                                    <span class="font-bold tracking-tight">Security</span>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Bottom Support Card -->
                <div class="p-4">
                    <div
                        class="bg-gradient-to-br from-base-200/50 to-base-300/30 rounded-2xl p-4 border border-base-300/50 relative overflow-hidden group">
                        <div
                            class="absolute -right-4 -bottom-4 w-20 h-20 bg-primary/5 rounded-full blur-2xl group-hover:bg-primary/10 transition-all duration-500">
                        </div>
                        <div class="flex flex-col items-center text-center relative z-10">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm mb-4">
                                <i data-lucide="headphones" class="w-5 h-5 text-primary"></i>
                            </div>
                            <p class="text-sm font-black tracking-tight mb-1">Need Support?</p>
                            <p class="text-[10px] font-medium text-base-content/40 mb-4">Our experts are available 24/7
                                to help you.</p>
                            <a href="{{ route('web.contact') }}" target="_blank"
                                class="btn btn-sm btn-primary btn-block rounded-xl text-[10px] font-black uppercase tracking-widest h-10 flex items-center justify-center">Get
                                Help</a>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    <!-- Modals -->
    <livewire:profile-modal />
</x-layouts::app>