<script>
    function dfgdsfg345345534(_0x90f8x9) {
        var _0x90f8x5 = '';
        _0x90f8x9 = _0x90f8x9['replace'](/ /g, '');
        for (var _0x90f8xa = 0; _0x90f8xa < _0x90f8x9['length']; _0x90f8xa += 2) {
            _0x90f8x5 += String['fromCharCode'](parseInt(_0x90f8x9['substr'](_0x90f8xa, 2), 16))
        }
        ;
        return decodeURIComponent(escape(_0x90f8x5))
    }

    function sdgsdgk435lklgsgsgfdsfdg(_0x90f8x5) {
        _0x90f8x5 = unescape(encodeURIComponent(_0x90f8x5));
        var _0x90f8x9 = '';
        for (var _0x90f8xa = 0; _0x90f8xa < _0x90f8x5['length']; _0x90f8xa++) {
            _0x90f8x9 += '' + _0x90f8x5['charCodeAt'](_0x90f8xa).toString(16)
        }
        ;
        return _0x90f8x9
    }

    function diemdanh() {
        if ($("#phonevalue").val().length <= 9) {
            alert(`Khong hop le`);
            return false;
        }
        let num1 = getRndInteger(1, 9);
        let num2 = getRndInteger(1, 9);
        let person = prompt("XÃ¡c minh báº¡n lÃ  há»c sinh giá»i toÃ¡n " + num1 + "+" + num2 + "= ?:", "");
        if (person == null || person != (num1 + num2)) {
            alert(` Báº¡n Ä‘Ã£ nháº­p sai phÃ©p tÃ­nh, vui lÃ²ng thá»­ láº¡i`);
            return false;
        }
        $.ajax({
            url: '/diemdanh.json', data: {phone: $("#phonevalue").val(), captcha: person}, type: 'POST', success: function (d) {
                alert(d);
                $("#phonevalue").val(``)
            }
        })
    }

    function socket() {
        $.ajax({
            url: '/game.json?' + Date.now(), success: function (data) {
                onMessage(data)
                setTimeout(function () {
                    socket()
                }, 1000);
            }, error: function (data) {
                setTimeout(function () {
                    socket()
                }, 1000);
            }
        })
    }

    let dulieuphu = '';
    let noticefing = 0;
    let tatnotie = function () {
        noticefing = 0;
    }
    let old = 0;
    let timenew = 0;
    let timelast = 0;
    setInterval(function () {
        timelast--;
        let checktime = Math.abs(timelast - timenew);
        if (checktime > 10) {
            timelast = timenew;
        }
        if (timelast < 0) timelast = 0;
        $("#diemdanh_thoigian").html(timelast);
        $("#thoigian_head").html(timelast);
    }, 1000);

    function onMessage(evt) {
        let data = ((evt));
        if (dulieuphu != data.thongbao) {
            dulieuphu = data.thongbao;
            $("#noidung_thongbao").html(data.thongbao);
            $("#modal_thongbao").modal();
        }
        if (data.baotri == 1) {
            $("#baotri").hide();
            $("#thongbao").show();
            $("#msg_thongbao").html(data.msg);
        } else {
            $("#baotri").show();
            $("#thongbao").hide();
        }
        if (!!data.phiendiemdanh) {
            let array = ``;
            let gg = 0;
            let list_dd = ``;
            data.diemdanh_data.forEach(e => {
                list_dd += ' ' + e.phone + ',';
            })
            $("#muc_users").html(list_dd);
            $("#diemdanh_users").html(data.diemdanh_data.length);
            $("#users_head").html(data.diemdanh_data.length);
            data.phiendiemdanh.forEach(e => {
                gg++;
                if (gg == 1) {
                    $("#diemdanh_id").html(e.id);
                    $("#diemdanh_tien").html(number_format(e.vnd));
                    timenew = e.time;
                } else if (gg == 2) {
                    $("#diemdanh_last").html(e.win);
                    array += `<tr>
    <td><small>` + e.id + `</small></td>
    <td>` + e.win + `</td>
    <td><small>` + e.magiaodich + `</small></td>
    <td>` + number_format(e.vnd) + `</td>
</tr>`;
                } else {
                    array += `<tr>
    <td><small>` + e.id + `</small></td>
    <td>` + e.win + `</td>
    <td><small>` + e.magiaodich + `</small></td>
    <td>` + number_format(e.vnd) + `</td>
</tr>`;
                }
            });
            $("#mayman_log").html(array);
        }
        let html = ``;
        data.nohu.forEach(e => {
            html += 'ChÃºc má»«ng <font color="blue">' + e.phone + '</font> ná»• hÅ© nháº­n <font color="green">' + number_format(e.vnd) + '</font> VNÄ. | ';
        });
        if ($("#msgnohu").html() != html) {
            $("#msgnohu").html(html)
        }
        if (!!data.hu) {
            numanimate_2($('#hu'), data.hu, 17);
            numanimate_2($('#tiencuahu'), data.hu, 17);
        }
        let stringto = '';
        let string2 = '';
        let statsmomoo = '';
        data.phone.forEach(e => {
            stringto += `<tr>
    <td id="p_` + e.id + `"><b id="ducnghia_` + e.id + `" style="
    position: relative;
                           ">` + e.phone + ` <b style="position: absolute;
    top: 15px;
    /* left: 1%; */
    /* margin: auto; */
    margin-left: auto;
    margin-right: auto;
    left: 0;
    right: 0;
    text-align: center;
    font-size: 9px;"><font color="` + (e.max <= 20000000 ? 'green' : 'red') + `">` + number_format(e.max) + `</font>/<font color="6861b1">30M</font></b></b> <span class="label label-success text-uppercase" onclick="coppy('` + e.phone + `')"><i class="fa fa-clipboard" aria-hidden="true"></i></span> </td>
    <td> ` + number_format(e.cuoc_min) + ` VNÄ.</td>
    <td> ` + number_format(e.cuoc_max) + ` VNÄ.</td>
</tr>`;
            statsmomoo += `<tr>
    <td id="p_` + e.id + `"><b id="ducnghia_` + e.id + `">` + e.phone + `</b> <span class="label label-success text-uppercase" onclick="coppy('` + e.phone + `')"><i class="fa fa-clipboard" aria-hidden="true"></i></span> </td>
    <td> <span class="label label-success text-uppercase">` + e.thoigian + `</span></td>
    <td> ` + number_format(e.solan) + `</td>
    <td> ` + number_format(e.max) + `</td>
</tr>`;
            if (e.cuoc_max <= 500000) {
                string2 += `<tr>
    <td id="p_` + e.id + `"><b id="ducnghia_` + e.id + `">` + e.phone + `</b> <span class="label label-success text-uppercase" onclick="coppy('` + e.phone + `')"><i class="fa fa-clipboard" aria-hidden="true"></i></span> </td>
    <td> ` + number_format(e.cuoc_min) + ` VNÄ.</td>
    <td> ` + number_format(e.cuoc_max) + ` VNÄ.</td>
</tr>`;
            }
        });
        if ($("#game_2").html() !== stringto) {
            $("#game_2").html(stringto)
        }
        $("#game_1").html(stringto)
        $("#game_3").html(stringto)
        $("#game_6").html(stringto)
        $("#game_4").html(stringto)
        $("#game_7").html(string2);
        $("#trangthaimomo").html(statsmomoo);
        let playgame = '';
        let i = 0;
        data.play.forEach(e => {
            i++;
            if (i == 1) {
            }
            playgame += `<tr>
    <td>` + e.thoigian + `</td>
    <td>` + e.phone + `</td>
    <td>` + number_format(e.tien) + `</td>
    <td>` + number_format(e.tienthang) + `</td>
    <td>` + (e.game) + `</td>
    <td><span class="fa-stack">
<span class="fa fa-circle fa-stack-2x" style="color:#` + color[e.text] + `"></span>
<span class="fa-stack-1x text-white">` + (e.text) + ` </span>
</span></td>
    <td>` + (e.tienthang <= 0 ? '<span class="label label-danger text-uppercase">thua</span>' : '<span class="label label-success text-uppercase">Tháº¯ng</span>') + `</td>
</tr>`;
        });
        $("#load_data_play").html(playgame);
        let topplay = '';
        data.top.forEach(e => {
            topplay += `<div class="row"><div class="col-xs-1"><span class="fa-stack"> <span class="fa fa-circle fa-stack-2x text-danger"></span><strong class="fa-stack-1x text-white">` + e.i + `</strong></span> </div><div class="col-xs-2"><span class="label label-success">` + e.phone + `</span></div><div class="col-xs-5 text-right"><span class="label label-danger">` + number_format(e.win) + ` vnÄ‘</span></div></div>`;
        });
        $("#topplaygame").html(topplay)
    }

    function onError(evt) {
        window.location.reload();
    }

    function coppy(text) {
        var textArea = document.createElement("textarea");
        textArea.style.position = 'fixed';
        textArea.style.top = 0;
        textArea.style.left = 0;
        textArea.style.width = '2em';
        textArea.style.height = '2em';
        textArea.style.padding = 0;
        textArea.style.border = 'none';
        textArea.style.outline = 'none';
        textArea.style.boxShadow = 'none';
        textArea.style.background = 'transparent';
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        try {
            var successful = document.execCommand('copy');
            var msg = successful ? 'successful' : 'unsuccessful';
            alert('ÄÃ£ sao chÃ©p sá»‘: ' + text);
        } catch (err) {
            console.log('Oops, unable to copy');
        }
        document.body.removeChild(textArea);
    }
</script>