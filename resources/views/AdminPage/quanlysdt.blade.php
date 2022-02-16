@extends('layouts.admin')

@section('style')
<!-- DataTables -->
<link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
@endsection

@section('script')

@if (\Session::has('message'))
<script>
    swal("Thông báo", "{{ \Session::get('message') }}", "{{ \Session::get('status') }}");
</script>
@endif

<script src="https://adminlte.io/themes/AdminLTE/bower_components/jquery/dist/jquery.min.js"></script>
<script src="https://adminlte.io/themes/AdminLTE/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="https://adminlte.io/themes/AdminLTE/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script>
    $(function () {
      $('#my-table').DataTable({
        'paging'      : true,
        'lengthChange': true,
        'searching'   : true,
        'ordering'    : true,
        'info'        : true,
        'autoWidth'   : true
      })
    })
  </script>
@endsection

@section('content')
<div class="box">
    <div class="box-header">
      <div style="float: left;">
        <h3 class="box-title">
          {{ $GetSetting->desc }}
      </h3>
      </div>
      <div style="float: right;">
        <a href="{{ route('admin_quanlysdt_add') }}">
          <button class="btn btn-info">
          Thêm số điện thoại
        </button>
        </a>
      </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">

      <div class="table-responsive">
        <table id="my-table" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>ID</th>
                  <th>TÊN</th>
                  <th>SỐ TIỀN</th>
                  <th>SĐT</th>
                  <th>MẬT KHẨU</th>
                  <th>TOKEN</th>
                  <th>GIỚI HẠN</th>
                  <th>STATUS</th>
                  <th>Số lần bank hôm nay</th>
                  <th>TIME</th>
                  <th>CHỨC NĂNG</th>
                </tr>
                </thead>
                <tbody>
                  @foreach($GetAccountMomo as $row)
                  <tr>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ number_format($row->amount) }}đ</td>
                    <td>{{ $row->sdt }}</td>
                    <td>{{ $row->password }}</td>
                    <td>{{ $row->token }}</td>
                    <td>{{ number_format($row->gioihan) }}đ</td>
                    <td>
                      <button class="btn btn-{{ $row->status_class }} btn-sm">
                        {{ $row->status_text }}
                      </button>
                    </td>
                    <td>{{ number_format($row['countbank']) }} lần</td>
                    <td>{{ $row->created_at }}</td>
                    <td>
                      <a href="{{ route('admin_quanlysdt_delete', $row->id) }}">
                      <button class="btn btn-danger btn-sm">
                        Xóa
                      </button>
                      </a>

                      <a href="{{ route('admin_quanlysdt_edit', $row->id) }}">
                      <button class="btn btn-success btn-sm">
                        Chỉnh sửa
                      </button>
                      </a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
      </div>

    <!-- /.box-body -->
  </div>
@endsection
