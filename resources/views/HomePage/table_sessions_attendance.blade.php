@foreach($listSessionsPast as $sessionPast)
    <tr>
        <td><small>{{ $sessionPast->id }}</small></td>
        <td>{{ $sessionPast->getPhone() }}</td>
        <td><small>{{ $sessionPast->bill_code }}</small></td>
        <td>{{ number_format($sessionPast->amount) }} VNĐ</td>
    </tr>
@endforeach