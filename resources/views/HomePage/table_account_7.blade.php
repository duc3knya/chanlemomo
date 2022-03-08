@php
    $dem = 0;
    $accountMomosGroupTypesLo = $accountMomosGroupTypesAllGames;
    if (!is_null($accountMomosGroupTypes->get(CONFIG_GAME_LO)) && count($accountMomosGroupTypes->get(CONFIG_GAME_LO)) > 0){
        $accountMomosGroupTypesLo = $accountMomosGroupTypes->get(CONFIG_GAME_LO)->merge($accountMomosGroupTypesLo);
    }
@endphp
@if(count($accountMomosGroupTypesLo) > 0)
    @foreach($accountMomosGroupTypesLo->take(LITMIT_SHOW_SDT_ON_WEB) as $rowLo)
        <tr>
            <td id="p_27"style='<?php if($rowLo['color_countbank'] == 'red' || $rowLo['color_tiencuoc'] == 'red'){
                 echo 'padding:2px';
            } ?>'>@if($rowLo['color_countbank'] == 'red'|| $rowLo['color_tiencuoc'] == 'red' )
                     <span style='font-size: 75%; color:red ' >Số Sắp Bảo Trì. Lấy số khác</span><br>
                    @else
                 @endif<b id="ducnghia_27"
                             style="position: relative;<?php if($rowLo['color_countbank'] == 'red' || $rowLo['color_tiencuoc'] == 'red'){
                 echo 'top:-5px';
            } ?>">{{ $rowLo['sdt'] }} <span
                            class="label label-success text-uppercase"
                            onclick="coppy('{{ $rowLo['sdt'] }}')"><i
                                class="fa fa-clipboard" aria-hidden="true"></i></span>
                    <b style="position: absolute;
                                                                top: 15px;
                                                                margin-left: auto;
                                                                margin-right: auto;
                                                                left: 0;
                                                                right: 0;
                                                                text-align: center;
                                                                font-size: 7.5px;">
                        <font color="{{ $rowLo['color_tiencuoc'] }}">{{ number_format($rowLo['sumTienCuoc']) }}</font>/
                        <font color="6861b1">30M</font>|
                        <font color="{{ $rowLo['color_countbank'] }}">{{ $rowLo['countbank'] }}</font>/
                        <font color="6861b1">{{ CONFIG_LIMIT_LAN_BANK }}</font>
                    </b>
                </b>
            </td>
            <td>{{ number_format($rowLo['min']) }} VNĐ</td>
            <td> {{ number_format($rowLo['max']) }} VNĐ</td>

        </tr>
        @php
            $dem ++;
        @endphp
    @endforeach
@endif
