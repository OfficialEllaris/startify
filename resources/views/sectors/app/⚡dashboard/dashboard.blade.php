<div class="max-w-[1400px] mx-auto bg-white lg:rounded-4xl rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.05)] px-4 lg:px-8 py-8 border border-base-300/30">
    @php
        $businesses = $this->businesses;
        // The businesses collection is now paginated, so we compute aggregate stats from the base query directly to reflect all records
        $baseQuery = auth()->user()->isManager() ? \App\Models\Business::query() : \App\Models\Business::where('user_id', auth()->id());
        $total = $businesses->total();
        $pending = (clone $baseQuery)->whereIn('status', [\App\Enums\BusinessStatus::Submitted, \App\Enums\BusinessStatus::UnderReview, \App\Enums\BusinessStatus::InProgress])->count();
        $approved = (clone $baseQuery)->where('status', \App\Enums\BusinessStatus::Approved)->count();
    @endphp

    <!-- Welcome Section -->
    <div class="mb-6 lg:mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-xl lg:text-2xl font-black tracking-tight mb-1 text-base-content">
                Welcome, {{ explode(' ', auth()->user()->name)[0] }}!
            </h2>
            <p class="text-base-content/50 text-xs lg:text-sm font-medium max-w-xl">
                {{ auth()->user()->isManager() ? 'Your command center for business formation management.' : 'Ready to turn your vision into a registered reality? Track your progress below.' }}
            </p>
        </div>
        <div class="hidden md:flex items-center gap-3 bg-base-200/50 px-4 py-2.5 rounded-2xl border border-base-300/50">
            <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-wider opacity-50">
                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                Member since {{ auth()->user()->created_at->format('M Y') }}
            </div>
            <div class="w-px h-4 bg-base-300/50"></div>
            <div class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-wider">
                <span class="w-2 h-2 rounded-full bg-success animate-pulse"></span>
                <span class="text-success">{{ $total }} {{ str('Filing')->plural($total) }}</span>
            </div>
        </div>
    </div>


    <!-- Stats Grid -->
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 lg:gap-4 mb-8 lg:mb-10">
        <!-- Total Applications -->
        <div class="relative overflow-hidden group bg-gradient-to-br from-base-100 to-base-200/50 p-5 rounded-2xl border border-base-300/50 transition-all duration-500 hover:shadow-xl hover:shadow-primary/5 hover:-translate-y-0.5">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-primary/5 rounded-full blur-3xl transition-all duration-500 group-hover:bg-primary/10"></div>
            <div class="flex justify-between items-start mb-3">
                <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary group-hover:scale-110 transition-transform duration-500">
                    <i data-lucide="layers" class="w-5 h-5"></i>
                </div>
                <span class="text-[9px] font-black uppercase tracking-[0.2em] opacity-30">Total Scale</span>
            </div>
            <div class="text-3xl font-black tracking-tighter mb-0.5">{{ $total }}</div>
            <div class="text-[10px] font-bold opacity-40 uppercase tracking-widest">Active Filings</div>
        </div>

        <!-- Pending Review -->
        <div class="relative overflow-hidden group bg-gradient-to-br from-base-100 to-base-200/50 p-5 rounded-2xl border border-base-300/50 transition-all duration-500 hover:shadow-xl hover:shadow-warning/5 hover:-translate-y-0.5">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-warning/5 rounded-full blur-3xl transition-all duration-500 group-hover:bg-warning/10"></div>
            <div class="flex justify-between items-start mb-3">
                <div class="w-10 h-10 bg-warning/10 rounded-xl flex items-center justify-center text-warning group-hover:scale-110 transition-transform duration-500">
                    <i data-lucide="clock" class="w-5 h-5"></i>
                </div>
                <span class="text-[9px] font-black uppercase tracking-[0.2em] opacity-30">In Progress</span>
            </div>
            <div class="text-3xl font-black tracking-tighter mb-0.5">{{ $pending }}</div>
            <div class="text-[10px] font-bold opacity-40 uppercase tracking-widest">Awaiting Review</div>
        </div>

        <!-- Approved LLCs -->
        <div class="relative overflow-hidden group bg-gradient-to-br from-base-100 to-base-200/50 p-5 rounded-2xl border border-base-300/50 transition-all duration-500 hover:shadow-xl hover:shadow-success/5 hover:-translate-y-0.5">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-success/5 rounded-full blur-3xl transition-all duration-500 group-hover:bg-success/10"></div>
            <div class="flex justify-between items-start mb-3">
                <div class="w-10 h-10 bg-success/10 rounded-xl flex items-center justify-center text-success group-hover:scale-110 transition-transform duration-500">
                    <i data-lucide="award" class="w-5 h-5"></i>
                </div>
                <span class="text-[9px] font-black uppercase tracking-[0.2em] opacity-30">Success Rate</span>
            </div>
            <div class="text-3xl font-black tracking-tighter mb-0.5">{{ $approved }}</div>
            <div class="text-[10px] font-bold opacity-40 uppercase tracking-widest">Completed LLCs</div>
        </div>
    </div>

    <!-- Table Header Actions -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-4 gap-4">
        <div>
            <h3 class="text-lg font-black tracking-tight text-base-content flex items-center gap-2 mb-1">
                Project Registry
                <span class="bg-base-200 text-base-content/40 text-[9px] px-2 py-0.5 rounded-md font-black tracking-widest uppercase">{{ $businesses->total() }} Entries</span>
            </h3>
            <p class="text-xs font-medium opacity-40 max-w-sm">Detailed overview of all business entities and their current legal standing.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto">
            <div class="dropdown" x-data="{ open: false }">
                <button @click="open = !open" class="btn btn-sm h-9 bg-white border border-base-300/50 hover:bg-base-100 px-4 font-bold text-[10px] rounded-xl shadow-sm">
                    <i data-lucide="arrow-up-down" class="w-3 h-3 opacity-40 mr-1.5"></i>
                    Sort
                    @if($sortBy !== 'created_at' || $sortDirection !== 'desc')
                        <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                    @endif
                </button>
                <ul x-show="open" @click.outside="open = false" x-transition
                    class="absolute z-10 mt-2 p-2 shadow-xl bg-white rounded-xl w-44 border border-base-300/30">
                    @foreach(['name' => 'Name', 'state' => 'State', 'status' => 'Status', 'created_at' => 'Date'] as $col => $label)
                        <li>
                            <button wire:click="sort('{{ $col }}')" @click="open = false"
                                class="w-full text-left px-3 py-2 rounded-lg text-xs font-medium transition-all hover:bg-primary/5 hover:text-primary flex items-center justify-between {{ $sortBy === $col ? 'bg-primary/10 text-primary font-bold' : '' }}">
                                {{ $label }}
                                @if($sortBy === $col)
                                    <i data-lucide="{{ $sortDirection === 'asc' ? 'arrow-up' : 'arrow-down' }}" class="w-3 h-3"></i>
                                @endif
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="relative flex-1 lg:max-w-xs">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none opacity-30">
                    <i data-lucide="search" class="w-3.5 h-3.5"></i>
                </span>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search entities..."
                    class="input input-sm h-9 w-full bg-white border border-base-300/50 rounded-xl pl-9 text-[10px] font-medium focus:bg-base-100 transition-all">
            </div>
        </div>
        @if(!auth()->user()->isManager())
            <a href="{{ route('app.onboarding') }}" class="btn btn-primary btn-sm h-9 px-5 rounded-xl font-black text-[10px] uppercase tracking-widest shadow-lg shadow-primary/30 w-full lg:hidden">
                <i data-lucide="plus" class="w-3.5 h-3.5 mr-1.5"></i>
                New Entity
            </a>
        @endif
    </div>

    <!-- Table Content -->
    <div class="overflow-x-auto -mx-4 sm:-mx-6 lg:mx-0">
        <table class="table table-sm w-full border-separate border-spacing-y-1.5">
            <thead class="text-base-content/40 font-bold text-[10px] uppercase tracking-widest">
                <tr>
                    <th class="pl-6 bg-transparent cursor-pointer hover:text-primary transition-colors" wire:click="sort('name')">
                        <span class="flex items-center gap-1">Business Name
                            @if($sortBy === 'name') <i data-lucide="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-3 h-3 text-primary"></i> @endif
                        </span>
                    </th>
                    @if(auth()->user()->isManager())
                        <th class="bg-transparent">Client</th>
                    @endif
                    <th class="bg-transparent cursor-pointer hover:text-primary transition-colors" wire:click="sort('state')">
                        <span class="flex items-center gap-1">State
                            @if($sortBy === 'state') <i data-lucide="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-3 h-3 text-primary"></i> @endif
                        </span>
                    </th>
                    <th class="bg-transparent">Progress</th>
                    <th class="bg-transparent cursor-pointer hover:text-primary transition-colors" wire:click="sort('status')">
                        <span class="flex items-center gap-1">Status
                            @if($sortBy === 'status') <i data-lucide="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-3 h-3 text-primary"></i> @endif
                        </span>
                    </th>
                    <th class="bg-transparent cursor-pointer hover:text-primary transition-colors" wire:click="sort('created_at')">
                        <span class="flex items-center gap-1">Date
                            @if($sortBy === 'created_at') <i data-lucide="{{ $sortDirection === 'asc' ? 'chevron-up' : 'chevron-down' }}" class="w-3 h-3 text-primary"></i> @endif
                        </span>
                    </th>
                    <th class="text-right pr-6 bg-transparent">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($businesses as $business)
                    @php
                        $progress = match($business->status->value) {
                            'submitted' => 20,
                            'under_review' => 40,
                            'in_progress' => 60,
                            'filed' => 80,
                            'approved' => 100,
                            default => 10,
                        };
                        $progressColor = match($business->status->value) {
                            'approved' => 'bg-success',
                            'rejected' => 'bg-error',
                            'under_review' => 'bg-warning',
                            default => 'bg-primary',
                        };
                    @endphp
                    <tr class="bg-base-200/20 hover:bg-base-100 transition-all duration-200 cursor-pointer group">
                        <td class="pl-4 py-2.5 rounded-l-2xl border-y border-l border-base-300/30">
                            <div class="flex items-center gap-3">
                                <div class="avatar placeholder">
                                    <div class="bg-gradient-to-br from-primary/10 to-primary/20 text-primary rounded-full w-9 h-9 flex items-center justify-center ring-2 ring-primary/10">
                                        <span class="text-[10px] font-black tracking-tighter leading-none">{{ substr($business->name, 0, 2) }}</span>
                                    </div>
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span class="font-bold text-sm text-base-content tracking-tight group-hover:text-primary transition-colors truncate max-w-[160px]">{{ $business->name }}</span>
                                    <span class="text-[9px] font-black opacity-30 uppercase tracking-[0.1em]">{{ $business->type->label() }}</span>
                                </div>
                            </div>
                        </td>
                        @if(auth()->user()->isManager())
                            <td class="py-2.5 border-y border-base-300/30">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-base-content/80 truncate max-w-[120px]">{{ $business->user->name }}</span>
                                    <span class="text-[9px] font-medium opacity-40 truncate max-w-[120px]">{{ $business->user->email }}</span>
                                </div>
                            </td>
                        @endif
                        <td class="py-2.5 border-y border-base-300/30">
                            <div class="flex items-center gap-1.5">
                                <i data-lucide="map-pin" class="w-3 h-3 opacity-30"></i>
                                <span class="text-xs font-bold text-base-content/70">{{ $business->state }}</span>
                            </div>
                        </td>
                        <td class="py-2.5 border-y border-base-300/30 w-32">
                            <div class="flex flex-col gap-1">
                                <div class="flex justify-between items-center">
                                    <span class="text-[8px] font-black opacity-30 uppercase tracking-widest">Progress</span>
                                    <span class="text-[9px] font-black text-primary">{{ $progress }}%</span>
                                </div>
                                <div class="h-1.5 w-full bg-base-200 rounded-full overflow-hidden">
                                    <div class="h-full {{ $progressColor }} rounded-full transition-all duration-1000" style="width: {{ $progress }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="py-2.5 border-y border-base-300/30">
                            @php
                                $statusStyles = [
                                    'submitted' => ['bg' => 'bg-info/10', 'text' => 'text-info', 'icon' => 'send'],
                                    'under_review' => ['bg' => 'bg-warning/10', 'text' => 'text-warning', 'icon' => 'search'],
                                    'in_progress' => ['bg' => 'bg-secondary/10', 'text' => 'text-secondary', 'icon' => 'activity'],
                                    'filed' => ['bg' => 'bg-accent/10', 'text' => 'text-accent', 'icon' => 'file-text'],
                                    'approved' => ['bg' => 'bg-success/10', 'text' => 'text-success', 'icon' => 'check-circle'],
                                    'rejected' => ['bg' => 'bg-error/10', 'text' => 'text-error', 'icon' => 'x-circle'],
                                ];
                                $style = $statusStyles[$business->status->value] ?? ['bg' => 'bg-base-300', 'text' => 'text-base-content', 'icon' => 'help-circle'];
                            @endphp
                            <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full {{ $style['bg'] }} {{ $style['text'] }} border border-current/5 whitespace-nowrap">
                                <i data-lucide="{{ $style['icon'] }}" class="w-3 h-3"></i>
                                <span class="text-[9px] font-black uppercase tracking-wider">
                                    {{ str($business->status->value)->replace('_', ' ') }}
                                </span>
                            </div>
                        </td>
                        <td class="py-2.5 border-y border-base-300/30 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-xs font-bold text-base-content/70">{{ $business->created_at->format('M d, Y') }}</span>
                                <span class="text-[9px] font-medium opacity-30 tracking-tight">{{ $business->created_at->diffForHumans() }}</span>
                            </div>
                        </td>
                        <td class="py-2.5 pr-4 rounded-r-2xl border-y border-r border-base-300/30 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button wire:click="viewFiling({{ $business->id }})" class="btn btn-ghost btn-sm btn-circle opacity-30 hover:opacity-100 hover:bg-primary/10 hover:text-primary transition-all">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                                @if(auth()->user()->isManager())
                                    <button wire:click="editFiling({{ $business->id }})" class="btn btn-ghost btn-sm btn-circle opacity-30 hover:opacity-100 hover:bg-secondary/10 hover:text-secondary transition-all" title="Edit Filing">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </button>
                                    <button wire:click="deleteFiling({{ $business->id }})" class="btn btn-ghost btn-sm btn-circle opacity-30 hover:opacity-100 hover:bg-error/10 hover:text-error transition-all" title="Delete Filing" wire:confirm="Are you sure you want to delete this filing?">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->isManager() ? '7' : '6' }}" class="py-20 text-center">
                            @if($search)
                                <div class="flex flex-col items-center opacity-30">
                                    <i data-lucide="search-x" class="w-14 h-14 mb-3" stroke-width="1"></i>
                                    <p class="text-lg font-black tracking-tight mb-1">No results found</p>
                                    <p class="text-xs font-medium opacity-60">No filings match "{{ $search }}"</p>
                                </div>
                            @else
                                <div class="flex flex-col items-center opacity-20">
                                    <i data-lucide="file-plus-2" class="w-14 h-14 mb-3" stroke-width="1"></i>
                                    <p class="text-lg font-black tracking-tight mb-1">No filings yet</p>
                                    @if(!auth()->user()->isManager())
                                        <p class="text-xs font-medium opacity-60 mb-4">Start your first business formation today</p>
                                        <a href="{{ route('app.onboarding') }}" class="btn btn-primary btn-sm rounded-xl text-[10px] font-black uppercase tracking-widest px-6">
                                            <i data-lucide="plus" class="w-3 h-3 mr-1"></i> New Filing
                                        </a>
                                    @else
                                        <p class="text-xs font-medium opacity-60">No client filings to manage yet</p>
                                    @endif
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($businesses->hasPages())
        <div class="flex flex-col sm:flex-row justify-between items-center mt-8 gap-4 px-2">
            <div class="flex gap-2">
                <button wire:click="previousPage" {{ $businesses->onFirstPage() ? 'disabled' : '' }} class="btn btn-sm btn-outline rounded-lg border-base-300 font-bold text-xs px-6 {{ $businesses->onFirstPage() ? 'btn-disabled' : '' }}">Previous</button>
                <button wire:click="nextPage" {{ !$businesses->hasMorePages() ? 'disabled' : '' }} class="btn btn-sm btn-outline rounded-lg border-base-300 font-bold text-xs px-6 {{ !$businesses->hasMorePages() ? 'btn-disabled' : '' }}">Next</button>
            </div>
            <div class="text-[10px] font-bold opacity-40 uppercase tracking-widest">
                Page {{ $businesses->currentPage() }} of {{ $businesses->lastPage() }}
            </div>
        </div>
    @endif

    <!-- Filing Detail Modal -->
    @if($viewingBusiness = $this->viewingBusiness)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data="{ init() { document.body.classList.add('overflow-hidden'); return () => document.body.classList.remove('overflow-hidden'); } }">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeModal"></div>
            
            <!-- Modal Content -->
            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-lg max-h-[85vh] overflow-y-auto border border-base-300/30 animate-in zoom-in-95 fade-in duration-200">
                <!-- Header -->
                <div class="sticky top-0 bg-white/95 backdrop-blur-sm rounded-t-3xl border-b border-base-300/20 px-6 py-4 flex items-center justify-between z-10">
                    <div class="flex items-center gap-3">
                        <div class="bg-gradient-to-br from-primary/10 to-primary/20 text-primary rounded-full w-10 h-10 flex items-center justify-center ring-2 ring-primary/10">
                            <span class="text-xs font-black">{{ substr($viewingBusiness->name, 0, 2) }}</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-black tracking-tight">{{ $viewingBusiness->name }}</h3>
                            <p class="text-[10px] font-bold uppercase tracking-wider opacity-40">{{ $viewingBusiness->type->label() }}</p>
                        </div>
                    </div>
                    <button wire:click="closeModal" class="btn btn-ghost btn-sm btn-circle hover:bg-base-200">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>

                <!-- Body -->
                <div class="px-6 py-5 space-y-5">
                    @php
                        $modalProgress = match($viewingBusiness->status->value) {
                            'submitted' => 20, 'under_review' => 40, 'in_progress' => 60,
                            'filed' => 80, 'approved' => 100, default => 10,
                        };
                        $modalStatusStyles = [
                            'submitted' => ['bg' => 'bg-info/10', 'text' => 'text-info', 'icon' => 'send'],
                            'under_review' => ['bg' => 'bg-warning/10', 'text' => 'text-warning', 'icon' => 'search'],
                            'in_progress' => ['bg' => 'bg-secondary/10', 'text' => 'text-secondary', 'icon' => 'activity'],
                            'filed' => ['bg' => 'bg-accent/10', 'text' => 'text-accent', 'icon' => 'file-text'],
                            'approved' => ['bg' => 'bg-success/10', 'text' => 'text-success', 'icon' => 'check-circle'],
                            'rejected' => ['bg' => 'bg-error/10', 'text' => 'text-error', 'icon' => 'x-circle'],
                        ];
                        $modalStyle = $modalStatusStyles[$viewingBusiness->status->value] ?? ['bg' => 'bg-base-300', 'text' => 'text-base-content', 'icon' => 'help-circle'];
                    @endphp

                    <!-- Status & Progress -->
                    <div class="bg-base-200/30 rounded-2xl p-4 border border-base-300/20">
                        <div class="flex items-center justify-between mb-3">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full {{ $modalStyle['bg'] }} {{ $modalStyle['text'] }} border border-current/5">
                                <i data-lucide="{{ $modalStyle['icon'] }}" class="w-3.5 h-3.5"></i>
                                <span class="text-[10px] font-black uppercase tracking-wider">{{ str($viewingBusiness->status->value)->replace('_', ' ') }}</span>
                            </div>
                            <span class="text-xs font-black text-primary">{{ $modalProgress }}%</span>
                        </div>
                        <div class="h-2 w-full bg-base-200 rounded-full overflow-hidden">
                            <div class="h-full bg-primary rounded-full transition-all duration-1000" style="width: {{ $modalProgress }}%"></div>
                        </div>
                    </div>

                    <!-- Details Grid -->
                    <div class="space-y-3">
                        <p class="text-[9px] font-black uppercase tracking-[0.15em] opacity-30">Filing Details</p>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-base-100 rounded-xl p-3 border border-base-300/20">
                                <p class="text-[9px] font-bold uppercase tracking-wider opacity-40 mb-0.5">State</p>
                                <p class="text-sm font-bold flex items-center gap-1.5">
                                    <i data-lucide="map-pin" class="w-3 h-3 opacity-40"></i>
                                    {{ $viewingBusiness->state }}
                                </p>
                            </div>
                            <div class="bg-base-100 rounded-xl p-3 border border-base-300/20">
                                <p class="text-[9px] font-bold uppercase tracking-wider opacity-40 mb-0.5">Entity Type</p>
                                <p class="text-sm font-bold">{{ $viewingBusiness->type->label() }}</p>
                            </div>
                            <div class="bg-base-100 rounded-xl p-3 border border-base-300/20">
                                <p class="text-[9px] font-bold uppercase tracking-wider opacity-40 mb-0.5">Filed On</p>
                                <p class="text-sm font-bold">{{ $viewingBusiness->submitted_at?->format('M d, Y') ?? $viewingBusiness->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="bg-base-100 rounded-xl p-3 border border-base-300/20">
                                <p class="text-[9px] font-bold uppercase tracking-wider opacity-40 mb-0.5">Last Updated</p>
                                <p class="text-sm font-bold">{{ $viewingBusiness->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Purpose -->
                    @if($viewingBusiness->purpose)
                        <div class="space-y-2">
                            <p class="text-[9px] font-black uppercase tracking-[0.15em] opacity-30">Business Purpose</p>
                            <div class="bg-base-100 rounded-xl p-3 border border-base-300/20">
                                <p class="text-sm text-base-content/70 leading-relaxed">{{ $viewingBusiness->purpose }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Registered Agent -->
                    <div class="space-y-2">
                        <p class="text-[9px] font-black uppercase tracking-[0.15em] opacity-30">Registered Agent</p>
                        <div class="bg-base-100 rounded-xl p-3 border border-base-300/20">
                            @if($viewingBusiness->use_registrar_agent)
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 bg-primary/10 rounded-lg flex items-center justify-center">
                                        <i data-lucide="shield-check" class="w-3.5 h-3.5 text-primary"></i>
                                    </div>
                                    <span class="text-sm font-bold">{{ config('app.name') }} Registered Agent Service</span>
                                </div>
                            @else
                                <div class="space-y-1">
                                    <p class="text-sm font-bold">{{ $viewingBusiness->agent_name ?? 'N/A' }}</p>
                                    <p class="text-xs text-base-content/50">{{ $viewingBusiness->agent_address ?? '' }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Owner Info (manager view) -->
                    @if(auth()->user()->isManager() && $viewingBusiness->user)
                        <div class="space-y-2">
                            <p class="text-[9px] font-black uppercase tracking-[0.15em] opacity-30">Client Information</p>
                            <div class="bg-base-100 rounded-xl p-3 border border-base-300/20 flex items-center gap-3">
                                <div class="bg-primary/10 text-primary rounded-full w-8 h-8 flex items-center justify-center">
                                    <span class="text-[10px] font-black">{{ substr($viewingBusiness->user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold">{{ $viewingBusiness->user->name }}</p>
                                    <p class="text-[10px] text-base-content/40">{{ $viewingBusiness->user->email }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
    <!-- Edit Filing Modal -->
    @if($editingBusiness = $this->editingBusiness)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" x-data="{ init() { document.body.classList.add('overflow-hidden'); return () => document.body.classList.remove('overflow-hidden'); } }">
            <!-- Backdrop -->
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" wire:click="closeModal"></div>
            
            <!-- Modal Content -->
            <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-base-300/30 animate-in zoom-in-95 fade-in duration-200">
                <!-- Header -->
                <div class="bg-base-200/50 px-6 py-4 flex items-center justify-between border-b border-base-300/20">
                    <div>
                        <h3 class="font-bold text-lg tracking-tight">Edit Status</h3>
                        <p class="text-xs opacity-60">{{ $editingBusiness->name }}</p>
                    </div>
                    <button wire:click="closeModal" class="btn btn-ghost btn-sm btn-circle opacity-50 hover:opacity-100">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
                
                <!-- Body -->
                <div class="p-6 space-y-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-bold">Filing Status</span>
                        </label>
                        <select wire:model="editStatus" class="select select-bordered w-full rounded-2xl bg-base-100">
                            @foreach(\App\Enums\BusinessStatus::cases() as $status)
                                <option value="{{ $status->value }}">{{ str($status->value)->replace('_', ' ')->title() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="bg-base-200/30 px-6 py-4 border-t border-base-300/20 flex justify-end gap-2">
                    <button wire:click="closeModal" class="btn btn-ghost rounded-2xl">Cancel</button>
                    <button wire:click="saveEdit" class="btn btn-primary rounded-2xl px-6">Save Changes</button>
                </div>
            </div>
        </div>
    @endif
</div>