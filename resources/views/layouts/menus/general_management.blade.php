{{-- General Management Menu --}}

<li class="pc-item {{ request()->routeIs('general-management.home') ? 'active' : '' }}">
    <a href="{{ route('general-management.home') }}" class="pc-link">
        <span class="pc-micon">
            <i class="ti ti-home"></i>
        </span>
        <span class="pc-mtext">{{ __('messages.home') }}</span>
    </a>
</li>

<li class="pc-item pc-caption">
    <label>{{ __('messages.user_management') }}</label>
</li>

<li class="pc-item {{ request()->routeIs('general-management.users.*') ? 'active' : '' }}">
    <a href="{{ route('general-management.users.index') }}" class="pc-link">
        <span class="pc-micon">
            <i class="ti ti-users"></i>
        </span>
        <span class="pc-mtext">{{ __('messages.users') }}</span>
    </a>
</li>

<li class="pc-item pc-caption">
    <label>{{ __('messages.medical_data') }}</label>
</li>

<li class="pc-item {{ request()->routeIs('general-management.medical-file-categories.*') ? 'active' : '' }}">
    <a href="{{ route('general-management.medical-file-categories.index') }}" class="pc-link">
        <span class="pc-micon">
            <i class="ti ti-folder"></i>
        </span>
        <span class="pc-mtext">{{ __('messages.medical_file_categories') }}</span>
    </a>
</li>
