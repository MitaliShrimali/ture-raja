@extends('agent.layouts.app')

@section('title', 'Invoice - Tour Raja Agent')

@section('content')


        <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest mb-8">Billing Information</h3>

            <div class="space-y-6">
                @forelse($payments ?? [] as $payment)
                <div class="bg-gray-50/50 rounded-[32px] border border-gray-100 overflow-hidden group hover:border-primary/20 transition-all">
                    <!-- Progress Strip at Top -->
                    <div class="h-1.5 w-full bg-gray-100">
                        <div class="h-full bg-primary" style="width: 100%"></div>
                    </div>
                    
                    <div class="p-8 flex items-center justify-between">
                        <div class="space-y-4">
                            <h4 class="text-sm font-bold text-gray-600 uppercase">{{ strtoupper($payment->type ?? 'Payment') }} ({{ $payment->invoice_number ?? $payment->payment_id ?? 'INV-UNKNOWN' }})</h4>
                            <div class="space-y-1">
                                <p class="text-[10px] text-gray-400 font-medium">Transaction Id : {{ $payment->payment_id ?? 'N/A' }}</p>
                                <p class="text-[10px] text-gray-400 font-medium">Date : {{ \Carbon\Carbon::parse($payment->date ?? $payment->created_at)->format('d M Y') }}</p>
                                <p class="text-[10px] text-gray-400 font-medium">Status : <span class="text-green-500">{{ $payment->status ?? 'Success' }}</span></p>
                                @php
                                    $meta = isset($payment->invoice_data) && !empty($payment->invoice_data) ? json_decode($payment->invoice_data, true) : null;
                                @endphp
                                @if($meta)
                                    <p class="text-[10px] text-gray-400 font-medium mt-2">Method : <span class="text-gray-600">{{ $meta['gateway'] ?? 'N/A' }}</span></p>
                                    <p class="text-[10px] text-gray-400 font-medium">Sender : <span class="text-gray-600">{{ $meta['sender'] ?? 'N/A' }}</span></p>
                                @endif
                            </div>
                        </div>

                        <div class="text-right flex flex-col items-end">
                            <p class="text-3xl font-bold text-gray-400 mb-6 flex items-center">
                                <i class="fas fa-rupee-sign text-lg mr-2"></i> {{ number_format(($payment->amount ?? 0) * 1.18, 2) }}
                            </p>
                            <a href="{{ route('agent.invoice.download', $payment->id) }}" target="_blank" class="bg-primary text-white px-8 py-3 rounded-2xl text-xs font-bold shadow-lg shadow-orange-100 hover:scale-105 transition-all">
                                View Invoice
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <p class="text-gray-400">No invoices found.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Footer -->
@endsection
