"use client";

import React from "react";
import { Plus, Rocket, Save, Info, Building2, Mail, Phone, Globe, ShieldCheck, Zap } from "lucide-react";
import FormInput from "../components/forms/FormInput";
import { motion } from "framer-motion";

const AgentOnboarding = () => {
  return (
    <div className="space-y-10 pb-12 max-w-7xl mx-auto">
      {/* Header */}
      <div className="space-y-2">
        <p className="text-xs font-bold text-primary uppercase tracking-widest">Admin / Management</p>
        <h1 className="text-5xl font-black font-syne text-foreground tracking-tight">Onboard New Agent</h1>
        <p className="text-muted-text font-medium max-w-2xl">
          Expand the Horizon network by registering a new premium travel agent partner. All fields are required for secure portal access.
        </p>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-10">
        {/* Main Form Area */}
        <div className="lg:col-span-2 space-y-8">
          {/* Entity Information Section */}
          <div className="bg-white rounded-[40px] shadow-premium border border-border-soft p-10 space-y-8">
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 bg-primary/5 rounded-2xl flex items-center justify-center text-primary">
                <Building2 size={22} />
              </div>
              <h3 className="text-2xl font-black font-syne text-foreground">Entity Information</h3>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <FormInput label="Travel Agent Name" placeholder="e.g. Atlas Global Travels" />
              <FormInput label="Email Address" placeholder="contact@agency.com" icon={<Mail size={18} />} />
              <FormInput label="Mobile Number" placeholder="+1 (555) 000-0000" icon={<Phone size={18} />} />
              
              <div className="space-y-2">
                <label className="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Assigned Region</label>
                <select className="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all font-bold text-foreground text-sm">
                  <option>North America</option>
                  <option>Europe</option>
                  <option>Asia Pacific</option>
                  <option>Middle East</option>
                </select>
              </div>
            </div>
          </div>

          {/* Service Configuration Section */}
          <div className="bg-white rounded-[40px] shadow-premium border border-border-soft p-10 space-y-8">
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 bg-primary/5 rounded-2xl flex items-center justify-center text-primary">
                <Zap size={22} />
              </div>
              <h3 className="text-2xl font-black font-syne text-foreground">Service Configuration</h3>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div className="bg-[#FDFDFD] p-6 rounded-[28px] border border-border-soft flex items-center justify-between">
                <div className="space-y-1">
                  <p className="text-sm font-black text-foreground">Service Guaranteed</p>
                  <p className="text-[10px] text-muted-text font-bold uppercase">Enable automated SLA monitoring</p>
                </div>
                <label className="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" className="sr-only peer" defaultChecked />
                  <div className="w-12 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                </label>
              </div>

              <div className="bg-[#FDFDFD] p-6 rounded-[28px] border border-border-soft flex items-center justify-between opacity-50">
                <div className="space-y-1">
                  <p className="text-sm font-black text-foreground">API Access</p>
                  <p className="text-[10px] text-muted-text font-bold uppercase">Allow third-party integrations</p>
                </div>
                <label className="relative inline-flex items-center cursor-pointer">
                  <input type="checkbox" className="sr-only peer" />
                  <div className="w-12 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                </label>
              </div>
            </div>
          </div>
        </div>

        {/* Sidebar Controls Area */}
        <div className="space-y-8">
          {/* Tier Selection Card */}
          <div className="bg-white rounded-[40px] shadow-premium border border-border-soft p-8 space-y-6">
            <h4 className="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Tier Selection</h4>
            <div className="space-y-3">
              {[
                { name: "Standard", desc: "Up to 50 bookings/mo" },
                { name: "Premium", desc: "Unlimited bookings & VIP support", active: true },
                { name: "Enterprise", desc: "Custom white-label solutions" }
              ].map((tier) => (
                <button 
                  key={tier.name}
                  className={`w-full p-5 rounded-3xl text-left border-2 transition-all ${tier.active ? 'border-primary bg-primary/[0.02]' : 'border-gray-100 hover:border-gray-200 bg-white'}`}
                >
                  <p className="text-sm font-black text-foreground">{tier.name}</p>
                  <p className="text-[10px] text-muted-text font-bold uppercase mt-1">{tier.desc}</p>
                </button>
              ))}
            </div>
          </div>

          {/* Account Status Card */}
          <div className="bg-white rounded-[40px] shadow-premium border border-border-soft p-8 space-y-6">
            <h4 className="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Account Status</h4>
            <div className="bg-gray-100 p-1.5 rounded-2xl flex">
              <button className="flex-1 py-3 px-6 rounded-xl text-sm font-black bg-primary text-white shadow-lg shadow-primary/20 transition-all">Active</button>
              <button className="flex-1 py-3 px-6 rounded-xl text-sm font-black text-muted-text hover:text-foreground transition-all">Inactive</button>
            </div>
          </div>

          {/* Action Buttons */}
          <div className="space-y-4">
            <button className="w-full bg-primary hover:bg-primary-hover text-white rounded-3xl py-6 font-black text-lg shadow-xl shadow-primary/20 transition-all flex items-center justify-center gap-3 group">
              <Rocket size={22} className="group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform" />
              Create User Account
            </button>
            <button className="w-full bg-gray-200 hover:bg-gray-300 text-foreground rounded-3xl py-6 font-black text-lg transition-all flex items-center justify-center gap-3">
              <Save size={22} />
              Save Draft
            </button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default AgentOnboarding;
