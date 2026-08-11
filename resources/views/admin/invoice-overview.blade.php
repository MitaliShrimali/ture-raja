@extends('layouts.admin')

@section('admin_title', 'Invoice Overview')

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
            <button onclick="downloadPDF()" style="background-color: #2e7d32 !important;" class="flex-1 sm:flex-none justify-center hover:bg-[#1b5e20] text-white px-6 py-3.5 rounded-2xl font-black text-xs uppercase tracking-widest transition-all shadow-lg shadow-green-500/10 flex items-center gap-2">
                <i data-lucide="download" size="16"></i> Download PDF
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
                    <div class="flex gap-2 items-center">
                        <div class="relative w-28 shrink-0">
                            <select class="phone-country-code w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-3 outline-none text-xs font-medium focus:ring-2 focus:ring-[#D35400]/20">
                                <option value="+91" data-len="10" selected>🇮🇳 +91</option>
                                <option value="+1" data-len="10">🇺🇸 +1</option>
                                <option value="+44" data-len="10">🇬🇧 +44</option>
                                <option value="+62" data-len="11">🇮🇩 +62</option>
                                <option value="+65" data-len="8">🇸🇬 +65</option>
                                <option value="+971" data-len="9">🇦🇪 +971</option>
                                <option value="+61" data-len="9">🇦🇺 +61</option>
                                <option value="+66" data-len="9">🇹🇭 +66</option>
                                <option value="+60" data-len="10">🇲🇾 +60</option>
                            </select>
                        </div>
                        <div class="relative flex-grow">
                            <input type="tel" required placeholder="Phone *"
                                class="phone-number-val w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none focus:ring-2 focus:ring-[#D35400]/20 transition-all font-medium text-foreground shadow-sm">
                        </div>
                    </div>
                    <input type="hidden" class="phone-full-val" name="customer_phone" value="{{ $invoiceData['customer_phone'] }}">
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
                <button type="submit" style="background-color: #D35400 !important;" class="px-6 py-3 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-xl shadow-orange-500/20 transition-all hover:opacity-90">Save Changes</button>
            </div>
        </form>
    </div>

    <!-- Printable Invoice Page Area -->
    <div x-show="!editMode" class="invoice-container bg-white rounded-[40px] border border-border-soft shadow-premium p-12 md:p-16 max-w-4xl mx-auto relative overflow-hidden font-sans" id="invoice-sheet">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start gap-8 border-b border-slate-100 pb-10">
            <!-- Company Info & Logo -->
            <div class="space-y-4">
                <svg style="height: 30px; width: auto;" height="30" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1263.1 414.1">
                  <defs>
                    <linearGradient id="tour raja-gradient-invoice" x1="131.4" y1="428.13" x2="297.8" y2="428.13" gradientTransform="translate(0 -181.39)" gradientUnits="userSpaceOnUse">
                      <stop offset="0" stop-color="#f9703b"/>
                      <stop offset="1" stop-color="#e44100"/>
                    </linearGradient>
                  </defs>
                  
                  <!-- "tour" part (Orange) -->
                  <g fill="#f15922">
                    <path d="M123.6,268.6c-2.5-2.8-5.6-4.2-9.2-4.2h-10.3c-8,0-14.6-3.1-19.8-9.3-5.3-6.2-7.9-14.1-7.9-23.5v-81.6h30.3c4.1,0,7.4-1.2,10-3.7s3.8-5.4,3.8-8.9-1.3-7.3-3.8-9.7c-2.6-2.4-5.9-3.6-10-3.6h-30.3v-47.7c0-4.3-1.4-7.8-4.2-10.6-2.8-2.8-6.5-4.2-11-4.2s-7.8,1.4-10.6,4.2c-2.8,2.8-4.2,6.3-4.2,10.6v47.7h-16.8c-4.1,0-7.4,1.2-10,3.6s-3.9,5.6-3.9,9.7,1.3,6.4,3.9,8.9,5.9,3.7,10,3.7h16.8v81.6c0,12,2.5,22.7,7.6,32.1,5.1,9.3,11.9,16.8,20.7,22.2,8.7,5.5,18.5,8.2,29.5,8.2h6.1c4.9,0,9-1.4,12.2-4.2s4.8-6.4,4.8-10.6-1.2-7.8-3.7-10.6v-.1h0Z"/>
                    <path d="M468.9,117.4c-4.3,0-7.9,1.4-10.6,4.2-2.8,2.8-4.2,6.4-4.2,11v92.5c0,8.2-2.3,15.5-6.8,21.9s-10.6,11.6-18.2,15.5-16.3,5.8-26,5.8-19.2-2-27.2-6c-8.1-4-14.5-10-19.2-18.1-4.7-8.1-7.1-18.3-7.1-30.8v-80.9c0-4.3-1.5-7.9-4.4-10.8s-6.5-4.4-10.8-4.4-7.9,1.4-10.8,4.4c-2.9,2.9-4.4,6.5-4.4,10.8v80.9c0,17.6,3.4,32.6,10.3,44.8,6.9,12.3,16.2,21.5,28.1,27.9,11.8,6.3,25.3,9.5,40.3,9.5s26.9-3.1,38.2-9.2c6.8-3.7,12.8-8.3,17.9-13.6v6.1c0,4.5,1.4,8.2,4.2,11,2.8,2.8,6.3,4.2,10.6,4.2s8.2-1.4,11-4.2,4.2-6.4,4.2-11v-146.4c0-4.5-1.4-8.2-4.2-11-2.8-2.8-6.4-4.2-11-4.2l.1.1h0Z"/>
                    <path d="M601.7,121.1c-6-3.8-14.9-5.6-26.8-5.6s-24.3,3-35,9c-2.1,1.2-4,2.5-6,3.8-.5-3-1.6-5.4-3.4-7.2-2.5-2.5-6.2-3.7-11.1-3.7s-8.4,1.3-11,3.9-3.9,6.2-3.9,11v146.7c0,4.9,1.3,8.7,3.9,11.1,2.6,2.5,6.2,3.7,11,3.7s8.6-1.3,11.1-3.9,3.7-6.2,3.7-11v-94.8c0-10.1,3-18.5,9-25.1,6-6.7,14.3-11.3,24.7-14.2,7.5-2.1,7.7-2,21.8-1,3.1.2,5.8.9,8.4.6,2.6-.2,4.8-1.1,6.6-2.6s3.2-3.8,4-6.8c1.3-5.6-1.1-10.3-7.1-14l.1.1h0Z"/>
                  </g>
                  
                  <!-- 'u' base (Dark orange) -->
                  <path fill="#e44100" d="M285.5,146.3c-4.4-4.4-7.2-9.7-7.2-9.7-8.6-9.8-18.7-16.8-31-21.9-10-4.1-21.3-6.4-32.8-6.4s-22.8,2.3-32.8,6.4c-12.3,5.1-22.4,12.1-31,21.9,0,0-2.8,5.3-7.2,9.7,2.9-5.1,4.6-9.9,8.5-14.3h0c8.4-9.6,18.9-17.2,30.8-22.1,9.8-4,20.4-6.3,31.7-6.3s21.9,2.2,31.7,6.3c11.9,4.9,22.5,12.5,30.8,22.1h0c3.9,4.4,5.6,9.2,8.5,14.3"/>
                  
                  <!-- 'u' smile (Gradient) -->
                  <path fill="url(#tour raja-gradient-invoice)" d="M297.8,204.8c0,45.9-37,90.2-82.9,90.2s-83.5-44.5-83.5-90.5c0-2,0-4,.3-6,3,43.2,48.5,61.4,82.9,62.6,25.7.9,79.9-19.2,83-62.4.1,2,.2,4.1.2,6.2"/>
                
                  <!-- "raja" part & eyes (Hardcoded Dark Gray / Black) -->
                  <g fill="#1a1a1a" fill-rule="evenodd">
                    <path d="M143.6,174.6c0,5.6-4.5,10.1-10.1,10.1s-10.1-4.5-10.1-10.1,4.5-10.1,10.1-10.1,10.1,4.5,10.1,10.1"/>
                    <path d="M307.5,174.6c0,5.6-4.5,10.1-10.1,10.1s-10.1-4.5-10.1-10.1,4.5-10.1,10.1-10.1,10.1,4.5,10.1,10.1"/>
                    <path d="M746.7,135.5c1.3-5.6-1.1-10.3-7.1-14-6-3.8-14.9-5.6-26.8-5.6s-24.3,3-35,9c-7.6,4.3-14.1,9.7-19.5,16.2v-8.5c0-4.9-1.2-8.7-3.7-11.1-2.5-2.5-6.2-3.7-11.1-3.7s-8.4,1.3-11,3.9-3.9,6.2-3.9,11v146.7c0,4.9,1.3,8.7,3.9,11.1,2.6,2.5,6.2,3.7,11,3.7s8.7-1.3,11.1-3.9c2.5-2.6,3.7-6.2,3.7-11v-94.8c0-10.1,3-18.5,9-25.1,6-6.7,14.2-11.4,24.7-14.2,10.4-2.8,22.3-3.1,35.6-1,3,.6,5.8.9,8.4.6,2.6-.2,4.8-1.1,6.6-2.6s3.2-3.8,4-6.8l.1.1Z"/>
                    <path d="M893.2,290c2.9,2.9,6.5,4.4,10.8,4.4s8.2-1.5,11-4.4,4.2-6.5,4.2-10.8v-73.2c0-17-3.9-32.2-11.8-45.8-7.8-13.5-18.4-24.2-31.8-32.1-13.3-7.8-28.5-11.8-45.5-11.8s-31.9,3.9-45.3,11.8c-13.4,7.8-24.1,18.5-31.9,31.9-7.8,13.4-11.8,28.8-11.8,45.9s3.7,32.2,11,45.8c7.3,13.5,17.3,24.2,30,31.9s27,11.6,42.9,11.6,30-3.8,42.4-11.4c8.3-5.1,15.5-11.5,21.4-19.1v14.5c0,4.3,1.5,7.9,4.4,10.8h0ZM860.9,260.4c-8.9,5.5-19.2,8.2-30.8,8.2s-21.3-2.7-30.5-8.2c-9.1-5.5-16.3-12.9-21.6-22.4s-7.9-20.1-7.9-31.9,2.6-22.8,7.9-32.2c5.3-9.5,12.5-16.9,21.6-22.4s19.3-8.2,30.5-8.2,21.9,2.7,30.8,8.2c8.9,5.5,16,13,21.3,22.4,5.3,9.5,7.9,20.2,7.9,32.2s-2.6,22.5-7.9,31.9c-5.3,9.5-12.4,16.9-21.3,22.4h0Z"/>
                    <path d="M1179.7,160.3c-7.8-13.5-18.4-24.2-31.8-32.1-13.3-7.8-28.5-11.8-45.5-11.8s-31.9,3.9-45.3,11.8c-13.4,7.8-24.1,18.5-31.9,31.9s-11.8,28.8-11.8,45.9,3.7,32.2,11,45.8c7.3,13.5,17.3,24.2,30,31.9s27,11.6,42.9,11.6,30-3.8,42.4-11.4c8.3-5.1,15.5-11.5,21.4-19.1v14.5c0,4.3,1.5,7.9,4.4,10.8s6.5,4.4,10.8,4.4,8.2-1.5,11-4.4,4.2-6.5,4.2-10.8v-73.2c0-17-3.9-32.2-11.8-45.8h0ZM1133.3,260.4c-8.9,5.5-19.2,8.2-30.8,8.2s-21.3-2.7-30.5-8.2c-9.1-5.5-16.3-12.9-21.6-22.4-5.3-9.5-7.9-20.1-7.9-31.9s2.6-22.8,7.9-32.2c5.3-9.5,12.5-16.9,21.6-22.4s19.3-8.2,30.5-8.2,21.9,2.7,30.8,8.2,16,13,21.3,22.4c5.3,9.5,7.9,20.2,7.9,32.2s-2.6,22.5-7.9,31.9c-5.3,9.5-12.4,16.9-21.3,22.4h0Z"/>
                    <path d="M951.2,139.4v152.3c0,8.8-2,16.6-6,23.4s-9.4,12.1-16.3,16.1c-6.9,4-14.8,5.9-23.9,5.9s-7.8,1.4-10.6,4.2-4.2,6.5-4.2,11,1.4,8.2,4.2,11,6.4,4.2,10.6,4.2c15,0,28.3-3.3,39.7-9.8,11.4-6.6,20.3-15.5,26.8-26.9s9.7-24.4,9.7-39v-152.2c-9.9-1.5-19.9-1.6-30,0v-.2h0Z"/>
                    <path d="M993.7,102.7c-6.4,1.6-12,4.4-17,8.6-.4.3-.6,1-.6,1.6,0,1.6.1,3.2.2,4.8-1.5-3.5-2.6-7-4-10.3-1.5-3.4-3.4-6.5-6.4-9.1-5.7,5.3-8,12.3-10,19.4,0-1.5-.2-2.9,0-4.3.2-1.2-.3-2.1-1.3-2.7-3.2-1.9-6.3-4-9.6-5.7-2.2-1.1-4.7-1.7-7.1-2.6,0,.2-.2.3-.2.4,3.3,7.8,5.8,15.9,7.7,24.1.3,1.1,1,.8,1.7.7,1.4-.3,2.9-.5,4.3-.7,1.6-.3,3.2-.4,4.8-.6,6.7-.7,13.5-.6,20.2,0,1.7.2,3.3.4,5,.7,1.2.2,2.4.4,3.6.6,1.3.3,1.7,0,2-1.3,1-3.9,2-7.8,3.2-11.7,1.3-4,2.8-8,4.2-12h-.4l-.3.1h0Z"/>
                    <path d="M984.7,131c-1.2-.2-2.4-.3-3.6-.5-2.8-.4-5.5-.9-8.3-1-5.9-.2-11.9,0-17.9.3-1.3,0-2.5.2-3.8.4-1.7.3-3.3.6-5.1.9.2,1.4.3,2.5.5,3.9,1.5-.3,3.1-.5,4.6-.8,10-1.5,20-1.4,30,0,1.3.2,2.7.4,4,.7.2-1.1.3-1.9.4-2.7,0-.7,0-1.2-.9-1.3l.1.1Z"/>
                    <path d="M966,94.5c1.5,0,2.8-1.3,2.8-2.8s-1.3-2.8-2.9-2.8-2.9,1.3-2.9,2.8c0,1.6,1.3,2.8,2.9,2.8h.1Z"/>
                    <path d="M998.5,94.1c-1.7,0-2.8,1.2-2.8,2.8s1.3,2.8,2.9,2.8,2.8-1.3,2.7-3c0-1.6-1.2-2.7-2.8-2.7v.1h0Z"/>
                    <path d="M933.6,99.8c1.6,0,2.8-1.2,2.9-2.8,0-1.7-1.1-2.8-2.8-2.8s-2.8,1.1-2.8,2.8c0,1.6,1.2,2.8,2.8,2.8h-.1Z"/>
                    <path d="M1206.9,84.2c-10.2,0-18.4,8.2-18.4,18.4s8.2,18.4,18.4,18.4,18.4-8.2,18.4-18.4-8.2-18.4-18.4-18.4ZM1206.9,118.2c-8.6,0-15.6-7-15.6-15.6s7-15.6,15.6-15.6,15.6,7,15.6,15.6-7,15.6-15.6,15.6Z"/>
                    <path d="M1214,99.8c0-1.5-.6-2.9-1.7-4s-2.5-1.7-4-1.7h-5.7c-.8,0-1.4.6-1.4,1.4v14.2c0,.8.6,1.4,1.4,1.4s1.4-.6,1.4-1.4v-4.2h3.8l3.5,5.1c.4.6,1.3.8,2,.4.6-.4.8-1.3.4-2l-2.8-4.1c.5-.3,1-.6,1.4-1,1.1-1.1,1.7-2.5,1.7-4v-.1h0ZM1210.4,101.8c-.5.5-1.1.7-1.7.8h-4.4v-5.7h4.2c.8,0,1.5.3,2,.8s.8,1.3.8,2-.3,1.5-.8,2c0,0-.1.1-.1.1Z"/>
                  </g>
                </svg>

                <div class="space-y-1 text-xs text-slate-500 font-medium leading-relaxed">
                    <p class="font-bold text-slate-900 text-sm">Tour Raja Private Limited</p>
                    <p>H-15, Sector 63, Noida, Uttar Pradesh 201301</p>
                    <p>Email: finance@tour raja.com | Ph: +91 120 4455 6677</p>
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

/* Single page PDF generation overrides */
.generating-pdf {
    padding: 0.3in !important;
    box-shadow: none !important;
    border: none !important;
    border-radius: 0px !important;
}
.generating-pdf .pb-10 {
    padding-bottom: 1rem !important;
}
.generating-pdf .py-10 {
    padding-top: 1rem !important;
    padding-bottom: 1rem !important;
}
.generating-pdf .my-8 {
    margin-top: 0.75rem !important;
    margin-bottom: 0.75rem !important;
}
.generating-pdf .gap-12 {
    gap: 1rem !important;
}
.generating-pdf .pt-6 {
    padding-top: 0.75rem !important;
}
.generating-pdf .py-4 {
    padding-top: 0.35rem !important;
    padding-bottom: 0.35rem !important;
}
.generating-pdf .pt-8 {
    padding-top: 1rem !important;
}
.generating-pdf .space-y-6 > :not([hidden]) ~ :not([hidden]) {
    --tw-space-y-reverse: 0 !important;
    margin-top: calc(1rem * calc(1 - var(--tw-space-y-reverse))) !important;
    margin-bottom: calc(1rem * var(--tw-space-y-reverse)) !important;
}
.generating-pdf .space-y-4 > :not([hidden]) ~ :not([hidden]) {
    --tw-space-y-reverse: 0 !important;
    margin-top: calc(0.75rem * calc(1 - var(--tw-space-y-reverse))) !important;
    margin-bottom: calc(0.75rem * var(--tw-space-y-reverse)) !important;
}
.generating-pdf .space-y-3 > :not([hidden]) ~ :not([hidden]) {
    --tw-space-y-reverse: 0 !important;
    margin-top: calc(0.5rem * calc(1 - var(--tw-space-y-reverse))) !important;
    margin-bottom: calc(0.5rem * var(--tw-space-y-reverse)) !important;
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    function downloadPDF() {
        const element = document.getElementById('invoice-sheet');
        
        // Save original styles
        const originalBoxShadow = element.style.boxShadow;
        const originalBorderRadius = element.style.borderRadius;
        const originalBorder = element.style.border;
        
        // Apply single-page print formatting class
        element.classList.add('generating-pdf');
        
        const opt = {
            margin:       0.15,
            filename:     '{{ $invoiceData["invoice_no"] }}.pdf',
            image:        { type: 'jpeg', quality: 1.0 },
            html2canvas:  { scale: 2, useCORS: true, logging: false },
            jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' }
        };
        
        html2pdf().set(opt).from(element).save().then(() => {
            // Restore original styles
            element.classList.remove('generating-pdf');
            element.style.boxShadow = originalBoxShadow;
            element.style.borderRadius = originalBorderRadius;
            element.style.border = originalBorder;
        });
    }
</script>
@endsection
