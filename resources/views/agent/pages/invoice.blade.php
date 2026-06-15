@extends('agent.layouts.app')

@section('title', 'Invoice - Tour Raja Agent')

@section('content')


        <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100">
            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-widest mb-8">Billing Information</h3>

            <div class="space-y-6">
                <?php for($i=0; $i<3; $i++): ?>
                <div class="bg-gray-50/50 rounded-[32px] border border-gray-100 overflow-hidden group hover:border-primary/20 transition-all">
                    <!-- Progress Strip at Top -->
                    <div class="h-1.5 w-full bg-gray-100">
                        <div class="h-full bg-primary" style="width: <?php echo ($i+1) * 30; ?>%"></div>
                    </div>
                    
                    <div class="p-8 flex items-center justify-between">
                        <div class="space-y-4">
                            <h4 class="text-sm font-bold text-gray-600 uppercase">SERVICE GUARANTEED (INV-2026/27/000016)</h4>
                            <div class="space-y-1">
                                <p class="text-[10px] text-gray-400 font-medium">No of package listings : 0</p>
                                <p class="text-[10px] text-gray-400 font-medium">Transaction Id : </p>
                                <p class="text-[10px] text-gray-400 font-medium">Start Date : 05 Apr 2026</p>
                                <p class="text-[10px] text-gray-400 font-medium">Expiry Date : 10 Feb 2027</p>
                            </div>
                        </div>

                        <div class="text-right flex flex-col items-end">
                            <p class="text-3xl font-bold text-gray-400 mb-6 flex items-center">
                                <i class="fas fa-rupee-sign text-lg mr-2"></i> 0.00
                            </p>
                            <button class="bg-primary text-white px-8 py-3 rounded-2xl text-xs font-bold shadow-lg shadow-orange-100 hover:scale-105 transition-all">
                                View Invoice
                            </button>
                        </div>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Footer -->
@endsection
