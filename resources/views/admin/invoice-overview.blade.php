@extends('layouts.admin')

@section('content')
@php
    $subtotal = 0;
    foreach($invoiceData['services'] as $svc) {
        $subtotal += ($svc['qty'] * $svc['price']);
    }
    $sgst = round($subtotal * 0.09, 2);
    $cgst = round($subtotal * 0.09, 2);
    $grandTotal = $subtotal + $sgst + $cgst;

    // Convert number to words helper
    if (!function_exists('numberToWords')) {
        function numberToWords($number) {
            $decimal = round($number - ($no = floor($number)), 2) * 100;
            $hundred = null;
            $digits_length = strlen($no);
            $i = 0;
            $str = array();
            $words = array(
                0 => '', 1 => 'One', 2 => 'Two',
                3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
                7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
                10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
                13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
                16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
                19 => 'Nineteen', 20 => 'Twenty',
                30 => 'Thirty', 40 => 'Forty', 50 => 'Fifty',
                60 => 'Sixty', 70 => 'Seventy',
                80 => 'Eighty', 90 => 'Ninety'
            );
            $digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
            while( $i < $digits_length ) {
                $divider = ($i == 2) ? 10 : 100;
                $number = floor($no % $divider);
                $no = floor($no / $divider);
                $i += $divider == 10 ? 1 : 2;
                if ($number) {
                    $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                    $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                    $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural. $hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.$hundred;
                } else $str[] = null;
            }
            $Rupees = implode('', array_reverse($str));
            $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
            return ($Rupees ? $Rupees . 'Rupees ' : '') . ($paise ? 'and ' . $paise : '') . 'Only';
        }
    }
    $amountInWords = numberToWords($grandTotal);
@endphp

<div class="space-y-8 pb-12" x-data="{ editMode: false }">
    
    <!-- Top Action bar (Hidden on Print) -->
    <div class="max-w-4xl mx-auto flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-border-soft shadow-sm no-print">
        <div class="flex items-center gap-3">
            <a href="{{ url('/admin/payments') }}" class="p-3 bg-gray-50 hover:bg-gray-100 rounded-2xl text-muted-text hover:text-foreground transition-all">
                <i data-lucide="arrow-left" size="20"></i>
            </a>
            <div>
                <h3 class="text-lg font-black tracking-tight text-foreground">Invoice Overview</h3>
                <p class="text-xs text-muted-text font-medium">Reviewing transaction {{ $invoiceData['invoice_no'] }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <button @click="editMode = !editMode" class="flex-1 sm:flex-none justify-center bg-gray-100 hover:bg-gray-200 text-foreground px-6 py-3.5 rounded-2xl font-black text-xs uppercase tracking-widest transition-all flex items-center gap-2">
                <i data-lucide="edit-3" size="16"></i> 
                <span x-text="editMode ? 'View Invoice' : 'Edit Details'">Edit Details</span>
            </button>
            <button onclick="window.print()" style="background-color: #D35400 !important;" class="flex-1 sm:flex-none justify-center hover:bg-[#b84500] text-white px-6 py-3.5 rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-lg shadow-orange-500/10 flex items-center gap-2">
                <i data-lucide="printer" size="16"></i> Print Invoice
            </button>
        </div>
    </div>

    <!-- Edit Form View (Only shown when editMode is true) -->
    <div x-show="editMode" class="max-w-4xl mx-auto bg-white rounded-[40px] border border-border-soft shadow-premium p-10 space-y-8 no-print" style="display: none;">
        <div class="border-b border-border-soft pb-4">
            <h3 class="text-xl font-black text-foreground">Edit Invoice Details</h3>
            <p class="text-xs text-muted-text font-medium font-sans">Modify values to generate customized invoice view.</p>
        </div>
        
        <form action="{{ url('/admin/payments/invoice/update') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="id" value="{{ $payment->id }}" />
            
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Invoice Number</label>
                    <input required type="text" name="invoice_no" value="{{ $invoiceData['invoice_no'] }}" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#D35400]/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Issue Date</label>
                    <input required type="text" name="invoice_date" value="{{ $invoiceData['invoice_date'] }}" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#D35400]/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Customer / Agency Name</label>
                    <input required type="text" name="customer_name" value="{{ $invoiceData['customer_name'] }}" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#D35400]/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">GST Number</label>
                    <input type="text" name="customer_gstin" value="{{ $invoiceData['customer_gstin'] }}" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#D35400]/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Address</label>
                <textarea required name="customer_address" rows="3" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#D35400]/20 transition-all font-medium text-foreground shadow-sm resize-none">{{ $invoiceData['customer_address'] }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Phone</label>
                    <input required type="text" name="customer_phone" value="{{ $invoiceData['customer_phone'] }}" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#D35400]/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Email</label>
                    <input required type="email" name="customer_email" value="{{ $invoiceData['customer_email'] }}" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#D35400]/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Place of Supply</label>
                    <input required type="text" name="place_of_supply" value="{{ $invoiceData['place_of_supply'] }}" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#D35400]/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">State Code</label>
                    <input required type="text" name="state_code" value="{{ $invoiceData['state_code'] }}" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#D35400]/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Payment Due Label</label>
                    <input required type="text" name="payment_due" value="{{ $invoiceData['payment_due'] }}" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#D35400]/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Due Date</label>
                    <input required type="text" name="due_date" value="{{ $invoiceData['due_date'] }}" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#D35400]/20 transition-all font-medium text-foreground shadow-sm" />
                </div>
            </div>

            <!-- Services/Items Loop -->
            <div class="space-y-4 pt-4 border-t border-gray-100">
                <h4 class="text-[10px] font-black text-[#D35400] uppercase tracking-widest">Service & Price details</h4>
                @foreach($invoiceData['services'] as $idx => $svc)
                    <div class="bg-gray-50/50 p-6 rounded-3xl border border-gray-100 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-muted-text uppercase tracking-widest">Plan Name</label>
                                <input required type="text" name="service_name[]" value="{{ $svc['name'] }}" class="w-full bg-white border-none rounded-xl py-3 px-4 outline-none focus:ring-2 focus:ring-[#D35400]/20 transition-all font-medium text-foreground text-sm shadow-sm" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-muted-text uppercase tracking-widest">SAC/HSN Code</label>
                                <input required type="text" name="service_sac[]" value="{{ $svc['sac_hsn'] }}" class="w-full bg-white border-none rounded-xl py-3 px-4 outline-none focus:ring-2 focus:ring-[#D35400]/20 transition-all font-medium text-foreground text-sm shadow-sm" />
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[9px] font-black text-muted-text uppercase tracking-widest">Description</label>
                            <input required type="text" name="service_description[]" value="{{ $svc['description'] }}" class="w-full bg-white border-none rounded-xl py-3 px-4 outline-none focus:ring-2 focus:ring-[#D35400]/20 transition-all font-medium text-foreground text-sm shadow-sm" />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-muted-text uppercase tracking-widest">Quantity</label>
                                <input required type="number" name="service_qty[]" value="{{ $svc['qty'] }}" class="w-full bg-white border-none rounded-xl py-3 px-4 outline-none focus:ring-2 focus:ring-[#D35400]/20 transition-all font-medium text-foreground text-sm shadow-sm" />
                            </div>
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-muted-text uppercase tracking-widest">Unit Price</label>
                                <input required type="number" step="0.01" name="service_price[]" value="{{ $svc['price'] }}" class="w-full bg-white border-none rounded-xl py-3 px-4 outline-none focus:ring-2 focus:ring-[#D35400]/20 transition-all font-medium text-foreground text-sm shadow-sm" />
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-muted-text uppercase tracking-widest pl-1">Terms & Conditions</label>
                <textarea name="notes" rows="4" class="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#D35400]/20 transition-all font-medium text-foreground shadow-sm resize-none">{{ $invoiceData['notes'] }}</textarea>
            </div>
            
            <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                <button type="button" @click="editMode = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black text-muted-text uppercase tracking-widest transition-all">Cancel</button>
                <button type="submit" class="px-6 py-3 bg-[#D35400] text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-orange-500/20 hover:bg-[#b84500] transition-all">Save Changes</button>
            </div>
        </form>
    </div>

    <!-- Printable Invoice Page Area -->
    <div x-show="!editMode" class="invoice-container bg-white rounded-[40px] border border-border-soft shadow-premium p-12 md:p-16 max-w-4xl mx-auto relative overflow-hidden font-sans" id="invoice-sheet">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start gap-8 border-b border-slate-100 pb-10">
            <!-- Company Info & Logo -->
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <!-- Official Tourraja Logo component (small 30px height) -->
                    <x-logo class="h-[30px] w-auto" />
                </div>

                <div class="space-y-1 text-xs text-slate-500 font-medium leading-relaxed">
                    <p class="font-bold text-slate-900 text-sm">Tour Raja Private Limited</p>
                    <p>H-15, Sector 63, Noida, Uttar Pradesh 201301</p>
                    <p>Email: finance@tourraja.com | Ph: +91 120 4455 6677</p>
                    <p class="pt-1"><span class="font-bold text-[#D35400] bg-[#FFF9F6] border border-[#FDEBD0] px-2.5 py-0.5 rounded-full text-[9px] tracking-wide">GSTIN: 09AAHCT0000A1Z5</span></p>
                </div>
            </div>

            <!-- Invoice Badge Box -->
            <div class="bg-[#FFF9F6] border border-[#FDEBD0] rounded-[24px] p-6 min-w-[260px]">
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider">Invoice Number</span>
                        <span class="text-[9px] font-black text-[#D35400] uppercase tracking-wider bg-[#FFF2EB] px-2 py-0.5 rounded-full">INVOICE</span>
                    </div>
                    <div>
                        <span class="text-lg font-extrabold text-[#D35400] tracking-tight">{{ $invoiceData['invoice_no'] }}</span>
                    </div>
                    <div class="h-px bg-[#FDEBD0]"></div>
                    <div>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block mb-1">Date of Issue</span>
                        <span class="text-xs font-bold text-slate-800">{{ $invoiceData['invoice_date'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Section (Party Details & Origin) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 py-10 border-b border-slate-100">
            <!-- Billed To -->
            <div class="space-y-3">
                <span class="text-[9px] font-black text-[#D35400] uppercase tracking-wider bg-[#FFF9F6] px-2.5 py-1 rounded-full inline-block">Party Details (Billed To)</span>
                <div class="space-y-1">
                    <h3 class="text-xl font-extrabold text-slate-900 leading-tight">{{ $invoiceData['customer_name'] }}</h3>
                    <div class="text-xs text-slate-600 font-medium leading-relaxed space-y-1">
                        <p class="whitespace-pre-line text-slate-600">{{ $invoiceData['customer_address'] }}</p>
                        @if(!empty($invoiceData['customer_gstin']))
                            <p class="pt-1"><span class="font-bold text-slate-800">GSTIN:</span> {{ $invoiceData['customer_gstin'] }}</p>
                        @endif
                        <p><span class="font-bold text-slate-800">Contact:</span> {{ $invoiceData['customer_phone'] }}</p>
                        <p><span class="font-bold text-slate-800">Email:</span> {{ $invoiceData['customer_email'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Supply & Origin -->
            <div class="space-y-3">
                <span class="text-[9px] font-black text-[#D35400] uppercase tracking-wider bg-[#FFF9F6] px-2.5 py-1 rounded-full inline-block">Supply & Origin</span>
                <div class="grid grid-cols-2 gap-y-3 gap-x-6 pt-1">
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5">Place of Supply</span>
                        <span class="text-xs font-bold text-slate-800">{{ $invoiceData['place_of_supply'] }}</span>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5">State Code</span>
                        <span class="text-xs font-bold text-slate-800">{{ $invoiceData['state_code'] }}</span>
                    </div>
                    <div class="col-span-2">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5">Payment Due</span>
                        <span class="text-xs font-extrabold text-slate-800">{{ $invoiceData['payment_due'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Services / Items Table (Highlighted header and border box) -->
        <div class="my-8 border border-[#FDEBD0] rounded-[24px] overflow-hidden bg-white">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#FFF9F6] border-b border-[#FDEBD0]">
                        <th class="py-4 pl-6 text-[9px] font-black text-[#D35400] uppercase tracking-wider w-12">Item</th>
                        <th class="py-4 text-[9px] font-black text-[#D35400] uppercase tracking-wider">Plan & Description</th>
                        <th class="py-4 text-[9px] font-black text-[#D35400] uppercase tracking-wider w-24">SAC/HSN</th>
                        <th class="py-4 text-[9px] font-black text-[#D35400] uppercase tracking-wider w-16 text-center">Qty</th>
                        <th class="py-4 text-[9px] font-black text-[#D35400] uppercase tracking-wider w-28 text-right">Price</th>
                        <th class="py-4 pr-6 text-[9px] font-black text-[#D35400] uppercase tracking-wider w-32 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#FDEBD0]/30">
                    @foreach($invoiceData['services'] as $idx => $svc)
                        @php
                            $lineNo = str_pad($idx + 1, 2, '0', STR_PAD_LEFT);
                            $lineTotal = $svc['qty'] * $svc['price'];
                        @endphp
                        <tr>
                            <td class="py-4 pl-6 text-xs font-bold text-slate-400 align-top">{{ $lineNo }}</td>
                            <td class="py-4 pr-4 align-top">
                                <span class="text-xs font-extrabold text-slate-900 block">{{ $svc['name'] }}</span>
                                <span class="text-[11px] text-slate-500 font-medium mt-0.5 block leading-relaxed">{{ $svc['description'] }}</span>
                            </td>
                            <td class="py-4 text-xs font-bold text-slate-600 align-top">{{ $svc['sac_hsn'] }}</td>
                            <td class="py-4 text-xs font-extrabold text-slate-900 text-center align-top">{{ $svc['qty'] }}</td>
                            <td class="py-4 text-xs font-bold text-slate-700 text-right align-top">₹{{ number_format($svc['price'], 2) }}</td>
                            <td class="py-4 pr-6 text-xs font-extrabold text-[#D35400] text-right align-top">₹{{ number_format($lineTotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Footer Breakdown & Bank Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 pt-6">
            <!-- Bank Details & Terms -->
            <div class="space-y-6">
                <!-- Bank Info -->
                <div class="space-y-3">
                    <span class="text-[9px] font-black text-[#D35400] uppercase tracking-wider bg-[#FFF9F6] px-2.5 py-1 rounded-full inline-block">Bank Details (HDFC)</span>
                    <div class="bg-[#FFF9F6] border border-[#FDEBD0] rounded-[20px] p-5 text-xs leading-relaxed space-y-1 text-slate-600 font-medium">
                        <p><span class="font-bold text-slate-800">Account Name:</span> Tour Raja Private Limited</p>
                        <p><span class="font-bold text-slate-800">Account No:</span> 50200001234567</p>
                        <p><span class="font-bold text-slate-800">IFSC Code:</span> HDFC0001234</p>
                        <p><span class="font-bold text-slate-800">Branch:</span> Sector 63, Noida</p>
                    </div>
                </div>

                <!-- Terms & Conditions -->
                <div class="space-y-3">
                    <span class="text-[9px] font-black text-[#D35400] uppercase tracking-wider bg-[#FFF9F6] px-2.5 py-1 rounded-full inline-block">Terms & Conditions</span>
                    <div class="text-[9px] text-slate-500 font-medium leading-relaxed whitespace-pre-line pl-1">
                        {{ $invoiceData['notes'] }}
                    </div>
                </div>
            </div>

            <!-- Tax Summary Breakdown -->
            <div class="space-y-6 flex flex-col justify-between items-end">
                <div class="w-full space-y-2">
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-bold text-slate-500">Subtotal</span>
                        <span class="font-bold text-slate-800">₹{{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-medium text-slate-500">SGST (9%)</span>
                        <span class="font-medium text-slate-800">₹{{ number_format($sgst, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="font-medium text-slate-500">CGST (9%)</span>
                        <span class="font-medium text-slate-800">₹{{ number_format($cgst, 2) }}</span>
                    </div>
                    <div class="h-px bg-slate-200 my-2"></div>
                    <div class="flex justify-between items-end">
                        <span class="text-[9px] font-extrabold text-[#D35400] uppercase tracking-wider pb-1">Grand Total</span>
                        <div class="text-right">
                            <span class="text-4xl font-extrabold text-[#D35400] tracking-tight">₹{{ number_format($grandTotal, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Tiny text size for Amount in Words -->
                <div class="text-right w-full pt-2">
                    <p class="text-[9px] font-black text-[#D35400] uppercase tracking-wider mb-0.5">Amount In Words</p>
                    <p class="text-[11px] font-bold text-slate-700 italic leading-snug">{{ $amountInWords }}</p>
                </div>

                <!-- Signature Section -->
                <div class="text-center pt-8 min-w-[200px] border-t border-slate-200 w-full md:w-auto">
                    <p class="font-serif text-base text-slate-700 italic tracking-wider mb-1">Rohan Mehra</p>
                    <div class="h-px bg-slate-200 my-1"></div>
                    <p class="text-[9px] font-black text-[#D35400] uppercase tracking-wider block mb-0.5">Director's Signature</p>
                    <p class="text-[8px] text-slate-400 font-medium italic">Computer generated invoice, no signature required for validation</p>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>

<!-- Invoice print styles and custom color definitions -->
<style>
/* Custom Color Highlights (Fallbacks for JIT CSS compilation) */
.text-\[\#D35400\] {
    color: #D35400 !important;
}
.bg-\[\#FFF9F6\] {
    background-color: #FFF9F6 !important;
}
.bg-\[\#FFF2EB\] {
    background-color: #FFF2EB !important;
}
.border-\[\#FDEBD0\] {
    border-color: #FDEBD0 !important;
}
.divide-\[\#FDEBD0\]\/30 > * + * {
    border-color: rgba(253, 235, 208, 0.3) !important;
}

@media print {
    /* Hide layout elements, headers and sidebars */
    aside, header, #sidebar-nav, .no-print, .shrink-0 {
        display: none !important;
    }

    /* Reset global layout containers to print cleanly in natural document order */
    html, body {
        background: white !important;
        height: auto !important;
        overflow: visible !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    /* Neutralize flex wrappers and overflow hidden containers */
    .flex, main, .overflow-y-auto, .overflow-x-hidden, .custom-scroll {
        display: block !important;
        overflow: visible !important;
        height: auto !important;
        width: auto !important;
        padding: 0 !important;
        margin: 0 !important;
        border: none !important;
        box-shadow: none !important;
    }

    /* Display the invoice sheet naturally on the page */
    .invoice-container, #invoice-sheet {
        display: block !important;
        position: relative !important;
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 auto !important;
        padding: 0.5cm !important;
        border: none !important;
        box-shadow: none !important;
        background: white !important;
        overflow: visible !important;
    }

    /* Force background color rendering */
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    @page {
        size: A4 portrait;
        margin: 1.5cm 1cm 1.5cm 1cm;
    }
}
</style>
@endsection
