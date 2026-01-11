<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Expenses PDF</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #888; padding: 8px; text-align: left; }
        th { background: #eee; }
        h2 { margin-bottom: 0; }
        .summary { margin-top: 20px; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Expenses Report</h2>
    <div>Period: {{ $from }} to {{ $to }}</div>
    <table>
        <thead>
            <tr>
                <th>Category</th>
                <th>Title</th>
                <th>Amount</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses as $exp)
            <tr>
                <td>{{ $categories->where('id', $exp->category_id)->first()->name ?? 'N/A' }}</td>
                <td>{{ $exp->title }}</td>
                <td>Rs. {{ number_format($exp->amount, 2) }}</td>
                <td>{{ $exp->date }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="summary">
        Total Expenses: Rs. {{ number_format($expenses->sum('amount'), 2) }}
    </div>
</body>
</html>
