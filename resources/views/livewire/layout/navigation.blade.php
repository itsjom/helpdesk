<?php

use Livewire\Volt\Component;

new class extends Component
{
    #[\Livewire\Attributes\On('profile-updated')]
    public function refreshProfile(): void
    {
        // Trigger re-render to update the displayed profile photo
    }

    public function logout(\App\Livewire\Actions\Logout $logout): void
    {
        $logout();
        $this->redirect('/', navigate: true);
    }
}; ?>

<aside class="w-[220px] bg-[#2d2d2d] h-screen flex flex-col sticky top-0 shadow-lg shadow-black/10">
    <!-- Logo Section -->
    <div class="h-16 flex items-center px-6 border-b border-white/10 shrink-0">
        <a href="{{ auth()->user()?->hasRole('admin') ? route('admin.dashboard') : route('user.tickets') }}" wire:navigate class="flex items-center gap-2">
            <img src="{{ asset('favicon.ico') }}" alt="Logo" class="w-6 h-6 object-contain">
            <span class="text-[15px] font-bold tracking-tight text-white uppercase letter-spacing-0.1em">Helpdesk</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-3 py-8 space-y-2 overflow-y-auto">
        @if(auth()->user()?->hasRole('admin') || !auth()->check())
            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </x-nav-link>
            <x-nav-link :href="route('admin.tickets')" :active="request()->routeIs('admin.tickets')" wire:navigate>
                {{ __('Tickets') }}
            </x-nav-link>
            <x-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')" wire:navigate>
                {{ __('Users') }}
            </x-nav-link>
            <x-nav-link :href="route('admin.faqs')" :active="request()->routeIs('admin.faqs')" wire:navigate>
                {{ __('FAQs') }}
            </x-nav-link>
            <x-nav-link :href="route('admin.reports')" :active="request()->routeIs('admin.reports')" wire:navigate>
                {{ __('Reports') }}
            </x-nav-link>
            
            <div class="pt-4 pb-2">
                <div class="px-3 text-xs font-semibold text-white/50 uppercase tracking-wider">
                    My Account
                </div>
            </div>
            
            <x-nav-link :href="route('user.tickets')" :active="request()->routeIs('user.tickets')" wire:navigate>
                {{ __('My Tickets') }}
            </x-nav-link>
            <x-nav-link :href="route('user.tickets.create')" :active="request()->routeIs('user.tickets.create')" wire:navigate>
                {{ __('Request Ticket') }}
            </x-nav-link>
        @else

            <x-nav-link :href="route('user.tickets')" :active="request()->routeIs('user.tickets')" wire:navigate>
                {{ __('My Tickets') }}
            </x-nav-link>
            <x-nav-link :href="route('user.tickets.create')" :active="request()->routeIs('user.tickets.create')" wire:navigate>
                {{ __('Request Ticket') }}
            </x-nav-link>
        @endif
    </nav>

    <!-- User Profile & Logout -->
    <div class="p-4 bg-white/5 border-t border-white/10 shrink-0">
        <div class="flex flex-col space-y-6">
            <div class="px-2 flex items-center gap-3">
                    <div class="h-9 w-9 rounded-none bg-white/10 flex items-center justify-center shrink-0 border border-white/20">
                        <span class="text-white text-xs font-bold">{{ substr(auth()->user()?->name ?? 'System', 0, 1) }}</span>
                    </div>
                <div class="min-w-0">
                    <div class="text-[13px] font-bold text-white truncate">{{ auth()->user()?->name ?? 'Guest User' }}</div>
                    <div class="text-[11px] text-white/40 truncate">{{ auth()->user()?->email ?? 'guest@system.local' }}</div>
                </div>
            </div>
            
            <div class="space-y-1">
                @auth
                <button wire:click="logout" class="w-full text-left px-2 py-2 text-sm text-white/70 hover:text-white hover:bg-white/10 rounded-md transition-colors flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Log Out
                </button>
                @endauth
            </div>
        </div>
    </div>
</aside>
