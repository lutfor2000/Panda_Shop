<!DOCTYPE html>
<html lang="en">
<head>
    {{-- <meta charset="UTF-8"> --}}
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice #{{ $invoice->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; },

        table { width: 100%; border-collapse: collapse; margin-top: 20px; },
        
        th{
            background-color: #c8c9caff;
            border: 1px solid #eeededff;
        },

        th, td { border: 1px solid #eeededff; },
        th, td { padding: 8px; text-align: left; },
        h1 { margin-bottom: 0; }
    </style>
</head>
<body>
    <h1>Invoice #{{ $invoice->id }}</h1>
    <p>Date: {{ $invoice->created_at->format('Y-m-d') }}</p>
    <p>Customer: {{ $invoice->customer->cus_name }}</p>

    <table >
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Discount Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
        @foreach($invoice->products as $item)
            <tr>
                <td>{{ $item->product->title }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price, 2) }}</td>
                <td>{{ number_format($item->product->discount_price, 2) }}</td>
                <td>{{ number_format($item->product->discount_price * $item->quantity, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <h4>Vat: {{ number_format($invoice->vat, 2) }}</h4>
    <h4>Total: {{ number_format($invoice->payable, 2) }}</h4>
</body>
</html>
