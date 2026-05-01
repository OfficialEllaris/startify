<?php

use App\Models\Trader;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

new #[Layout('layouts::dashboard'), Title('Manage Traders')] class extends Component {
    use WithFileUploads;

    public $name = '';
    public $avatar = null;
    public $strategy = 'Scalping';
    public $win_rate = 0;
    public $profit_percentage = 0;
    public $total_copiers = 0;
    public $risk_level = 'Low';
    public $is_active = true;

    public $editingTraderId = null;
    public $isCreating = false;

    public function mount()
    {
        if (!Auth::user()->isManager()) {
            abort(403);
        }
    }

    public function createTrader()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|max:1024',
            'strategy' => 'required|string',
            'win_rate' => 'required|numeric|min:0|max:100',
            'profit_percentage' => 'required|numeric',
            'total_copiers' => 'required|integer|min:0',
            'risk_level' => 'required|in:Low,Medium,High',
        ]);

        $avatarPath = $this->avatar ? $this->avatar->store('traders', 'public') : null;

        Trader::create([
            'name' => $this->name,
            'avatar' => $avatarPath,
            'strategy' => $this->strategy,
            'win_rate' => $this->win_rate,
            'profit_percentage' => $this->profit_percentage,
            'total_copiers' => $this->total_copiers,
            'risk_level' => $this->risk_level,
            'is_active' => $this->is_active,
        ]);

        $this->resetFields();
        $this->isCreating = false;
        $this->dispatch('notify', message: 'Trader created successfully!');
    }

    public function editTrader($id)
    {
        $trader = Trader::findOrFail($id);
        $this->editingTraderId = $id;
        $this->name = $trader->name;
        $this->strategy = $trader->strategy;
        $this->win_rate = $trader->win_rate;
        $this->profit_percentage = $trader->profit_percentage;
        $this->total_copiers = $trader->total_copiers;
        $this->risk_level = $trader->risk_level;
        $this->is_active = $trader->is_active;
        $this->isCreating = true;
    }

    public function updateTrader()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|max:1024',
            'strategy' => 'required|string',
            'win_rate' => 'required|numeric|min:0|max:100',
            'profit_percentage' => 'required|numeric',
            'total_copiers' => 'required|integer|min:0',
            'risk_level' => 'required|in:Low,Medium,High',
        ]);

        $trader = Trader::findOrFail($this->editingTraderId);
        
        $data = [
            'name' => $this->name,
            'strategy' => $this->strategy,
            'win_rate' => $this->win_rate,
            'profit_percentage' => $this->profit_percentage,
            'total_copiers' => $this->total_copiers,
            'risk_level' => $this->risk_level,
            'is_active' => $this->is_active,
        ];

        if ($this->avatar) {
            $data['avatar'] = $this->avatar->store('traders', 'public');
        }

        $trader->update($data);

        $this->resetFields();
        $this->isCreating = false;
        $this->dispatch('notify', message: 'Trader updated successfully!');
    }

    public function deleteTrader($id)
    {
        Trader::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Trader deleted successfully!');
    }

    public function toggleStatus($id)
    {
        $trader = Trader::findOrFail($id);
        $trader->update(['is_active' => !$trader->is_active]);
    }

    private function resetFields()
    {
        $this->name = '';
        $this->avatar = null;
        $this->strategy = 'Scalping';
        $this->win_rate = 0;
        $this->profit_percentage = 0;
        $this->total_copiers = 0;
        $this->risk_level = 'Low';
        $this->is_active = true;
        $this->editingTraderId = null;
    }

    #[Computed]
    public function traders()
    {
        return Trader::latest()->get();
    }
};