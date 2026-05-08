<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $data = [
            'metrics' => [
                'totalRevenue' => '₹128,430',
                'revenueGrowth' => '+12.5%',
                'activeAgents' => '1,240',
                'agentGrowth' => '+8.2%',
                'activePackages' => '452',
                'packageGrowth' => '+15.3%',
                'totalSubscribers' => '8,920',
                'subscriberGrowth' => '+5.4%',
            ],
            'recentActivities' => [
                ['user' => 'Rahul Sharma', 'action' => 'New agent registration', 'status' => 'pending', 'time' => '2 MINS AGO'],
                ['user' => 'Global Travels', 'action' => 'Package approved: Bali Getaway', 'status' => 'completed', 'time' => '15 MINS AGO'],
                ['user' => 'Anita Desai', 'action' => 'Subscription upgraded to Premium', 'status' => 'completed', 'time' => '1 HOUR AGO'],
            ]
        ];

        return view('admin.dashboard', compact('data'));
    }

    // Inventory & Stays
    public function packages() { return view('admin.packages'); }
    public function hotels() { return view('admin.hotels'); }
    public function amenities() { return view('admin.amenities'); }
    public function holidayTypes() { return view('admin.holiday-types'); }
    public function activities() { return view('admin.activities'); }

    // Admin Central
    public function users() { return view('admin.users'); }
    public function agents() { return view('admin.agents'); }
    public function leads() { return view('admin.leads'); }

    // Subscription Oversight
    public function paidUsers() { return view('admin.paid-users'); }
    public function userPlans()
    {
        return view('admin.user-plans');
    }

    public function payments()
    {
        return view('admin.payments');
    }

    public function ads()
    {
        return view('admin.ads');
    }

    public function plans()
    {
        return view('admin.plans');
    }

    // Platform Settings
    public function homeEditor()
    {
        return view('admin.banners');
    }

    public function notifications()
    {
        return view('admin.notifications');
    }

    public function cms()
    {
        return view('admin.cms');
    }
    public function contact()
    {
        return view('admin.contact-us');
    }
    public function subscribers()
    {
        return view('admin.subscribers');
    }
    public function settings()
    {
        return view('admin.settings');
    }
}
