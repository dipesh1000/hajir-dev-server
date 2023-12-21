<!DOCTYPE html>
<html>

<head>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }
    </style>
</head>

<body>

    <h2>Monthly Report</h2>

    <table>
        <tr>
            <th>Name</th>
            <th>Status</th>
            <th>Amount</th>
        </tr>

        @if ($data['candidates'] && count($data['candidates']) > 0)
            @foreach ($data['candidates'] as $key => $value)
                <tr>
                    <td>{{ $value['name'] }}</td>
                    <td>{{ $value['status'] }}</td>
                    <td>{{ $value['amount'] }}</td>
                </tr>
            @endforeach
        @endif

        <tr>
            <th>Balance</th>
            <th></th>
            <th>{{$data['balance']??0}}</th>
        </tr>
    </table>

</body>

</html>
