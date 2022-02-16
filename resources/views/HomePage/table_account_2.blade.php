@php
    $dem = 0;
    $accountMomosGroupTypesTaiXiu = $accountMomosGroupTypesAllGames;
    if (!is_null($accountMomosGroupTypes->get(CONFIG_TAI_XIU)) && count($accountMomosGroupTypes->get(CONFIG_TAI_XIU)) > 0){
        $accountMomosGroupTypesTaiXiu = $accountMomosGroupTypes->get(CONFIG_TAI_XIU)->merge($accountMomosGroupTypesTaiXiu);
    }
@endphp
@if(count($accountMomosGroupTypesTaiXiu) > 0)
    @foreach($accountMomosGroupTypesTaiXiu->take(5) as $rowTaiXiu)
        <tr>
            <td id="p_27"><b id="ducnghia_27"
                             style="position: relative;">{{ $rowTaiXiu['sdt'] }} <span
                            class="label label-success text-uppercase"
                            onclick="coppy('{{ $rowTaiXiu['sdt'] }}')"><i
                                class="fa fa-clipboard" aria-hidden="true"></i></span>
                    <b style="position: absolute;
                                                                top: 15px;
                                                                margin-left: auto;
                                                                margin-right: auto;
                                                                left: 0;
                                                                right: 0;
                                                                text-align: center;
                                                                font-size: 7.5px;">
                        <font color="{{ $rowTaiXiu['color_tiencuoc'] }}">{{ number_format($rowTaiXiu['sumTienCuoc']) }}</font>/
                        <font color="6861b1">30M</font>|
                        <font color="{{ $rowTaiXiu['color_countbank'] }}">{{ $rowTaiXiu['countbank'] }}</font>/
                        <font color="6861b1">{{ CONFIG_LIMIT_LAN_BANK }}</font>
                    </b>
                </b>
            </td>
            <td>{{ number_format($rowTaiXiu['min']) }} VNĐ</td>
            <td> {{ number_format($rowTaiXiu['max']) }} VNĐ</td>

            @php
                $dem ++;
            @endphp
        </tr>
    @endforeach
@endif
