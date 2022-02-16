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
        <form action="{{ route('admin_doi_mat_khau_action') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-12 col-md-6">
                    <label>Mật khẩu hiện tại:</label>
                    <input type="password" class="form-control" name="old_password">
                </div>

                <div class="col-12 col-md-6">
                    <label>Mật khẩu mới:</label>
                    <input type="password" class="form-control" name="password">
                </div>

                <div class="col-12 col-md-12"><br /></div>

                <div class="col-12 col-md-6">
                    <label>Nhập lại mật khẩu mới:</label>
                    <input type="password" class="form-control" name="password_confirmation">
                </div>

                <div class="col-12 col-md-12"><br /></div>

                <div class="col-12 col-md-12">
                    <button class="btn btn-info" style="width: 100%;">Đổi mật khẩu</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
