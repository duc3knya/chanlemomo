@extends('layouts.admin')

@section('style')

@endsection

@section('script')

@endsection

@section('content')
    <div class="row">

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fas fa-award"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">TỔNG DOANH THU</span>
                    <span class="info-box-number">{{ number_format($doanhthu['tongdoanhthu']) }}<small> vnđ</small></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fas fa-calendar-day"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">DOANH THU HÔM NAY</span>
                    <span class="info-box-number">{{ number_format($doanhthu['doanhthuhomnay']) }}<small> vnđ</small></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fas fa-calendar-day"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">DOANH THU THÁNG NẦY</span>
                    <span class="info-box-number">{{ number_format($doanhthu['doanhthuthangnay']) }}<small> vnđ</small></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fas fa-calendar-day"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">DOANH THU NĂM NAY</span>
                    <span class="info-box-number">{{ number_format($doanhthu['doanhthunamnay']) }}<small> vnđ</small></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

    </div>

    <br />

    <div class="row">

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="far fa-hand-scissors"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">TỔNG LƯỢT CHƠI CHẴN LẺ</span>
                    <span class="info-box-number">{{ number_format($tongluotchoi['chanle']) }}<small> lượt</small></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fas fa-dice"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">TỔNG LƯỢT CHƠI TÀI XỈU</span>
                    <span class="info-box-number">{{ number_format($tongluotchoi['taixiu']) }}<small> lượt</small></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fas fa-hand-scissors"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">TỔNG LƯỢT CHƠI CHẴN LẺ 2</span>
                    <span class="info-box-number">{{ number_format($tongluotchoi['chanle2']) }}<small> lượt</small></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fas fa-cubes"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">TỔNG LƯỢT CHƠI GẤP 3</span>
                    <span class="info-box-number">{{ number_format($tongluotchoi['gap3']) }}<small> lượt</small></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fas fa-sort-numeric-up"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">TỔNG LƯỢT CHƠI TỔNG 3 SỐ</span>
                    <span class="info-box-number">{{ number_format($tongluotchoi['tong3so']) }}<small> lượt</small></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fas fa-percentage"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">TỔNG LƯỢT CHƠI TỔNG 1 PHẦN 3</span>
                    <span class="info-box-number">{{ number_format($tongluotchoi['1phan3']) }}<small> lượt</small></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

    </div>

    <br />

    <div class="row">

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fas fa-bomb"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">TỔNG LƯỢT CHƠI NỔ  HŨ</span>
                    <span class="info-box-number">{{ number_format($tongluotchoi['nohu']) }}<small> lượt</small></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fab fa-joomla"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">TỔNG DOANH THU NỔ HŨ</span>
                    <span class="info-box-number">{{ number_format($doanhthu['nohu']) }}<small> vnđ</small></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fas fa-wine-glass-alt"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">TIỀN TRONG HŨ</span>
                    <span class="info-box-number">{{ number_format($thongtin['tientronghu']) }}<small> vnđ</small></span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->

    </div>
@endsection
