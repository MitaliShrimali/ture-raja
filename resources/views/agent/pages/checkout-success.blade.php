@extends('agent.layouts.app')

@section('title', 'Payment Successful - Tour Raja Agent')

@section('content')
<div class="min-h-screen bg-gray-50/50 flex flex-col items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 text-center relative overflow-hidden">
        <!-- Decorative Background Elements -->
        <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-br from-green-400 to-green-600 opacity-10"></div>
        <div class="absolute -top-16 -right-16 w-32 h-32 bg-green-400 rounded-full blur-3xl opacity-20"></div>

        <div class="relative z-10">
            <!-- Success Icon -->
            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                <i class="fas fa-check text-4xl text-green-500 animate-[bounce_1s_ease-in-out]"></i>
            </div>

            <!-- Content -->
            <h1 class="text-3xl font-black text-gray-800 mb-2">Payment Successful!</h1>
            <p class="text-gray-500 text-sm font-medium mb-8">
                Thank you for your purchase. Your payment of 
                <span class="text-gray-800 font-bold">₹{{ number_format($payment->amount, 2) }}</span> 
                for <span class="text-gray-800 font-bold">{{ $payment->plan_type }}</span> has been processed successfully.
            </p>

            <!-- Buttons -->
            <div class="flex flex-col gap-3">
                <a href="{{ route('invoice.download', $payment->id) }}" target="_blank"
                   class="w-full py-3.5 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold rounded-xl shadow-lg shadow-green-200 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                    <i class="fas fa-file-invoice"></i> View Invoice
                </a>
                <a href="{{ route('agent.dashboard') }}" 
                   class="w-full py-3.5 bg-white border-2 border-gray-100 hover:bg-gray-50 text-gray-700 font-bold rounded-xl transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                    <i class="fas fa-home text-gray-400"></i> Go to Dashboard
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
