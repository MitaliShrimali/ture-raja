@extends('agent.layouts.app')

@section('title', 'Services - Tour Raja Agent')

@section('content')


        <!-- Services Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            <?php 
            $services = [
                ['name' => 'Visa', 'icon' => 'fas fa-passport'],
                ['name' => 'Travel Insurance', 'icon' => 'fas fa-shield-alt'],
                ['name' => 'Flight Booking', 'icon' => 'fas fa-plane'],
                ['name' => 'International Tour', 'icon' => 'fas fa-globe'],
                ['name' => 'Domestic Tour', 'icon' => 'fas fa-map-marked-alt'],
                ['name' => 'Train Booking', 'icon' => 'fas fa-train'],
                ['name' => 'Passport', 'icon' => 'fas fa-id-card'],
                ['name' => 'Bus Booking', 'icon' => 'fas fa-bus'],
                ['name' => 'Hotel Booking', 'icon' => 'fas fa-hotel'],
                ['name' => 'Holidays Packages', 'icon' => 'fas fa-umbrella-beach'],
                ['name' => 'Cruise Packages', 'icon' => 'fas fa-ship'],
                ['name' => 'Ticket Reservation', 'icon' => 'fas fa-ticket-alt'],
                ['name' => 'Rental Car/Bikes', 'icon' => 'fas fa-car'],
                ['name' => 'Devotions Package', 'icon' => 'fas fa-pray'],
            ];
            foreach($services as $s): ?>
            <div class="bg-white p-6 rounded-[24px] shadow-sm border border-gray-100 flex items-center group hover:shadow-xl hover:shadow-gray-200/50 hover:scale-[1.02] transition-all cursor-pointer">
                <div class="w-12 h-12 bg-gray-50 text-gray-400 rounded-xl flex items-center justify-center mr-4 group-hover:bg-primary/10 group-hover:text-primary transition-colors">
                    <i class="<?php echo $s['icon']; ?> text-lg"></i>
                </div>
                <h4 class="text-xs font-bold text-gray-600 leading-tight group-hover:text-gray-800 transition-colors"><?php echo $s['name']; ?></h4>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Footer -->
@endsection
