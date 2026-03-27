@extends('admin.layouts.app')

@section('panel')

    <div class="row gy-4 justify-content-between mb-3 pb-3">
        <div class="col-xl-4 col-md-6">
            <div class="d-flex flex-wrap justify-content-start w-100">
                <form class="form-inline w-100">
                    <div class="search-input--wrap position-relative">
                        <input type="text" name="search" class="form-control" placeholder="@lang('Search')..." value="{{ request()->search ?? '' }}">
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
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>@lang('SI')</th>
                                    <th>@lang('Plan')</th>
                                    <th>@lang('Price')</th>
                                    <th>@lang('Period')</th>
                                    <th>@lang('Popular Status')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                           <tbody id="items_table__body" >
                                @forelse($plans as $index => $plan)
                                    <tr>
                                        <td>#{{ $index+1 }}</td>
                                        <td>{{__($plan->name)}}</td>
                                        <td>{{__($general->cur_sym)}}{{showAmount($plan->price)}}</td>
                                        <td>
                                            {{ ucfirst(__($plan->period))}}
                                        </td>

                                        <td>
                                           @php echo $plan->isPopular(); @endphp
                                        </td>
                                   
                                         <td>
                                            @php echo $plan->statusBadge; @endphp
                                        </td>
                                       
                                          <td data-label="Actions">
                                            <div class="d-flex justify-content-end align-items-center gap-2">
                                                <div class="form-group mb-0">
                                                    <label class="switch m-0" aria-label="Change Status" data-bs-original-title="Change Status">
                                                        <input type="checkbox" class="toggle-switch confirmationBtn" data-action="{{ route('admin.plan.status', $plan->id) }}" data-question="Are you sure to change plan status from this system?" checked="" value="1">
                                                        <span class="slider round"></span>
                                                    </label>
                                                </div>

                                                <a href="{{ route('admin.plan.edit', $plan->id) }}" class="btn btn--sm" aria-label="Edit" data-bs-original-title="Edit">
                                                    <i class="fa-solid fa-pen-to-square"></i>
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
            </div>
        </div>
    </div>
<x-confirmation-modal></x-confirmation-modal>

@endsection

@push('breadcrumb-plugins')
    <a class="btn btn--primary" href="{{ route('admin.plan.create') }}"><i class="fa-solid fa-plus"></i>
        @lang('Add Plan')</a>
@endpush