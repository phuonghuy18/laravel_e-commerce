<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Item Statistics</title>
</head>
<body>
    <h1>Order Item Statistics</h1>

    <table border="1">
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Sale Price</th>
                <th>Total Quantity Sold</th>
                <th>Total Revenue</th>
                <th>Import Price</th>
                <th>Import Quantity</th>
                <th>Total Import Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach($statistics as $stat)
                <tr>
                    <td>{{ $stat->product_id }}</td>
                    <td>{{ $stat->name }}</td>
                    <td>{{ number_format($stat->sale_price ?? 0) }}</td>
                    <td>{{ $stat->total_qty ?? 0 }}</td>
                    <td>{{ number_format($stat->total_revenue ?? 0) }}</td>
                    <td>{{ number_format($stat->import_price ?? 0) }}</td>
                    <td>{{ $stat->import_qty ?? 0 }}</td>
                    <td>{{ number_format($stat->total_import_price ?? 0) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
