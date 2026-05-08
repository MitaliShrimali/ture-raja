"use client";

import React from "react";
import { Camera, MapPin, Globe, Mail, Phone, Share2, MessageCircle, Briefcase, Video, Send, Save, X } from "lucide-react";
import FormInput from "../components/forms/FormInput";

const AgentOnboardingDetailed = () => {
  return (
    <div className="space-y-10 pb-12 max-w-7xl mx-auto">
      {/* Header */}
      <div className="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div className="space-y-2">
          <p className="text-xs font-bold text-primary uppercase tracking-widest">Agent Management</p>
          <h1 className="text-5xl font-black font-syne text-foreground tracking-tight">Add New Paid User</h1>
          <p className="text-muted-text font-medium">Onboard a new agency partner to the platform hub ecosystem.</p>
        </div>
        <div className="flex items-center gap-4">
          <button className="px-8 py-3 bg-gray-100 text-muted-text rounded-2xl text-sm font-black transition-all hover:bg-gray-200">
            Discard Changes
          </button>
          <button className="px-8 py-3 bg-[#B23B11] text-white rounded-2xl text-sm font-black transition-all hover:bg-[#96320D] shadow-xl shadow-primary/20">
            Submit Application
          </button>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
        {/* Left Column: Image & Options */}
        <div className="lg:col-span-3 space-y-8">
          <div className="bg-white rounded-[40px] shadow-premium border border-border-soft p-10 flex flex-col items-center text-center space-y-6">
            <div className="relative group">
              <div className="w-40 h-40 bg-[#FDFDFD] border-2 border-dashed border-gray-200 rounded-[32px] flex flex-col items-center justify-center text-muted-text group-hover:border-primary/40 transition-all cursor-pointer">
                <Camera size={32} className="opacity-30 mb-2" />
                <p className="text-[10px] font-bold uppercase tracking-widest">Upload Logo</p>
              </div>
              <button className="absolute bottom-2 right-2 w-10 h-10 bg-primary text-white rounded-2xl flex items-center justify-center shadow-lg hover:scale-110 transition-all">
                <Plus size={20} />
              </button>
            </div>
            <div className="space-y-2">
              <h4 className="text-sm font-black text-foreground">Company Profile Image</h4>
              <p className="text-[10px] text-muted-text font-medium leading-relaxed">
                Upload a high-resolution logo or headshot. Min 500x500px suggested.
              </p>
            </div>
          </div>

          <div className="bg-white rounded-[40px] shadow-premium border border-border-soft p-8 space-y-6">
            <div className="flex items-center gap-3">
              <div className="w-8 h-1 bg-primary rounded-full" />
              <h4 className="text-sm font-black text-foreground uppercase tracking-widest">Platform Options</h4>
            </div>
            <div className="bg-gray-50 p-6 rounded-[28px] border border-border-soft flex items-center justify-between">
              <div className="space-y-1">
                <p className="text-xs font-black text-foreground">Hide Contact Details</p>
                <p className="text-[10px] text-muted-text font-bold leading-tight">Keep private from public listings</p>
              </div>
              <label className="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" className="sr-only peer" />
                <div className="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
              </label>
            </div>
          </div>
        </div>

        {/* Middle Column: Detailed Info */}
        <div className="lg:col-span-5 space-y-8">
          <div className="bg-white rounded-[40px] shadow-premium border border-border-soft p-10 space-y-8">
            <div className="flex items-center gap-3">
              <div className="w-1 h-6 bg-primary rounded-full" />
              <h3 className="text-2xl font-black font-syne text-foreground tracking-tight">Agent/Company Information</h3>
            </div>

            <div className="space-y-6">
              <FormInput label="Company Name" placeholder="Ascent Global Ventures" />
              <div className="grid grid-cols-2 gap-4">
                <FormInput label="Mobile Number" placeholder="+1 (555) 000-0000" />
                <FormInput label="Phone Number" placeholder="+1 (555) 123-4567" />
              </div>
              <FormInput label="Official Email" placeholder="admin@company.com" />
              <div className="grid grid-cols-2 gap-4">
                <FormInput label="Country" placeholder="United States" />
                <FormInput label="State/Province" placeholder="California" />
              </div>
              <div className="grid grid-cols-2 gap-4">
                <FormInput label="City" placeholder="San Francisco" />
                <FormInput label="Pincode/Zip" placeholder="94105" />
              </div>
              <div className="space-y-2">
                <label className="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Full Address</label>
                <textarea 
                  placeholder="Suite 400, 101 California St."
                  className="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all font-bold text-foreground placeholder:text-muted-text/30 text-sm h-32 resize-none"
                />
              </div>
            </div>
          </div>
        </div>

        {/* Right Column: Social & Contacts */}
        <div className="lg:col-span-4 space-y-8">
          <div className="bg-white rounded-[40px] shadow-premium border border-border-soft p-10 space-y-8">
            <div className="flex items-center gap-3">
              <div className="w-1 h-6 bg-primary rounded-full" />
              <h3 className="text-2xl font-black font-syne text-foreground tracking-tight">Social & Web Presence</h3>
            </div>

            <div className="space-y-6">
              <div className="space-y-2">
                <label className="text-[10px] font-black text-muted-text uppercase tracking-widest pl-2 opacity-60">About Us / Bio</label>
                <textarea 
                  placeholder="Brief description of the agency's mission and history..."
                  className="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none text-sm font-medium text-foreground h-24 resize-none"
                />
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div className="relative">
                  <Share2 className="absolute left-4 top-1/2 -translate-y-1/2 text-muted-text" size={16} />
                  <input type="text" placeholder="Facebook URL" className="w-full bg-[#FDFDFD] border border-border-soft rounded-2xl py-3 pl-12 pr-4 text-xs font-medium outline-none" />
                </div>
                <div className="relative">
                  <MessageCircle className="absolute left-4 top-1/2 -translate-y-1/2 text-muted-text" size={16} />
                  <input type="text" placeholder="Twitter URL" className="w-full bg-[#FDFDFD] border border-border-soft rounded-2xl py-3 pl-12 pr-4 text-xs font-medium outline-none" />
                </div>
                <div className="relative">
                  <Briefcase className="absolute left-4 top-1/2 -translate-y-1/2 text-muted-text" size={16} />
                  <input type="text" placeholder="LinkedIn URL" className="w-full bg-[#FDFDFD] border border-border-soft rounded-2xl py-3 pl-12 pr-4 text-xs font-medium outline-none" />
                </div>
                <div className="relative">
                  <Globe className="absolute left-4 top-1/2 -translate-y-1/2 text-muted-text" size={16} />
                  <input type="text" placeholder="Google Plus" className="w-full bg-[#FDFDFD] border border-border-soft rounded-2xl py-3 pl-12 pr-4 text-xs font-medium outline-none" />
                </div>
                <div className="relative">
                  <Camera className="absolute left-4 top-1/2 -translate-y-1/2 text-muted-text" size={16} />
                  <input type="text" placeholder="Instagram URL" className="w-full bg-[#FDFDFD] border border-border-soft rounded-2xl py-3 pl-12 pr-4 text-xs font-medium outline-none" />
                </div>
                <div className="relative">
                  <Phone className="absolute left-4 top-1/2 -translate-y-1/2 text-muted-text" size={16} />
                  <input type="text" placeholder="Skype ID" className="w-full bg-[#FDFDFD] border border-border-soft rounded-2xl py-3 pl-12 pr-4 text-xs font-medium outline-none" />
                </div>
              </div>

              <div className="space-y-2">
                <label className="text-[10px] font-black text-muted-text uppercase tracking-widest pl-2 opacity-60">Website URL</label>
                <input type="text" placeholder="https://www.example.com" className="w-full bg-[#FDFDFD] border border-border-soft rounded-full py-4 px-6 text-sm font-medium outline-none" />
              </div>
            </div>
          </div>

          <div className="bg-white rounded-[40px] shadow-premium border border-border-soft p-10 space-y-8">
            <div className="flex items-center gap-3">
              <div className="w-1 h-6 bg-primary rounded-full" />
              <h3 className="text-2xl font-black font-syne text-foreground tracking-tight">Primary Contact Person</h3>
            </div>
            <div className="grid grid-cols-2 gap-4">
              <FormInput label="Full Name" placeholder="Johnathan Doe" />
              <FormInput label="Personal Email" placeholder="j.doe@company.com" />
            </div>
          </div>

          <div className="flex items-center justify-end gap-6 pt-4">
            <p className="text-[10px] text-muted-text font-medium text-right max-w-[140px]">
              * All fields are required for premium certification.
            </p>
            <button className="flex items-center gap-2 px-6 py-3 text-muted-text hover:text-foreground font-black text-sm uppercase tracking-widest">
              Cancel
            </button>
            <button className="bg-primary text-white px-10 py-5 rounded-[24px] font-black text-sm uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all">
              Save Profile
            </button>
          </div>
        </div>
      </div>
    </div>
  );
};

// Helper for the upload button
const Plus = ({ size }: { size: number }) => (
  <svg width={size} height={size} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round">
    <line x1="12" y1="5" x2="12" y2="19" /><line x1="5" y1="12" x2="19" y2="12" />
  </svg>
);

export default AgentOnboardingDetailed;
