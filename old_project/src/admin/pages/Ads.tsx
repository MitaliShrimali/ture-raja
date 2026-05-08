"use client";

import React, { useState } from "react";
import { Plus, Search, Filter, Download, Eye, Edit3, Trash2, Megaphone, TrendingUp, Clock, AlertCircle } from "lucide-react";
import StatusBadge from "../components/common/StatusBadge";
import AdCampaignForm from "./AdCampaignForm";

const ads = [
  { sr: "01", title: "Summer Expedition Promo", id: "ADV-2023-9901", advertiser: "Travel Agency Pro", expiry: "Oct 24, 2024", status: "Live", preview: "https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&q=80&w=200" },
  { sr: "02", title: "Luxury Resort Spotlight", id: "ADV-2023-8824", advertiser: "Heli-Sky Resorts", expiry: "Dec 12, 2023", status: "Expired", preview: "https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&q=80&w=200" },
  { sr: "03", title: "Winter Gear Flash Sale", id: "ADV-2024-1205", advertiser: "Nomad Outfitters", expiry: "Jan 30, 2025", status: "Paused", preview: "https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&q=80&w=200" },
];

const Ads = () => {
  const [showCreateForm, setShowCreateForm] = useState(false);

  if (showCreateForm) {
    return (
      <div className="relative">
        <button 
          onClick={() => setShowCreateForm(false)}
          className="absolute top-0 right-0 z-50 p-3 bg-gray-100 rounded-2xl text-muted-text hover:text-foreground transition-all"
        >
          Close
        </button>
        <AdCampaignForm />
      </div>
    );
  }

  return (
    <div className="space-y-10 pb-12">
      <div className="space-y-4">
        <div className="flex items-center gap-2 text-[10px] font-black text-muted-text uppercase tracking-widest">
          <span>Pages</span>
          <span className="opacity-40">/</span>
          <span className="text-primary">Dashboard</span>
        </div>
        <div className="flex flex-col md:flex-row md:items-end justify-between gap-6">
          <div className="space-y-2">
            <h1 className="text-5xl font-black font-syne text-foreground tracking-tight">Advertisement Campaigns</h1>
            <p className="text-muted-text font-medium">Monitor, track, and optimize your global advertising reach.</p>
          </div>
          <button 
            onClick={() => setShowCreateForm(true)}
            className="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3"
          >
            <Plus size={20} /> Create New Ad
          </button>
        </div>
      </div>

      {/* Campaign Stats */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div className="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft flex items-center gap-6">
          <div className="w-16 h-16 bg-orange-50 rounded-3xl flex items-center justify-center text-primary">
            <Megaphone size={32} />
          </div>
          <div>
            <div className="flex items-center gap-2">
              <p className="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Active Now</p>
              <span className="text-[10px] font-bold text-success">+12%</span>
            </div>
            <h4 className="text-3xl font-black font-syne text-foreground tracking-tight">248</h4>
          </div>
        </div>
        <div className="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft flex items-center gap-6">
          <div className="w-16 h-16 bg-blue-50 rounded-3xl flex items-center justify-center text-blue-500">
            <TrendingUp size={32} />
          </div>
          <div>
            <div className="flex items-center gap-2">
              <p className="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Total Impressions</p>
              <span className="text-[10px] font-bold text-blue-500">Target Reached</span>
            </div>
            <h4 className="text-3xl font-black font-syne text-foreground tracking-tight">1.2M</h4>
          </div>
        </div>
        <div className="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft flex items-center gap-6">
          <div className="w-16 h-16 bg-red-50 rounded-3xl flex items-center justify-center text-danger">
            <Clock size={32} />
          </div>
          <div>
            <div className="flex items-center gap-2">
              <p className="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Expiring Soon</p>
              <span className="text-[10px] font-bold text-danger">Action Needed</span>
            </div>
            <h4 className="text-3xl font-black font-syne text-foreground tracking-tight">14</h4>
          </div>
        </div>
      </div>

      {/* Active Ads Table */}
      <div className="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div className="p-8 border-b border-border-soft flex flex-col md:flex-row md:items-center justify-between gap-6">
          <div className="flex items-center gap-8">
            <h3 className="text-xl font-black font-syne">Active Advertisements</h3>
            <div className="flex items-center bg-gray-100 p-1 rounded-xl">
              <button className="px-4 py-1.5 text-[10px] font-black uppercase tracking-widest text-muted-text hover:text-foreground">All (1,042)</button>
              <button className="px-4 py-1.5 text-[10px] font-black uppercase tracking-widest bg-white text-primary rounded-lg shadow-sm">Live (248)</button>
            </div>
          </div>
          <div className="flex items-center gap-3">
            <button className="p-2.5 text-muted-text hover:text-foreground"><Filter size={20} /></button>
            <button className="p-2.5 text-muted-text hover:text-foreground"><Download size={20} /></button>
          </div>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-left">
            <thead>
              <tr className="bg-gray-50/50">
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">SR. NO</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">TITLE & DETAILS</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">BANNER PREVIEW</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">ADVERTISER</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">EXPIRY DATE</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTIONS</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-border-soft">
              {ads.map((ad, idx) => (
                <tr key={idx} className="group hover:bg-gray-50/30 transition-colors">
                  <td className="py-6 px-8 text-sm font-bold text-muted-text opacity-40">{ad.sr}</td>
                  <td className="py-6 px-8">
                    <div className="space-y-1">
                      <p className="text-sm font-black text-foreground">{ad.title}</p>
                      <p className="text-[10px] font-bold text-muted-text uppercase tracking-tighter">ID: {ad.id}</p>
                    </div>
                  </td>
                  <td className="py-6 px-8">
                    <div className="w-24 h-12 rounded-xl overflow-hidden border border-border-soft bg-gray-100">
                      <img src={ad.preview} alt="Preview" className="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity" />
                    </div>
                  </td>
                  <td className="py-6 px-8">
                    <div className="flex items-center gap-2">
                      <div className="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center font-black text-[10px] text-muted-text">
                        {ad.advertiser.split(' ').map(n => n[0]).join('')}
                      </div>
                      <span className="text-sm font-bold text-muted-text">{ad.advertiser}</span>
                    </div>
                  </td>
                  <td className="py-6 px-8 text-sm font-bold text-muted-text">{ad.expiry}</td>
                  <td className="py-6 px-8">
                    <StatusBadge label={ad.status} type={ad.status === 'Live' ? 'success' : ad.status === 'Paused' ? 'warning' : 'neutral'} />
                  </td>
                  <td className="py-6 px-8 text-right">
                    <div className="flex items-center justify-end gap-1">
                      <button className="p-2 text-muted-text hover:text-primary"><Eye size={18} /></button>
                      <button className="p-2 text-muted-text hover:text-primary"><Edit3 size={18} /></button>
                      <button className="p-2 text-muted-text hover:text-danger"><Trash2 size={18} /></button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
};

export default Ads;
