<script type="text/javascript">
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        @if($canAttendance)
            timelast = Number('{{ $secondRealTime }}');
        setTimeSessionAttendance();
        @endif
        setTimeout(getDataAfterLoad(), 3000)

        let timeRefreshTable = Number({{TIME_REFRESH_LOAD_DATA_AFTER}});
        setInterval(function () {
            if (timeRefreshTable > 0) {
                timeRefreshTable--;
            } else {
                getDataAfterLoad()
                timeRefreshTable = {{TIME_REFRESH_LOAD_DATA_AFTER}};
            }
            $(".coundown-time").html(timeRefreshTable);
        }, 1000);

    });

    function getDataAfterLoad() {
        $.ajax({
            url: '{{route('home.get_data_after_load')}}',
            type: 'post',
            success: function (data) {
                if (data.status == 2) {
                    console.log("Lỗi")
                } else {
                    $('#lich_su_thang').html(data.lich_su_thang)
                    $('#table_trang_thai_momo').html(data.view_table_trang_thai_momo)
                    $('#view_top_tuan').html(data.view_top_tuan)
                    var countViews = Number({{ count(Config::get('constant.list_game')) }});
                    for (let i = 1; i <= countViews; i++) {
                        $('#table_account_' + i).html(data.view_table_account[i])
                    }
                    $('.setting_tiencuoc_1').html(data.tiencuoc_1)
                    $('.setting_tiencuoc_2').html(data.tiencuoc_2)
                    $('.setting_tiencuoc_3').html(data.tiencuoc_3)
                    $('.setting_tiencuoc_4_1').html(data.tiencuoc_4_1)
                    $('.setting_tiencuoc_4_2').html(data.tiencuoc_4_2)
                    $('.setting_tiencuoc_4_3').html(data.tiencuoc_4_3)
                    $('.setting_tiencuoc_5_1').html(data.tiencuoc_5_1)
                    $('.setting_tiencuoc_5_2').html(data.tiencuoc_5_2)
                    $('.setting_tiencuoc_5_3').html(data.tiencuoc_5_3)
                    $('.setting_tiencuoc_6').html(data.tiencuoc_6)
                }
            },

            error: function () {
                console.log("Lỗi")

            }
        });
    }

    function socket(timelast) {
        $.ajax({
            url: '{{ route('home.attendance.realtime') }}',
            data: {time: timelast},
            type: 'post',
            success: function (data) {
                let result = JSON.parse(data);
                $('.diemdanh_users').html(result.count_users_attendance);
                $('#diemdanh_last').html(result.phone_win_latest);
                $('#diemdanh_id').html(result.session_current_code);
                $('#muc_users').html(result.phones_attendance);
                $('#mayman_log').html(result.view_list_session_past);
                $("#diemdanh_tongtien").html(result.total_amount);
                if (timelast % 10 == 0) {
                    $("#thoigian_head").html(result.second_realtime);
                    delete window.timelast;
                    window.timelast = Number(result.second_realtime);
                }
            }, error: function (data) {
            }
        })
    }


    function setTimeSessionAttendance() {
        setInterval(function () {
            if (timelast > 0) {
                timelast--;
            } else {
                timelast = Number('{{ $timeEach }}');
            }
            $("#thoigian_head").html(timelast);
            $("#diemdanh_thoigian").html(timelast);
            if (timelast % 2 == 0)
                socket(timelast);
        }, 1000);
    }

    function getRndInteger(min, max) {
        return Math.floor(Math.random() * (max - min)) + min;
    }

    function diemdanh() {
        var num1 = getRndInteger(1, 9);
        var num2 = getRndInteger(1, 9);
        let phone = $("#phonevalue").val();
        var floatRegex = /^((\d+(\.\d *)?)|((\d*\.)?\d+))$/;
        var phoneRegex = /\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/;
        if (phone.length <= 9 || !floatRegex.test(phone) || !phoneRegex.test(phone)) {
            alert(`Khong hop le`);
            return false;
        }

        let person = prompt("Mã xác minh " + num1 + "+" + num2 + "= ?:", "");
        if (person == null || person != (num1 + num2)) {
            alert(`Bạn đã nhập sai phép tính. Vui lòng thử lại`);
            return false;
        }
        $.ajax({
            url: '{{ route('home.attendance_session') }}',
            data: {phone: $("#phonevalue").val(), captcha: person},
            type: 'POST',
            success: function (data) {
                if (data.status == 2) {
                    alert(data.message);
                } else {
                    alert("Điểm danh thành công!");
                    num1 = Number('{{ random_int(1,9) }}');
                    num2 = Number('{{ random_int(1,9) }}');
                    $("#phonevalue").val(``)
                }
            }
        })
    }

    function diemDanhNgay(button) {

        let phone = $('#PhoneDiemDanhNgay').first().val();
        if (phone.trim() == "") {
            alert("Bạn chưa nhập số điện thoại")
            return false;
        }
        $(button).attr('disabled', true);
        $(button).css('cursor', "not-allowed");
        $.ajax({
            url: '{{ route('home.attendance_date') }}',
            data: {phone: phone},
            type: 'POST',
            success: function (data) {
                alert(data.message)
                $("#PhoneDiemDanhNgay").val(``);
                $(button).removeAttr('disabled');
                $(button).css('cursor', "auto");
            }
        })
    }
</script>
