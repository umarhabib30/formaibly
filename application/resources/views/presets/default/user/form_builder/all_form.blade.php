@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard__wrapper">
        <div class="row g-4 justify-content-center">
            <div class="col-xl-12">
                <div class="profile__wrap card p-4">
                    <div class="dashboard__table">
                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                            <h5 class="mb-0">{{__($pageTitle)}}</h5>
                            <form action="">
                                <div class="search__box">
                                    <input type="text" class="form--control" name="search"
                                        value="{{ request()->search }}" placeholder="@lang('Search Title')">
                                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                                </div>
                            </form>
                            
                        </div>
                        <table class="table table--responsive--lg">
                            <thead>
                                <tr>
                                    <th>@lang('SI')</th>
                                    <th class="text-center">@lang('Title')</th>
                                    <th class="text-center">@lang('Submission Limit')</th>
                                    <th class="text-center">@lang('Answer Limit')</th>
                                    <th class="text-center">@lang('Status')</th>
                                    <th class="text-center">@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($forms ?? [] as $item)
                                    <tr>
                                        <td data-label="@lang('SI')">#{{ $loop->iteration }}</td>
                                        <td data-label="@lang('title')" class="text-center">
                                            {{ __($item->title) }}
                                        </td>

                                        <td data-label="@lang('Answer Limit')" class="text-center">
                                            {{ $item->submission_limit }}
                                        </td>

                                        <td data-label="@lang('People Answer')" class="text-center">
                                            {{ $item->question_limit }}
                                        </td>

                                        <td data-label="@lang('Status')" class="text-center">
                                            @php
                                                echo $item->statusBadge($item->status);
                                            @endphp
                                        </td>

                                        <td data-label="@lang('Details')" class="text-center">
                                            <a class="btn btn--base custom--btn"
                                                href="{{ route('form.details',[slug($item->title),$item->id] ) }}">
                                                @lang('Start Form')
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
                    {{ $forms->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
