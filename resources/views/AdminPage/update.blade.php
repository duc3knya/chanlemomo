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
        <p># Thông tin phiên bản:</p>
        <ul>
            <li>Phiên bản hiện tại: {{ $emyinfo[0] }}</li>
            <li>Phiên bản mới nhất: {{ $eserverinfo[0] }}</li>
        </ul>
        <hr />
        <p># Nội dung bản cập nhật: </p>
        <ul>
            @php
                $dem = 0;
            @endphp
            @if($emyinfo[0] == $eserverinfo[0])
                <li>Phiên bản hiện tại đã là mới nhất</li>
            @else
                @foreach ($eserverinfo as $r)
                    @if($dem > 0)
                        <li>
                            {{ $r }}
                            <br />
                        </li>
                    @endif

                        @php
                            $dem ++;
                        @endphp
                @endforeach
            @endif
        </ul>
        <hr />
        <a href="{{ route('admin.update') }}">
            <button class="btn btn-info">Cập nhật phiên bản mới</button>
        </a>
    </div>
</div>
@endsection
