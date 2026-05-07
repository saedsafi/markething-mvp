<tr>
    <td>
        <strong>{{ $type }}</strong>
        <p class="table-muted">{{ $time }}</p>
    </td>

    <td>{{ $user }}</td>

    <td>{{ $model }}</td>

    <td>{{ $tokens }}</td>

    <td>{{ $cost }}</td>

    <td>
        <span class="status {{ $statusClass ?? 'active-status' }}">
            {{ $status }}
        </span>
    </td>

    <td>
        <button class="mini-btn" type="button" data-open-log>
            View
        </button>
    </td>
</tr>