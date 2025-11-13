{{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
<div class="col-md-3 p-3">

    {{-- <h1 class="text-2xl font-bold mb-6">Create Admin / Vendor</h1> --}}

    {{-- @if (session('status'))
        <div class="mb-4 rounded bg-green-50 border border-green-200 text-green-800 px-4 py-2">
            {{ session('status') }}
        </div>
    @endif --}}
    {{-- <x-action-message on="user-created" class="mb-4 bg-green-500">
        User created!
    </x-action-message> --}}
    <!-- Trigger button -->
    <button @click="open = true" class="px-4 py-2 bg-blue-600 text-white rounded">
        Edit profile
    </button>

    <!-- Modal -->
    <div x-data="{ open: false }" x-show="open"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-md p-6 md:w-96" @click.away="open = false">
            <h2 class="text-lg font-bold">Update profile</h2>
            <p class="mt-2 text-gray-600">Make changes to your personal details.</p>

            <form class="mt-4 space-y-4">
                <div>
                    <label class="block text-gray-700">Name</label>
                    <input type="text" placeholder="Your name" class="w-full border rounded px-2 py-1">
                </div>

                <div>
                    <label class="block text-gray-700">Date of birth</label>
                    <input type="date" class="w-full border rounded px-2 py-1">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Save changes</button>
                </div>
            </form>
        </div>
    </div>




    <form wire:submit.prevent="createUser" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">Name</label>
            <input type="text" wire:model.defer="form.name" class="w-full rounded border-gray-300"
                placeholder="Full name" />
            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" wire:model.defer="form.email" class="w-full rounded border-gray-300"
                placeholder="email@example.com" />
            @error('email')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <div class="flex items-center justify-between">
                <label class="block text-sm font-medium mb-1">Password</label>
                <button type="button" wire:click="generatePassword" class="text-sm underline">Generate
                    strong</button>
            </div>
            <input type="text" wire:model.defer="form.password" class="w-full rounded border-gray-300"
                placeholder="Min 8 chars, mixed case, number" />
            @error('password')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Role</label>
            <select wire:model="form.role" class="w-full rounded border-gray-300">
                <option value="vendor">Vendor</option>
                <option value="admin">Admin</option>
            </select>
            @error('role')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Optional welcome email toggle --}}
        {{-- <label class="inline-flex items-center space-x-2">
            <input type="checkbox" wire:model="sendWelcome" class="rounded border-gray-300">
            <span>Send welcome email</span>
        </label> --}}

        <div class="pt-2">
            <button type="submit" class="px-4 py-2 bg-gray-900 text-white rounded hover:bg-black">
                Create User
            </button>
        </div>
    </form>
</div>

{{-- <div class="col-md-9"></div> --}}
