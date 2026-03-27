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
                            <div class="col-md-3 col-sm-6 mb-2 mb-md-0 text-end">
                                <a class="btn btn--base"
                                    title="@lang('Create Form')" href="{{ route('user.form.create') }}">
                                    <i class="fa-solid fa-plus"></i> @lang('Create Form')
                                </a>
                            </div>
                        </div>
                        <table class="table table--responsive--lg">
                            <thead>
                                <tr>
                                    <th>@lang('SI')</th>
                                    <th class="text-center">@lang('Title')</th>
                                    <th class="text-center">@lang('Submission Limit')</th>
                                    <th class="text-center">@lang('Answer Limit')</th>
                                    <th class="text-center">@lang('Status')</th>
                                    <th >@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($forms as $item)
                                    <tr>
                                        <td data-label="@lang('SI')">#{{ $loop->iteration }}</td>
                                        <td data-label="@lang('title')" class="text-center">
                                            <a href="{{ route('form.details',[slug($item->title),$item->id] ) }}">{{ __($item->title) }} <i class="fa-solid fa-arrow-up-right-from-square"></i></a>
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

                                        <td data-label="@lang('Details')" >
                                            <a class="btn btn--base btn--sm copyLinkBtn" href="javascript:void(0)"
                                                title="@lang('Copy')"
                                                data-url="{{ route('form.details',[slug($item->title),$item->id] ) }}">
                                                <i class="fa-solid fa-copy"></i>
                                            </a>

                                            <a class="btn btn--base btn--sm confirmationBtn" href="javascript:void(0)"
                                                title="@lang('Status')" title="@lang($item->status ? 'Disable' : 'Enable')"
                                                data-question="@lang('Are you sure to change this survey status?')"
                                                data-action="{{ route('user.form.status', $item->id) }}">
                                                <i class="fa-solid fa-left-right"></i>
                                            </a>

                                            <a class="btn btn--sm btn--base" title="@lang('View')"
                                                href="{{ route('user.form.view', $item->id) }}"><i
                                                    class="fa-solid fa-eye"></i></a>

                                            <a class="btn btn--base btn--sm" title="@lang('Submission List')"
                                                href="{{ route('user.form.answer.user.list', $item->id) }}">
                                                <i class="fa-solid fa-list"></i>
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
    <x-confirmation-modal></x-confirmation-modal>
@endsection

@push('script')
    <script>
        $(document).on('click', '.copyLinkBtn', function() {
            let link = $(this).data('url');

            navigator.clipboard.writeText(link)
                .then(() => {
                    notify('success', 'Link copied to clipboard!');
                })
                .catch(err => {
                    console.error('Failed to copy: ', err);
                });
        });
    </script>
@endpush
