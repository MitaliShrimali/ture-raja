"use client";

import React from "react";
import { Plus, Edit3, Trash2, ExternalLink, Info, Monitor, Smartphone, Layout, ArrowRight } from "lucide-react";
import StatusBadge from "../components/common/StatusBadge";

const banners = [
  { 
    id: "01", 
    title: "Get Up to 20% OFF* on South India Holiday Packages", 
    desc: "Summer Explorer Series", 
    link: "/packages/south-india", 
    status: "Active", 
    image: "https://images.unsplash.com/photo-1548013146-72479768bbaa?auto=format&fit=crop&q=80&w=400" 
  },
  { 
    id: "02", 
    title: "Experience the Majesty of the Himalayas", 
    desc: "Adventure Peak Season", 
    link: "/packages/himalayas", 
    status: "Active", 
    image: "https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?auto=format&fit=crop&q=80&w=400" 
  },
  { 
    id: "03", 
    title: "Exclusive Monsoon Retreats in Goa", 
    desc: "Off-Season Deals", 
    link: "/campaign/goa-monsoon", 
    status: "Inactive", 
    image: "https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&q=80&w=400" 
  },
];

const ManageBanners = () => {
  return (
    <div className="space-y-10 pb-12">
      <div className="space-y-4">
        <div className="flex items-center gap-2 text-[10px] font-black text-muted-text uppercase tracking-widest">
          <span>Management</span>
          <span className="opacity-40">/</span>
          <span className="text-primary">Home Banners</span>
        </div>
        <div className="flex flex-col md:flex-row md:items-end justify-between gap-6">
          <div className="space-y-2">
            <h1 className="text-5xl font-black font-syne text-foreground tracking-tight">Manage Banners</h1>
            <p className="text-muted-text font-medium max-w-2xl">
              Curate the first impression for your travelers. High-impact banners drive 40% more conversions.
            </p>
          </div>
          <button className="bg-[#B23B11] hover:bg-[#96320D] text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3">
            <Plus size={20} /> Add New Banner
          </button>
        </div>
      </div>

      {/* Stats */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
        {[
          { label: "Total Banners", value: "12", sub: "+2 this month" },
          { label: "Active Now", value: "08", active: true },
          { label: "Avg. CTR", value: "4.2%", sub: "High" },
          { label: "Upcoming", value: "03", sub: "Scheduled", highlight: true },
        ].map((stat, i) => (
          <div key={i} className={`bg-white p-8 rounded-[32px] shadow-soft border border-border-soft space-y-2 ${stat.highlight ? 'bg-orange-50/50' : ''}`}>
            <p className="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">{stat.label}</p>
            <div className="flex items-end gap-3">
              <h4 className="text-4xl font-black font-syne text-foreground">{stat.value}</h4>
              {stat.active && <div className="w-2.5 h-2.5 bg-success rounded-full mb-2 animate-pulse" />}
              {stat.sub && <span className={`text-[10px] font-bold uppercase mb-1 ${stat.label === 'Avg. CTR' ? 'text-primary' : stat.label === 'Total Banners' ? 'text-blue-500' : 'text-muted-text'}`}>{stat.sub}</span>}
            </div>
          </div>
        ))}
      </div>

      {/* Banner List */}
      <div className="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left">
            <thead>
              <tr className="bg-gray-50/50 border-b border-border-soft">
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">BANNER IMAGE</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">DESCRIPTION & MARKETING</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">TARGET LINK</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTIONS</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-border-soft">
              {banners.map((banner, idx) => (
                <tr key={idx} className="group hover:bg-gray-50/30 transition-colors">
                  <td className="py-8 px-10">
                    <div className="w-40 h-24 rounded-2xl overflow-hidden border border-border-soft bg-gray-100 group-hover:scale-105 transition-transform duration-500">
                      <img src={banner.image} alt="Banner" className="w-full h-full object-cover" />
                    </div>
                  </td>
                  <td className="py-8 px-10">
                    <div className="max-w-xs space-y-1">
                      <p className="text-sm font-black text-foreground leading-tight">{banner.title}</p>
                      <p className="text-[10px] font-bold text-muted-text uppercase tracking-widest opacity-60">{banner.desc}</p>
                    </div>
                  </td>
                  <td className="py-8 px-10">
                    <div className="flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-600 rounded-full w-fit">
                      <ExternalLink size={12} />
                      <span className="text-[10px] font-black tracking-tighter uppercase">{banner.link}</span>
                    </div>
                  </td>
                  <td className="py-8 px-10">
                    <StatusBadge label={banner.status} type={banner.status === 'Active' ? 'success' : 'neutral'} />
                  </td>
                  <td className="py-8 px-10 text-right">
                    <div className="flex items-center justify-end gap-2">
                      <button className="p-3 text-muted-text hover:text-primary hover:bg-primary/5 rounded-2xl transition-all"><Edit3 size={18} /></button>
                      <button className="p-3 text-muted-text hover:text-danger hover:bg-danger/5 rounded-2xl transition-all"><Trash2 size={18} /></button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {/* Live Preview Component Simulation */}
      <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div className="lg:col-span-8 bg-white rounded-[40px] shadow-premium border border-border-soft p-10 relative overflow-hidden group">
          <div className="flex items-center justify-between mb-10">
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 bg-primary/5 rounded-2xl flex items-center justify-center text-primary">
                <Layout size={22} />
              </div>
              <h3 className="text-2xl font-black font-syne text-foreground tracking-tight">Live Preview</h3>
            </div>
            <div className="flex items-center gap-2 bg-gray-100 p-1.5 rounded-xl">
              <button className="p-2 bg-white rounded-lg shadow-sm text-primary"><Monitor size={18} /></button>
              <button className="p-2 text-muted-text hover:text-foreground transition-all"><Smartphone size={18} /></button>
            </div>
          </div>

          <div className="relative rounded-[32px] overflow-hidden aspect-[16/7] shadow-2xl">
            <img src={banners[0].image} alt="Preview" className="w-full h-full object-cover" />
            <div className="absolute inset-0 bg-black/40 flex items-center px-16">
              <div className="max-w-md space-y-6">
                <span className="bg-primary px-4 py-1.5 rounded-full text-[10px] font-black text-white uppercase tracking-widest">Live Preview</span>
                <h2 className="text-4xl font-black font-syne text-white leading-tight">Discover the Soul of South India with Exclusive Tours</h2>
                <p className="text-white/80 font-medium">Tailor-made itineraries for the discerning explorer.</p>
                <button className="bg-foreground text-white px-8 py-4 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-primary transition-all">
                  Explore Packages
                </button>
              </div>
            </div>
          </div>
        </div>

        <div className="lg:col-span-4 bg-[#B23B11] p-10 rounded-[40px] shadow-2xl text-white relative overflow-hidden flex flex-col justify-between">
          <div className="space-y-6 relative z-10">
            <div className="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center">
              <Layout size={28} className="text-white" />
            </div>
            <h4 className="text-3xl font-black font-syne leading-tight">Optimization Tips</h4>
            <ul className="space-y-4">
              <li className="flex items-start gap-3">
                <div className="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-[10px] mt-1 shrink-0">1</div>
                <p className="text-sm font-medium text-white/80 leading-relaxed">Use high-contrast imagery for better visibility on all devices.</p>
              </li>
              <li className="flex items-start gap-3">
                <div className="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-[10px] mt-1 shrink-0">2</div>
                <p className="text-sm font-medium text-white/80 leading-relaxed">Keep primary marketing text under 60 characters for readability.</p>
              </li>
              <li className="flex items-start gap-3">
                <div className="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center text-[10px] mt-1 shrink-0">3</div>
                <p className="text-sm font-medium text-white/80 leading-relaxed">Update banners every 14 days to prevent audience fatigue.</p>
              </li>
            </ul>
          </div>
          <button className="w-full py-5 bg-white/10 hover:bg-white/20 border border-white/20 rounded-2xl font-black text-xs uppercase tracking-widest transition-all relative z-10">
            View Detailed Analytics
          </button>
          
          <div className="absolute right-0 bottom-0 w-2/3 h-2/3 opacity-20 translate-x-1/4 translate-y-1/4">
             <Monitor size={300} strokeWidth={0.5} />
          </div>
        </div>
      </div>
    </div>
  );
};

export default ManageBanners;
