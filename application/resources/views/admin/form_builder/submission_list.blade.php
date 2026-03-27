@extends('admin.layouts.app')
@section('panel')
    <form method="GET" id="statusForm">
        <div class="row gy-4 justify-content-end mb-3">
            <div class="col-xl-2 col-lg-6">
                <div class="d-flex justify-content-end">
                    <select id="status-filter" name="status" class="form-control form-select bg--transparent outline">
                        <option value="all" {{ request()->status == 'all' ? 'selected' : '' }}>@lang('All')</option>
                        <option value="approved" {{ request()->status == 'approved' ? 'selected' : '' }}>@lang('Approved')
                        </option>
                        <option value="pending" {{ request()->status == 'pending' ? 'selected' : '' }}>@lang('Pending')
                        </option>
                        <option value="reject" {{ request()->status == 'reject' ? 'selected' : '' }}>@lang('Rejected')
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
                                    <th class="text-center">@lang('Survey Title')</th>
                                    <th class="text-center">@lang('Created-At')</th>
                                    <th class="text-center">@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody id="items_table__body">
                                @forelse($formBuilderAnswers as $item)
                                    <tr>
                                        <td>#{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ __($item?->form_builder->title) }}</td>
                                        <td class="text-center">{{ showDateTime($item->created_at) }}</td>
                                        <td class="text-center">
                                            @php
                                                echo $item->statusBadge($item->status);
                                            @endphp
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-end gap-2">
                                                <a href="{{ route('admin.form.builder.submission.detail', $item->id) }}"
                                                    class="btn btn-sm" title="@lang('View')">
                                                    <i class="fa-solid fa-eye"></i>
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

                <div id="pagination-wrapper" class="pagination__wrapper py-4 {{ $formBuilderAnswers->hasPages() ? '' : 'd-none' }}">
                    @if ($formBuilderAnswers->hasPages())
                        {{ paginateLinks($formBuilderAnswers) }}
                    @endif
                </div>
            </div>
        </div>
    </div>
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
