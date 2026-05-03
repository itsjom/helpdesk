<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $username = '';
    public $photo;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->username = Auth::user()->username;
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'lowercase', 'max:50', Rule::unique(User::class)->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png,gif,webp', 'max:4096'],
        ]);

        if ($this->photo) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $validated['profile_photo_path'] = $this->photo->store('profile-photos', 'public');
        }

        unset($validated['photo']);
        $user->fill($validated);

        $user->save();

        $this->photo = null;

        $this->dispatch('profile-updated', name: $user->name);
    }
}; ?>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and username.") }}
        </p>
    </header>

    <form wire:submit="updateProfileInformation" class="mt-6 space-y-6">
        <!-- Profile Photo -->
        <div>
            <x-input-label for="photo" :value="__('Profile Photo')" />
            
            <div class="mt-2 flex items-center gap-4">
                <div class="h-20 w-20 rounded-full overflow-hidden bg-gray-100 border border-gray-200 shrink-0">
                    @if ($photo)
                        <img src="{{ $photo->temporaryUrl() }}" class="h-full w-full object-cover">
                    @elseif (auth()->user()->profile_photo_path)
                        <img src="{{ Storage::url(auth()->user()->profile_photo_path) }}" class="h-full w-full object-cover">
                    @else
                        <svg class="h-full w-full text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    @endif
                </div>

                <div>
                    <input type="file" wire:model="photo" id="photo" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#2d2d2d] file:text-white hover:file:bg-[#454545] transition-colors cursor-pointer" accept=".jpg,.jpeg,.png,.gif,.webp">
                    <div wire:loading wire:target="photo" class="text-sm text-gray-500 mt-2">Uploading...</div>
                    <x-input-error class="mt-2" :messages="$errors->get('photo')" />
                </div>
            </div>
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input wire:model="name" id="name" name="name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input wire:model="username" id="username" name="username" type="text" class="mt-1 block w-full" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('username')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            <x-action-message class="me-3" on="profile-updated">
                {{ __('Saved.') }}
            </x-action-message>
        </div>
    </form>
</section>
