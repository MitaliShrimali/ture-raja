"use client";

import React, { useState } from "react";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import PackageCard from "@/components/PackageCard";
import { packages } from "@/data/packages";
import { User, Heart, History, Settings, Camera, MapPin, Calendar, LogOut } from "lucide-react";

export default function ProfilePage() {
  const [activeTab, setActiveTab] = useState("wishlist");

  return (
    <main className="bg-background min-h-screen">
      <Navbar />
      
      {/* Profile Header */}
      <div className="relative pt-32 pb-48 bg-foreground overflow-hidden">
        <div className="absolute inset-0 z-0 opacity-30">
          <img src="/tourex/hero-bg.png" alt="Profile Cover" className="w-full h-full object-cover" />
        </div>
        <div className="container-custom relative z-10 flex flex-col items-center text-center text-white">
          <div className="relative mb-6">
            <div className="w-32 h-32 md:w-40 md:h-40 rounded-[40px] border-4 border-white overflow-hidden shadow-2xl">
              <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=John" alt="Avatar" className="w-full h-full object-cover bg-white" />
            </div>
            <button className="absolute bottom-2 right-2 w-10 h-10 bg-primary text-white rounded-2xl flex items-center justify-center shadow-lg hover:bg-primary-hover transition-colors">
              <Camera size={20} />
            </button>
          </div>
          <h1 className="text-4xl font-black mb-2">John Doe</h1>
          <div className="flex items-center gap-4 text-white/60 font-medium">
            <div className="flex items-center gap-1">
              <MapPin size={16} />
              <span>New York, USA</span>
            </div>
            <div className="w-1 h-1 bg-white/20 rounded-full" />
            <div className="flex items-center gap-1">
              <Calendar size={16} />
              <span>Joined April 2024</span>
            </div>
          </div>
        </div>
      </div>

      {/* Main Dashboard */}
      <div className="container-custom -mt-24 pb-20 relative z-20">
        <div className="flex flex-col lg:flex-row gap-8">
          {/* Sidebar Tabs */}
          <div className="lg:w-1/4 shrink-0">
            <div className="bg-white rounded-[32px] p-4 shadow-soft border border-gray-50 flex flex-col">
              {[
                { id: "profile", label: "Personal Info", icon: User },
                { id: "wishlist", label: "My Wishlist", icon: Heart },
                { id: "history", label: "Booking History", icon: History },
                { id: "settings", label: "Settings", icon: Settings },
              ].map((tab) => (
                <button
                  key={tab.id}
                  onClick={() => setActiveTab(tab.id)}
                  className={`flex items-center gap-4 p-5 rounded-[20px] font-bold transition-all ${
                    activeTab === tab.id 
                      ? "bg-primary text-white shadow-lg shadow-primary/20" 
                      : "text-gray-400 hover:text-primary hover:bg-primary/5"
                  }`}
                >
                  <tab.icon size={22} />
                  <span>{tab.label}</span>
                </button>
              ))}
              <hr className="my-4 border-gray-100" />
              <button className="flex items-center gap-4 p-5 rounded-[20px] font-bold text-red-500 hover:bg-red-50 transition-all">
                <LogOut size={22} />
                <span>Sign Out</span>
              </button>
            </div>
          </div>

          {/* Tab Content */}
          <div className="flex-1 space-y-8">
            {activeTab === "wishlist" && (
              <div className="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <div className="flex items-center justify-between">
                  <h2 className="text-3xl font-black text-foreground">Saved Packages</h2>
                  <span className="bg-primary/10 text-primary px-4 py-1.5 rounded-full font-bold text-sm">
                    {packages.length} Saved
                  </span>
                </div>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                  {packages.map((pkg) => (
                    <PackageCard key={pkg.id} pkg={pkg} />
                  ))}
                </div>
              </div>
            )}

            {activeTab === "profile" && (
              <div className="bg-white rounded-[32px] p-8 md:p-12 shadow-soft border border-gray-50 space-y-10 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <h2 className="text-3xl font-black">Personal Information</h2>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                  <div className="space-y-2">
                    <label className="text-xs font-bold uppercase tracking-widest text-gray-400">First Name</label>
                    <input type="text" defaultValue="John" className="w-full bg-background border border-gray-100 rounded-2xl py-4 px-6 font-bold" />
                  </div>
                  <div className="space-y-2">
                    <label className="text-xs font-bold uppercase tracking-widest text-gray-400">Last Name</label>
                    <input type="text" defaultValue="Doe" className="w-full bg-background border border-gray-100 rounded-2xl py-4 px-6 font-bold" />
                  </div>
                  <div className="space-y-2 md:col-span-2">
                    <label className="text-xs font-bold uppercase tracking-widest text-gray-400">Email Address</label>
                    <input type="email" defaultValue="john.doe@example.com" className="w-full bg-background border border-gray-100 rounded-2xl py-4 px-6 font-bold" />
                  </div>
                  <div className="space-y-2">
                    <label className="text-xs font-bold uppercase tracking-widest text-gray-400">Phone Number</label>
                    <input type="text" defaultValue="+1 (234) 567-890" className="w-full bg-background border border-gray-100 rounded-2xl py-4 px-6 font-bold" />
                  </div>
                  <div className="space-y-2">
                    <label className="text-xs font-bold uppercase tracking-widest text-gray-400">Date of Birth</label>
                    <input type="text" defaultValue="25 Jan 1990" className="w-full bg-background border border-gray-100 rounded-2xl py-4 px-6 font-bold" />
                  </div>
                </div>
                <button className="bg-primary hover:bg-primary-hover text-white px-10 py-4 rounded-full font-bold transition-all shadow-lg shadow-primary/20">
                  Save Changes
                </button>
              </div>
            )}

            {activeTab === "history" && (
              <div className="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
                <h2 className="text-3xl font-black text-foreground">Recent Bookings</h2>
                <div className="space-y-6">
                  {packages.slice(0, 2).map((pkg) => (
                    <div key={pkg.id} className="bg-white rounded-[32px] p-6 shadow-soft border border-gray-50 flex flex-col md:flex-row items-center gap-6 hover:shadow-card transition-all">
                      <div className="w-full md:w-48 h-32 rounded-2xl overflow-hidden shrink-0">
                        <img src={pkg.image} alt={pkg.title} className="w-full h-full object-cover" />
                      </div>
                      <div className="flex-1 space-y-1">
                        <div className="flex items-center justify-between mb-2">
                           <span className="text-primary font-bold text-xs uppercase tracking-widest">Confirmed</span>
                           <span className="text-gray-400 text-sm font-medium">Booked on 12 Apr 2024</span>
                        </div>
                        <h4 className="text-xl font-bold">{pkg.title}</h4>
                        <p className="text-gray-400 font-medium">Package Price: ${pkg.price}</p>
                      </div>
                      <button className="w-full md:w-auto px-8 py-3 bg-foreground text-white rounded-xl font-bold hover:bg-black transition-colors">
                        View Details
                      </button>
                    </div>
                  ))}
                </div>
              </div>
            )}
          </div>
        </div>
      </div>

      <Footer />
    </main>
  );
}
