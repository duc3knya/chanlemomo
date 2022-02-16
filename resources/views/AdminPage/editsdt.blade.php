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
        <form action="{{ route('admin_quanlysdt_edit_action') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $GetAccountMomo->id }}">
            <div class="row">
                <div class="col-12 col-md-6">
                    <label>Số điện thoại:</label>
                    <input class="form-control" name="sdt" value="{{ $GetAccountMomo->sdt }}">
                </div>

                <div class="col-12 col-md-6">
                    <label>Mật khẩu:</label>
                    <input class="form-control" name="password" value="{{ $GetAccountMomo->password }}">
                </div>

                <div class="col-12 col-md-12"><br /></div>

                <div class="col-12 col-md-6">
                    <label>Token WEB2M:</label>
                    <input class="form-control" name="token" value="{{ $GetAccountMomo->token }}">
                </div>

                <div class="col-12 col-md-6">
                    <label>Giới hạn:</label>
                    <input class="form-control" name="gioihan" value="{{ $GetAccountMomo->gioihan }}">
                </div>

                <div class="col-12 col-md-6">
                    <label>Bảo trì:</label>
                    <select class="form-control" name="status">
                        @if($GetAccountMomo->status == 1)
                        <option value="1" selected>Không</option>
                        <option value="2">Có</option>
                        @else
                        <option value="2" selected>Có</option>
                        <option value="1">Không</option>
                        @endif
                    </select>
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
