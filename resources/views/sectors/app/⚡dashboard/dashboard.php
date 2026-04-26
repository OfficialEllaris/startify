<?php

use App\Enums\BusinessStatus;
use App\Models\Business;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::dashboard')] class extends Component
{
    use WithPagination;

    public string $sortBy = 'created_at';

    public string $sortDirection = 'desc';

    public string $search = '';

    public ?int $viewingBusinessId = null;

    public ?int $editingBusinessId = null;

    public string $editStatus = '';

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
        unset($this->businesses);
    }

    #[On('searchUpdated')]
    public function searchUpdated(string $search): void
    {
        $this->search = $search;
        $this->resetPage();
        unset($this->businesses);
    }

    public function viewFiling(int $businessId): void
    {
        $this->viewingBusinessId = $businessId;
    }

    public function closeModal(): void
    {
        $this->viewingBusinessId = null;
        $this->editingBusinessId = null;
    }

    #[Computed]
    public function viewingBusiness(): ?Business
    {
        if (! $this->viewingBusinessId) {
            return null;
        }

        $user = Auth::user();
        $query = Business::with(['user'])->where('id', $this->viewingBusinessId);

        if (! $user->isManager()) {
            $query->where('user_id', $user->id);
        }

        return $query->first();
    }

    public function updateStatus(int $businessId, string $newStatus): void
    {
        $user = Auth::user();

        if (! $user->isManager()) {
            abort(403);
        }

        $business = Business::findOrFail($businessId);
        $oldStatus = $business->status;

        $parsedStatus = BusinessStatus::tryFrom($newStatus);

        if (! $parsedStatus || $oldStatus === $parsedStatus) {
            return;
        }

        $business->update(['status' => $parsedStatus]);

        $this->dispatch('status-updated', ['business_id' => $business->id]);
    }

    public function editFiling(int $businessId): void
    {
        $this->editingBusinessId = $businessId;
        $business = Business::find($businessId);
        if ($business) {
            $this->editStatus = $business->status->value;
        }
    }

    public function saveEdit(): void
    {
        if (! $this->editingBusinessId) {
            return;
        }

        $user = Auth::user();
        if (! $user->isManager()) {
            abort(403);
        }

        $business = Business::findOrFail($this->editingBusinessId);
        $parsedStatus = BusinessStatus::tryFrom($this->editStatus);

        if ($parsedStatus && $business->status !== $parsedStatus) {
            $business->update(['status' => $parsedStatus]);
            $this->dispatch('status-updated', ['business_id' => $business->id]);
        }

        $this->editingBusinessId = null;
    }

    public function deleteFiling(int $businessId): void
    {
        $user = Auth::user();
        if (! $user->isManager()) {
            abort(403);
        }

        $business = Business::findOrFail($businessId);
        $business->delete();
    }

    #[Computed]
    public function editingBusiness(): ?Business
    {
        if (! $this->editingBusinessId) {
            return null;
        }

        $user = Auth::user();
        if (! $user->isManager()) {
            return null;
        }

        return Business::with(['user'])->where('id', $this->editingBusinessId)->first();
    }

    #[Computed]
    public function businesses()
    {
        $user = Auth::user();
        $query = $user->isManager()
            ? Business::with(['user'])
            : Business::where('user_id', $user->id);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('state', 'like', "%{$this->search}%")
                    ->orWhere('status', 'like', "%{$this->search}%");
            });
        }

        return $query->orderBy($this->sortBy, $this->sortDirection)->paginate(10);
    }
};
