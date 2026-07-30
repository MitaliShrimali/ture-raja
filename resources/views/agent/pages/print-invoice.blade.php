<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $payment->invoice_number ?? $payment->payment_id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8" onload="window.print()">
    <div class="max-w-3xl mx-auto bg-white p-12 rounded-lg shadow-sm border border-gray-100">
        <div class="flex justify-between items-start mb-12">
            <div>
                <h1 class="text-4xl font-bold text-orange-500 tracking-tighter">TOUR RAJA</h1>
                <p class="text-gray-500 mt-2 text-sm">Tour Raja Inc.<br>123 Travel Street<br>Booking City, 12345</p>
            </div>
            <div class="text-right">
                <h2 class="text-2xl font-bold text-gray-800 uppercase tracking-widest">INVOICE</h2>
                <p class="text-gray-500 mt-2"># {{ $payment->invoice_number ?? $payment->payment_id }}</p>
                <p class="text-gray-500">Date: {{ \Carbon\Carbon::parse($payment->date ?? $payment->created_at)->format('d M Y') }}</p>
            </div>
        </div>

        <div class="mb-12">
            <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4">Billed To</h3>
            <p class="text-lg font-medium text-gray-800">{{ $payment->user_name ?? $agent->name ?? 'Agent' }}</p>
            <p class="text-gray-500">{{ $payment->email ?? $agent->email ?? '' }}</p>
            <p class="text-gray-500">{{ $agent->company_name ?? '' }}</p>
        </div>

        <table class="w-full mb-12">
            <thead>
                <tr class="border-b-2 border-gray-100">
                    <th class="text-left py-4 text-sm font-bold text-gray-400 uppercase tracking-widest">Description</th>
                    <th class="text-right py-4 text-sm font-bold text-gray-400 uppercase tracking-widest">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr class="border-b border-gray-100">
                    <td class="py-6">
                        <p class="font-medium text-gray-800">{{ strtoupper($payment->type ?? 'Payment') }}</p>
                        <p class="text-sm text-gray-500 mt-1">Transaction ID: {{ $payment->payment_id ?? 'N/A' }}</p>
                    </td>
                    <td class="py-6 text-right font-medium text-gray-800">
                        ₹ {{ number_format($payment->amount ?? 0, 2) }}
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td class="py-6 text-right text-gray-500 font-medium">Subtotal</td>
                    <td class="py-6 text-right font-medium text-gray-800">₹ {{ number_format($payment->amount ?? 0, 2) }}</td>
                </tr>
                <tr>
                    <td class="py-4 text-right text-gray-500 font-medium border-t-2 border-gray-100">Taxes & Fees (18% GST)</td>
                    <td class="py-4 text-right font-medium text-gray-800 border-t-2 border-gray-100">₹ {{ number_format(($payment->amount ?? 0) * 0.18, 2) }}</td>
                </tr>
                <tr>
                    <td class="py-4 text-right text-gray-800 font-bold text-lg border-t-2 border-gray-100">Total</td>
                    <td class="py-4 text-right text-orange-500 font-bold text-2xl border-t-2 border-gray-100">₹ {{ number_format(($payment->amount ?? 0) * 1.18, 2) }}</td>
                </tr>
            </tfoot>
        </table>

        <div class="text-center text-gray-400 text-sm mt-16 pt-8 border-t border-gray-100">
            <p>Thank you for your business!</p>
            <p class="mt-2 mb-4">Payment Status: <span class="text-green-500 font-medium">{{ $payment->status ?? 'Success' }}</span></p>
            
            @php
                $meta = isset($payment->invoice_data) && !empty($payment->invoice_data) ? json_decode($payment->invoice_data, true) : null;
            @endphp
            <div class="inline-block text-left bg-gray-50 p-6 rounded-xl text-xs w-full max-w-lg mt-8 border border-gray-100">
                <p class="font-bold text-gray-700 mb-4 uppercase tracking-widest border-b border-gray-200 pb-3">Transaction & Banking Details</p>
                <div class="grid grid-cols-2 gap-x-8 gap-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-500 font-medium">Payment Gateway</span>
                        <span class="font-bold text-gray-900">{{ $meta['gateway'] ?? 'Razorpay / UPI' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 font-medium">Sender Bank/UPI</span>
                        <span class="font-bold text-gray-900">{{ $meta['sender'] ?? ($agent->name ?? 'Agent') . '@upi' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 font-medium">Receiver Bank Account</span>
                        <span class="font-bold text-gray-900">{{ $meta['receiver'] ?? 'Tour Raja Inc. (HDFC)' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 font-medium">Transaction Reference</span>
                        <span class="font-bold text-gray-900">{{ $payment->payment_id ?? 'TXN_' . rand(10000, 99999) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
