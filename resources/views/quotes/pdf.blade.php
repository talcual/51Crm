<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quote {{ $quote->quote_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #1f2937; }
        h1 { font-size: 20px; margin-bottom: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #d1d5db; padding: 6px 8px; text-align: left; }
        th { background-color: #f3f4f6; }
        .totals { width: 250px; margin-left: auto; margin-top: 12px; }
        .totals td { border: none; padding: 2px 8px; }
        .totals .total-row td { border-top: 1px solid #1f2937; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Quote {{ $quote->quote_number }}</h1>
    <p>Status: {{ ucfirst($quote->status) }} | Valid Until: {{ optional($quote->valid_until)->format('M d, Y') ?? 'N/A' }}</p>

    <p>
        <strong>Client:</strong> {{ $quote->client->name ?? 'N/A' }}<br>
        {{ $quote->client->email ?? '' }}<br>
        {{ $quote->client->phone ?? '' }}
    </p>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quote->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->unit_price, 2) }}</td>
                    <td>${{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr><td>Subtotal</td><td>${{ number_format($quote->subtotal, 2) }}</td></tr>
        <tr><td>Tax</td><td>${{ number_format($quote->tax, 2) }}</td></tr>
        <tr><td>Discount</td><td>-${{ number_format($quote->discount, 2) }}</td></tr>
        <tr class="total-row"><td>Total</td><td>${{ number_format($quote->total, 2) }}</td></tr>
    </table>

    @if($quote->terms)
        <p><strong>Terms:</strong><br>{{ $quote->terms }}</p>
    @endif

    @if($quote->notes)
        <p><strong>Notes:</strong><br>{{ $quote->notes }}</p>
    @endif
</body>
</html>
