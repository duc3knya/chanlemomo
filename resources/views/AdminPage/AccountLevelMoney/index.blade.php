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
                'paging': true,
                'lengthChange': true,
                'searching': true,
                'ordering': true,
                'info': true,
                'autoWidth': true
            })
        })
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            mark_offset($('.mark-offset'));
        });

        function mark_offset(jquery_elements, attribute_name) {
            var offset = 1;
            jquery_elements.each(function () {
                if (attribute_name == undefined) {
                    $(this).html(offset);
                } else {
                    $(this).attr(attribute_name, offset);
                }

                offset += 1;
            });
        }
    </script>
    <script type="text/javascript">
        function addAccountLevels() {
            let form = $('.form_create');
            let data = {};
            data.sdt = form.find('.sdt').first().val();
            data.type = form.find('.type').first().val();
            data.min = form.find('.min').first().val();
            data.max = form.find('.max').first().val();
            $.ajax({
                url: '{{route('admin_level_money.store')}}',
                type: "POST",
                data: data,
                success: function (data) {
                    if (data.status == 2) {
                        swal(data.message, "", 'error');
                    } else {
                        location.reload();
                    }
                },

                error: function () {
                    swal("Vui lòng thử lại", "", 'errors');
                }
            });
        }

        function showEdit(id) {
            $.ajax({
                url: '{{route('admin_level_money.edit')}}',
                type: "POST",
                data: {'id': id},
                success: function (data) {
                    if (data.status == 2) {
                        swal(data.message, 'errors');
                    } else {
                        $('.form_update').html(data);
                    }
                },

                error: function () {
                    swal("Vui lòng thử lại", 'errors');
                }
            });
        }

        function UpdateAccountLevel(id) {
            let form = $('.form_update');
            let data = {};
            data.id = id;
            data.sdt = form.find('.sdtupdate').first().val();
            data.type = form.find('.typeupdate').first().val();
            data.min = form.find('.minupdate').first().val();
            data.max = form.find('.maxupdate').first().val();
            $.ajax({
                url: '{{route('admin_level_money.update')}}',
                type: "POST",
                data: data,
                success: function (data) {
                    if (data.status == 2) {
                        swal(data.message, "", 'error');
                    } else {
                        swal("Cập nhật dữ liệu thành công", "", 'success');
                        $('.row_' + id).replaceWith(data);
                        mark_offset($('.mark-offset'));
                        $("#modalUpdate .close").click()
                    }
                },

                error: function () {
                    swal("Vui lòng thử lại", 'errors');
                }
            });
        }

        function deleteAccount(id) {
            $.ajax({
                url: '{{route('admin_level_money.delete')}}',
                type: "POST",
                data: {id: id},
                success: function (data) {
                    if (data.status == 2) {
                        swal(data.message, 'errors');
                    } else {
                        swal("Cập nhật dữ liệu thành công", 'success');
                        $('.row_' + id).remove();
                        mark_offset($('.mark-offset'));
                    }
                },

                error: function () {
                    swal("Vui lòng thử lại", 'errors');
                }
            });
        }
    </script>
@endsection

@section('content')
    <div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="modalCreateLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content form_create">
                @include('AdminPage.AccountLevelMoney.form_template')
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" onclick="addAccountLevels()">Thêm</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalUpdate" tabindex="-1" aria-labelledby="modalUpdateLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content form_update">
            </div>
        </div>
    </div>
    <div class="box">
        <div class="box-header">
            <div style="float: left;">
                <h3 class="box-title">
                    Quản lý hạn mức sđt
                </h3>
            </div>
            <div style="float: right;">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalCreate">
                    Thêm
                </button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">

            <div class="table-responsive">
                <table id="my-table" class="table table-bordered table-hover">
                    <thead>

                    <tr>
                        <th>ID</th>
                        <th>SĐT</th>
                        <th>Trò chơi</th>
                        <th>Min</th>
                        <th>Max</th>
                        <th>Trạng thái</th>
                        <th>CHỨC NĂNG</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($accounts as $account)
                        @include('AdminPage.AccountLevelMoney.row')
                    @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
@endsection
