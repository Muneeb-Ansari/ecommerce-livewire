<div class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a 
                    class="nav-link {{ request()->is('/') ? 'active' : '' }}" 
                    href="{{ url('/') }}"
                >
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>

            @can('viewAny', App\Models\User::class)
                <li class="nav-item">
                    <a 
                        class="nav-link {{ request()->is('users*') ? 'active' : '' }}" 
                        href="{{ url('/users') }}"
                    >
                        <i class="fas fa-users"></i> Users
                    </a>
                </li>
            @endcan

            <li class="nav-item">
                <a 
                    class="nav-link {{ request()->is('tasks*') ? 'active' : '' }}" 
                    href="{{ url('/tasks') }}"
                >
                    <i class="fas fa-tasks"></i> Tasks
                </a>
            </li>
        </ul>
    </div>
</div>
