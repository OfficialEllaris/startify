<?php

use App\Models\User;
use App\Enums\UserRole;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts.web'), Title('Contact Us')] class extends Component
{
    public $name;
    public $email;
    public $subject;
    public $message;
    public $manager;

    public function mount()
    {
        $this->manager = User::where('role', UserRole::Manager)->first();
    }

    public function submit()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        \Illuminate\Support\Facades\Mail::to(config('mail.from.address'))
            ->send(new \App\Mail\ContactNotification($validated));

        $this->reset(['name', 'email', 'subject', 'message']);

        session()->flash('success', 'Your message has been sent successfully!');
    }
};
