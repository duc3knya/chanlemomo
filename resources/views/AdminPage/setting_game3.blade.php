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
        <form action="{{ route($GetSetting->action) }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $Setting_Game->id }}">
            <div class="row">
                <div class="col-12 col-md-6">
                    <label>Tiền cược:</label>
                    <input class="form-control" name="tiencuoc" value="{{ $Setting_Game->tiencuoc }}">
                </div>

                <div class="col-12 col-md-6">
                    <label>Tiền mặc định:</label>
                    <input class="form-control" name="tienmacdinh" value="{{ $Setting_Game->tienmacdinh }}">
                </div>

                <div class="col-12 col-md-12"><br /></div>

                <div class="col-12 col-md-6">
                    <label>% tiền cược vào hũ:</label>
                    <input class="form-control" name="ptvaohu" value="{{ $Setting_Game->ptvaohu }}">
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
