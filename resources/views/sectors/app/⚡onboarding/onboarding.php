<?php

use App\Enums\BusinessStatus;
use App\Enums\BusinessType;
use App\Models\Business;
use App\Services\RegistrationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public int $step = 1;

    public bool $isAuthenticated = false;

    // Step 1: State
    public string $state = '';

    // Step 2: Business
    public string $business_name = '';

    public string $business_type = '';

    public string $business_purpose = '';

    // Step 3: Registered Agent
    public bool $use_registrar_agent = true;

    public string $agent_name = '';

    public string $agent_address = '';

    // Step 4: User Contact Details
    public string $user_name = '';

    public string $user_email = '';

    public string $user_phone = '';

    public string $user_address = '';

    // Step 6: Create Account (Only if guest)
    public string $password = '';

    public string $password_confirmation = '';

    public function mount()
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->isManager()) {
                abort(403);
            }

            $this->isAuthenticated = true;
            $this->user_name = $user->name;
            $this->user_email = $user->email;
            $this->user_phone = $user->phone ?? '';
            $this->user_address = $user->address ?? '';
        }
    }

    public function nextStep()
    {
        $this->validateStep();

        // If authenticated and on Review step (Step 5), there is no Step 6.
        if ($this->isAuthenticated && $this->step === 5) {
            return;
        }

        $this->step++;
    }

    public function previousStep()
    {
        $this->step--;
    }

    public function submit(RegistrationService $service)
    {
        $this->validateStep();

        if ($this->isAuthenticated) {
            $user = Auth::user();

            // Update user contact details if they were changed
            $user->update([
                'phone' => $this->user_phone,
                'address' => $this->user_address,
            ]);

            Business::create([
                'user_id' => $user->id,
                'state' => $this->state,
                'name' => $this->business_name,
                'type' => $this->business_type,
                'purpose' => $this->business_purpose,
                'use_registrar_agent' => $this->use_registrar_agent,
                'agent_name' => $this->use_registrar_agent ? null : $this->agent_name,
                'agent_address' => $this->use_registrar_agent ? null : $this->agent_address,
                'status' => BusinessStatus::Submitted,
                'submitted_at' => now(),
            ]);

            return redirect()->route('app.dashboard');
        }

        // Unauthenticated Flow
        $data = [
            'user' => [
                'name' => $this->user_name,
                'email' => $this->user_email,
                'phone' => $this->user_phone,
                'address' => $this->user_address,
                'password' => $this->password,
            ],
            'business' => [
                'state' => $this->state,
                'name' => $this->business_name,
                'type' => $this->business_type,
                'purpose' => $this->business_purpose,
                'use_registrar_agent' => $this->use_registrar_agent,
                'agent_name' => $this->use_registrar_agent ? null : $this->agent_name,
                'agent_address' => $this->use_registrar_agent ? null : $this->agent_address,
            ],
        ];

        $service->cacheRegistration($data);

        $this->step = 7; // Success step for guests
    }

    protected function validateStep()
    {
        if ($this->step === 1) {
            $this->validate([
                'state' => ['required', 'string', 'max:255'],
            ]);
        } elseif ($this->step === 2) {
            $this->validate([
                'business_name' => ['required', 'string', 'max:255', 'unique:businesses,name'],
                'business_type' => ['required', Rule::enum(BusinessType::class)],
                'business_purpose' => ['required', 'string', 'max:1000'],
            ]);
        } elseif ($this->step === 3) {
            if (! $this->use_registrar_agent) {
                $this->validate([
                    'agent_name' => ['required', 'string', 'max:255'],
                    'agent_address' => ['required', 'string', 'max:500'],
                ]);
            }
        } elseif ($this->step === 4) {
            $this->validate([
                'user_name' => ['required', 'string', 'max:255'],
                'user_email' => ['required', 'string', 'email', 'max:255', $this->isAuthenticated ? 'unique:users,email,'.auth()->id() : 'unique:users,email'],
                'user_phone' => ['required', 'string', 'max:20'],
                'user_address' => ['required', 'string', 'max:500'],
            ]);
        } elseif ($this->step === 6 && ! $this->isAuthenticated) {
            $this->validate([
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
        }
    }

    #[Computed]
    public function stateOptions()
    {
        return [
            'Alabama',
            'Alaska',
            'Arizona',
            'Arkansas',
            'California',
            'Colorado',
            'Connecticut',
            'Delaware',
            'Florida',
            'Georgia',
            'Hawaii',
            'Idaho',
            'Illinois',
            'Indiana',
            'Iowa',
            'Kansas',
            'Kentucky',
            'Louisiana',
            'Maine',
            'Maryland',
            'Massachusetts',
            'Michigan',
            'Minnesota',
            'Mississippi',
            'Missouri',
            'Montana',
            'Nebraska',
            'Nevada',
            'New Hampshire',
            'New Jersey',
            'New Mexico',
            'New York',
            'North Carolina',
            'North Dakota',
            'Ohio',
            'Oklahoma',
            'Oregon',
            'Pennsylvania',
            'Rhode Island',
            'South Carolina',
            'South Dakota',
            'Tennessee',
            'Texas',
            'Utah',
            'Vermont',
            'Virginia',
            'Washington',
            'West Virginia',
            'Wisconsin',
            'Wyoming',
        ];
    }
};
