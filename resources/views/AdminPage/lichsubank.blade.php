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
                  <th>SĐT BANK</th>
                  <th>SĐT NGƯỜI NHẬN</th>
                  <th>SỐ TIỀN</th>
                  <th>NỘI DUNG</th>
                  <th>RESPONSE WEB2M</th>
                  <th>TIME</th>
                </tr>
                </thead>
                <tbody>

                @foreach($getLichSuBank as $row)
                <tr>
                    <td>{{ $row['sdtbank'] }}</td>
                    <td>{{ $row['nguoinhan'] }}</td>
                    <td>{{ number_format($row['sotien']) }}đ</td>
                    <td>{{ $row['noidung'] }}</td>
                    <td>{{ json_encode( json_decode($row['response']), JSON_UNESCAPED_UNICODE  ) }}</td>
                    <td>{{ $row['created_at'] }}</td>
                </tr>
                @endforeach

                </tbody>
              </table>
      </div>

    <!-- /.box-body -->
  </div>
@endsection
