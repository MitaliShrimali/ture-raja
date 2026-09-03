@extends('layouts.admin')

@section('admin_title', 'Invoice Overview')

@section('content')

<style>
/* Ensure all buttons show cursor pointer (finger cursor) */
button, a.btn, [role="button"] {
    cursor: pointer !important;
}

@media print {
    /* Hide everything on the page by default when printing */
    body * {
        visibility: hidden !important;
    }

    /* Only show #invoice-sheet and its contents */
    #invoice-sheet, #invoice-sheet * {
        visibility: visible !important;
    }

    /* Position #invoice-sheet cleanly at top-left of printed page */
    #invoice-sheet {
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
        box-shadow: none !important;
        background: #ffffff !important;
    }

    /* Hide any element with class no-print */
    .no-print {
        display: none !important;
    }

    /* Force background colors to print */
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    @page {
        size: A4 portrait;
        margin: 0.5cm;
    }
}
</style>

<div class="space-y-6 pb-12" x-data="{ editMode: false }">
    
    <!-- Top Action bar (Hidden on Print) -->
    <div class="max-w-4xl mx-auto flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-gray-200 shadow-sm no-print">
        <div class="flex items-center gap-3">
            <a href="{{ url('/admin/payments') }}" class="p-2.5 bg-gray-100 hover:bg-gray-200 rounded-xl text-gray-600 transition-all cursor-pointer">
                <i data-lucide="arrow-left" size="18"></i>
            </a>
            <div>
                <div class="text-base font-bold text-gray-900">Invoice Overview</div>
                <p class="text-xs text-gray-500 font-medium">Invoice No: {{ $invoiceData['invoice_no'] }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <button @click="editMode = !editMode" class="cursor-pointer flex-1 sm:flex-none justify-center bg-gray-100 hover:bg-gray-200 text-gray-800 px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all flex items-center gap-2">
                <i data-lucide="edit-3" size="15"></i> 
                <span x-text="editMode ? 'View Invoice' : 'Edit Details'">Edit Details</span>
            </button>
            <button id="pdf-dl-btn" onclick="downloadPDF(event)" style="background-color: #2e7d32 !important;" class="cursor-pointer flex-1 sm:flex-none justify-center hover:opacity-90 text-white px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all shadow-md flex items-center gap-2">
                <i data-lucide="download" size="15"></i> Download PDF
            </button>
            <button onclick="printInvoice(event)" style="background-color: #d35400 !important;" class="cursor-pointer flex-1 sm:flex-none justify-center hover:opacity-90 text-white px-5 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all shadow-md flex items-center gap-2">
                <i data-lucide="printer" size="15"></i> Print Invoice
            </button>
        </div>
    </div>

    <!-- Edit Form View (Shown when editMode is true) -->
    <div x-show="editMode" class="max-w-4xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-lg p-8 space-y-6 no-print" style="display: none;">
        <div class="border-b border-gray-200 pb-3 flex justify-between items-center">
            <div>
                <div class="text-lg font-bold text-gray-900">Edit Invoice Details</div>
                <p class="text-xs text-gray-500 font-medium">Modify values to customize and update the agent's invoice.</p>
            </div>
            <span class="text-xs font-semibold text-orange-600 bg-orange-50 px-3 py-1 rounded-full border border-orange-200">Will update in Agent view</span>
        </div>
        
        <form action="{{ url('/admin/payments/invoice/update') }}" method="POST" class="space-y-5">
            @csrf
            <input type="hidden" name="id" value="{{ $payment->id }}" />
            
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Invoice Number</label>
                    <input required type="text" name="invoice_no" value="{{ $invoiceData['invoice_no'] }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 px-4 outline-none focus:ring-2 focus:ring-orange-500/20 font-medium text-sm text-gray-900" />
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Dated (Issue Date)</label>
                    <input required type="text" name="invoice_date" value="{{ $invoiceData['invoice_date'] }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 px-4 outline-none focus:ring-2 focus:ring-orange-500/20 font-medium text-sm text-gray-900" />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Agent / Company Name</label>
                    <input required type="text" name="customer_name" value="{{ $invoiceData['customer_name'] }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 px-4 outline-none focus:ring-2 focus:ring-orange-500/20 font-medium text-sm text-gray-900" />
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">GSTIN / UIN</label>
                    <input type="text" name="customer_gstin" value="{{ $invoiceData['customer_gstin'] ?? '-' }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 px-4 outline-none focus:ring-2 focus:ring-orange-500/20 font-medium text-sm text-gray-900" />
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Address</label>
                <textarea required name="customer_address" rows="2" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 px-4 outline-none focus:ring-2 focus:ring-orange-500/20 font-medium text-sm text-gray-900 resize-none">{{ $invoiceData['customer_address'] }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Party Mobile No.</label>
                    <input type="text" required name="customer_phone" value="{{ $invoiceData['customer_phone'] }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 px-4 outline-none focus:ring-2 focus:ring-orange-500/20 font-medium text-sm text-gray-900" />
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Place of Supply</label>
                    <input required type="text" name="place_of_supply" value="{{ $invoiceData['place_of_supply'] }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 px-4 outline-none focus:ring-2 focus:ring-orange-500/20 font-medium text-sm text-gray-900" />
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Reverse Charge</label>
                    <input type="text" name="reverse_charge" value="{{ $invoiceData['reverse_charge'] ?? 'NA' }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 px-4 outline-none focus:ring-2 focus:ring-orange-500/20 font-medium text-sm text-gray-900" />
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Sale Type</label>
                    <input type="text" name="sale_type" value="{{ $invoiceData['sale_type'] ?? 'DEBIT MEMO' }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 px-4 outline-none focus:ring-2 focus:ring-orange-500/20 font-medium text-sm text-gray-900" />
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Tax Rate (%)</label>
                    <input type="number" step="0.01" name="tax_rate" value="{{ $invoiceData['tax_rate'] ?? 0 }}" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 px-4 outline-none focus:ring-2 focus:ring-orange-500/20 font-medium text-sm text-gray-900" />
                </div>
            </div>

            <!-- Services/Items Loop -->
            <div class="space-y-3 pt-3 border-t border-gray-200">
                <div class="text-xs font-bold text-orange-600 uppercase tracking-wider">Service & Line Items</div>
                @foreach($invoiceData['services'] as $idx => $svc)
                    <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 space-y-3">
                        <div class="grid grid-cols-12 gap-3">
                            <div class="col-span-5 space-y-1">
                                <label class="text-[10px] font-bold text-gray-500 uppercase">Plan / Item Description</label>
                                <input required type="text" name="service_name[]" value="{{ $svc['name'] }}" class="w-full bg-white border border-gray-200 rounded-lg py-2 px-3 text-xs font-semibold text-gray-900" />
                            </div>
                            <div class="col-span-2 space-y-1">
                                <label class="text-[10px] font-bold text-gray-500 uppercase">SAC/HSN</label>
                                <input type="text" name="service_sac[]" value="{{ $svc['sac_hsn'] ?? '-' }}" class="w-full bg-white border border-gray-200 rounded-lg py-2 px-3 text-xs font-medium text-gray-900 text-center" />
                            </div>
                            <div class="col-span-2 space-y-1">
                                <label class="text-[10px] font-bold text-gray-500 uppercase">Package Listings</label>
                                <input type="number" name="service_listings[]" value="{{ $svc['package_listings'] ?? 0 }}" class="w-full bg-white border border-gray-200 rounded-lg py-2 px-3 text-xs font-medium text-gray-900 text-center" />
                            </div>
                            <div class="col-span-1 space-y-1">
                                <label class="text-[10px] font-bold text-gray-500 uppercase">Qty</label>
                                <input required type="number" name="service_qty[]" value="{{ $svc['qty'] }}" class="w-full bg-white border border-gray-200 rounded-lg py-2 px-2 text-xs font-medium text-gray-900 text-center" />
                            </div>
                            <div class="col-span-2 space-y-1">
                                <label class="text-[10px] font-bold text-gray-500 uppercase">Price (₹)</label>
                                <input required type="number" step="0.01" name="service_price[]" value="{{ $svc['price'] }}" class="w-full bg-white border border-gray-200 rounded-lg py-2 px-3 text-xs font-medium text-gray-900 text-right" />
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="space-y-1">
                <label class="text-xs font-bold text-gray-700 uppercase tracking-wider">Terms & Conditions</label>
                <textarea name="notes" rows="2" class="w-full bg-gray-50 border border-gray-200 rounded-xl py-2.5 px-4 outline-none focus:ring-2 focus:ring-orange-500/20 font-medium text-sm text-gray-900 resize-none">{{ $invoiceData['notes'] ?? 'We declare that this invoice shows the actual price of the services described and that all particulars are true and correct.' }}</textarea>
            </div>
            
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-gray-200">
                <button type="button" @click="editMode = false" class="cursor-pointer px-5 py-2.5 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-bold text-gray-700 uppercase tracking-wider transition-all">Cancel</button>
                <button type="submit" style="background-color: #d35400 !important;" class="cursor-pointer px-6 py-2.5 text-white rounded-xl text-xs font-bold uppercase tracking-wider shadow-md transition-all hover:opacity-90">Save & Update Invoice</button>
            </div>
        </form>
    </div>

    <!-- Perfectly Balanced Single Page Printable Reference Invoice UI Area -->
    <div x-show="!editMode" class="bg-white rounded-2xl border border-gray-200 shadow-md p-8 max-w-4xl mx-auto relative overflow-hidden font-sans" id="invoice-sheet" style="box-sizing: border-box;">

        <!-- Header Section -->
        <div class="flex justify-between items-start mb-5 pt-1">
            <!-- Left Logo & Metadata -->
            <div>
                <!-- TourRaja Official SVG Logo -->
                <svg style="height: 36px; width: auto;" height="36" viewBox="0 0 1263.1 414.1" xmlns="http://www.w3.org/2000/svg">
                    <g fill="#f15922">
                        <path d="M123.6,268.6c-2.5-2.8-5.6-4.2-9.2-4.2h-10.3c-8,0-14.6-3.1-19.8-9.3-5.3-6.2-7.9-14.1-7.9-23.5v-81.6h30.3c4.1,0,7.4-1.2,10-3.7s3.8-5.4,3.8-8.9-1.3-7.3-3.8-9.7c-2.6-2.4-5.9-3.6-10-3.6h-30.3v-47.7c0-4.3-1.4-7.8-4.2-10.6-2.8-2.8-6.5-4.2-11-4.2s-7.8,1.4-10.6,4.2c-2.8,2.8-4.2,6.3-4.2,10.6v47.7h-16.8c-4.1,0-7.4,1.2-10,3.6s-3.9,5.6-3.9,9.7,1.3,6.4,3.9,8.9,5.9,3.7,10,3.7h16.8v81.6c0,12,2.5,22.7,7.6,32.1,5.1,9.3,11.9,16.8,20.7,22.2,8.7,5.5,18.5,8.2,29.5,8.2h6.1c4.9,0,9-1.4,12.2-4.2s4.8-6.4,4.8-10.6-1.2-7.8-3.7-10.6v-.1h0Z"/>
                        <path d="M468.9,117.4c-4.3,0-7.9,1.4-10.6,4.2-2.8,2.8-4.2,6.4-4.2,11v92.5c0,8.2-2.3,15.5-6.8,21.9s-10.6,11.6-18.2,15.5-16.3,5.8-26,5.8-19.2-2-27.2-6c-8.1-4-14.5-10-19.2-18.1-4.7-8.1-7.1-18.3-7.1-30.8v-80.9c0-4.3-1.5-7.9-4.4-10.8s-6.5-4.4-10.8-4.4-7.9,1.4-10.8,4.4c-2.9,2.9-4.4,6.5-4.4,10.8v80.9c0,17.6,3.4,32.6,10.3,44.8,6.9,12.3,16.2,21.5,28.1,27.9,11.8,6.3,25.3,9.5,40.3,9.5s26.9-3.1,38.2-9.2c6.8-3.7,12.8-8.3,17.9-13.6v6.1c0,4.5,1.4,8.2,4.2,11,2.8,2.8,6.3,4.2,10.6,4.2s8.2-1.4,11-4.2,4.2-6.4,4.2-11v-146.4c0-4.5-1.4-8.2-4.2-11-2.8-2.8-6.4-4.2-11-4.2l.1.1h0Z"/>
                        <path d="M601.7,121.1c-6-3.8-14.9-5.6-26.8-5.6s-24.3,3-35,9c-2.1,1.2-4,2.5-6,3.8-.5-3-1.6-5.4-3.4-7.2-2.5-2.5-6.2-3.7-11.1-3.7s-8.4,1.3-11,3.9-3.9,6.2-3.9,11v146.7c0,4.9,1.3,8.7,3.9,11.1,2.6,2.5,6.2,3.7,11,3.7s8.6-1.3,11.1-3.9,3.7-6.2,3.7-11v-94.8c0-10.1,3-18.5,9-25.1,6-6.7,14.3-11.3,24.7-14.2,7.5-2.1,7.7-2,21.8-1,3.1.2,5.8.9,8.4.6,2.6-.2,4.8-1.1,6.6-2.6s3.2-3.8,4-6.8c1.3-5.6-1.1-10.3-7.1-14l.1.1h0Z"/>
                    </g>
                    <path fill="#e44100" d="M285.5,146.3c-4.4-4.4-7.2-9.7-7.2-9.7-8.6-9.8-18.7-16.8-31-21.9-10-4.1-21.3-6.4-32.8-6.4s-22.8,2.3-32.8,6.4c-12.3,5.1-22.4,12.1-31,21.9,0,0-2.8,5.3-7.2,9.7,2.9-5.1,4.6-9.9,8.5-14.3h0c8.4-9.6,18.9-17.2,30.8-22.1,9.8-4,20.4-6.3,31.7-6.3s21.9,2.2,31.7,6.3c11.9,4.9,22.5,12.5,30.8,22.1h0c3.9,4.4,5.6,9.2,8.5,14.3"/>
                    <path fill="#f15922" d="M297.8,204.8c0,45.9-37,90.2-82.9,90.2s-83.5-44.5-83.5-90.5c0-2,0-4,.3-6,3,43.2,48.5,61.4,82.9,62.6,25.7.9,79.9-19.2,83-62.4.1,2,.2,4.1.2,6.2"/>
                    <g fill="#1a1a1a" fill-rule="evenodd">
                        <path d="M143.6,174.6c0,5.6-4.5,10.1-10.1,10.1s-10.1-4.5-10.1-10.1,4.5-10.1,10.1-10.1,4.5,10.1,10.1"/>
                        <path d="M307.5,174.6c0,5.6-4.5,10.1-10.1,10.1s-10.1-4.5-10.1-10.1,4.5-10.1,10.1-10.1,4.5,10.1,10.1"/>
                        <path d="M746.7,135.5c1.3-5.6-1.1-10.3-7.1-14-6-3.8-14.9-5.6-26.8-5.6s-24.3,3-35,9c-7.6,4.3-14.1,9.7-19.5,16.2v-8.5c0-4.9-1.2-8.7-3.7-11.1-2.5-2.5-6.2-3.7-11.1-3.7s-8.4,1.3-11,3.9-3.9,6.2-3.9,11v146.7c0,4.9,1.3,8.7,3.9,11.1,2.6,2.5,6.2,3.7,11,3.7s8.7-1.3,11.1-3.9c2.5-2.6,3.7-6.2,3.7-11v-94.8c0-10.1,3-18.5,9-25.1,6-6.7,14.2-11.4,24.7-14.2,10.4-2.8,22.3-3.1,35.6-1,3,.6,5.8.9,8.4.6,2.6-.2,4.8-1.1,6.6-2.6s3.2-3.8,4-6.8l.1.1Z"/>
                        <path d="M893.2,290c2.9,2.9,6.5,4.4,10.8,4.4s8.2-1.5,11-4.4,4.2-6.5,4.2-10.8v-73.2c0-17-3.9-32.2-11.8-45.8-7.8-13.5-18.4-24.2-31.8-32.1-13.3-7.8-28.5-11.8-45.5-11.8s-31.9,3.9-45.3,11.8c-13.4,7.8-24.1,18.5-31.9,31.9-7.8,13.4-11.8,28.8-11.8,45.9s3.7,32.2,11,45.8c7.3,13.5,17.3,24.2,30,31.9s27,11.6,42.9,11.6,30-3.8,42.4-11.4c8.3-5.1,15.5-11.5,21.4-19.1v14.5c0,4.3,1.5,7.9,4.4,10.8h0ZM860.9,260.4c-8.9,5.5-19.2,8.2-30.8,8.2s-21.3-2.7-30.5-8.2c-9.1-5.5-16.3-12.9-21.6-22.4s-7.9-20.1-7.9-31.9,2.6-22.8,7.9-32.2c5.3-9.5,12.5-16.9,21.6-22.4s19.3-8.2,30.5-8.2,21.9,2.7,30.8,8.2c8.9,5.5,16,13,21.3,22.4,5.3,9.5,7.9,20.2,7.9,32.2s-2.6,22.5-7.9,31.9c-5.3,9.5-12.4,16.9-21.3,22.4h0Z"/>
                        <path d="M1179.7,160.3c-7.8-13.5-18.4-24.2-31.8-32.1-13.3-7.8-28.5-11.8-45.5-11.8s-31.9,3.9-45.3,11.8c-13.4,7.8-24.1,18.5-31.9,31.9s-11.8,28.8-11.8,45.9,3.7,32.2,11,45.8c7.3,13.5,17.3,24.2,30,31.9s27,11.6,42.9,11.6,30-3.8,42.4-11.4c8.3-5.1,15.5-11.5,21.4-19.1v14.5c0,4.3,1.5,7.9,4.4,10.8s6.5,4.4,10.8,4.4,8.2-1.5,11-4.4,4.2-6.5,4.2-10.8v-73.2c0-17-3.9-32.2-11.8-45.8h0ZM1133.3,260.4c-8.9,5.5-19.2,8.2-30.8,8.2s-21.3-2.7-30.5-8.2c-9.1-5.5-16.3-12.9-21.6-22.4-5.3-9.5-7.9-20.1-7.9-31.9s2.6-22.8,7.9-32.2c5.3-9.5,12.5-16.9,21.6-22.4s19.3-8.2,30.5-8.2,21.9,2.7,30.8,8.2,16,13,21.3,22.4,5.3,9.5,7.9,20.2,7.9,32.2s-2.6,22.5-7.9,31.9c-5.3,9.5-12.4,16.9-21.3,22.4h0Z"/>
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

                <!-- Sub-header metadata line -->
                <div class="flex items-center gap-4 text-xs font-semibold text-gray-700 mt-3" style="font-size: 12px !important; font-weight: 600 !important; color: #374151 !important;">
                    <span><i class="fas fa-id-card text-gray-500"></i> PAN No.: AAKCT0397C</span>
                    <span><i class="fas fa-phone text-gray-500"></i> 8238214646</span>
                    <span><i class="fas fa-envelope text-gray-500"></i> info@tourraja.com</span>
                    <span><i class="fas fa-globe text-gray-500"></i> www.tour-raja.com</span>
                </div>
            </div>

            <!-- Right INVOICE Heading & Badge -->
            <div class="text-right flex flex-col items-end gap-2">
                <div class="bg-[#f15922] text-white text-[10px] font-extrabold px-3 py-1 rounded tracking-widest uppercase shadow-sm" style="font-size: 10px !important; font-weight: 800 !important; background-color: #f15922 !important; color: #ffffff !important;">
                    ORIGINAL COPY
                </div>
                <div style="font-size: 30px !important; font-weight: 800 !important; color: #0f2540 !important; line-height: 1 !important; letter-spacing: 0.05em !important;" class="uppercase">INVOICE</div>
            </div>
        </div>

        <!-- Box 1: Party Details & Invoice Metadata -->
        <div class="border border-gray-300 rounded-xl p-5 mb-5 bg-white">
            <div class="grid grid-cols-2 gap-6 relative">
                <!-- Vertical Divider -->
                <div class="absolute inset-y-0 left-1/2 w-px bg-gray-200"></div>

                <!-- Left: Party Details -->
                <div class="pr-4 space-y-2">
                    <div style="font-size: 12px !important; font-weight: 700 !important; color: #d35400 !important; letter-spacing: 0.025em !important;" class="uppercase">PARTY DETAILS:</div>
                    <div style="font-size: 16px !important; font-weight: 700 !important; color: #111827 !important; line-height: 1.25 !important;">{{ $invoiceData['customer_name'] }}</div>
                    <div style="font-size: 12px !important; color: #374151 !important; line-height: 1.5 !important;" class="whitespace-pre-line">{{ $invoiceData['customer_address'] }}</div>
                    
                    <div style="font-size: 12px !important; color: #374151 !important;" class="pt-1 space-y-1">
                        <div class="grid grid-cols-12">
                            <span class="col-span-5 text-gray-600 font-medium" style="font-size: 12px !important;">Party Mobile No.</span>
                            <span class="col-span-1" style="font-size: 12px !important;">:</span>
                            <span class="col-span-6 font-semibold" style="font-size: 12px !important;">{{ $invoiceData['customer_phone'] ?? '-' }}</span>
                        </div>
                        <div class="grid grid-cols-12">
                            <span class="col-span-5 text-gray-600 font-medium" style="font-size: 12px !important;">GSTIN/UIN</span>
                            <span class="col-span-1" style="font-size: 12px !important;">:</span>
                            <span class="col-span-6 font-semibold" style="font-size: 12px !important;">{{ $invoiceData['customer_gstin'] ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Invoice Metadata -->
                <div class="pl-6 space-y-2 text-xs text-gray-700" style="font-size: 12px !important;">
                    <div class="grid grid-cols-12 items-center">
                        <span class="col-span-5 font-semibold text-gray-700" style="font-size: 12px !important;">Invoice No.</span>
                        <span class="col-span-1" style="font-size: 12px !important;">:</span>
                        <span class="col-span-6 font-bold text-[#d35400] text-sm" style="font-size: 14px !important; color: #d35400 !important; font-weight: 700 !important;">{{ $invoiceData['invoice_no'] }}</span>
                    </div>
                    <div class="grid grid-cols-12 items-center">
                        <span class="col-span-5 font-semibold text-gray-700" style="font-size: 12px !important;">Dated</span>
                        <span class="col-span-1" style="font-size: 12px !important;">:</span>
                        <span class="col-span-6 font-bold text-[#d35400]" style="font-size: 12px !important; color: #d35400 !important; font-weight: 700 !important;">{{ $invoiceData['invoice_date'] }}</span>
                    </div>
                    <div class="grid grid-cols-12 items-center">
                        <span class="col-span-5 font-semibold text-gray-700" style="font-size: 12px !important;">Place of Supply</span>
                        <span class="col-span-1" style="font-size: 12px !important;">:</span>
                        <span class="col-span-6 font-bold text-[#d35400]" style="font-size: 12px !important; color: #d35400 !important; font-weight: 700 !important;">{{ $invoiceData['place_of_supply'] }}</span>
                    </div>
                    <div class="grid grid-cols-12 items-center">
                        <span class="col-span-5 font-semibold text-gray-700" style="font-size: 12px !important;">Reverse Charge</span>
                        <span class="col-span-1" style="font-size: 12px !important;">:</span>
                        <span class="col-span-6 font-bold text-[#d35400]" style="font-size: 12px !important; color: #d35400 !important; font-weight: 700 !important;">{{ $invoiceData['reverse_charge'] ?? 'NA' }}</span>
                    </div>
                    <div class="grid grid-cols-12 items-center">
                        <span class="col-span-5 font-semibold text-gray-700" style="font-size: 12px !important;">Sale Type</span>
                        <span class="col-span-1" style="font-size: 12px !important;">:</span>
                        <span class="col-span-6 font-bold text-[#d35400]" style="font-size: 12px !important; color: #d35400 !important; font-weight: 700 !important;">{{ $invoiceData['sale_type'] ?? 'DEBIT MEMO' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Items Table -->
        <div class="border border-gray-300 rounded-xl overflow-hidden mb-5">
            <table class="w-full text-xs text-left border-collapse" style="font-size: 12px !important;">
                <thead>
                    <tr class="bg-[#041e42] text-white" style="background-color: #041e42 !important; color: #ffffff !important;">
                        <th class="py-3 px-3 text-center font-bold w-12 border-r border-slate-700" style="font-size: 12px !important; padding: 12px 12px !important;">No.</th>
                        <th class="py-3 px-4 font-bold border-r border-slate-700" style="font-size: 12px !important; padding: 12px 16px !important;">Plan</th>
                        <th class="py-3 px-3 text-center font-bold border-r border-slate-700" style="font-size: 12px !important; padding: 12px 12px !important;">SAC/HSN Code</th>
                        <th class="py-3 px-3 text-center font-bold border-r border-slate-700" style="font-size: 12px !important; padding: 12px 12px !important;">No. of package listing</th>
                        <th class="py-3 px-3 text-center font-bold border-r border-slate-700" style="font-size: 12px !important; padding: 12px 12px !important;">Quantity</th>
                        <th class="py-3 px-4 text-right font-bold border-r border-slate-700" style="font-size: 12px !important; padding: 12px 16px !important;">Price</th>
                        <th class="py-3 px-4 text-right font-bold" style="font-size: 12px !important; padding: 12px 16px !important;">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($invoiceData['services'] as $idx => $svc)
                    <tr>
                        <td class="py-3.5 px-3 text-center font-medium text-gray-600 border-r border-gray-200" style="font-size: 12px !important;">{{ $idx + 1 }}</td>
                        <td class="py-3.5 px-4 font-semibold text-gray-900 border-r border-gray-200" style="font-size: 12px !important; font-weight: 600 !important; color: #111827 !important;">{{ $svc['name'] }}</td>
                        <td class="py-3.5 px-3 text-center text-gray-600 border-r border-gray-200" style="font-size: 12px !important;">{{ $svc['sac_hsn'] ?? '-' }}</td>
                        <td class="py-3.5 px-3 text-center font-medium text-gray-700 border-r border-gray-200" style="font-size: 12px !important;">{{ $svc['package_listings'] ?? 0 }}</td>
                        <td class="py-3.5 px-3 text-center font-medium text-gray-700 border-r border-gray-200" style="font-size: 12px !important;">x {{ $svc['qty'] }}</td>
                        <td class="py-3.5 px-4 text-right font-medium text-gray-800 border-r border-gray-200" style="font-size: 12px !important;">₹{{ number_format($svc['price'], 2) }}</td>
                        <td class="py-3.5 px-4 text-right font-bold text-gray-900" style="font-size: 12px !important; font-weight: 700 !important;">{{ number_format($svc['qty'] * $svc['price'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Tax & Grand Total Section -->
        <div class="grid grid-cols-12 gap-6 mb-5 items-start">
            <!-- Left: Tax Rate Breakdown -->
            <div class="col-span-7 space-y-2 text-xs text-gray-700 pt-1" style="font-size: 12px !important;">
                <div class="grid grid-cols-12">
                    <span class="col-span-4 font-semibold text-gray-600" style="font-size: 12px !important;">Tax Rate</span>
                    <span class="col-span-1" style="font-size: 12px !important;">:</span>
                    <span class="col-span-7 font-bold text-gray-800" style="font-size: 12px !important; font-weight: 700 !important;">{{ $invoiceData['tax_rate'] ?? 0 }}%</span>
                </div>
                <div class="grid grid-cols-12">
                    <span class="col-span-4 font-semibold text-gray-600" style="font-size: 12px !important;">Taxable Amt</span>
                    <span class="col-span-1" style="font-size: 12px !important;">:</span>
                    <span class="col-span-7 font-bold text-gray-800" style="font-size: 12px !important; font-weight: 700 !important;">₹ {{ number_format($invoiceData['taxable_amt'] ?? 0, 2) }}</span>
                </div>
                <div class="grid grid-cols-12">
                    <span class="col-span-4 font-semibold text-gray-600" style="font-size: 12px !important;">Total Tax</span>
                    <span class="col-span-1" style="font-size: 12px !important;">:</span>
                    <span class="col-span-7 font-bold text-gray-800" style="font-size: 12px !important; font-weight: 700 !important;">{{ number_format($invoiceData['total_tax'] ?? 0, 2) }}</span>
                </div>
            </div>

            <!-- Right: Grand Total Box & Amount in Words -->
            <div class="col-span-5 space-y-2.5">
                <div class="bg-[#fff5f2] border border-[#fed7aa] rounded-xl p-3.5 flex justify-between items-center" style="background-color: #fff5f2 !important; border-color: #fed7aa !important;">
                    <span class="text-xs font-bold text-gray-700 uppercase tracking-wider" style="font-size: 12px !important; font-weight: 700 !important; color: #374151 !important;">GRAND TOTAL</span>
                    <span class="text-xl font-extrabold text-[#d35400]" style="font-size: 20px !important; font-weight: 800 !important; color: #d35400 !important;">₹ {{ number_format($invoiceData['grand_total'] ?? 0, 2) }}</span>
                </div>
                <div>
                    <div style="font-size: 11px !important; font-weight: 700 !important; color: #4b5563 !important;">Rupees in Words:</div>
                    <div style="font-size: 12px !important; font-weight: 800 !important; color: #d35400 !important; line-height: 1.375 !important;">{{ $amountInWords }}</div>
                </div>
            </div>
        </div>

        <!-- Box 2: Bank Details Section -->
        <div class="border border-gray-300 rounded-xl p-5 mb-5 relative overflow-hidden bg-white">
            <div class="grid grid-cols-2 gap-6 relative z-10">
                <!-- Vertical Divider -->
                <div class="absolute inset-y-0 left-1/2 w-px bg-gray-200"></div>

                <!-- Left: Bank Details -->
                <div class="space-y-2 pr-4 text-xs text-gray-700" style="font-size: 12px !important;">
                    <div style="font-size: 12px !important; font-weight: 700 !important; color: #d35400 !important; letter-spacing: 0.025em !important;" class="flex items-center gap-2 uppercase">
                        <i class="fas fa-university" style="font-size: 14px !important;"></i> BANK DETAILS:
                    </div>
                    <div class="space-y-1.5 pt-1" style="font-size: 12px !important;">
                        <div class="grid grid-cols-12">
                            <span class="col-span-5 text-gray-600 font-medium" style="font-size: 12px !important;">Bank Name</span>
                            <span class="col-span-1" style="font-size: 12px !important;">:</span>
                            <span class="col-span-6 font-semibold" style="font-size: 12px !important;">HDFC Bank</span>
                        </div>
                        <div class="grid grid-cols-12">
                            <span class="col-span-5 text-gray-600 font-medium" style="font-size: 12px !important;">Account Name</span>
                            <span class="col-span-1" style="font-size: 12px !important;">:</span>
                            <span class="col-span-6 font-semibold" style="font-size: 12px !important;">TOUR RAJA PRIVATE LIMITED</span>
                        </div>
                        <div class="grid grid-cols-12">
                            <span class="col-span-5 text-gray-600 font-medium" style="font-size: 12px !important;">Account Number</span>
                            <span class="col-span-1" style="font-size: 12px !important;">:</span>
                            <span class="col-span-6 font-semibold" style="font-size: 12px !important;">50200081149750</span>
                        </div>
                        <div class="grid grid-cols-12">
                            <span class="col-span-5 text-gray-600 font-medium" style="font-size: 12px !important;">IFSC Code</span>
                            <span class="col-span-1" style="font-size: 12px !important;">:</span>
                            <span class="col-span-6 font-semibold" style="font-size: 12px !important;">HDFC0006552</span>
                        </div>
                        <div class="grid grid-cols-12">
                            <span class="col-span-5 text-gray-600 font-medium" style="font-size: 12px !important;">Branch</span>
                            <span class="col-span-1" style="font-size: 12px !important;">:</span>
                            <span class="col-span-6 font-semibold" style="font-size: 12px !important;">NANAVATI CHOWK RAJKOT</span>
                        </div>
                    </div>
                </div>

                <!-- Right: Bank Building Graphic Watermark -->
                <div class="flex items-center justify-center pl-6 opacity-30">
                    <svg width="110" height="85" viewBox="0 0 100 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M50 10L10 30H90L50 10Z" stroke="#94a3b8" stroke-width="2.5"/>
                        <rect x="18" y="34" width="8" height="30" rx="2" stroke="#94a3b8" stroke-width="2"/>
                        <rect x="38" y="34" width="8" height="30" rx="2" stroke="#94a3b8" stroke-width="2"/>
                        <rect x="58" y="34" width="8" height="30" rx="2" stroke="#94a3b8" stroke-width="2"/>
                        <rect x="74" y="34" width="8" height="30" rx="2" stroke="#94a3b8" stroke-width="2"/>
                        <line x1="10" y1="68" x2="90" y2="68" stroke="#94a3b8" stroke-width="3"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Box 3: Terms & Conditions and Authorized Signatory -->
        <div class="border border-gray-300 rounded-xl p-5 mb-6 bg-white">
            <div class="grid grid-cols-2 gap-6 relative">
                <!-- Vertical Line -->
                <div class="absolute inset-y-0 left-1/2 w-px bg-gray-200"></div>

                <!-- Left: Terms -->
                <div class="pr-4 space-y-1.5">
                    <div style="font-size: 12px !important; font-weight: 700 !important; color: #d35400 !important; letter-spacing: 0.025em !important;" class="uppercase">TERMS & CONDITIONS</div>
                    <div style="font-size: 12px !important; color: #4b5563 !important; line-height: 1.6 !important;" class="pt-1 whitespace-pre-line">
                        {{ $invoiceData['notes'] ?? 'We declare that this invoice shows the actual price of the services described and that all particulars are true and correct.' }}
                    </div>
                </div>

                <!-- Right: Authorized Signatory -->
                <div class="pl-6 space-y-1.5 text-left">
                    <div style="font-size: 12px !important; font-weight: 700 !important; color: #d35400 !important; letter-spacing: 0.025em !important;" class="uppercase">FOR TOUR RAJA PRIVATE LIMITED</div>
                    <div class="pt-1 pb-0.5">
                        <img src="{{ asset('assets/signature.png') }}" style="height: 52px; width: auto; mix-blend-mode: multiply; filter: contrast(125%);" alt="Authorized Signatory Signature">
                    </div>
                    <div class="border-b border-dotted border-gray-400 w-48 mb-1.5"></div>
                    <div style="font-size: 12px !important; font-weight: 700 !important; color: #111827 !important;">Mithilkumar M. Chandrani</div>
                    <div style="font-size: 12px !important; color: #4b5563 !important; font-weight: 500 !important;">Director</div>
                </div>
            </div>
        </div>

        <!-- Footer Accent -->
        <div class="flex items-center justify-center gap-4 text-xs font-semibold text-gray-600 pt-1" style="font-size: 12px !important;">
            <div class="h-px bg-orange-400 flex-1 max-w-[140px]"></div>
            <span>Thank you for your business!</span>
            <div class="h-px bg-orange-400 flex-1 max-w-[140px]"></div>
        </div>

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
    async function downloadPDF(e) {
        if (e && e.preventDefault) e.preventDefault();
        const btn = document.getElementById('pdf-dl-btn') || (e ? e.currentTarget : null);
        let origHTML = '';
        if (btn) {
            origHTML = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Downloading PDF...';
        }

        try {
            // Silently fetch clean standalone invoice template in background without leaving or reloading the page
            const res = await fetch("{{ url('/admin/payments/invoice/'.$payment->id.'/download') }}");
            const htmlText = await res.text();
            
            const iframe = document.createElement('iframe');
            iframe.style.position = 'fixed';
            iframe.style.top = '-9999px';
            iframe.style.left = '-9999px';
            iframe.style.width = '1024px';
            iframe.style.height = '1400px';
            document.body.appendChild(iframe);
            
            const doc = iframe.contentWindow.document;
            doc.open();
            doc.write(htmlText);
            doc.close();

            // Allow elements/fonts to evaluate inside offscreen iframe
            await new Promise(r => setTimeout(r, 400));

            const target = doc.getElementById('invoice-sheet');
            if (!target) throw new Error('Invoice sheet element not found in background template');

            const canvas = await html2canvas(target, {
                scale: 2,
                logging: false,
                useCORS: true,
                allowTaint: true,
                backgroundColor: '#ffffff'
            });

            document.body.removeChild(iframe);

            const imgData = canvas.toDataURL('image/jpeg', 0.98);
            const { jsPDF } = window.jspdf;
            const pdf = new jsPDF({
                orientation: 'portrait',
                unit: 'mm',
                format: 'a4'
            });

            const pdfWidth = pdf.internal.pageSize.getWidth();
            const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

            pdf.addImage(imgData, 'JPEG', 0, 0, pdfWidth, pdfHeight);
            
            // Direct file download to PC Downloads folder right from current page!
            pdf.save('{{ $invoiceData["invoice_no"] }}.pdf');

        } catch (err) {
            console.error('PDF generation error:', err);
        } finally {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = origHTML;
            }
        }
    }

    function printInvoice(e) {
        if (e && e.preventDefault) e.preventDefault();
        // Open clean printable invoice view matching agent print UI
        window.open("{{ url('/admin/payments/invoice/'.$payment->id.'/download') }}?autoprint=1", '_blank');
    }
</script>
@endsection
