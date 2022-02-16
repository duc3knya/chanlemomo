@if(isset($checkCanAttendance) && $checkCanAttendance)
    <div class="row collapse show" id="diemDanhCard" style="">
        <div class="col-lg-12">
            <div class="body">
                <div class="text-center">

                    <font color="blue"><big><b>Điểm Danh Nhận Quà Miễn Phí</b></big></font>
                    <br>
                    <small><i class="fa fa-info-circle" aria-hidden="true"></i> Mã quà: <font
                                color="orange"><b id="diemdanh_id">{{ $attendanceSessionCurrent->id }}</b></font></small><br>

                    <small><i class="fa fa-usd" aria-hidden="true"></i> Giá trị: <font color="Maroon">
                        <!--<b id="">{{ number_format($setting['money_min']) }} ~ {{ number_format($setting['money_max']) }}</b> vnđ</font></small><br>-->
                        <b id="">{{ number_format($setting['money_min']) }} ~ {{ number_format('100000') }}</b> vnđ</font></small><br>

                    <small><i class="fa fa-user" aria-hidden="true"></i>: <font color="333366"><b
                                    id="diemdanh_users" class="diemdanh_users">{{ $countUsersAttendance }}</b> người</font></small><br>

                    <small><i class="fa fa-clock-o" aria-hidden="true"></i> Quay thưởng sau: <font
                                color="660000"><b
                                    id="diemdanh_thoigian">{{ $canAttendance ? $secondRealTime : $timeEach }}</b>
                            giây</font></small><br>
                    <small><i class="fa fa-star" aria-hidden="true"></i> Thắng phiên trước: <font
                                color="333300"><b id="diemdanh_last">{{$phoneWinLatest}}</b></font></small><br>
                    <small><i class="fa fa-bandcamp" aria-hidden="true"></i> Tổng tiền đã trao: <font color="blue"><b
                                    id="diemdanh_tongtien">{{ number_format($totalAmount) }}</b> VNĐ</font></small>
                    <div class="form-group occard" id="occard">
                        <label for="exampleInputEmail1">Số điện thoại:</label>
                        <input type="text" class="form-control" id="phonevalue" aria-describedby="emailHelp"
                               placeholder="03837755">
                        <small id="emailHelp" class="form-text text-muted">Nhập số điện thoại của bạn để
                            điểm danh.</small>
                        <br>
                        <button class="btn btn-success" data-toggle="modal" data-target="#modalDiemDanh"
                                onclick="diemdanh()">Điểm danh
                        </button>
                    </div>

                    <button class="btn btn-info"
                            onclick="$('#muc_huongdan').show();$('#muc_users').hide();$('#muc_lichsu').hide();">
                        Cách chơi
                    </button>
                    <button class="btn btn-dark" data-toggle="modal"
                            onclick="$('#muc_huongdan').hide();$('#muc_users').hide();$('#muc_lichsu').show();">
                        Lịch sử
                    </button>
                    <button class="btn btn-danger"
                            onclick="$('#muc_huongdan').hide();$('#muc_users').show();$('#muc_lichsu').hide();">
                        Danh sách
                    </button>
                </div>
                <?php
                $time = (int) $timeEach;
                $startTime = $setting['start_time'];
                $endTime = $setting['end_time'];
                ?>
                <div class="occho" id="muc_huongdan">
                    - Mỗi phiên quà các bạn có {{ $time >= 86400 ? $time/86400 : ($time >= 3600 ? $time/3600 : $time/60) }}  {{ $time >=86400 ? "ngày":($time >= 3600 ? "tiếng" : "phút") }} để điểm danh. <br>
                    - Số điện thoại điểm danh phải chơi {{ env('APP_NAME') }} ít nhất 1 lần trong ngày. Không giới hạn số
                    lần điểm danh trong ngày. <br>
                    - Khi hết thời gian, người may mắn sẽ nhận được số tiền của phiên đó. <br>
                    - Game chỉ hoạt động từ <b>{{ \Carbon\Carbon::parse($startTime)->format('H:i') }} sáng</b> đến {{ \Carbon\Carbon::parse($endTime)->format('H:i') }} tối
                </div>

                <div class="occho" id="muc_lichsu" style="display:none;">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover text-center">
                            <thead>
                            <tr role="row" class="bg-primary">
                                <th class="text-center text-white">Mã</th>
                                <th class="text-center text-white">SDT</th>
                                <th class="text-center text-white">Mã GD</th>
                                <th class="text-center text-white">VND</th>
                            </tr>
                            </thead>
                            <tbody id="mayman_log">
                            @include('HomePage.table_sessions_attendance')
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="occho" id="muc_users" style="display:none;">
                    @foreach($usersAttendance as $userAttendance)
                        {{ $userAttendance->getPhone() }},
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif