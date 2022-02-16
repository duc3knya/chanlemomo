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
        <form action="{{ route('admin_config_message_action') }}" method="POST">
            @csrf
            <div class="row">

                <div class="col-12 col-md-6">
                    <label>Trả thưởng:</label>
                    <input type="text" class="form-control" name="trathuong" value="{{ $GetConfigMessageMomo['0']['message'] }}">
                </div>

                <div class="col-12 col-md-6">
                    <label>Trả thưởng top tuần:</label>
                    <input type="text" class="form-control" name="thuongtoptuan" value="{{ $GetConfigMessageMomo['1']['message'] }}">
                </div>

                <div class="col-12 col-md-12"><br /></div>

                <div class="col-12 col-md-6">
                    <label>Trả thưởng nổ hũ:</label>
                    <input type="text" class="form-control" name="nohuu" value="{{ $GetConfigMessageMomo['2']['message'] }}">
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
