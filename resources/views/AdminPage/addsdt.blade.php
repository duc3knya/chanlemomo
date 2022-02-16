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
        <form action="{{ route('admin_quanlysdt_add_action') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-12 col-md-6">
                    <label>Số điện thoại:</label>
                    <input class="form-control" name="sdt" value="">
                </div>

                <div class="col-12 col-md-6">
                    <label>Mật khẩu:</label>
                    <input class="form-control" name="password" value="">
                </div>

                <div class="col-12 col-md-12"><br /></div>

                <div class="col-12 col-md-6">
                    <label>Token WEB2M:</label>
                    <input class="form-control" name="token" value="">
                </div>

                <div class="col-12 col-md-6">
                    <label>Giới hạn:</label>
                    <input class="form-control" name="gioihan" value="">
                </div>

                <div class="col-12 col-md-6">
                    <label>Bảo trì:</label>
                    <select class="form-control" name="status">
                        <option value="1" selected>Không</option>
                        <option value="2">Có</option>
                    </select>
                </div>
                <div class="col-12 col-md-6">
                    <label>Web API</label>
                    <select class="form-control" name="webapi">
                        <option value="1" selected>Web 2B</option>
                        <option value="2">Thuê API</option>
                    </select>
                </div>
                <div class="col-12 col-md-12"><br /></div>

                <div class="col-12 col-md-12">
                    <button class="btn btn-info" style="width: 100%;">Thêm</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
