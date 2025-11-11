<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class UserCreate extends Component
{

    public array $form = [
        'name' => '',
        'email' => '',
        'password' => '',
        'role' => 'vendor', // default
    ];

    private array $allowedRoles = ['admin','vendor'];

     public function mount(): void
    {
        // Fast-fail guard
        abort_unless(Auth::check() && Auth::user()->role === 'admin', 403);
    }

    protected function rules(): array
    {
        return [
            'form.name'     => ['required','string','max:255'],
            'form.email'    => ['required','string','email','max:255','unique:users,email'],
            'form.password' => [ 'required', Password::min(8)->letters()->mixedCase()->numbers() ],
            'form.role'     => ['required','in:admin,vendor'],
        ];
    }

    public function generatePassword(): void
    {
        // Random strong password: 12 chars alnum + symbols
        $this->form['password'] = str()->password(12, symbols: true);
    }

    public function createUser(): void
    {
        $this->validate();

         if (! in_array($this->form['role'], $this->allowedRoles, true)) {
            $this->addError('form.role', 'Invalid role selected.');
            return;
        }

        // Double-check role input
        $user = User::create([
            'name'     => $this->form['name'],
            'email'    => $this->form['email'],
            'password' => Hash::make($this->form['password']),
            'role'     => $this->form['role'],
        ]);

        // (Optional) send welcome email here if you have mailable
        // if ($this->sendWelcome) {
        //     Mail::to($user->email)->send(new \App\Mail\WelcomeUserMailable($user, $this->password));
        // }

        // Reset form (except role)
        $this->form = [
            'name' => '',
            'email' => '',
            'password' => '',
        ];
        $this->dispatch('user-created');

        session()->flash('status', 'User created: '.$user->email.' ('.$user->role.')');
    }

    public function render()
    {
        return view('livewire.admin.user-create');
    }
}
