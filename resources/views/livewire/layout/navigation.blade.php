<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    #[\Livewire\Attributes\On('profile-updated')]
    public function refreshProfile(): void
    {
        // Trigger re-render to update the displayed profile photo
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<aside class="w-[220px] bg-[#2d2d2d] h-screen flex flex-col sticky top-0 shadow-lg shadow-black/10">
    <!-- Logo Section -->
    <div class="h-16 flex items-center px-6 border-b border-white/10 shrink-0">
        <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('user.tickets') }}" wire:navigate class="flex items-center">
            <span class="text-[15px] font-bold tracking-tight text-white uppercase letter-spacing-0.1em">Helpdesk</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-3 py-8 space-y-2 overflow-y-auto">
        @if(auth()->user()->role === 'admin')
            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </x-nav-link>
            <x-nav-link :href="route('admin.tickets')" :active="request()->routeIs('admin.tickets*')" wire:navigate>
                {{ __('Tickets') }}
            </x-nav-link>
            <x-nav-link :href="route('admin.users')" :active="request()->routeIs('admin.users')" wire:navigate>
                {{ __('Users') }}
            </x-nav-link>
            <x-nav-link :href="route('admin.reports')" :active="request()->routeIs('admin.reports')" wire:navigate>
                {{ __('Reports') }}
            </x-nav-link>
        @else
            <div class="px-3 mb-4">
                <span class="text-[10px] font-bold text-white/30 uppercase tracking-[0.2em]">My Workspace</span>
            </div>
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
                @if (auth()->user()->profile_photo_path)
                    <img src="{{ Storage::url(auth()->user()->profile_photo_path) }}" class="h-9 w-9 rounded-full object-cover shrink-0 border border-white/20">
                @else
                    <div class="h-9 w-9 rounded-full bg-white/10 flex items-center justify-center shrink-0 border border-white/20">
                        <span class="text-white text-xs font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                @endif
                <div class="min-w-0">
                    <div class="text-[13px] font-bold text-white truncate">{{ auth()->user()->name }}</div>
                    <div class="text-[11px] text-white/40 truncate">{{ auth()->user()->username }}</div>
                </div>
            </div>
            
            <div class="space-y-1">
                <a href="{{ route('profile') }}" wire:navigate class="flex items-center px-3 py-2 text-[12px] font-medium text-white/60 hover:bg-white/10 hover:text-white rounded-lg transition-all">
                    {{ __('Profile Settings') }}
                </a>
                <button wire:click="logout" class="w-full flex items-center px-3 py-2 text-[12px] font-medium text-white/60 hover:bg-white/10 hover:text-white rounded-lg transition-all">
                    {{ __('Log Out') }}
                </button>
            </div>
        </div>
    </div>
</aside>
