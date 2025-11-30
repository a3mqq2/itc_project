{{-- Reception Menu --}}

<li class="pc-item {{ request()->routeIs('reception.home') ? 'active' : '' }}">
    <a href="{{ route('reception.home') }}" class="pc-link">
        <span class="pc-micon">
            <i class="ti ti-home"></i>
        </span>
        <span class="pc-mtext">{{ __('messages.home') }}</span>
    </a>
</li>

<li class="pc-item pc-caption">
    <label>{{ __('messages.medical_data') }}</label>
</li>

<li class="pc-item {{ request()->routeIs('reception.medical-files.*') ? 'active' : '' }}">
    <a href="{{ route('reception.medical-files.index') }}" class="pc-link">
        <span class="pc-micon">
            <i class="ti ti-file-text"></i>
        </span>
        <span class="pc-mtext">{{ __('messages.medical_files') }}</span>
    </a>
</li>

<li class="pc-item {{ request()->routeIs('reception.appointments.*') ? 'active' : '' }}">
    <a href="{{ route('reception.appointments.index') }}" class="pc-link">
        <span class="pc-micon">
            <i class="ti ti-calendar-event"></i>
        </span>
        <span class="pc-mtext">{{ __('messages.appointments') }}</span>
    </a>
</li>
