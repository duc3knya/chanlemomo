@php
    $dem = 0;
    $accountMomosGroupTypesTong3So = $accountMomosGroupTypesAllGames;
    if (!is_null($accountMomosGroupTypes->get(CONFIG_TONG_3_SO)) && count($accountMomosGroupTypes->get(CONFIG_TONG_3_SO)) > 0){
        $accountMomosGroupTypesTong3So = $accountMomosGroupTypes->get(CONFIG_TONG_3_SO)->merge($accountMomosGroupTypesTong3So);
    }
@endphp
@if(count($accountMomosGroupTypesTong3So) > 0)
    @foreach($accountMomosGroupTypesTong3So->take(LITMIT_SHOW_SDT_ON_WEB) as $rowTong3So)
        <tr>
            <td id="p_27"style='<?php if($rowTong3So['color_countbank'] == 'red' || $rowTong3So['color_tiencuoc'] == 'red'){
                 echo 'padding:2px';
            } ?>'>@if($rowTong3So['color_countbank'] == 'red'|| $rowTong3So['color_tiencuoc'] == 'red' )
                     <span style='font-size: 75%; color:red ' >Số Sắp Bảo Trì. Lấy số khác</span><br>
                    @else
                 @endif<b id="ducnghia_27"
                             style="position: relative;<?php if($rowTong3So['color_countbank'] == 'red' || $rowTong3So['color_tiencuoc'] == 'red'){
                 echo 'top:-5px';
            } ?>">{{ $rowTong3So['sdt'] }} <span
                            class="label label-success text-uppercase"
                            onclick="coppy('{{ $rowTong3So['sdt'] }}')"><i
                                class="fa fa-clipboard" aria-hidden="true"></i></span>
                    <b style="position: absolute;
                                                                top: 15px;
                                                                margin-left: auto;
                                                                margin-right: auto;
                                                                left: 0;
                                                                right: 0;
                                                                text-align: center;
                                                                font-size: 7.5px;">
                        <font color="{{ $rowTong3So['color_tiencuoc'] }}">{{ number_format($rowTong3So['sumTienCuoc']) }}</font>/
                        <font color="6861b1">30M</font>|
                        <font color="{{ $rowTong3So['color_countbank'] }}">{{ $rowTong3So['countbank'] }}</font>/
                        <font color="6861b1">{{ CONFIG_LIMIT_LAN_BANK }}</font>
                    </b>
                </b>
            </td>
            <td>{{ number_format($rowTong3So['min']) }} VNĐ</td>
            <td> {{ number_format($rowTong3So['max']) }} VNĐ</td>

        </tr>
        @php
            $dem ++;
        @endphp
    @endforeach
@endif
