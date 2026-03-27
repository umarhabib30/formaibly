@extends('admin.layouts.app')

@section('panel')
    <div class="row gy-4 justify-content-between mb-3 pb-3">
        <div class="col-xl-3 col-md-6">
            <div class="d-flex flex-wrap justify-content-start w-100">
                <form class="form-inline w-100">
                    <div class="search-input--wrap position-relative">
                        <input type="text" name="search" class="form-control" placeholder="@lang('Search')..."
                            value="{{ request()->search ?? '' }}">
                        <button class="search--btn position-absolute"><i class="fa fa-search"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>@lang('SI')</th>
                                    <th>@lang('User')</th>
                                    <th>@lang('Plan')</th>
                                    <th>@lang('Price')</th>
                                    <th>@lang('Period')</th>
                                    <th>@lang('Started At')</th>
                                    <th>@lang('Expired At')</th>
                                    <th>@lang('Status')</th>
                                </tr>
                            </thead>
                            <tbody id="items_table__body">
                                @forelse($subscriptions as $index => $item)
                                    <tr>
                                        <td>#{{ $index+1 }}</td>
                                        <td class="user--td">
                                            <div class="d-flex justify-content-between justify-content-lg-start gap-3">
                                                <div
                                                    class="user--info d-flex gap-3 align-items-start align-items-lg-center justify-content-lg-start justify-content-end flex-wrap flex-md-nowrap">
                                                    <div class="user--thumb-two">
                                                        <img src="{{ getImage(getFilePath('userProfile') . '/' . $item?->user->image) }}"
                                                            alt="@lang('Image')">
                                                    </div>
                                                    <div class="user--content ">
                                                        @if ($item->user)
                                                            <a href="{{ route('admin.users.detail', $item->user->id) }}">
                                                                {{ $item->user->fullname }}
                                                                <p>{{ '@' . $item->user?->username }}</p>
                                                            </a>
                                                            @else
                                                            <p>@lang('N/A')</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $item?->plan->name }}</td>
                                        <td>{{ __($general->cur_sym) }}{{ showAmount($item?->plan->price) }}</td>
                                        <td>
                                            @if ($item?->plan->period == 'monthly')
                                                <span class="badge badge--warning">@lang('Monthly')</span>
                                            @else
                                                <span class="badge badge--success">@lang('Yearly')</span>
                                            @endif
                                        </td>
                                        <td>{{ showDateTime($item->started_at, 'd M, Y') }}</td>
                                        <td>{{ showDateTime($item->expired_at, 'd M, Y') }}</td>
                                        <td>
                                            @if ($item->expired_at < now())
                                                <span class="badge badge--danger">@lang('Expired')</span>
                                            @else
                                                <span class="badge badge--success">@lang('Running')</span>
                                            @endif
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
            </div>
        </div>
    </div>
@endsection


