"use client";

import React from "react";
import { Settings, Globe, Shield, Bell, Mail, Database, CreditCard, Users, MessageCircle, Layout, Activity, ChevronRight, Save } from "lucide-react";

const settingCards = [
  { title: "General Settings", desc: "Platform name, logo, and core identity", icon: <Globe size={24} />, color: "bg-blue-50 text-blue-500" },
  { title: "Preferences", desc: "Language, currency, and regional defaults", icon: <Settings size={24} />, color: "bg-purple-50 text-purple-500" },
  { title: "Mail Setup", icon: <Mail size={24} />, desc: "SMTP configuration and server limits", color: "bg-orange-50 text-primary" },
  { title: "Mail Template", icon: <Layout size={24} />, desc: "Visual editor for automated system emails", color: "bg-green-50 text-success" },
  { title: "Home Page Banner", icon: <Layout size={24} />, desc: "Hero section and marketing banners", color: "bg-pink-50 text-pink-500" },
  { title: "Payment Gateway", icon: <CreditCard size={24} />, desc: "Stripe, PayPal, and Bank integrations", color: "bg-yellow-50 text-yellow-600" },
  { title: "Roles & Permissions", icon: <Users size={24} />, desc: "RBAC and team access management", color: "bg-indigo-50 text-indigo-500" },
  { title: "Whatsapp Template", icon: <MessageCircle size={24} />, desc: "Automated traveler notifications", color: "bg-green-50 text-green-600" },
  { title: "API Health Card", icon: <Activity size={24} />, desc: "Live system uptime and error monitoring", color: "bg-red-50 text-danger" },
];

const PlatformSettings = () => {
  return (
    <div className="space-y-10 pb-12 max-w-6xl mx-auto">
      <div className="flex items-center justify-between">
        <div className="space-y-2">
          <p className="text-xs font-bold text-primary uppercase tracking-widest">Platform Admin</p>
          <h1 className="text-5xl font-black font-syne text-foreground tracking-tight">System Settings</h1>
          <p className="text-muted-text font-medium">Configure core platform parameters and global defaults.</p>
        </div>
        <button className="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3">
          <Save size={20} /> Save All Changes
        </button>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {settingCards.map((card, i) => (
          <button 
            key={i}
            className="group bg-white p-8 rounded-[40px] shadow-soft border border-border-soft hover:shadow-premium hover:border-primary/20 transition-all text-left flex flex-col justify-between h-64 relative overflow-hidden"
          >
            <div className={`w-14 h-14 rounded-2xl ${card.color} flex items-center justify-center transition-transform group-hover:scale-110`}>
              {card.icon}
            </div>
            <div className="space-y-2 relative z-10">
              <h4 className="text-xl font-black font-syne text-foreground">{card.title}</h4>
              <p className="text-xs text-muted-text font-medium leading-relaxed">{card.desc}</p>
            </div>
            <div className="flex items-center gap-2 text-primary font-black uppercase text-[10px] tracking-widest pt-4 opacity-0 group-hover:opacity-100 transition-all">
              Configure <ChevronRight size={14} />
            </div>
            
            {/* Background Pattern */}
            <div className="absolute -right-8 -bottom-8 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity">
              {React.cloneElement(card.icon as React.ReactElement<any>, { size: 160 })}
            </div>
          </button>
        ))}
      </div>
    </div>
  );
};

export default PlatformSettings;
