"use client";

import React from "react";
import { Upload, Rocket, Save, Info, Megaphone, Target, Calendar, Globe, Search, ArrowRight } from "lucide-react";
import FormInput from "../components/forms/FormInput";

const AdCampaignForm = () => {
  return (
    <div className="space-y-10 pb-12 max-w-7xl mx-auto">
      <div className="flex items-center justify-between">
        <div className="space-y-2">
          <h1 className="text-5xl font-black font-syne text-foreground tracking-tight">Create New Ad Campaign</h1>
          <p className="text-muted-text font-medium">Define placement, duration, and visual creative for your new advertisement.</p>
        </div>
        <div className="flex items-center gap-4">
          <button className="px-8 py-3 bg-gray-100 text-muted-text rounded-2xl text-sm font-black transition-all hover:bg-gray-200">
            Save as Draft
          </button>
          <button className="px-8 py-3 bg-[#B23B11] text-white rounded-2xl text-sm font-black transition-all hover:bg-[#96320D] shadow-xl shadow-primary/20">
            Launch Ad
          </button>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <div className="lg:col-span-8 space-y-8">
          {/* Campaign Information */}
          <div className="bg-white rounded-[40px] shadow-premium border border-border-soft p-10 space-y-8">
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 bg-primary/5 rounded-2xl flex items-center justify-center text-primary">
                <Info size={22} />
              </div>
              <h3 className="text-2xl font-black font-syne text-foreground tracking-tight">Campaign Information</h3>
            </div>

            <div className="space-y-6">
              <FormInput label="Ad Title" placeholder="e.g. Summer Mediterranean Getaway 2024" />
              
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="space-y-2">
                  <label className="text-[10px] font-black text-muted-text uppercase tracking-widest pl-2">Advertiser / Agent</label>
                  <div className="relative group">
                    <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-muted-text" size={16} />
                    <input type="text" placeholder="Search advertisers..." className="w-full bg-[#F5F5F5] rounded-2xl py-4 pl-12 pr-6 outline-none text-sm font-bold" />
                  </div>
                </div>
                <FormInput label="Target URL" placeholder="https://horizon-ascent.com/promo" icon={<Globe size={18} />} />
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div className="space-y-2">
                  <label className="text-[10px] font-black text-muted-text uppercase tracking-widest pl-2">Target State/City</label>
                  <select className="w-full bg-[#F5F5F5] rounded-2xl py-4 px-6 outline-none text-sm font-bold">
                    <option>All Locations (Global)</option>
                    <option>India - All States</option>
                    <option>USA - New York</option>
                  </select>
                </div>
                <FormInput label="Expiry Date" type="date" icon={<Calendar size={18} />} />
              </div>
            </div>
          </div>

          {/* Advertisement Banner Upload */}
          <div className="bg-white rounded-[40px] shadow-premium border border-border-soft p-10 space-y-8">
            <div className="flex items-center justify-between">
              <div className="flex items-center gap-3">
                <div className="w-10 h-10 bg-primary/5 rounded-2xl flex items-center justify-center text-primary">
                  <Megaphone size={22} />
                </div>
                <h3 className="text-2xl font-black font-syne text-foreground tracking-tight">Advertisement Banner</h3>
              </div>
              <span className="text-[10px] font-black text-muted-text uppercase tracking-widest bg-gray-100 px-4 py-1.5 rounded-full">Required: 1920 x 480 px</span>
            </div>

            <div className="relative group">
              <div className="w-full aspect-[4/1] bg-[#F8F7F4] border-2 border-dashed border-gray-200 rounded-[32px] flex flex-col items-center justify-center text-muted-text hover:border-primary/40 transition-all cursor-pointer">
                <div className="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-lg mb-4 text-primary">
                  <Upload size={32} />
                </div>
                <p className="text-lg font-black font-syne text-foreground">Click to Upload New Creative</p>
                <p className="text-xs font-bold uppercase tracking-widest text-muted-text opacity-60 mt-1">or drag and drop JPG, PNG up to 10MB</p>
              </div>
            </div>

            <div className="p-6 bg-primary/5 rounded-[28px] border border-primary/10 flex gap-4">
              <div className="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-primary shrink-0 shadow-sm">
                <Target size={20} />
              </div>
              <div className="space-y-1">
                <p className="text-sm font-black text-foreground">Creative Tip</p>
                <p className="text-xs text-muted-text font-medium leading-relaxed">
                  Use high-contrast text and a clear Call To Action (CTA) on the right side of the banner for optimal visibility on desktop layouts.
                </p>
              </div>
            </div>
          </div>
        </div>

        <div className="lg:col-span-4 space-y-8">
          {/* Ad Placement */}
          <div className="bg-white rounded-[40px] shadow-premium border border-border-soft p-8 space-y-6">
            <h4 className="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Ad Placement</h4>
            <div className="space-y-3">
              {[
                { name: "Homepage Hero", desc: "Primary high-traffic landing section", active: true },
                { name: "Package Listing", desc: "Embedded within travel packages" },
                { name: "Search Results", desc: "Side placement on explore page" }
              ].map((place) => (
                <button 
                  key={place.name}
                  className={`w-full p-5 rounded-3xl text-left border-2 transition-all flex items-center gap-4 ${place.active ? 'border-primary bg-primary/[0.02]' : 'border-gray-100 hover:border-gray-200 bg-white'}`}
                >
                  <div className={`w-5 h-5 rounded-full border-2 flex items-center justify-center ${place.active ? 'border-primary' : 'border-gray-300'}`}>
                    {place.active && <div className="w-2.5 h-2.5 bg-primary rounded-full" />}
                  </div>
                  <div>
                    <p className="text-sm font-black text-foreground">{place.name}</p>
                    <p className="text-[10px] text-muted-text font-bold uppercase mt-0.5">{place.desc}</p>
                  </div>
                </button>
              ))}
            </div>
          </div>

          {/* Billing Card */}
          <div className="bg-white rounded-[40px] shadow-premium border border-border-soft p-8 space-y-6">
            <div className="flex items-center gap-3">
              <div className="w-8 h-1 bg-primary rounded-full" />
              <h4 className="text-xs font-black text-muted-text uppercase tracking-widest">Billing</h4>
            </div>
            
            <div className="space-y-4">
              <div className="space-y-2">
                <p className="text-[10px] font-black text-muted-text uppercase tracking-widest text-center">Sale Type</p>
                <select className="w-full bg-[#F5F5F5] rounded-2xl py-4 px-6 outline-none text-sm font-bold text-center">
                  <option>Fixed Price (Monthly)</option>
                  <option>Pay-per-click (PPC)</option>
                </select>
              </div>
              
              <div className="space-y-2">
                <p className="text-[10px] font-black text-muted-text uppercase tracking-widest text-center">Total Price (INR)</p>
                <div className="bg-[#F5F5F5] rounded-2xl py-4 px-6 text-center font-black font-syne text-2xl">
                  ₹ 2,500.00
                </div>
                <p className="text-[10px] text-muted-text font-bold text-center uppercase mt-1 opacity-50">Excludes platform service taxes of 2.5%.</p>
              </div>
            </div>
          </div>

          {/* Summary Card */}
          <div className="bg-[#B23B11] p-8 rounded-[40px] shadow-xl text-white space-y-8">
            <h4 className="text-xl font-black font-syne tracking-tight">Summary</h4>
            <div className="space-y-4 text-sm font-bold">
              <div className="flex justify-between border-b border-white/10 pb-3">
                <span className="opacity-60">Reach Est.</span>
                <span>45k - 60k</span>
              </div>
              <div className="flex justify-between border-b border-white/10 pb-3">
                <span className="opacity-60">Placement</span>
                <span>Homepage</span>
              </div>
              <div className="flex justify-between border-b border-white/10 pb-3">
                <span className="opacity-60">Duration</span>
                <span>30 Days</span>
              </div>
            </div>
            <div className="pt-2">
              <div className="flex items-end justify-between">
                <span className="text-white/60 font-black text-xs uppercase">Estimated Cost</span>
                <span className="text-3xl font-black font-syne">₹2,562.50</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default AdCampaignForm;
