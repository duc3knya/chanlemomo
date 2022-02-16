<center class="" style="width: 76%;
            margin: auto;">
    <marquee><b>
            @foreach($ListLichSuChoiMomo as $row)
                Chúc mừng <font color="blue">{{ $row->sdt_hidden }}</font> thắng lớn nhận <font
                        color="green">{{ number_format($row->tiennhan) }}
                </font> VNĐ. |
            @endforeach
            .</b></marquee>
</center>

<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover text-center">
        <thead>
        <tr role="row" class="bg-primary2">
            <th class="text-center text-white">Thời gian</th>
            <th class="text-center text-white">Số điện thoại</th>
            <th class="text-center text-white">Tiền cược</th>
            <th class="text-center text-white">Tiền nhận</th>
            <th class="text-center text-white">Trò chơi</th>
            <th class="text-center text-white">Nội dung</th>
            <th class="text-center text-white">trạng thái</th>
        </tr>
        </thead>
        <tbody role="alert" aria-live="polite" aria-relevant="all" class="">
        @foreach($ListLichSuChoiMomo as $row)
            <tr>
                <td>{{ $row->created_at }}</td>
                <td>{{ $row->sdt_hidden }}</td>
                <td>{{ number_format($row->tiencuoc) }}</td>
                <td>{{ number_format($row->tiennhan) }}</td>
                <td>{{ $row->trochoi }}</td>
                <td>{{ strtoupper ($row->noidung) }}</td>
                <td><span class="label label-success text-uppercase">
                                            Thắng
                                        </span></td>
            </tr>
        @endforeach

        </tbody>
    </table>
</div>