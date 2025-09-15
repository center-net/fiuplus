<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public string $credential = '';
    public string $password = '';
    public bool $remember = false;

    protected $rules = [
        'credential' => 'required|string',
        'password' => 'required|string',
    ];

    public function login()
    {
        $this->validate();

        $fieldType = filter_var($this->credential, FILTER_VALIDATE_EMAIL) ? 'email' : (is_numeric($this->credential) ? 'phone' : 'username');

        $credentials = [
            $fieldType => $this->credential,
            'password' => $this->password
        ];

        if (Auth::attempt($credentials, $this->remember)) {
            session()->regenerate();

            return redirect()->intended('/');
        }

        session()->flash('error', 'بيانات الاعتماد المقدمة غير صحيحة.');
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('layouts.auth', ['title' => 'تسجيل الدخول']);
    }
}