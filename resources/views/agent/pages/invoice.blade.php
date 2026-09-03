@extends('agent.layouts.app')

@section('title', 'Invoice - Tour Raja Agent')

@section('content')

<div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest">Billing & Invoices</h3>
            <p class="text-xs text-gray-400 mt-1">View and download your official Tour Raja invoice receipts.</p>
        </div>
    </div>

    <div class="space-y-6">
        @forelse($payments ?? [] as $payment)
        @php
            $invData = \App\Http\Controllers\AdminController::prepareInvoiceData($payment);
        @endphp
        <div class="bg-gray-50/50 rounded-[32px] border border-gray-100 overflow-hidden group hover:border-orange-500/20 transition-all">
            <!-- Top Progress Strip -->
            <div class="h-1.5 w-full bg-gray-100">
                <div class="h-full bg-[#f15922]" style="width: 100%"></div>
            </div>
            
            <div class="p-8 flex items-center justify-between">
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <h4 class="text-sm font-bold text-gray-800 uppercase">{{ $invData['services'][0]['name'] ?? $payment->plan_type ?? 'Payment' }}</h4>
                        <span class="bg-orange-100 text-[#d35400] text-[10px] font-extrabold px-2.5 py-0.5 rounded-full">{{ $invData['invoice_no'] }}</span>
                    </div>
                    <div class="space-y-1 text-xs text-gray-500 font-medium">
                        <p>Transaction ID : <span class="font-semibold text-gray-700">{{ $payment->payment_id ?? 'N/A' }}</span></p>
                        <p>Date of Issue : <span class="font-semibold text-gray-700">{{ $invData['invoice_date'] }}</span></p>
                        <p>Payment Status : <span class="text-green-600 font-bold">{{ $payment->status ?? 'Success' }}</span></p>
                    </div>
                </div>

                <div class="text-right flex flex-col items-end">
                    <p class="text-2xl font-extrabold text-gray-900 mb-4 flex items-center">
                        ₹ {{ number_format($invData['grand_total'] ?? $payment->amount ?? 0, 2) }}
                    </p>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('agent.invoice.download', $payment->id) }}" target="_blank" class="bg-[#d35400] text-white px-6 py-2.5 rounded-xl text-xs font-bold shadow-md hover:scale-105 transition-all flex items-center gap-2">
                            <i class="fas fa-file-invoice"></i> View Invoice
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12">
            <i class="fas fa-file-invoice text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-400 font-medium">No invoices found for your account.</p>
        </div>
        @endforelse
    </div>
</div>

@endsection
