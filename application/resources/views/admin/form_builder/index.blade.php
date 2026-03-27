@extends('admin.layouts.app')
@section('panel')
    <form method="GET" id="statusForm">
        <div class="row gy-4 justify-content-between mb-3 pb-3">
            <div class="col-xl-4 col-lg-6">
                <div class="d-flex flex-wrap justify-content-start">
                    <div class="search-input--wrap position-relative">
                        <input type="text" name="search" class="form-control" placeholder="@lang('Search Survey Title')..."
                            value="{{ request()->search ?? '' }}">
                        <button class="search--btn position-absolute"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-lg-6">
                <div class="d-flex justify-content-end">
                    <select id="status-filter" name="status" class="form-control form-select bg--transparent outline">
                        <option value="all" {{ request()->status == 'all' ? 'selected' : '' }}>@lang('All')</option>
                        <option value="enable" {{ request()->status == 'enable' ? 'selected' : '' }}>@lang('Enable')
                        </option>
                        <option value="disable" {{ request()->status == 'disable' ? 'selected' : '' }}>@lang('Disable')
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </form>

    <div class="row gy-4">
        <div class="col-md-12 mb-30">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two custom-data-table">
                            <thead>
                                <tr>
                                    <th>@lang('SI')</th>
                                    <th>@lang('Author')</th>
                                    <th>@lang('Title')</th>
                                    <th>@lang('Submission Limit')</th>
                                    <th>@lang('Question Limit')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody id="items_table__body">
                                @forelse($formBuilders as $item)
                                    <tr>
                                        <td>
                                            @if ($item->pendingCount())
                                                <div class="blob white pointer-dot"></div>
                                            @endif
                                            #{{ $loop->iteration }}
                                        </td>
                                        <td> 
                                            <a href="{{ route('admin.users.detail', $item->user->id) }}">
                                                {{ '@' . $item?->user->username }}
                                          
                                            </a>
                                        </td>

                                        <td>{{ $item->title }}</td>
                                        <td>{{ $item->submission_limit }}</td>
                                        <td>{{ $item->question_limit }}</td>

                                        <td>
                                            @php
                                                echo $item->statusBadge($item->status);
                                            @endphp
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-end gap-2">
                                              
                                                <a href="{{ route('admin.form.builder.details', $item->id) }}" class="btn btn-sm"
                                                    title="@lang('View')">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.form.builder.submission.list', $item->id) }}"
                                                    class="btn btn-sm" title="@lang('List')">
                                                    <i class="fa-solid fa-list-check"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="pagination-wrapper"
                    class="pagination__wrapper py-4 {{ $formBuilders->hasPages() ? '' : 'd-none' }}">
                    @if ($formBuilders->hasPages())
                        {{ paginateLinks($formBuilders) }}
                    @endif
                </div>
            </div>
        </div>
    </div>


    <x-confirmation-modal></x-confirmation-modal>
@endsection

@push('script')
    <script>
        (function($) {
            "use strict";

            $('#status-filter').on('change', function() {
                $('#statusForm').submit();
            });

            const message = sessionStorage.getItem('success_message');
            if (message) {
                setTimeout(() => {
                    notify('success', message);
                    sessionStorage.removeItem('success_message');
                }, 500);
            }

        })(jQuery);
    </script>
@endpush
