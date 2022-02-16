@php
    $dem = 0;
    $accountMomosGroupTypesGap3 = $accountMomosGroupTypesAllGames;
    if (!is_null($accountMomosGroupTypes->get(CONFIG_GAP_3)) && count($accountMomosGroupTypes->get(CONFIG_GAP_3)) > 0){
        $accountMomosGroupTypesGap3 = $accountMomosGroupTypes->get(CONFIG_GAP_3)->merge($accountMomosGroupTypesGap3);
    }
@endphp
@if(count($accountMomosGroupTypesGap3) > 0)
    @foreach($accountMomosGroupTypesGap3->take(5) as $rowGap3)
        <tr>
            <td id="p_27"><b id="ducnghia_27"
                             style="position: relative;">{{ $rowGap3['sdt'] }} <span
                            class="label label-success text-uppercase"
                            onclick="coppy('{{ $rowGap3['sdt'] }}')"><i
                                class="fa fa-clipboard" aria-hidden="true"></i></span>
                    <b style="position: absolute;
                                                                top: 15px;
                                                                margin-left: auto;
                                                                margin-right: auto;
                                                                left: 0;
                                                                right: 0;
                                                                text-align: center;
                                                                font-size: 7.5px;">
                        <font color="{{ $rowGap3['color_tiencuoc'] }}">{{ number_format($rowGap3['sumTienCuoc']) }}</font>/
                        <font color="6861b1">30M</font>|
                        <font color="{{ $rowGap3['color_countbank'] }}">{{ $rowGap3['countbank'] }}</font>/
                        <font color="6861b1">{{ CONFIG_LIMIT_LAN_BANK }}</font>
                    </b>
                </b>
            </td>
            <td>{{ number_format($rowGap3['min']) }} VNĐ</td>
            <td>{{ number_format($rowGap3['max']) }} VNĐ</td>

        </tr>
        @php
            $dem ++;
        @endphp
    @endforeach
@endif
