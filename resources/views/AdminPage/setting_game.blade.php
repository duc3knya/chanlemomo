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
                    <label>Min:</label>
                    <input class="form-control" name="min" value="{{ $Setting_Game->min }}">
                </div>

                <div class="col-12 col-md-6">
                    <label>Max:</label>
                    <input class="form-control" name="max" value="{{ $Setting_Game->max }}">
                </div>

                <div class="col-12 col-md-12"><br /></div>

                <div class="col-12 col-md-6">
                    <label>Số điện thoại:</label>
                    <div>
                        @foreach($GetAccountMomo as $row)
                        <div class="form-check">
                            <label class="icheckbox_minimal-blue disabled">
                                <input class="minimal" type="checkbox" value="{{ $row->id }}" name="sdt[]" @if ($row->active == 1) {{ 'checked' }} @endif>
                              {{
                                  $row->sdt
                              }}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <label>Tỉ lệ:</label>
                    <input class="form-control" name="tile" value="{{ $Setting_Game->tile }}">
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
