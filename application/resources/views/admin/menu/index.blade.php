@extends('admin.layouts.app')
@section('panel')
<div class="row gy-4 justify-content-between mb-3 pb-4">
    <div class="col-xl-3 col-lg-6">
        <div class="d-flex flex-wrap justify-content-start w-100">
            <form class="form-inline w-100">
                <div class="search-input--wrap position-relative">
                    <input type="text" name="search" class="form-control" placeholder="@lang('Search menu')" value="{{ request()->search }}">
                    <button class="search--btn position-absolute" type="submit"><i class="fa fa-search"></i></button>
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
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('S.L')</th>
                                <th>@lang('Menu')</th>
                                <th>@lang('Menu Items')</th>
                                <th>@lang('Action')</th>
                            </tr>
                        </thead>
                        <tbody id="items_table__body">
                            @forelse($menus as $data)
                            <tr>
                                <td class="user--td">
                                    {{ $loop->iteration }}
                                </td>
                                <td>
                                    {{ __($data->name) }}
                                </td>
                                <td>
                                    {{ $data->items->count() }}
                                </td>
                                <td>
                                    <div class="d-flex justify-content-end align-items-center gap-2">
                                        <button type="button" title="@lang('Edit')" class="btn btn-sm edit" data-name="{{$data->name}}" data-action="{{ route('admin.menu.storeorupdate',$data->id) }}">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>

                                        <a href="{{ route('admin.menu.assign.item', $data->id) }}" class="btn btn-sm" title="@lang('Assign Items')">
                                            <i class="fa-solid fa-indent"></i>
                                        </a>

                                        <button title="@lang('Delete')" type="button"
                                            class="btn btn-sm btn--danger confirmationBtn"
                                            data-action="{{ route('admin.menu.delete',$data->id) }}"
                                            data-question="@lang('Are you sure to delete this menu?')">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
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


<div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="bulkActionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="menuForm" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="bulkActionModalLabel" class="modal-title">@lang('Add new menu')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="@lang('Close')"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="ad_name"> @lang('Name'):</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="@lang('Name')" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn--primary w-100 submit_btn">@lang('Save')</button>
                </div>
            </div>
        </form>
    </div>
</div>


<x-confirmation-modal></x-confirmation-modal>
@endsection

@push('breadcrumb-plugins')
<a class="btn btn--primary addModal" data-toggle="modal" data-action="{{ route('admin.menu.storeorupdate') }}"><i class="fa-solid fa-plus"></i> @lang('Add Menu')</a>
@endpush



@push('script')
<script>
    (function($) {
        'use strict';
        $('.addModal').on('click', function () {
            let action = $(this).data('action');
            $('#menuForm')[0].reset();
            $('#menuForm').attr('action', action);
            $('#menuModal .modal-title').text("@lang('Add New Menu')");
            $('#menuModal .submit_btn').text("@lang('Submit')");
            $('#menuModal').modal('show');
        });


        $('.edit').on('click', function () {
            let modal = $('#menuModal');
            let action = $(this).data('action');
            $('#menuForm')[0].reset();
            $('#menuForm').attr('action', action);
            $('#menuModal .modal-title').text("@lang('Update Menu')");
            modal.find('input[name=name]').val($(this).data('name'));
            $('#menuModal .submit_btn').text("@lang('Update')");
            $('#menuModal').modal('show');
        });

    })(jQuery);

</script>
@endpush
