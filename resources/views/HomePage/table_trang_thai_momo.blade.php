@if(count($ListAccounts) > 0)
    @foreach($ListAccounts as $ListAccount)
        <tr>
            <td id="p_27"><b id="ducnghia_27">{{ $ListAccount['sdt'] }}</b> <span
                        class="label label-{{ $ListAccount['status_class'] }} text-uppercase"
                        onclick="coppy('{{ $ListAccount['sdt'] }}')"><i
                            class="fa fa-clipboard" aria-hidden="true"></i></span></td>
            <td>
                <span class="label label-{{ $ListAccount['status_class'] }} text-uppercase">{{ $ListAccount['status_text'] }}</span>
            </td>
{{--        <td>{{ \Carbon\Carbon::parse($ListAccount['created_at'])->format('d-m-Y H:o') }}</td>--}}
        <!--<td> {{ number_format($ListAccount['sumTienCuoc']) }} / {{ number_format($ListAccount['gioihan']) }} VNĐ</td>-->
        <td>{{ number_format($ListAccount['countbank']) }}/{{ CONFIG_LIMIT_LAN_BANK }}</td>
        </tr>
    @endforeach
@endif