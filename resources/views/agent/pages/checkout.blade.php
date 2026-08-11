@extends('agent.layouts.app')

@section('title', 'Checkout - Tour Raja Agent')

@section('content')

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Left Side: Order Summary -->
        <div class="lg:w-1/3">
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 sticky top-8">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest mb-8">Order Summary</h3>
                
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 rounded-full bg-orange-50 flex items-center justify-center text-[#ea580c]">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-gray-900">{{ $itemName }}</h4>
                            <p class="text-xs text-gray-400 font-medium capitalize">{{ str_replace('_', ' ', $type) }}</p>
                        </div>
                    </div>
                </div>
                
                <hr class="my-6 border-gray-100">

                <div class="space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 font-medium">Subtotal</span>
                        <span class="text-gray-900 font-bold">₹{{ number_format($amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-500 font-medium">Taxes & Fees ({{ $gst }}% GST)</span>
                        <span class="text-gray-900 font-bold">₹{{ number_format($amount * ($gst / 100), 2) }}</span>
                    </div>
                </div>

                <hr class="my-6 border-gray-100">

                <div class="flex justify-between items-center mb-8">
                    <span class="text-gray-900 font-black">Total Due</span>
                    <span class="text-3xl text-[#ea580c] font-black">₹{{ number_format($totalAmount, 2) }}</span>
                </div>

                <p class="text-[10px] text-gray-400 text-center font-medium leading-relaxed">
                    By completing this payment, you agree to Tour Raja's <a href="#" class="text-[#ea580c]">Terms of Service</a> and <a href="#" class="text-[#ea580c]">Privacy Policy</a>.
                </p>
            </div>
        </div>

        <!-- Right Side: Payment Methods -->
        <div class="lg:w-2/3">
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest mb-8">Payment Gateway</h3>

                <form action="{{ config('services.payu.test_mode') ? 'https://test.payu.in/_payment' : config('services.payu.base_url', 'https://secure.payu.in/_payment') }}" method="POST" id="checkoutForm">
                    <input type="hidden" name="key" value="{{ $payuKey }}" />
                    <input type="hidden" name="txnid" value="{{ $txnid }}" />
                    <input type="hidden" name="productinfo" value="{{ $productinfo }}" />
                    <input type="hidden" name="amount" value="{{ number_format($totalAmount, 2, '.', '') }}" />
                    <input type="hidden" name="email" value="{{ $email }}" />
                    <input type="hidden" name="firstname" value="{{ $firstname }}" />
                    <input type="hidden" name="service_provider" value="payu_paisa" />
                    <input type="hidden" name="surl" value="{{ $surl }}" />
                    <input type="hidden" name="furl" value="{{ $furl }}" />
                    <input type="hidden" name="phone" value="{{ $phone }}" />
                    <input type="hidden" name="hash" value="{{ $hash }}" />
                    <input type="hidden" name="udf1" value="{{ $udf1 ?? '' }}" />
                    <input type="hidden" name="udf2" value="{{ $udf2 ?? '' }}" />
                    <input type="hidden" name="udf3" value="{{ $udf3 ?? '' }}" />
                    <input type="hidden" name="udf4" value="{{ $udf4 ?? '' }}" />
                    <input type="hidden" name="udf5" value="{{ $udf5 ?? '' }}" />
                    <input type="hidden" name="udf6" value="{{ $udf6 ?? '' }}" />
                    <input type="hidden" name="udf7" value="{{ $udf7 ?? '' }}" />
                    <input type="hidden" name="udf8" value="{{ $udf8 ?? '' }}" />
                    <input type="hidden" name="udf9" value="{{ $udf9 ?? '' }}" />
                    <input type="hidden" name="udf10" value="{{ $udf10 ?? '' }}" />
                    @if(request()->has('package_id'))
                        <input type="hidden" name="package_id" value="{{ request('package_id') }}">
                    @endif

                    <!-- Payment Options -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        <label class="flex flex-col p-4 bg-gray-50 border-2 border-[#ea580c] rounded-2xl cursor-pointer">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <input type="radio" name="gateway" value="UPI" checked class="w-4 h-4 text-[#ea580c] focus:ring-[#ea580c]">
                                    <span class="ml-3 text-sm font-bold text-gray-900">UPI / QR Code</span>
                                </div>
                                <i class="fas fa-qrcode text-gray-400"></i>
                            </div>
                            <!-- Fake QR Code -->
                            <div class="flex flex-col items-center justify-center p-4 bg-white rounded-xl border border-gray-100">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=tour raja@upi" alt="QR Code" class="w-32 h-32 mb-2">
                                <p class="text-xs text-gray-500 font-medium">Scan to pay tour raja@upi</p>
                                <input type="text" name="sender_details" placeholder="Enter your UPI ID (e.g. name@okaxis)" value="agent_mock@upi" class="mt-4 w-full text-xs px-3 py-2 border border-gray-200 rounded-lg focus:ring-orange-500 focus:border-orange-500">
                            </div>
                        </label>

                        <label class="flex flex-col p-4 bg-white border border-gray-200 hover:border-gray-300 rounded-2xl cursor-pointer transition-colors">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <input type="radio" name="gateway" value="Card" class="w-4 h-4 text-[#ea580c] focus:ring-[#ea580c]">
                                    <span class="ml-3 text-sm font-bold text-gray-900">Credit / Debit Card</span>
                                </div>
                                <i class="fas fa-credit-card text-gray-400"></i>
                            </div>
                            <div class="space-y-3 opacity-50 pointer-events-none">
                                <input type="text" placeholder="Card Number" class="w-full text-xs px-3 py-2 border border-gray-200 rounded-lg">
                                <div class="flex space-x-2">
                                    <input type="text" placeholder="MM/YY" class="w-1/2 text-xs px-3 py-2 border border-gray-200 rounded-lg">
                                    <input type="text" placeholder="CVC" class="w-1/2 text-xs px-3 py-2 border border-gray-200 rounded-lg">
                                </div>
                                <input type="text" placeholder="Name on Card" class="w-full text-xs px-3 py-2 border border-gray-200 rounded-lg">
                            </div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between p-6 bg-orange-50 rounded-2xl border border-orange-100 mb-8">
                        <div>
                            <h4 class="text-sm font-bold text-gray-900">Secure Payment</h4>
                            <p class="text-xs text-gray-500 font-medium">You will be securely redirected to PayU to complete this payment.</p>
                        </div>
                        <i class="fas fa-shield-alt text-[#ea580c] text-2xl opacity-50"></i>
                    </div>

                    <button type="submit" class="w-full py-4 bg-[#ea580c] text-white font-black rounded-xl shadow-lg shadow-orange-100 hover:bg-orange-600 transition-all active:scale-[0.98] uppercase tracking-widest flex items-center justify-center">
                        <i class="fas fa-lock mr-2"></i> Pay with PayU &#8377;{{ number_format($totalAmount, 2) }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
