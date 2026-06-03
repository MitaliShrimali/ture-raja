@extends('layouts.app')

@section('content')
<div class="bg-gray-50 pt-32 pb-12">
    <div class="container-custom">
        <div class="w-full">
            <h1 class="font-black text-primary mb-6" style="font-size: 30px; line-height: 1.2;">Terms And Conditions</h1>
            
            <div class="prose prose-lg max-w-none text-text-muted">
                <p>
                    All correspondence(s) in respect of Tours / Travel Service bookings should be addressed to M/s. TourMyIndia.com
                </p>

                <h3 class="text-lg font-bold text-foreground mt-8 mb-3">Payments:</h3>
                <p>
                    For all the services contracted, certain advance payment should be made to hold the booking, on confirmed basis & the balance amount can be paid either before your departure from your country or upon arrival in INDIA but definitely before the commencement of the services. Management personnels hold the right to decide upon the amount to be paid as advance payment, based on the nature of the service & the time left for the commencement of the service.
                </p>
                <p>
                    Apart from above in some cases like Special Train Journeys, hotels or resorts bookings during the peak season (X-Mas, New Year), full payment is required to be sent in advance.
                </p>

                <h3 class="text-lg font-bold text-foreground mt-8 mb-3">Mode of Payment:</h3>
                <p>
                    Overseas advance payment can be made through Wire Transfer to our bank.
                </p>

                <h3 class="text-lg font-bold text-foreground mt-8 mb-3">Cancellation Policy:</h3>
                <p>
                    In the event of cancellation of tour / travel services due to any avoidable / unavoidable reason/s we must be notified of the same in writing. Cancellation charges will be effective from the date we receive advice in writing, and cancellation charges would be as follows:
                </p>
                <ul class="list-disc pl-5 space-y-2 mb-4">
                    <li>45 days prior to arrival: 10% of the Tour / service cost</li>
                    <li>15 days prior to arrival: 25% of the Tour / service cost</li>
                    <li>07 days prior to arrival: 50% of the Tour / service cost</li>
                    <li>48 hours prior to arrival OR No Show: No Refund</li>
                </ul>
                <p>
                    Note: Written cancellation will accept on all working days, except Sunday, Any cancellation sent on Sunday's will be considered on next working day (Monday).
                </p>
                <p>
                    For the X-mas and new year period from 20 Dec to 05 Jan the payment is non-refundable.
                </p>
                <p>
                    In case you cancel the trip after commencement, refund would be restricted to a limited amount only which too would depend on the amount that we would be able to recover from the hoteliers/ contractors we patronize. For unused hotel accommodation, chartered transportation & missed meals etc. we do not bear any responsibility to refund.
                </p>

                <h3 class="text-lg font-bold text-foreground mt-8 mb-3">Wildlife Safaris cancellation:</h3>
                <p>
                    All the wildlife safaris booked into any of Indian Wildlife National Park/Sanctuaries are non refundable. Even date change request will be considered as cancellation and no payment will be refunded/ adjusted against it.
                </p>

                <h3 class="text-lg font-bold text-foreground mt-8 mb-3">Our Liabilities & Limitations:</h3>
                <p>
                    Please note that after the finalization of the Tour/ service Cost, if there are any Hike in entrance fees of monuments / museums, Taxes, fuel cost or guide charges – by Govt of India, the same would be charged as extra.
                </p><br>
                <p>
                    TourMyIndia.com act only in the capacity of agent for the hotels, airlines, transporters, railways & contractors providing other services & all exchange orders, receipts, contracts & tickets issued by us are issued subject to terms & conditions under which these services are provided by them.
                </p><br>
                <p>
                    All itineraries are sample itineraries, intended to give you a general idea of the likely trip schedule. Numerous factors such as weather, road conditions, the physical ability of the participants etc. may dictate itinerary changes either before the tour or while on the trail. We reserve the right to change any schedule in the interest of the trip participants' safety, comfort & general well being.
                </p><br>
                <p>
                    Our rates are based on the prevailing rates as negotiated by us with the hotels, airlines etc. Hotels and Airlines retain the right to modify the rates without notice. In case of such changes the rates quoted before the modification, can be changed by us according to the modifications by hotels or airlines. All hotel bookings are based on usual check in and check out time of the hotels until unless indicated in the itinerary.
                </p><br>
                <p>
                    We shall not be responsible for any delays & alterations in the programme or expenses incurred – directly or indirectly – due to natural hazards, flight cancellations, accident, breakdown of machinery or equipments, breakdown of transport, weather, sickness, landslides, political closures or any untoward incidents.
                </p><br>
                <p>
                    We shall not be responsible for any loss, injury or damage to person, property, or otherwise in connection with any accommodation, transportation or other services, resulting – directly or indirectly – from any act of GOD, dangers, fire, accident, breakdown in machinery or equipment, breakdown of transport, wars, civil disturbances, strikes, riots, thefts, pilferages, epidemics, medical or custom department regulations, defaults, or any other causes beyond our control.
                </p><br>
                <p>
                    We do not have any insurance policy covering the expenses for accident, sickness, loss due to theft, or any other reasons. Visitors are advised to seek such insurance arrangements in their home country. All baggages & personal property/s at all times are at the client's risk.
                </p><br>
                <p>
                    Please Note : We will not be responsible for any costs arising out of unforeseen circumstances like landslides, road blocks, bad weather, etc.
                </p><br>
            </div>
        </div>
    </div>

    <!-- Newsletter Section -->
    <div class="mt-12">
        <section class="pb-12 lg:pb-20 bg-gray-50">
            <div class="container-custom mx-auto px-6">
                <div class="bg-[#FFF9F0] rounded-[48px] overflow-hidden flex flex-col lg:flex-row">
                    <!-- Left: Content -->
                    <div class="flex-1 p-8 lg:p-20 flex flex-col justify-center space-y-8">
                        <div class="space-y-6 max-w-lg">
                            <span class="inline-block px-4 py-1.5 rounded-full bg-primary text-white text-[10px] font-black uppercase tracking-widest shadow-glow">
                                Join our newsletter
                            </span>
                            <h2 class="font-black text-foreground leading-[1.1] tracking-tight font-heading" style="font-size: 38px;">
                                Subscribe to see secret deals prices drop the moment you sign up!
                            </h2>
                            
                            @if(session('success') && str_contains(session('success'), 'subscrib'))
                                <div class="p-4 bg-green-50 border border-green-100 rounded-2xl text-green-700 font-bold text-sm flex items-center gap-3">
                                    <i data-lucide="check-circle" size="20"></i>
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="relative max-w-md mt-10">
                                @csrf
                                <input type="email" name="email" placeholder="Your Email" required class="w-full h-16 pl-8 pr-40 rounded-full bg-white border-none shadow-soft text-foreground font-bold focus:ring-2 focus:ring-primary/20 placeholder:text-text-muted/50">
                                <button type="submit" class="absolute right-2 top-2 h-12 px-8 rounded-full bg-black text-white font-black text-xs uppercase tracking-widest hover:bg-primary transition-all duration-300">
                                    Subscribe
                                </button>
                            </form>
                            <p class="text-text-muted text-xs font-bold opacity-60">No ads. No trails. No commitments</p>
                        </div>
                    </div>

                    <!-- Right: Image -->
                    <div class="flex-1 min-h-[300px] md:min-h-[400px] lg:min-h-[500px] relative">
                        <img src="https://images.unsplash.com/photo-1540541338287-41700207dee6?auto=format&fit=crop&q=80&w=1200" class="absolute inset-0 w-full h-full object-cover">
                        <!-- Glass Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t md:bg-gradient-to-r from-[#FFF9F0] to-transparent h-32 w-full md:h-full md:w-32"></div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
