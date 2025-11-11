<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="max-w-xl w-100 rounded-2xl border border-gray-200 bg-white shadow-sm">
        {{-- Header / Title --}}
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Manage User</h2>
        </div>

        {{-- Body --}}
        <div class="px-5 py-4">
            <p class="text-gray-600">Create and manage user here</p>

            @if (session('status'))
                <div class="mt-3 rounded-md bg-green-50 border border-green-200 px-3 py-2 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif
        </div>

        {{-- Footer with Button --}}
        <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-end gap-3">
            
            <x-secondary-button as="a" href="{{route('admin.users.create')}}" class="text-white bg-indigo-600 hover:bg-indigo-700" wire:navigate>
                {{__('Create User')}}
            </x-secondary-button>
        </div>
    </div>

</x-app-layout>
