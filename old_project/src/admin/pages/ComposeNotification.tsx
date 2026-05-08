"use client";

import React from "react";
import { Send, Layout, Users, Megaphone, Smartphone, Mail, AlertCircle, X, Plus } from "lucide-react";
import FormInput from "../components/forms/FormInput";

const ComposeNotification = () => {
  return (
    <div className="space-y-10 pb-12 max-w-5xl mx-auto">
      <div className="flex items-center justify-between">
        <div className="space-y-2">
          <h1 className="text-5xl font-black font-syne text-foreground tracking-tight">Compose Notification</h1>
          <p className="text-muted-text font-medium">Draft and dispatch platform-wide announcements.</p>
        </div>
        <button className="p-3 bg-gray-100 rounded-2xl text-muted-text hover:text-foreground transition-all">
          <X size={24} />
        </button>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <div className="lg:col-span-8 space-y-8">
          {/* Notification Details */}
          <div className="bg-white rounded-[40px] shadow-premium border border-border-soft p-10 space-y-8">
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 bg-primary/5 rounded-2xl flex items-center justify-center text-primary">
                <Megaphone size={22} />
              </div>
              <h3 className="text-2xl font-black font-syne text-foreground tracking-tight">Message Content</h3>
            </div>

            <div className="space-y-6">
              <FormInput label="Notification Title" placeholder="e.g. Seasonal Commission Update" />
              
              <div className="space-y-2">
                <label className="text-[10px] font-black text-muted-text uppercase tracking-widest pl-2">Detailed Message</label>
                <textarea 
                  placeholder="Write your message here..."
                  className="w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all font-bold text-foreground placeholder:text-muted-text/30 text-sm h-48 resize-none"
                />
              </div>

              <div className="grid grid-cols-2 gap-6">
                <div className="space-y-2">
                  <label className="text-[10px] font-black text-muted-text uppercase tracking-widest pl-2">Target Audience</label>
                  <select className="w-full bg-[#F5F5F5] rounded-2xl py-4 px-6 outline-none text-sm font-bold">
                    <option>All Agents</option>
                    <option>Premium Agents Only</option>
                    <option>New Registered Users</option>
                  </select>
                </div>
                <div className="space-y-2">
                  <label className="text-[10px] font-black text-muted-text uppercase tracking-widest pl-2">Delivery Channel</label>
                  <select className="w-full bg-[#F5F5F5] rounded-2xl py-4 px-6 outline-none text-sm font-bold">
                    <option>Dashboard Only</option>
                    <option>Dashboard + Email</option>
                    <option>Dashboard + Mobile Push</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div className="lg:col-span-4 space-y-8">
          {/* Priority Card */}
          <div className="bg-white rounded-[40px] shadow-premium border border-border-soft p-8 space-y-6">
            <h4 className="text-xs font-black text-muted-text uppercase tracking-widest pl-2">Priority Level</h4>
            <div className="space-y-3">
              {[
                { name: "Normal", color: "bg-gray-100", active: true },
                { name: "High", color: "bg-orange-100" },
                { name: "Critical", color: "bg-red-100" }
              ].map((p) => (
                <button 
                  key={p.name}
                  className={`w-full p-4 rounded-2xl text-left border-2 transition-all flex items-center justify-between ${p.active ? 'border-primary bg-primary/[0.02]' : 'border-gray-50 bg-white'}`}
                >
                  <span className="text-sm font-black text-foreground">{p.name}</span>
                  <div className={`w-3 h-3 rounded-full ${p.active ? 'bg-primary' : 'bg-gray-200'}`} />
                </button>
              ))}
            </div>
          </div>

          {/* Action Buttons */}
          <div className="space-y-4">
            <button className="w-full bg-primary hover:bg-primary-hover text-white rounded-3xl py-6 font-black text-lg shadow-xl shadow-primary/20 transition-all flex items-center justify-center gap-3">
              <Send size={22} />
              Dispatch Now
            </button>
            <button className="w-full bg-gray-100 hover:bg-gray-200 text-foreground rounded-3xl py-6 font-black text-lg transition-all flex items-center justify-center gap-3">
              <Layout size={22} />
              Schedule for Later
            </button>
          </div>

          <div className="p-6 bg-info/5 rounded-[28px] border border-info/10 flex gap-4">
            <div className="w-8 h-8 bg-white rounded-xl flex items-center justify-center text-info shrink-0 shadow-sm">
              <AlertCircle size={18} />
            </div>
            <p className="text-[10px] text-muted-text font-bold leading-relaxed uppercase">
              Dispatching to 2,842 verified recipients. This action cannot be undone once confirmed.
            </p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ComposeNotification;
