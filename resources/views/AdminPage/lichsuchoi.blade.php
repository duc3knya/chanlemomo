@extends('layouts.admin')

@section('style')
<!-- DataTables -->
<link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
@endsection

@section('script')
<script src="https://adminlte.io/themes/AdminLTE/bower_components/jquery/dist/jquery.min.js"></script>
<script src="https://adminlte.io/themes/AdminLTE/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="https://adminlte.io/themes/AdminLTE/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script>
    $(function () {
      $('#my-table').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : false,
        'info'        : true,
        'autoWidth'   : true
      })
    })
  </script>

    @if (\Session::has('message'))
    <script>
        swal("Thông báo", "{{ \Session::get('message') }}", "{{ \Session::get('status') }}");
    </script>
    @endif
@endsection

@section('content')
<div class="box">
    <div class="box-header">
      <h3 class="box-title">
          {{ $GetSetting->game }}
      </h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">

      <div class="table-responsive">
        <table id="my-table" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>MOMO NHẬN</th>
                  <th>SĐT</th>
                  <th>MÃ GIAO DỊCH</th>
                  <th>TIỀN CƯỢC</th>
                  <th>TIỀN NHẬN</th>
                  <th>NỘI DUNG</th>
                  <th>KẾT QUẢ</th>
                  <th>TRẠNG THÁI</th>
                  <th>TIME</th>
                  <th>THAO TÁC</th>
                </tr>
                </thead>
                <tbody>

                @foreach($GetLichSuChoiMomo as $row)
                <tr>
                    <td>{{ $row['sdt_get'] }}</td>
                    <td>{{ $row['sdt'] }}</td>
                    <td>{{ $row['magiaodich'] }}</td>
                    <td>{{ number_format($row['tiencuoc']) }}đ</td>
                    <td>{{ number_format($row['tiennhan']) }}đ</td>
                    <td>{{ $row['noidung'] }}</td>
                    <td><button class="btn btn-{{ $row['kq']['class'] }} btn-sm" disabled>{{ $row['kq']['text'] }}</button></td>
                    <td><button class="btn btn-{{ $row['tt']['class'] }} btn-sm" disabled>{{ $row['tt']['text'] }}</button></td>
                    <td>{{ $row['created_at'] }}</td>
                    <td>
                      <form action="{{ route('admin.setstatus') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $row['id'] }}">
                        <button class="btn btn-success" type="submit" name="status" value="3">Hoàn tất</button>
                      </form>
                    </td>
                </tr>
                @endforeach

                </tbody>
              </table>
      </div>

    <!-- /.box-body -->
  </div>
@endsection
