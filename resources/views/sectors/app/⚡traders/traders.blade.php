@push('styles')
    <style>
        button,
        a,
        [role="button"],
        .cursor-pointer,
        [wire\:click] {
            cursor: pointer !important;
        }
    </style>
@endpush

<div
    class="max-w-[1400px] mx-auto bg-white lg:rounded-4xl rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.05)] px-4 lg:px-8 py-8 border border-base-300/30">
    <!-- Header Section -->
    <div class="mb-6 lg:mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-xl lg:text-2xl font-black tracking-tight mb-1 text-base-content">
                Manage Traders
            </h2>
            <p class="text-base-content/50 text-xs lg:text-sm font-medium max-w-xl">
                Create and manage professional trader profiles that clients can copy.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <div
                class="hidden md:flex items-center gap-3 bg-base-200/50 px-4 py-2.5 rounded-2xl border border-base-300/50">
                <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-wider opacity-50">
                    <i data-lucide="users" class="w-3.5 h-3.5"></i>
                    Total Traders
                </div>
                <div class="w-px h-4 bg-base-300/50"></div>
                <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-wider">
                    <span class="text-primary">{{ $this->traders->count() }} Profiles</span>
                </div>
            </div>
            <button wire:click="$set('isCreating', true)"
                class="btn btn-primary btn-sm h-11 px-6 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary/20">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Add New Trader
            </button>
        </div>
    </div>

    <!-- Traders Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($this->traders as $trader)
            <div
                class="bg-white border border-base-300/30 rounded-[2.5rem] p-5 transition-all hover:shadow-2xl hover:shadow-primary/5 group relative overflow-hidden flex flex-col h-full border-b-4 border-b-base-300/10 hover:border-b-primary/40">
                @if(!$trader->is_active)
                    <div class="absolute inset-0 bg-white/60 backdrop-blur-[1px] z-10 flex items-center justify-center">
                        <span
                            class="bg-base-content text-white text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-widest">Inactive</span>
                    </div>
                @endif

                <div class="flex items-center justify-between mb-5">
                    <div class="flex items-center gap-3">
                        <div class="avatar">
                            <div
                                class="w-14 h-14 rounded-2xl ring-4 ring-primary/5 shadow-sm group-hover:scale-105 transition-transform duration-500">
                                @if($trader->avatar)
                                    <img src="{{ asset('storage/' . $trader->avatar) }}" alt="{{ $trader->name }}" />
                                @else
                                    <div
                                        class="bg-primary text-white flex items-center justify-center h-full text-lg font-black">
                                        {{ substr($trader->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3
                                class="text-base font-black tracking-tight text-base-content truncate whitespace-nowrap max-w-[100px]">
                                {{ $trader->name }}</h3>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span
                                    class="text-[10px] font-black text-primary uppercase tracking-widest whitespace-nowrap">{{ $trader->strategy }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <div class="text-success font-black text-lg tabular-nums whitespace-nowrap">
                            +{{ number_format($trader->profit_percentage, 1) }}%</div>
                        <div class="text-[10px] font-bold opacity-30 uppercase tracking-widest whitespace-nowrap">Monthly
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3 mb-6">
                    <div class="bg-base-200/30 rounded-2xl p-3 border border-base-300/10 text-center">
                        <p class="text-[10px] font-black opacity-30 uppercase tracking-widest mb-1 whitespace-nowrap">Win
                            Rate</p>
                        <p class="text-sm font-black text-base-content">{{ number_format($trader->win_rate, 1) }}%</p>
                    </div>
                    <div class="bg-base-200/30 rounded-2xl p-3 border border-base-300/10 text-center">
                        <p class="text-[10px] font-black opacity-30 uppercase tracking-widest mb-1 whitespace-nowrap">Risk
                        </p>
                        <p
                            class="text-sm font-black {{ $trader->risk_level === 'High' ? 'text-error' : ($trader->risk_level === 'Medium' ? 'text-warning' : 'text-success') }}">
                            {{ $trader->risk_level }}
                        </p>
                    </div>
                    <div class="bg-base-200/30 rounded-2xl p-3 border border-base-300/10 text-center">
                        <p class="text-[10px] font-black opacity-30 uppercase tracking-widest mb-1 whitespace-nowrap">
                            Copiers</p>
                        <p class="text-sm font-black text-base-content">{{ number_format($trader->total_copiers) }}+</p>
                    </div>
                </div>

                <div class="mt-auto flex items-center justify-between pt-4 border-t border-base-300/20">
                    <div class="flex items-center gap-2">
                        <button wire:click="toggleStatus({{ $trader->id }})"
                            class="btn btn-ghost btn-xs h-9 px-3 rounded-xl opacity-40 hover:opacity-100 hover:bg-base-200 transition-all">
                            <i data-lucide="{{ $trader->is_active ? 'eye-off' : 'eye' }}" class="w-4.5 h-4.5 mr-2"></i>
                            <span
                                class="text-[10px] font-black uppercase tracking-widest">{{ $trader->is_active ? 'Disable' : 'Enable' }}</span>
                        </button>
                    </div>
                    <div class="flex items-center gap-1">
                        <button wire:click="editTrader({{ $trader->id }})"
                            class="btn btn-ghost btn-sm h-9 w-9 rounded-xl opacity-40 hover:opacity-100 hover:bg-primary/10 hover:text-primary transition-all">
                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                        </button>
                        <button wire:click="deleteTrader({{ $trader->id }})"
                            wire:confirm="Are you sure you want to delete this trader profile?"
                            class="btn btn-ghost btn-sm h-9 w-9 rounded-xl opacity-40 hover:opacity-100 hover:bg-error/10 hover:text-error transition-all">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 flex flex-col items-center justify-center text-center opacity-30">
                <i data-lucide="users-round" class="w-16 h-16 mb-4" stroke-width="1"></i>
                <p class="text-xl font-black tracking-tight">No Traders Created</p>
                <p class="text-sm font-medium">Start by adding a professional trader profile for clients to copy.</p>
            </div>
        @endforelse
    </div>

    <!-- Modal for Create/Edit -->
    @if($isCreating)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" wire:click="$set('isCreating', false)"></div>
            <div
                class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-xl border border-base-300/30 overflow-hidden animate-in zoom-in-95 fade-in duration-200">
                <div class="px-8 py-6 bg-base-200/50 border-b border-base-300/20 flex items-center justify-between">
                    <h3 class="text-lg font-black tracking-tight text-base-content uppercase">
                        {{ $editingTraderId ? 'Edit Trader' : 'Create New Trader' }}
                    </h3>
                    <button wire:click="$set('isCreating', false)" class="btn btn-ghost btn-sm btn-circle opacity-50">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <div class="p-8 space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div class="form-control col-span-2">
                            <label class="label"><span
                                    class="label-text text-[10px] font-black uppercase tracking-widest opacity-40">Full
                                    Name</span></label>
                            <input wire:model="name" type="text"
                                class="input input-bordered h-12 rounded-xl bg-base-100 border-base-300/50 font-bold text-sm">
                        </div>

                        <div class="form-control">
                            <label class="label"><span
                                    class="label-text text-[10px] font-black uppercase tracking-widest opacity-40">Strategy</span></label>
                            <select wire:model="strategy"
                                class="select select-bordered h-12 rounded-xl bg-base-100 border-base-300/50 font-bold text-sm">
                                <option value="Scalping">Scalping</option>
                                <option value="Day Trading">Day Trading</option>
                                <option value="Swing Trading">Swing Trading</option>
                                <option value="Trend Following">Trend Following</option>
                                <option value="HFT">HFT (High Frequency)</option>
                            </select>
                        </div>

                        <div class="form-control">
                            <label class="label"><span
                                    class="label-text text-[10px] font-black uppercase tracking-widest opacity-40">Risk
                                    Level</span></label>
                            <select wire:model="risk_level"
                                class="select select-bordered h-12 rounded-xl bg-base-100 border-base-300/50 font-bold text-sm">
                                <option value="Low">Low Risk</option>
                                <option value="Medium">Medium Risk</option>
                                <option value="High">High Risk</option>
                            </select>
                        </div>

                        <div class="form-control">
                            <label class="label"><span
                                    class="label-text text-[10px] font-black uppercase tracking-widest opacity-40">Win Rate
                                    (%)</span></label>
                            <input wire:model="win_rate" type="number" step="0.1"
                                class="input input-bordered h-12 rounded-xl bg-base-100 border-base-300/50 font-bold text-sm">
                        </div>

                        <div class="form-control">
                            <label class="label"><span
                                    class="label-text text-[10px] font-black uppercase tracking-widest opacity-40">Avg
                                    Monthly Profit (%)</span></label>
                            <input wire:model="profit_percentage" type="number" step="0.1"
                                class="input input-bordered h-12 rounded-xl bg-base-100 border-base-300/50 font-bold text-sm">
                        </div>

                        <div class="form-control">
                            <label class="label"><span
                                    class="label-text text-[10px] font-black uppercase tracking-widest opacity-40">Total
                                    Copiers</span></label>
                            <input wire:model="total_copiers" type="number"
                                class="input input-bordered h-12 rounded-xl bg-base-100 border-base-300/50 font-bold text-sm">
                        </div>

                        <div class="form-control">
                            <label class="label"><span
                                    class="label-text text-[10px] font-black uppercase tracking-widest opacity-40">Profile
                                    Avatar</span></label>
                            <input wire:model="avatar" type="file"
                                class="file-input file-input-bordered h-12 rounded-xl bg-base-100 border-base-300/50 text-[10px] font-black uppercase">
                        </div>
                    </div>

                    <button wire:click="{{ $editingTraderId ? 'updateTrader' : 'createTrader' }}"
                        class="btn btn-primary w-full h-14 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-primary/20">
                        {{ $editingTraderId ? 'Save Changes' : 'Create Trader Profile' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>