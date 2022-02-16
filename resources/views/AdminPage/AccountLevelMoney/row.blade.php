<tr class="row_{{ $account['id'] }}">
    <td class="mark-offset"></td>
    <td>{{ $account['sdt'] }}</td>
    <td>{{ $account['game'] }}</td>
    <td>{{ number_format($account['min']) }}</td>
    <td>{{ number_format($account['max']) }}</td>
    <td>  <button class="btn btn-{{ $account['class_status'] }} btn-sm">
            {{ $account['text_status'] }}
        </button></td>
    <td>
        <a href="javascript:;" onclick="deleteAccount({{ $account['id'] }})">
            <button class="btn btn-danger btn-sm">
                Xóa
            </button>
        </a>

        <a href="javascript:;" onclick="showEdit({{ $account['id'] }})">
            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalUpdate">
                Chỉnh sửa
            </button>
        </a>
    </td>
</tr>