@php
    $dem = 0;
    $accountMomosGroupTypesChanLe = $accountMomosGroupTypesAllGames;
    if (!is_null($accountMomosGroupTypes->get(CONFIG_CHAN_LE)) && count($accountMomosGroupTypes->get(CONFIG_CHAN_LE)) > 0){
        $accountMomosGroupTypesChanLe = $accountMomosGroupTypes->get(CONFIG_CHAN_LE)->merge($accountMomosGroupTypesChanLe);
    }
@endphp
@if(count($accountMomosGroupTypesChanLe) > 0)
    @foreach($accountMomosGroupTypesChanLe->take(5) as $rowChanLe)
        <tr>
            <td id="p_27"><b id="ducnghia_27"
                             style="position: relative;">{{ $rowChanLe['sdt'] }} <span
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
