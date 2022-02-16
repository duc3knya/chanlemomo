@extends('layouts.admin')

@section('style')

@endsection

@section('script')
    @if ($errors->any())
        <script>
            swal("Thông báo", "{{ $errors->first() }}", "error");
        </script>
    @endif

    @if (\Session::has('message'))
        <script>
            swal("Thông báo", "{{ \Session::get('message') }}", "{{ \Session::get('status') }}");
        </script>
    @endif
@endsection

@section('content')
    <div class="box">
        <!-- /.box-header -->
        <div class="box-body">
            <!-- Repeater Html Start -->
            <div class="form-group form-group-last row">
                <div class="col-sm-2 pull-right">
                    <a href="javascript:;" data-repeater-create="" class="btn btn-info"
                       onclick="addFormRepeater(this.parentElement.parentElement.parentElement);">
                        <i class="fa fa-plus"></i> Thêm
                    </a>
                </div>
            </div>
            @foreach($settings as $setting)
                <div class="form-group form-group-last form-template">
                    <form enctype="multipart/form-data" role="form" method="POST" action=""
                          class="kt-form form-setting" style="margin-top: 10px;">
                        <div data-repeater-list="" class="col-sm-12">
                            <div data-repeater-item class="form-group row align-items-center">
                                <div class="col-sm-1">
                                    <label class="control-label"> Mốc chơi: </label>
                                </div>
                                <div class="col-sm-3">
                                    <div class="kt-form__group--inline">
                                        <input class="form-control input-mocchoi-{{ $setting['id'] }}" value="{{ $setting['mocchoi'] }}"/>
                                    </div>
                                    <div class="d-md-none kt-margin-b-10"></div>
                                </div>
                                <div class="col-sm-1">
                                    <label class="control-label"> Tiền nhận: </label>
                                </div>
                                <div class="col-sm-3">
                                    <div class="kt-form__group--inline">
                                        <div class="kt-form__control">
                                            <input type="text"
                                                   value="{{ $setting['tiennhan'] }}"
                                                   placeholder=""
                                                   class="edit-template form-control input-tiennhan-{{ $setting['id'] }}"
                                                   name="">
                                            <div class="show-template show-template-youtube edit-template" id=""></div>
                                        </div>
                                    </div>
                                    <div class="d-sm-none kt-margin-b-10"></div>
                                </div>
                                <div class="col-sm-1 text-right">
                                    <a href="javascript:;" data-repeater-delete=""
                                       class="btn btn-success"
                                       data-toggle="kt-tooltip"
                                       data-placement="top" data-skin="dark" title="" data-html="true"
                                       data-original-title="@lang('school/student_message.delete_file')"
                                       onclick="updateRepeater(this.parentElement.parentElement.parentElement.parentElement, {{ $setting['id'] }});">
                                        <i class="fa fa-save"></i>
                                    </a>
                                </div>
                                <div class="col-sm-1 text-right">
                                    <a href="javascript:;" data-repeater-delete=""
                                       class="btn btn-danger"
                                       data-toggle="kt-tooltip"
                                       data-placement="top" data-skin="dark" title="" data-html="true"
                                       data-original-title="@lang('school/student_message.delete_file')"
                                       onclick="removeRepeater(this.parentElement.parentElement.parentElement.parentElement, {{ $setting['id'] }});">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @endforeach
            <div class="form-group form-group-last list-attach-files kt-margin-b-5 kt-margin-t-5">
            </div>
            <div class="form-group form-group-last form-template-setting" style="display: none">
                <form enctype="multipart/form-data" role="form" method="POST" action=""
                      class="kt-form form-template-setting" style="margin-top: 10px;">
                    <div data-repeater-list="" class="col-sm-12">
                        <div data-repeater-item class="form-group row align-items-center">
                            <div class="col-sm-1">
                                <label class="control-label"> Mốc chơi: </label>
                            </div>
                            <div class="col-sm-3">
                                <div class="kt-form__group--inline">
                                    <input class="form-control input-mocchoi" placeholder="Mốc chơi"/>
                                </div>
                                <div class="d-md-none kt-margin-b-10"></div>
                            </div>
                            <div class="col-sm-1">
                                <label class="control-label"> Tiền nhận: </label>
                            </div>
                            <div class="col-sm-3">
                                <div class="kt-form__group--inline">
                                    <div class="kt-form__control">
                                        <input type="text"
                                               placeholder="Tiền nhận"
                                               class="edit-template form-control input-tiennhan"
                                               name="">
                                        <div class="show-template show-template-youtube edit-template" id=""></div>
                                    </div>
                                </div>
                                <div class="d-sm-none kt-margin-b-10"></div>
                            </div>
                            <div class="col-sm-1 text-right">
                                <a href="javascript:;"
                                   class="btn btn-danger"
                                   data-toggle="kt-tooltip"
                                   data-placement="top" data-skin="dark" title="" data-html="true"
                                   data-original-title="@lang('school/student_message.delete_file')"
                                   onclick="removeRepeater(this.parentElement.parentElement.parentElement.parentElement);">
                                    <i class="fa fa-times"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- Repeater End -->
            <div class="col-lg-12" style="margin-top: 20px">
                <div class="text-center">
                    <button class="btn btn-success" onclick="saveAttendanceDateSetting()">Lưu</i>
                    </button>
                </div>
            </div>

        </div>

    </div>
@endsection
<script>
    function addFormRepeater(form) {
        var template = $(form).find('.form-template-setting form').first();
        var list_attach_files = $(form).find('.list-attach-files').first();

        template.clone().appendTo(list_attach_files);
    }

    function removeRepeater(formElement, setting_id) {
        if (setting_id !== undefined) {
            $.ajax({
                url: "{{ route('admin_setting_attendance_date_delete') }}",
                type: "POST",
                data: {
                    setting_id: setting_id,
                },
                success: function (data) {
                    swal("Xóa thành công", 'success');
                },
                error: function () {
                    swal("Vui lòng thử lại", 'errors');
                }
            });
        }
        $(formElement).remove();
    }

    function updateRepeater(formElement, setting_id) {
        let tiennhan = $(formElement).find('.input-tiennhan-' + setting_id).first().val();
        let mocchoi = $(formElement).find('.input-mocchoi-' + setting_id).first().val();
        if (tiennhan.trim() == "" || mocchoi.trim() == "") {
            swal("Vui lòng điền đầy đủ thông tin");
            return false;
        }
        $.ajax({
            url: "{{ route('admin_setting_attendance_date_update') }}",
            type: "POST",
            data: {
                setting_id: setting_id,
                mocchoi: mocchoi,
                tiennhan: tiennhan,
            },
            success: function (data) {
                swal("Cập nhật thành công", 'success');
            },
            error: function () {
                swal("Vui lòng thử lại", 'errors');
            }
        });
    }

    function saveAttendanceDateSetting() {
        let listFormRepeater = $('.list-attach-files').find('.form-template-setting');
        listFormRepeater.each(function (index, form) {
            let tiennhan = $(form).find('.input-tiennhan').first().val();
            let mocchoi = $(form).find('.input-mocchoi').first().val();
            if (tiennhan.trim() == "" || mocchoi.trim() == "") {
                return true;
            }
            let finish = 0;
            if (index == listFormRepeater.length - 1) {
                finish = 1;
            }
            $.ajax({
                url: "{{ route('admin_setting_attendance_date_add') }}",
                type: "POST",
                data: {
                    mocchoi: tiennhan,
                    tiennhan: tiennhan,
                    finish: finish,
                },
                success: function (data) {
                    if (data.status == 2) {
                        swal("Vui lòng thử lại", 'errors');
                    }
                    if (finish == 1) {
                        location.reload();
                    }
                },
                error: function () {
                    swal("Vui lòng thử lại", 'errors');
                }
            });
            console.log(form)
        });
    }
</script>