<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function login()
    {
        return view('agent.auth.login');
    }

    public function dashboard()
    {
        return view('agent.pages.dashboard', [
            'page_title' => 'Dashboard',
            'page_breadcrumb' => 'Pages / Dashboard'
        ]);
    }

    public function about()
    {
        return view('agent.pages.about', [
            'page_title' => 'About Tour Raja',
            'page_breadcrumb' => 'Pages / About'
        ]);
    }

    public function addBranch()
    {
        return view('agent.pages.add-branch', [
            'page_title' => 'Add Branch',
            'page_breadcrumb' => 'Pages / Add Branch'
        ]);
    }

    public function addHotel()
    {
        return view('agent.pages.add-hotel', [
            'page_title' => 'Add Hotel',
            'page_breadcrumb' => 'Pages / Add Hotel'
        ]);
    }

    public function branch()
    {
        return view('agent.pages.branch', [
            'page_title' => 'Branches',
            'page_breadcrumb' => 'Pages / Branches'
        ]);
    }

    public function editImages()
    {
        return view('agent.pages.edit-images', [
            'page_title' => 'Edit Images',
            'page_breadcrumb' => 'Pages / Edit Images'
        ]);
    }

    public function editItinerary()
    {
        return view('agent.pages.edit-itinerary', [
            'page_title' => 'Edit Itinerary',
            'page_breadcrumb' => 'Pages / Edit Itinerary'
        ]);
    }

    public function editPackage()
    {
        return view('agent.pages.edit-package', [
            'page_title' => 'Edit Package',
            'page_breadcrumb' => 'Pages / Edit Package'
        ]);
    }

    public function feedback()
    {
        return view('agent.pages.feedback', [
            'page_title' => 'Feedback',
            'page_breadcrumb' => 'Pages / Feedback'
        ]);
    }

    public function gallery()
    {
        return view('agent.pages.gallery', [
            'page_title' => 'Gallery',
            'page_breadcrumb' => 'Pages / Gallery'
        ]);
    }

    public function hotels()
    {
        return view('agent.pages.hotels', [
            'page_title' => 'Hotels',
            'page_breadcrumb' => 'Pages / Hotels'
        ]);
    }

    public function invoice()
    {
        return view('agent.pages.invoice', [
            'page_title' => 'Invoice',
            'page_breadcrumb' => 'Pages / Invoice'
        ]);
    }

    public function leads()
    {
        return view('agent.pages.leads', [
            'page_title' => 'Leads',
            'page_breadcrumb' => 'Pages / Leads'
        ]);
    }

    public function myPackages()
    {
        return view('agent.pages.my-packages', [
            'page_title' => 'My Packages',
            'page_breadcrumb' => 'Pages / My Packages'
        ]);
    }

    public function notifications()
    {
        return view('agent.pages.notifications', [
            'page_title' => 'Notifications',
            'page_breadcrumb' => 'Pages / Notifications'
        ]);
    }

    public function payment()
    {
        return view('agent.pages.payment', [
            'page_title' => 'Payment',
            'page_breadcrumb' => 'Pages / Payment'
        ]);
    }

    public function profile()
    {
        return view('agent.pages.profile', [
            'page_title' => 'Profile',
            'page_breadcrumb' => 'Pages / Profile'
        ]);
    }

    public function services()
    {
        return view('agent.pages.services', [
            'page_title' => 'Services',
            'page_breadcrumb' => 'Pages / Services'
        ]);
    }

    public function settings()
    {
        return view('agent.pages.settings', [
            'page_title' => 'Settings',
            'page_breadcrumb' => 'Pages / Settings'
        ]);
    }
}
