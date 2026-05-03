<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        if (auth()->user()->role === 'admin') {
            $this->redirectIntended(default: route('admin.dashboard', absolute: false), navigate: true);
        } else {
            $this->redirectIntended(default: route('user.tickets', absolute: false), navigate: true);
        }
    }
}; ?>

<div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-8" :status="session('status')" />

    <form wire:submit="login" class="space-y-10">
        <!-- Username -->
        <div class="space-y-2">
            <label for="username" class="text-[11px] font-medium text-[#999999] uppercase tracking-widest block">
                {{ __('Username') }}
            </label>
            <input wire:model="form.username" id="username" type="text" name="username" required autofocus autocomplete="username" 
                class="input-field w-full">
            <x-input-error :messages="$errors->get('form.username')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="space-y-2">
            <div class="flex justify-between items-end">
                <label for="password" class="text-[11px] font-medium text-[#999999] uppercase tracking-widest block">
                    {{ __('Password') }}
                </label>
                @if (Route::has('password.request'))
                    <a class="text-[11px] text-[#999999] hover:text-[#2d2d2d] transition-colors underline underline-offset-4 decoration-[#e5e5e5]" href="{{ route('password.request') }}" wire:navigate>
                        {{ __('Forgot password?') }}
                    </a>
                @endif
            </div>
            <input wire:model="form.password" id="password" type="password" name="password" required autocomplete="current-password"
                class="input-field w-full">
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember" class="flex items-center cursor-pointer group">
                <input wire:model="form.remember" id="remember" type="checkbox" name="remember" 
                    class="w-4 h-4 rounded-none border-[#e5e5e5] text-[#2d2d2d] focus:ring-0 focus:ring-offset-0 transition-all cursor-pointer">
                <span class="ms-3 text-[13px] text-[#555555] group-hover:text-[#2d2d2d] transition-colors">{{ __('Keep me signed in') }}</span>
            </label>
        </div>

        <div class="pt-4">
            <button type="submit" class="btn-primary w-full py-4 text-[15px] font-semibold tracking-wide uppercase">
                {{ __('Sign In') }}
            </button>
        </div>
    </form>
</div>
