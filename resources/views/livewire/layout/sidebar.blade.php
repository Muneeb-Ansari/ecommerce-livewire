{{-- Responsive, collapsible sidebar using Tailwind + Alpine + Livewire --}}
<div
    x-data="{
        // entangle = Livewire <-> Alpine two-way binding
        open: @entangle('open'),
        collapsed: @entangle('collapsed'),
    }"
    class="relative"
>
    {{-- Top bar (mobile): hamburger button --}}
    <div class="lg:hidden flex items-center justify-between px-4 py-3 border-b">
        <button @click="open = true" class="p-2 rounded-md border hover:bg-gray-50">
            <!-- Hamburger icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <div class="font-semibold">Dashboard</div>

        <button @click="$dispatch('toggle-theme')" class="p-2 rounded-md border hover:bg-gray-50">
            <!-- placeholder action -->
            T
        </button>
    </div>

    {{-- Overlay (mobile) --}}
    <div
        x-show="open"
        x-transition.opacity
        class="fixed inset-0 bg-black/30 z-30 lg:hidden"
        @click="open = false"
    ></div>

    {{-- Sidebar container --}}
    <aside
        class="fixed z-40 inset-y-0 left-0 bg-white border-r shadow-sm
               transition-[width] duration-200 ease-in-out
               flex flex-col"
        :class="{
            'w-64': !collapsed,
            'w-20': collapsed,
            // mobile
            'translate-x-0 lg:translate-x-0': open,
            '-translate-x-full lg:translate-x-0': !open
        }"
        x-transition
    >
        {{-- Brand / collapse button --}}
        <div class="h-14 flex items-center justify-between px-3 border-b">
            <div class="flex items-center gap-2 overflow-hidden">
                <div class="h-8 w-8 rounded-lg bg-indigo-600"></div>
                <span class="font-semibold" x-show="!collapsed" x-transition>SoftHeight</span>
            </div>

            <button
                class="p-2 rounded-md hover:bg-gray-50 hidden lg:inline-flex"
                @click="collapsed = !collapsed"
                x-tooltip="Collapse"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M15 19l-7-7 7-7" />
                </svg>
            </button>

            {{-- Mobile close --}}
            <button class="p-2 rounded-md hover:bg-gray-50 lg:hidden" @click="open = false">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto py-3">
            @php
                $items = [
                    ['label' => 'Dashboard', 'icon' => 'M3 12h18M3 6h18M3 18h18', 'route' => 'dashboard'],
                    ['label' => 'Users',     'icon' => 'M5.121 17.804A7 7 0 1118.879 6.196...', 'route' => 'admin.users.index'],
                    ['label' => 'Create User','icon'=> 'M12 4v16m8-8H4', 'route' => 'admin.users.create'],
                    ['label' => 'Settings',  'icon' => 'M12 8c-2.209 0-4 1.791-4 4...', 'route' => 'settings.index'],
                ];
            @endphp

            <ul class="space-y-1">
                @foreach ($items as $item)
                    @php
                        // $active = request()->routeIs($item['route']);
                    @endphp

                    <li>
                        <a
                            {{-- Livewire SPA nav (v3) --}}
                            {{-- wire:navigate --}}
                            {{-- href="{{ route($item['route']) }}" --}}
                            {{-- class="group flex items-center gap-3 mx-2 px-3 py-2 rounded-md text-sm
                                   transition-colors
                                   {{ $active ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}" --}}
                        >
                            {{-- Simple icons as inline SVG paths (placeholders) --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="{{ $item['icon'] }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            <span x-show="!collapsed" x-transition class="truncate">{{ $item['label'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        {{-- Footer actions --}}
        <div class="border-t p-3">
            {{-- <a href="{{ route('profile.show') }}" --}}
               {{-- wire:navigate
               class="flex items-center gap-3 px-3 py-2 rounded-md text-sm hover:bg-gray-50"> --}}
                <div class="h-8 w-8 rounded-full bg-gray-200"></div>
                <div class="min-w-0" x-show="!collapsed" x-transition>
                    <div class="font-medium truncate">Muneeb Ansari</div>
                    <div class="text-xs text-gray-500 truncate">Admin</div>
                </div>
            </a>
        </div>
    </aside>

    {{-- Page content wrapper: give left padding so content is visible beside sidebar --}}
    <div class="lg:pl-[16rem]" :class="collapsed ? 'lg:pl-20' : 'lg:pl-[16rem]'">
        {{-- Your page content will go here; keep this wrapper in your layout --}}
        {{ $slot ?? '' }}
    </div>
</div>
