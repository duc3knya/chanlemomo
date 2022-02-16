@php
    $dem = 0;
@endphp
@foreach($UserTopTuan as $phone => $tiencuoc)
    @php
        $dem++;
    @endphp
    <div class="row">
        <div class="col-xs-1">
            <span class="fa-stack">
                <span class="fa fa-circle fa-stack-2x text-danger"></span>
                <strong class="fa-stack-1x text-white">{{ $dem }}</strong>
            </span>
        </div>

        <div class="col-xs-2">
            <span class="label label-success">{{ $phone }}</span>
        </div>
        <div class="col-xs-5 text-right">
            <span class="label label-danger">{{ number_format($tiencuoc) }} vnđ</span>
        </div>
    </div>
@endforeach