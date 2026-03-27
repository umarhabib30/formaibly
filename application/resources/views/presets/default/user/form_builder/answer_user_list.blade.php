@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard__wrapper">
        <div class="row g-4 justify-content-center">
            <div class="col-xl-12">
                <div class="profile__wrap card p-4">
                    <div class="dashboard__table">
                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                            <form action="">
                                <div class="search__box">
                                    <input type="text" class="form--control" name="search" value="{{ request()->search }}"
                                        placeholder="@lang('Search Title')">
                                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <table class="table table--responsive--lg">
                        <thead>
                            <tr>
                                <th>@lang('SI')</th>
                                <th class="text-center">@lang('Title')</th>
                                <th class="text-center">@lang('Created At')</th>
                                <th class="text-center">@lang('Status')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($formBuilderAnswers as $item)
                                <tr>
                                    <td data-label="@lang('SI')">#{{ $loop->iteration }}</td>
                               
                                    <td data-label="@lang('title')" class="text-center">
                                       <a href="{{ route('form.details',[slug($item?->form_builder->title),$item?->form_builder->id] ) }}">{{ __($item?->form_builder->title) }} <i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                                    </td>

                                    <td class="text-center" data-label="@lang('Created At')">
                                        {{ showDateTime($item?->form_builder->created_at) }}</td>

                                    <td class="text-center" data-label="@lang('Status')">
                                        @php
                                            echo $item->statusBadge($item?->form_builder->status);
                                        @endphp
                                    </td>

                                    <td data-label="@lang('Details')">
                                        <a href="{{ route('user.form.answer.detail', $item->id) }}"
                                            class="btn btn--sm btn--base" title="@lang('View')">
                                            <i class="fa-solid fa-eye"></i>
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
                {{ $formBuilderAnswers->links() }}
            </div>
        </div>
    </div>
@endsection
