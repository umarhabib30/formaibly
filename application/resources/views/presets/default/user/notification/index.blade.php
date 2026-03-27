@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard__wrapper">
        <div class="row g-4 justify-content-center">
            <div class="col-xl-12">
                <div class="profile__wrap card p-4">
                    <div class="dashboard__table">
                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                            <h5 class="mb-0">@lang('Notifications')</h5>
                            <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
                                <form action="">
                                    <div class="search__box">
                                        <input type="text" class="form--control" name="search"
                                            value="{{ request()->search }}" placeholder="@lang('Search Title')">
                                        <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <table class="table table--responsive--md">
                            <thead>
                                <tr>
                                    <th>@lang('SI No')</th>
                                    <th class="text-center">@lang('Title')</th>
                                    <th class="text-center">@lang('Read Status')</th>
                                    <th class="text-center">@lang('Date')</th>
                                    <th>@lang('Details')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($notifications ?? [] as $item)
                                    <tr>
                                        <td data-label="@lang('SI No')">#{{ $loop->iteration }}</td>
                                        <td data-label="@lang('Title')">{{ __(strLimit($item->title, 50)) }}
                                        </td>
                                        <td data-label="@lang('Read Status')">
                                            @php
                                                echo $item->statusBadge($item->status);
                                            @endphp
                                        </td>
                                        <td data-label="@lang('Date')" class="text-center">
                                            {{ showDateTime($item->created_at) }} </td>


                                        <td data-label="@lang('Details')">
                                            <a href="{{ route('user.read.notification', $item->id) }}"
                                                class="btn btn--base btn--sm action--btn">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="100%" class="text-center">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
