@php
    $user = auth()->user();
@endphp

<!--==========================   User-sidebar Start  ==========================-->
<div class="dashboard__sidebar">
    <div class="sidebar__close">
        <i class="las la-times"></i>
    </div>
    <div class="dashboard__logo">
        <a href="{{ route('home') }}">
            <img src="{{ getImage(getFilePath('logoIcon') . '/logo_white.png', '?' . time()) }}"
                alt="{{ config('app.name') }}">
        </a>
    </div>
    <div class="dashboard__menu">
        <ul>
            <li>
                <a href="{{ route('user.home') }}" class="{{ Route::is('user.home') ? 'active' : '' }}">
                    <i class="fa-solid fa-table-columns"></i>
                    @lang('Dashboard')
                </a>
            </li>


            <li>
                <a href="#my-forms"
                    class="{{ Route::is('user.form.index') ||
                    Route::is('user.form.submission') ||
                    Route::is('user.form.view') ||
                    Route::is('user.form.create') ||
                    Route::is('user.form.answer.user.list') ||
                    Route::is('user.form.answer.detail')
                        ? 'active'
                        : 'collapsed' }}"
                    data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ Route::is('user.form.index') || Route::is('user.form.submission') || Route::is('user.form.create') || Route::is('user.form.view') || Route::is('user.form.answer.user.list') || Route::is('user.form.answer.detail') || Route::is('form.details')  ? 'true' : 'false' }}"
                    aria-controls="my-forms">
                    <i class="fa-solid fa-table-list"></i>
                    @lang('Forms')
                    <span class="dropdown__arrow"><i class="fa-solid fa-chevron-right"></i></span></a>
                <div class="collapse {{ Route::is('user.form.index') || Route::is('user.form.create') || Route::is('user.form.submission') || Route::is('user.form.view') || Route::is('user.form.answer.user.list') || Route::is('user.form.answer.detail') || Route::is('form.details')  ? 'show' : '' }}"
                    id="my-forms">
                    <div class="sidebar__dropdown">
                        <ul>
                            <li>
                                <a href="{{ route('user.form.index') }}"
                                    class="{{ Route::is('user.form.index')|| Route::is('user.form.create') || Route::is('user.form.view') || Route::is('user.form.answer.user.list') || Route::is('user.form.answer.detail') ? 'active' : '' }}">
                                    <i class="fa-solid fa-indent"></i>
                                    @lang('Form List')
                                </a>
                            </li>
                            <li> <a href="{{ route('user.form.submission') }}"
                                    class="{{ Route::is('user.form.submission') ? 'active' : '' }}">
                                    <i class="fa-solid fa-clipboard-check"></i>
                                    @lang('Submission List')
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>

            <li>
                <a href="#payments"
                    class="{{ Route::is('user.credit.purchase') || Route::is('user.deposit') || Route::is('user.deposit.history') ? 'active' : 'collapsed' }}"
                    data-bs-toggle="collapse" role="button"
                    aria-expanded="{{ Route::is('user.credit.purchase') || Route::is('user.deposit') || Route::is('user.deposit.history') ? 'true' : 'false' }}"
                    aria-controls="payments">
                    <i class="fa-solid fa-money-bills"></i>
                    @lang('Payments')
                    <span class="dropdown__arrow"><i class="fa-solid fa-chevron-right"></i></span></a>
                <div class="collapse {{ Route::is('user.credit.purchase') || Route::is('user.deposit') || Route::is('user.deposit.history') ? 'show' : '' }}"
                    id="payments">
                    <div class="sidebar__dropdown">
                        <ul>
                            <li>
                                <a href="{{ route('user.credit.purchase') }}"
                                    class="{{ Route::is('user.credit.purchase') ? 'active' : '' }}">
                                    <i class="fa-solid fa-coins"></i>
                                    @lang('Credit Purchase')
                                </a>
                            </li>
                            <li> <a href="{{ route('user.deposit') }}"
                                    class="{{ Route::is('user.deposit') ? 'active' : '' }}">
                                    <i class="fa-solid fa-money-bill-transfer"></i>
                                    @lang('Deposit')
                                </a>
                            </li>
                            <li><a href="{{ route('user.deposit.history') }}"
                                    class="{{ Route::is('user.deposit.history') ? 'active' : '' }}">
                                    <i class="fa-solid fa-money-bill-1-wave"></i>
                                    @lang('Payments History')
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </li>

            <li>
                <a href="{{ route('user.transactions') }}"
                    class="{{ Route::is('user.transactions') ? 'active' : '' }}">
                    <i class="fa-solid fa-right-left"></i>
                    @lang('Transactions')
                </a>
            </li>
            <li>
                <a href="{{ route('ticket') }}" class="{{ Route::is('ticket') ? 'active' : '' }}">
                    <i class="fa-solid fa-ticket"></i>
                    @lang('Support Tickets')
                </a>
            </li>
            <li>
                <a href="{{ route('user.change.password') }}"
                    class="{{ Route::is('user.change.password') ? 'active' : '' }}">
                    <i class="fa-solid fa-key"></i>
                    @lang('Change Password')
                </a>
            </li>
            <li>
                <a href="{{ route('user.profile.setting') }}"
                    class="{{ Route::is('user.profile.setting') ? 'active' : '' }}">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                    @lang('Profile Setting')</a>
            </li>
            <li>
                <a href="{{ route('user.twofactor') }}" class="{{ Route::is('user.twofactor') ? 'active' : '' }}">
                    <i class="fa-solid fa-diagram-project"></i>
                    @lang('2FA Security')
                </a>
            </li>

            <li>
                <a href="{{ route('user.logout') }}">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    @lang('Logout')
                </a>
            </li>
        </ul>
    </div>
</div>
<!--==========================  User-sidebar End  ==========================-->
