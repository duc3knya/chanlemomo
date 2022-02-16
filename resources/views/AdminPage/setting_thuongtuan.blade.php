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
        <form action="{{ route('admin_setting_thuongtuan_action') }}" method="POST">
            @csrf
            <div class="row">

                @foreach($GetSettingThuongTuan as $row)
                <div class="col-12 col-md-6">
                    <label>Top {{ $row->top }}:</label>
                    <input class="form-control" name="top_{{ $row->top }}" value="{{ $row->phanthuong }}">
                    <br />
                </div>
                @endforeach

                
                <div class="col-12 col-md-12">
                    <button class="btn btn-info" style="width: 100%;">Lưu lại</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
