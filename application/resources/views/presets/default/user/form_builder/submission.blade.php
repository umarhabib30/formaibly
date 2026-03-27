@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard__wrapper">
        <div class="row g-4 justify-content-center">
            <div class="col-xl-12">
                <div class="profile__wrap card p-4">
                    <div class="dashboard__table">
                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                            <h3 class="dashboard-table__title fs--16 fw--700 mb--16">@lang('Submission List')</h3>
                            <form action="">
                                <div class="search__box">
                                    <input type="text" class="form--control" name="search" value="{{ request()->search }}"
                                        placeholder="@lang('Search Title')">
                                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                                </div>
                            </form>
                     
                        </div>
                        <table class="table table--responsive--lg">
                            
                            <thead>
                                <tr>
                                    <th>@lang('SI')</th>
                                    <th class="text-center">@lang('Title')</th>
                                    <th class="text-center">@lang('Total Question')</th>
                                    <th class="text-center">@lang('Total Answer')</th>
                                    <th class="text-center">@lang('Empty Answer')</th>
                                    <th class="text-center">@lang('Average Quality')</th>
                                    <th class="text-center">@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($formSubmissions as $item)
                                    <tr>
                                        <td>#{{ $loop->iteration }}</td>
                                        <td class="text-center">  <a href="{{ route('form.details',[slug($item->form_builder->title),$item->form_builder->id] ) }}">{{ __($item->form_builder->title) }} <i class="fa-solid fa-arrow-up-right-from-square"></i></a></td>
                                        <td class="text-center">{{ $item->total_question }}</td>
                                        <td class="text-center">{{ $item->total_answer }}</td>
                                        <td class="text-center">{{ $item->empty_answer }}</td>
                                        <td class="text-center">{{ $item->average_quality }}%</td>

                                        <td class="text-center">
                                            @php
                                                echo $item->statusBadge($item->status);
                                            @endphp
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-end gap-2">

                                                <a href="{{ route('user.form.submission.details', $item->id) }}"
                                                    class="btn btn--sm btn--base" title="@lang('View')">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $formSubmissions->links() }}
                </div>
            </div>
        </div>
    </div>
    <x-confirmation-modal></x-confirmation-modal>
@endsection
