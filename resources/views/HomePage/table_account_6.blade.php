@php
    $dem = 0;
    $accountMomosGroupTypes1Phan3 = $accountMomosGroupTypesAllGames;
    if (!is_null($accountMomosGroupTypes->get(CONFIG_1_PHAN_3)) && count($accountMomosGroupTypes->get(CONFIG_1_PHAN_3)) > 0){
        $accountMomosGroupTypes1Phan3 = $accountMomosGroupTypes->get(CONFIG_1_PHAN_3)->merge($accountMomosGroupTypes1Phan3);
    }
@endphp
@if(count($accountMomosGroupTypes1Phan3) > 0)
    @foreach($accountMomosGroupTypes1Phan3->take(5) as $row1Phan3)
        <tr>
            <td id="p_27"><b id="ducnghia_27"
                             style="position: relative;">{{ $row1Phan3['sdt'] }} <span
                            class="label label-success text-uppercase"
                            onclick="coppy('{{ $row1Phan3['sdt'] }}')"><i
                                class="fa fa-clipboard" aria-hidden="true"></i></span>
                    <b style="position: absolute;
                                                                top: 15px;
                                                                margin-left: auto;
                                                                margin-right: auto;
                                                                left: 0;
                                                                right: 0;
                                                                text-align: center;
                                                                font-size: 7.5px;">
                        <font color="{{ $row1Phan3['color_tiencuoc'] }}">{{ number_format($row1Phan3['sumTienCuoc']) }}</font>/
                        <font color="6861b1">30M</font>|
                        <font color="{{ $row1Phan3['color_countbank'] }}">{{ $row1Phan3['countbank'] }}</font>/
                        <font color="6861b1">{{ CONFIG_LIMIT_LAN_BANK }}</font>
                    </b>
                </b>
            </td>
            <td>{{ number_format($row1Phan3['min']) }} VNĐ</td>
            <td> {{ number_format($row1Phan3['max']) }} VNĐ</td>

        </tr>
        @php
            $dem ++;
        @endphp
    @endforeach
@endif
