@php
    $dem = 0;
    $accountMomosGroupTypesChanLe = $accountMomosGroupTypesAllGames;
    if (!is_null($accountMomosGroupTypes->get(CONFIG_CHAN_LE)) && count($accountMomosGroupTypes->get(CONFIG_CHAN_LE)) > 0){
        $accountMomosGroupTypesChanLe = $accountMomosGroupTypes->get(CONFIG_CHAN_LE)->merge($accountMomosGroupTypesChanLe);
    }
@endphp
@if(count($accountMomosGroupTypesChanLe) > 0)
    @foreach($accountMomosGroupTypesChanLe->take(LITMIT_SHOW_SDT_ON_WEB) as $rowChanLe)
        <tr>
            <td id="p_27" style='<?php if($rowChanLe['color_countbank'] == 'red' || $rowChanLe['color_tiencuoc'] == 'red'){
                 echo 'padding:2px';
            } ?>'>@if($rowChanLe['color_countbank'] == 'red'|| $rowChanLe['color_tiencuoc'] == 'red' )
                     <span style='font-size: 75%; color:red ' >Số Sắp Bảo Trì. Lấy số khác</span><br>
                    @else
                 @endif
                    <b id="ducnghia_27"
                             style="position: relative;<?php if($rowChanLe['color_countbank'] == 'red' || $rowChanLe['color_tiencuoc'] == 'red'){
                 echo 'top:-5px';
            } ?> ">{{ $rowChanLe['sdt'] }} <span
                            class="label label-success text-uppercase"
                            onclick="coppy('{{ $rowChanLe['sdt'] }}')"><i
                                class="fa fa-clipboard" aria-hidden="true"></i></span>
                    <b style="position: absolute;
                                                                top: 15px;
                                                                margin-left: auto;
                                                                margin-right: auto;
                                                                left: 0;
                                                                right: 0;
                                                                text-align: center;
                                                                font-size: 7.5px;">
                        <font color="{{ $rowChanLe['color_tiencuoc'] }}">{{ number_format($rowChanLe['sumTienCuoc']) }}</font>/
                        <font color="6861b1">30M</font>|
                        <font color="{{ $rowChanLe['color_countbank'] }}">{{ $rowChanLe['countbank'] }}</font>/
                        <font color="6861b1">{{ CONFIG_LIMIT_LAN_BANK }}</font>
                    </b>
                </b>
            </td>
            <td style="font-weight: bold;color: {{$rowChanLe['color_min']}}">{{ number_format($rowChanLe['min']) }} VNĐ</td>
            <td style="font-weight: bold;color: {{$rowChanLe['color_min']}}">{{ number_format($rowChanLe['max']) }} VNĐ</td>

            @php
                $dem ++;
            @endphp
        </tr>
    @endforeach
    @else{
              <td colspan="3" ><Mark>WEB ĐANG HẾT SỐ ĐỢI ADMIN THÊM SỐ !</Mark> </td> 
         }
@endif
