<div class="max-w-[1400px] mx-auto bg-white lg:rounded-4xl rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.05)] px-4 lg:px-8 py-8 border border-base-300/30">
    <!-- Header Section -->
    <div class="mb-6 lg:mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-xl lg:text-2xl font-black tracking-tight mb-1 text-base-content">
                Manage Copied Trades
            </h2>
            <p class="text-base-content/50 text-xs lg:text-sm font-medium max-w-xl">
                Monitor all active copy-trading subscriptions and user investments.
            </p>
        </div>
        <div class="relative flex-1 lg:max-w-xs">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none opacity-30">
                <i data-lucide="search" class="w-3.5 h-3.5"></i>
            </span>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search clients or traders..."
                class="input input-sm h-11 w-full bg-base-100 border border-base-300/50 rounded-xl pl-9 text-[10px] font-medium focus:bg-white transition-all">
        </div>
    </div>

    <!-- Table Content -->
    <div class="overflow-x-auto -mx-4 sm:-mx-6 lg:mx-0">
        <table class="table table-sm w-full border-separate border-spacing-y-1.5">
            <thead class="text-base-content/40 font-bold text-[10px] uppercase tracking-widest">
                <tr>
                    <th class="pl-6 bg-transparent">Client</th>
                    <th class="bg-transparent">Trader Strategy</th>
                    <th class="bg-transparent">Total Profit</th>
                    <th class="bg-transparent">Date Started</th>
                    <th class="text-right pr-6 bg-transparent">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($this->copiedTrades as $trade)
                    <tr class="bg-base-200/20 hover:bg-base-100 transition-all duration-200 group">
                        <td class="pl-4 py-3 rounded-l-2xl border-y border-l border-base-300/30">
                            <div class="flex items-center gap-3">
                                <div class="avatar placeholder">
                                    <div class="bg-primary/10 text-primary rounded-full w-9 h-9 flex items-center justify-center font-black text-[10px]">
                                        {{ substr($trade->user->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-sm text-base-content tracking-tight">{{ $trade->user->name }}</span>
                                    <span class="text-[9px] font-medium opacity-30 lowercase tracking-wider">{{ $trade->user->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 border-y border-base-300/30">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-base-200 flex items-center justify-center">
                                    <i data-lucide="zap" class="w-4 h-4 text-primary"></i>
                                </div>
                                <div class="flex flex-col min-w-0 max-w-[140px]">
                                    <span class="font-bold text-sm text-base-content tracking-tight truncate whitespace-nowrap">{{ $trade->trader->name }}</span>
                                    <span class="text-[9px] font-black text-primary uppercase tracking-widest truncate">{{ $trade->trader->strategy }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 border-y border-base-300/30">
                            <div class="flex flex-col">
                                <span class="text-sm font-black {{ $trade->profit >= 0 ? 'text-success' : 'text-error' }} tabular-nums">
                                    {{ $trade->profit >= 0 ? '+' : '-' }}${{ number_format(abs($trade->profit), 2) }}
                                </span>
                                <span class="text-[9px] font-bold opacity-30 uppercase tracking-widest">Net Profit</span>
                            </div>
                        </td>
                        <td class="py-3 border-y border-base-300/30 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-base-content/70">{{ $trade->created_at->format('M d, Y') }}</span>
                                <span class="text-[9px] font-medium opacity-30 tracking-tight">{{ $trade->created_at->diffForHumans() }}</span>
                            </div>
                        </td>
                        <td class="py-3 pr-4 rounded-r-2xl border-y border-r border-base-300/30">
                            <div class="flex items-center justify-end gap-2">
                                <button wire:click="openProfitModal({{ $trade->id }})"
                                        class="btn btn-ghost btn-sm h-9 px-3 rounded-xl opacity-40 hover:opacity-100 hover:bg-success/10 hover:text-success transition-all flex items-center gap-1.5">
                                    <i data-lucide="settings-2" class="w-3.5 h-3.5"></i>
                                    <span class="text-[9px] font-black uppercase tracking-widest">Manage Profit</span>
                                </button>
                                <button wire:click="stopCopying({{ $trade->id }})" wire:confirm="Are you sure you want to stop this copy trading strategy? This will remove the record from the database."
                                        class="btn btn-ghost btn-sm h-9 px-3 rounded-xl opacity-40 hover:opacity-100 hover:bg-error/10 hover:text-error transition-all flex items-center gap-1.5">
                                    <i data-lucide="stop-circle" class="w-3.5 h-3.5"></i>
                                    <span class="text-[9px] font-black uppercase tracking-widest">Stop Copy</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center">
                            <div class="flex flex-col items-center opacity-30">
                                <i data-lucide="layers-3" class="w-14 h-14 mb-3" stroke-width="1"></i>
                                <p class="text-lg font-black tracking-tight mb-1">No copied trades found</p>
                                <p class="text-xs font-medium opacity-60">No active subscriptions match your search criteria.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Profit Modal -->
    @if($isAddingProfit)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" wire:click="$set('isAddingProfit', false)"></div>
            <div class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-md border border-base-300/30 overflow-hidden animate-in zoom-in-95 fade-in duration-200">
                <div class="px-8 py-6 bg-base-200/50 border-b border-base-300/20 flex items-center justify-between">
                    <h3 class="text-lg font-black tracking-tight text-base-content uppercase">Manage Trading Profit</h3>
                    <button wire:click="$set('isAddingProfit', false)" class="opacity-30 hover:opacity-100 transition-opacity">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <div class="p-8 space-y-6">
                    <!-- Tab Switcher -->
                    <div class="flex p-1.5 bg-base-200 rounded-2xl border border-base-300/30">
                        <button wire:click="$set('profitMode', 'credit')" 
                            class="flex-1 py-3 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] transition-all {{ $profitMode === 'credit' ? 'bg-white shadow-lg text-success ring-1 ring-success/10' : 'opacity-40 hover:opacity-100' }}">
                            Credit
                        </button>
                        <button wire:click="$set('profitMode', 'debit')" 
                            class="flex-1 py-3 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] transition-all {{ $profitMode === 'debit' ? 'bg-white shadow-lg text-error ring-1 ring-error/10' : 'opacity-40 hover:opacity-100' }}">
                            Debit
                        </button>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text text-[10px] font-black uppercase tracking-widest opacity-40">Adjustment Amount (USDT)</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 font-black {{ $profitMode === 'credit' ? 'text-success' : 'text-error' }} text-lg">
                                {{ $profitMode === 'credit' ? '+' : '-' }}$
                            </span>
                            <input wire:model="amountToAdd" type="number" step="0.01" 
                                class="input input-bordered h-14 w-full pl-10 rounded-2xl bg-base-100 border-base-300/50 font-black text-xl tabular-nums">
                        </div>
                        @error('amountToAdd') <span class="text-error text-[10px] font-bold mt-1">{{ $message }}</span> @enderror
                    </div>

                    <button wire:click="addProfit" class="btn {{ $profitMode === 'credit' ? 'btn-success' : 'btn-error' }} w-full h-14 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl {{ $profitMode === 'credit' ? 'shadow-success/20' : 'shadow-error/20' }} text-white">
                        Confirm {{ ucfirst($profitMode) }} Adjustment
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Pagination -->
    @if($this->copiedTrades->hasPages())
        <div class="mt-8">
            {{ $this->copiedTrades->links() }}
        </div>
    @endif
</div>