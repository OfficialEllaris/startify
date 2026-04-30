<div x-data="{ open: @entangle('showModal'), activeTab: 'profile' }" @open-profile-modal.window="open = true; activeTab = $event.detail.tab || 'profile'">
    <!-- Modal Backdrop -->
    <div x-show="open" 
         x-transition.opacity.duration.300ms
         class="fixed inset-0 bg-base-300/60 backdrop-blur-sm z-[100]" 
         style="display: none;"></div>

    <!-- Modal Panel -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-8 sm:translate-y-0 sm:scale-95"
         class="fixed inset-0 z-[101] flex items-end sm:items-center justify-center p-0 sm:p-4 pointer-events-none" 
         style="display: none;">
        
        <div class="bg-base-100 w-full sm:max-w-xl rounded-t-[2rem] sm:rounded-[2rem] shadow-2xl pointer-events-auto flex flex-col max-h-[90vh] overflow-hidden" @click.away="open = false">
            
            <!-- Header -->
            <div class="flex items-center justify-between px-8 py-6 border-b border-base-300/30">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-primary/10 text-primary rounded-2xl flex items-center justify-center">
                        <i data-lucide="user-cog" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black tracking-tight">Account Settings</h2>
                        <p class="text-xs font-medium text-base-content/40">Manage your profile and security</p>
                    </div>
                </div>
                <button type="button" @click="open = false; $wire.closeModal()" class="btn btn-ghost btn-sm btn-square rounded-xl text-base-content/40 hover:text-base-content hover:bg-base-200">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <!-- Tabs -->
            <div class="flex px-8 border-b border-base-300/30 bg-base-200/20">
                <button @click="activeTab = 'profile'" 
                    class="h-14 px-6 text-xs font-black uppercase tracking-widest border-b-2 transition-all"
                    :class="activeTab === 'profile' ? 'border-primary text-primary' : 'border-transparent text-base-content/40 hover:text-base-content hover:border-base-300'">
                    Profile
                </button>
                <button @click="activeTab = 'security'" 
                    class="h-14 px-6 text-xs font-black uppercase tracking-widest border-b-2 transition-all"
                    :class="activeTab === 'security' ? 'border-primary text-primary' : 'border-transparent text-base-content/40 hover:text-base-content hover:border-base-300'">
                    Security
                </button>
                @if(auth()->user()->isManager())
                    <button @click="activeTab = 'wallet'" 
                        class="h-14 px-6 text-xs font-black uppercase tracking-widest border-b-2 transition-all"
                        :class="activeTab === 'wallet' ? 'border-primary text-primary' : 'border-transparent text-base-content/40 hover:text-base-content hover:border-base-300'">
                        Wallet
                    </button>
                @endif
            </div>

            <!-- Body -->
            <div class="p-8 overflow-y-auto custom-scrollbar">
                
                <!-- Profile Section -->
                <div x-show="activeTab === 'profile'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <h3 class="text-sm font-black uppercase tracking-widest text-base-content/30 mb-6 flex items-center gap-2">
                        <i data-lucide="circle-user" class="w-4 h-4"></i> Profile Information
                    </h3>


                    <form wire:submit="updateProfile" class="space-y-4">
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text text-[10px] font-black uppercase tracking-widest opacity-40">Full Name</span>
                            </label>
                            <input type="text" wire:model="name" class="input h-14 bg-base-200/50 border-none rounded-2xl w-full focus:bg-white focus:ring-4 focus:ring-primary/5 transition-all font-medium" required>
                            @error('name') <span class="text-error text-xs mt-1 font-medium px-2">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text text-[10px] font-black uppercase tracking-widest opacity-40">Email Address</span>
                            </label>
                            <input type="email" wire:model="email" class="input h-14 bg-base-200/50 border-none rounded-2xl w-full focus:bg-white focus:ring-4 focus:ring-primary/5 transition-all font-medium" required>
                            @error('email') <span class="text-error text-xs mt-1 font-medium px-2">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text text-[10px] font-black uppercase tracking-widest opacity-40">Phone Number</span>
                            </label>
                            <input type="text" wire:model="phone" class="input h-14 bg-base-200/50 border-none rounded-2xl w-full focus:bg-white focus:ring-4 focus:ring-primary/5 transition-all font-medium" placeholder="Enter phone number">
                            @error('phone') <span class="text-error text-xs mt-1 font-medium px-2">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text text-[10px] font-black uppercase tracking-widest opacity-40">Residential Address</span>
                            </label>
                            <textarea wire:model="address" class="textarea min-h-[80px] bg-base-200/50 border-none rounded-2xl w-full focus:bg-white focus:ring-4 focus:ring-primary/5 transition-all font-medium py-4" placeholder="Enter residential address"></textarea>
                            @error('address') <span class="text-error text-xs mt-1 font-medium px-2">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex justify-end mt-4">
                            <button type="submit" class="btn btn-primary h-12 px-8 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-primary/20">
                                <span wire:loading.remove wire:target="updateProfile">Save Profile</span>
                                <span wire:loading wire:target="updateProfile" class="loading loading-spinner loading-sm"></span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Password Section -->
                <div x-show="activeTab === 'security'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <h3 class="text-sm font-black uppercase tracking-widest text-base-content/30 mb-6 flex items-center gap-2">
                        <i data-lucide="shield" class="w-4 h-4"></i> Security Update
                    </h3>


                    <form wire:submit="updatePassword" class="space-y-4">
                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text text-[10px] font-black uppercase tracking-widest opacity-40">Current Password</span>
                            </label>
                            <input type="password" wire:model="current_password" class="input h-14 bg-base-200/50 border-none rounded-2xl w-full focus:bg-white focus:ring-4 focus:ring-primary/5 transition-all font-medium" required>
                            @error('current_password') <span class="text-error text-xs mt-1 font-medium px-2">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text text-[10px] font-black uppercase tracking-widest opacity-40">New Password</span>
                            </label>
                            <input type="password" wire:model="password" class="input h-14 bg-base-200/50 border-none rounded-2xl w-full focus:bg-white focus:ring-4 focus:ring-primary/5 transition-all font-medium" required>
                            @error('password') <span class="text-error text-xs mt-1 font-medium px-2">{{ $message }}</span> @enderror
                        </div>

                        <div class="form-control w-full">
                            <label class="label">
                                <span class="label-text text-[10px] font-black uppercase tracking-widest opacity-40">Confirm New Password</span>
                            </label>
                            <input type="password" wire:model="password_confirmation" class="input h-14 bg-base-200/50 border-none rounded-2xl w-full focus:bg-white focus:ring-4 focus:ring-primary/5 transition-all font-medium" required>
                        </div>

                        <div class="flex justify-end mt-4">
                            <button type="submit" class="btn btn-primary h-12 px-8 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-primary/20">
                                <span wire:loading.remove wire:target="updatePassword">Update Password</span>
                                <span wire:loading wire:target="updatePassword" class="loading loading-spinner loading-sm"></span>
                            </button>
                        </div>
                    </form>
                </div>

                @if(auth()->user()->isManager())
                <!-- Wallet Section -->
                <div x-show="activeTab === 'wallet'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <h3 class="text-sm font-black uppercase tracking-widest text-base-content/30 mb-6 flex items-center gap-2">
                        <i data-lucide="wallet" class="w-4 h-4"></i> Wallet Addresses
                    </h3>


                    <form wire:submit="updateWalletAddresses" class="space-y-4">
                        @foreach($wallet_addresses as $assetId => $address)
                            <div class="form-control w-full">
                                <label class="label">
                                    <span class="label-text text-[10px] font-black uppercase tracking-widest opacity-40">{{ $assetId === 'xdce-crowd-sale' ? 'XDC Network' : str_replace('-', ' ', ucwords($assetId, '-')) }}</span>
                                </label>
                                <input type="text" wire:model="wallet_addresses.{{ $assetId }}" class="input h-14 bg-base-200/50 border-none rounded-2xl w-full focus:bg-white focus:ring-4 focus:ring-primary/5 transition-all font-medium" placeholder="Enter {{ $assetId === 'xdce-crowd-sale' ? 'XDC' : str_replace('-', ' ', $assetId) }} address">
                            </div>
                        @endforeach

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="btn btn-primary h-12 px-8 rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-primary/20">
                                <span wire:loading.remove wire:target="updateWalletAddresses">Save Addresses</span>
                                <span wire:loading wire:target="updateWalletAddresses" class="loading loading-spinner loading-sm"></span>
                            </button>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>