@php
    $user = auth()->user();
    $userNotifications = \App\Models\UserNotification::where('user_id', $user->id)->orderBy('id', 'desc')->get();
    $userNotificationUnreadCount = \App\Models\UserNotification::where('user_id', $user->id)
        ->where('read_status', 1)
        ->count();
@endphp

<div class="dashboard__header">
    <div class="search__box">
        <h4>{{ __($pageTitle) }}</h4>
    </div>
    <div class="dashboard__header__widgets">
        <div class="dropdown">
            <button type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="notification__btn">
                    <i class="las la-bell"></i>
                    @if ($userNotificationUnreadCount)
                        <div class="blob white notification-dot">
                            <span class="notification__count">
                                <span>{{ $userNotificationUnreadCount }}</span>
                            </span>
                        </div>
                    @endif
                </span>
            </button>
            <div class="dropdown-menu">
                <div class="notification__wrap">
                    <div class="notification__header">
                        <h4>@lang('Notifications')</h4>
                        <span class="badge badge--base">{{ $userNotificationUnreadCount }} @lang('Unread')</span>
                    </div>
                    <div class="notification__body">
                        @foreach ($userNotifications ?? [] as $item)
                            <a 
                                href="{{ route('user.read.notification', $item->id) }}">
                                <p>
                                    {{ __(strLimit($item->title, 30)) }}
                                </p>
                                <span>{{ diffForHumans($item->created_at) }}</span>
                            </a>
                        @endforeach
                    </div>
                    <div class="notification__footer">
                        <a href="{{ route('user.notification.all') }}" class="btn btn--sm btn--base w-100">
                            @lang('View all')
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="dropdown">
            <button class="dropdown-toggle d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                <span class="profile__dropdown">
                    <img
                        src="{{ getImage(getFilePath('userProfile') . '/' . $user->image, getFileSize('userProfile')) }}"alt="@lang('image')">
                    <span>{{ $user->fullname ?? '' }}</span>
                </span>
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item" href="{{ route('user.profile.setting') }}">@lang('Profile Setting')</a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('user.change.password') }}">@lang('Change Password')</a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('user.twofactor') }}">@lang('2FA Security')</a>
                </li>
                <li>
                    <a class="dropdown-item" href="{{ route('user.logout') }}">@lang('Logout')</a>
                </li>
            </ul>
        </div>
        <span class="sidebar__open"><i class="fa-solid fa-bars"></i></span>
    </div>
</div>
