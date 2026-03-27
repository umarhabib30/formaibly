@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="dashboard__wrapper">
        <div class="row g-4 justify-content-center">
            <div class="col-xl-12">
                <div class="profile__wrap card p-4">
                    <div class="dashboard__table">
                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                            <h5 class="mb-0">@lang('Support Ticket')</h5>
                            <div class="search__box">
                                <a href="{{ route('ticket') }}" class="btn btn--md btn--base w-100">
                                    <i class="fa fa-plus"></i> @lang('My Ticket')
                                </a>
                            </div>
                            <form action="{{ route('ticket.store') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-4">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="profile__form">
                                            <label class="form-label" for="firstName">@lang('First Name')</label>
                                            <input type="text" class="form--control" id="firstName"
                                                placeholder="@lang('Name')" name="name"
                                                value="{{ $user->firstname ?? ('' . ' ' . $user->lastname ?? '') }}"
                                                required readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="profile__form">
                                            <label class="form-label" for="email">@lang('Email Address')</label>
                                            <input type="text" class="form--control" id="email"
                                                placeholder="@lang('Email Address')" name="email" value="{{ $user->email }}"
                                                required readonly>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="profile__form">
                                            <label class="form-label" for="subject">@lang('Subject')</label>
                                            <input type="text" class="form--control" id="subject"
                                                placeholder="@lang('subject')" name="subject" value="{{ old('Subject') }}"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="profile__form">
                                            <label class="form-label" for="priority">@lang('Priority')</label>
                                            <select class="form--control select-2" name="priority" required id="gateway">
                                                <option value="3">@lang('High')</option>
                                                <option value="2">@lang('Medium')</option>
                                                <option value="1">@lang('Low')</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-lg-12 col-md-12">
                                        <label class="form-label" for="state">@lang('Message')</label>
                                        <textarea name="message" id="inputMessage" rows="10" class="form--control" required>{{ old('message') }}</textarea>
                                    </div>

                                    <div class="col-lg-12 col-md-12">
                                        <div class="text-end">
                                            <button type="button" class="btn btn--base btn--md addFile">
                                                <i class="fa fa-plus"></i> @lang('Add New')
                                            </button>
                                        </div>
                                        <div class="file-upload">
                                            <label class="form-label">@lang('Attachments'):</label>
                                            <small class="text-danger">@lang('Max 5 files can be uploaded'). @lang('Maximum upload size is')
                                                {{ ini_get('upload_max_filesize') }}</small>
                                            <input type="file" name="attachments[]" id="inputAttachments"
                                                class="form--control">
                                            <div id="fileUploadsContainer"></div>
                                            <div class="d-flex justify-content-start gy-5">
                                                <p class="ticket-attachments-message">
                                                    @lang('Allowed File Extensions'): .@lang('jpg'),
                                                    .@lang('jpeg'),
                                                    .@lang('png'),
                                                    .@lang('pdf'), .@lang('doc'),
                                                    .@lang('docx')
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="profile__form">
                                            <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('style')
    <style>
        .input-group-text:focus {
            box-shadow: none !important;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            var fileAdded = 0;
            $('.addFile').on('click', function() {
                if (fileAdded >= 4) {
                    notify('error', "You have added maximum number of file");
                    return false;
                }
                fileAdded++;
                $("#fileUploadsContainer").append(`
                    <div class="input--group my-3">
                        <input type="file" name="attachments[]" class="form--control" required>
                        <button type="button" class="input-group-text btn-outline--danger remove-btn">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                `)
            });
            $(document).on('click', '.remove-btn', function() {
                fileAdded--;
                $(this).closest('.input--group').remove();
            });
        })(jQuery);
    </script>
@endpush
