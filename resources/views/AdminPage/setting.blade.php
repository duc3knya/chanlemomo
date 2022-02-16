@extends('layouts.admin')

@section('style')

@endsection

@section('script')
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
        <form action="{{ route('admin_setting_action') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-12 col-md-6">
                    <label>Tên trang:</label>
                    <input class="form-control" name="title" value="{{ $GetSetting->title }}">
                </div>

                <div class="col-12 col-md-6">
                    <label>Mô tả:</label>
                    <input class="form-control" name="description" value="{{ $GetSetting->description }}">
                </div>

                <div class="col-12 col-md-12"><br /></div>

                <div class="col-12 col-md-6">
                    <label>Logo:</label>
                    <input class="form-control" name="logo" value="{{ $GetSetting->logo }}">
                </div>

                <div class="col-12 col-md-6">
                    <label>Link video:</label>
                    <input class="form-control" name="linkvideoyoutube" value="{{ $GetSetting->linkvideoyoutube }}">
                </div>

                <div class="col-12 col-md-12"><br /></div>

                <div class="col-12 col-md-6">
                    <label>Zalo:</label>
                    <input class="form-control" name="zalo" value="{{ $GetSetting->zalo }}">
                </div>

                <div class="col-12 col-md-6">
                    <label>Bảo trì:</label>
                    <select class="form-control" name="baotri">
                        @if($GetSetting->baotri == 0)
                        <option value="0" selected>Không</option>
                        <option value="1">Có</option>
                        @else
                        <option value="1" selected>Có</option>
                        <option value="0">Không</option>
                        @endif
                    </select>
                </div>

                <div class="col-12 col-md-12"><br /></div>

                <div class="col-12 col-md-6">
                    <label>Màu header:</label>
                    <input type="color" class="form-control" name="color_header" value="{{ $GetSetting->color_header }}">
                </div>

                <div class="col-12 col-md-6">
                    <label>Màu table footer:</label>
                    <input type="color" class="form-control" name="color_footer" value="{{ $GetSetting->color_footer }}">
                </div>

                <div class="col-12 col-md-12"><br /></div>

                <div class="col-12 col-md-6">
                    <label>Màu table 1:</label>
                    <input type="color" class="form-control" name="color_table" value="{{ $GetSetting->color_table }}">
                </div>


                <div class="col-12 col-md-6">
                    <label>Màu table 2:</label>
                    <input type="color" class="form-control" name="color_table2" value="{{ $GetSetting->color_table2 }}">
                </div>

                <div class="col-12 col-md-12"><br /></div>

                <div class="col-12 col-md-6">
                    <label>Bật / tắt game chẵn lẻ:</label>
                    <select class="form-control" name="on_chanle">
                        @if ($GetSetting->on_chanle == 1)
                            <option value="1" selected>On</option>
                            <option value="2">Off</option>
                        @else
                            <option value="2" selected>Off</option>
                            <option value="1">On</option>
                        @endif
                    </select>
                </div>

                <div class="col-12 col-md-6">
                    <label>Bật / tắt game tài xỉu:</label>
                    <select class="form-control" name="on_taixiu">
                        @if ($GetSetting->on_taixiu == 1)
                            <option value="1" selected>On</option>
                            <option value="2">Off</option>
                        @else
                            <option value="2" selected>Off</option>
                            <option value="1">On</option>
                        @endif
                    </select>
                </div>

                <div class="col-12 col-md-12"><br /></div>

                <div class="col-12 col-md-6">
                    <label>Bật / tắt game chẵn lẻ 2:</label>
                    <select class="form-control" name="on_chanle2">
                        @if ($GetSetting->on_chanle2 == 1)
                            <option value="1" selected>On</option>
                            <option value="2">Off</option>
                        @else
                            <option value="2" selected>Off</option>
                            <option value="1">On</option>
                        @endif
                    </select>
                </div>

                <div class="col-12 col-md-6">
                    <label>Bật / tắt game gấp 3:</label>
                    <select class="form-control" name="on_gap3">
                        @if ($GetSetting->on_gap3 == 1)
                            <option value="1" selected>On</option>
                            <option value="2">Off</option>
                        @else
                             <option value="2" selected>Off</option>
                            <option value="1">On</option>
                        @endif
                    </select>
                </div>

                <div class="col-12 col-md-12"><br /></div>

                <div class="col-12 col-md-6">
                    <label>Bật / tắt game tổng 3 số:</label>
                    <select class="form-control" name="on_tong3so">
                        @if ($GetSetting->on_tong3so == 1)
                            <option value="1" selected>On</option>
                            <option value="2">Off</option>
                        @else
                            <option value="2" selected>Off</option>
                            <option value="1">On</option>
                        @endif
                    </select>
                </div>

                <div class="col-12 col-md-6">
                    <label>Bật / tắt game 1 phần 3:</label>
                    <select class="form-control" name="on_1phan3">
                        @if ($GetSetting->on_1phan3 == 1)
                            <option value="1" selected>On</option>
                            <option value="2">Off</option>
                        @else
                            <option value="2" selected>Off</option>
                            <option value="1">On</option>
                        @endif
                    </select>
                </div>

                <div class="col-12 col-md-12"><br /></div>

                <div class="col-12 col-md-6">
                    <label>Bật / tắt game nổ hũ:</label>
                    <select class="form-control" name="on_nohu">
                        @if ($GetSetting->on_nohu == 1)
                            <option value="1" selected>On</option>
                            <option value="2">Off</option>
                        @else
                            <option value="2" selected>Off</option>
                            <option value="1">On</option>
                        @endif
                    </select>
                </div>

                <div class="col-12 col-md-6">
                    <label>Bật / tắt trả thưởng tuần:</label>
                    <select class="form-control" name="on_trathuongtuan">
                        @if ($GetSetting->on_trathuongtuan == 1)
                            <option value="1" selected>On</option>
                            <option value="2">Off</option>
                        @else
                            <option value="2" selected>Off</option>
                            <option value="1">On</option>
                        @endif
                    </select>
                </div>
                <div class="col-12 col-md-6">
                    <label>Bật / tắt điểm danh nhận quà:</label>
                    <select class="form-control" name="on_diemdanh">
                        @if ($GetSetting->on_diemdanh == 1)
                            <option value="1" selected>On</option>
                            <option value="2">Off</option>
                        @else
                            <option value="2" selected>Off</option>
                            <option value="1">On</option>
                        @endif
                    </select>
                </div>
                <div class="col-12 col-md-6">
                    <label>Bật / tắt điểm danh ngày:</label>
                    <select class="form-control" name="on_diemdanh_ngay">
                        @if ($GetSetting->on_diemdanh_ngay == 1)
                            <option value="1" selected>On</option>
                            <option value="2">Off</option>
                        @else
                            <option value="2" selected>Off</option>
                            <option value="1">On</option>
                        @endif
                    </select>
                </div>

                <div class="col-12 col-md-12"><br /></div>

                <div class="col-12 col-md-12">
                    <label>Script:</label> 
                    <textarea class="form-control" rows="10" name="script">{{
                            $GetSetting->script
                        }}</textarea>
                </div>

                
                
                <div class="col-12 col-md-12"><br /></div>
                <div class="col-12 col-md-12">
                    <button class="btn btn-info" style="width: 100%;">Lưu lại</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
