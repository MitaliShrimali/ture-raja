"use client";

import React from "react";
import { Search, Download, Trash2, Eye, Mail, UserPlus, Heart, ChevronLeft, ChevronRight } from "lucide-react";

const subscribers = [
  { sr: "01", email: "hello@traveler.com", date: "Oct 24, 2024", status: "Active" },
  { sr: "02", email: "explorer@journal.com", date: "Oct 23, 2024", status: "Active" },
  { sr: "03", email: "admin@ascent.co", date: "Oct 22, 2024", status: "Active" },
  { sr: "04", email: "marketing@globaltrip.io", date: "Oct 21, 2024", status: "Active" },
  { sr: "05", email: "support@wanderlust.net", date: "Oct 20, 2024", status: "Active" },
];

const Subscribers = () => {
  return (
    <div className="space-y-10 pb-12">
      <div className="space-y-2">
        <h1 className="text-5xl font-black font-syne text-foreground tracking-tight">Subscriber Management</h1>
        <p className="text-muted-text font-medium">Oversee your community engagement and platform growth metrics.</p>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <div className="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft space-y-4">
          <div className="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-primary">
            <UserPlus size={24} />
          </div>
          <div>
            <div className="flex items-center gap-2">
              <p className="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Total Subscribers</p>
              <span className="text-[10px] font-bold text-success">+12.5%</span>
            </div>
            <h4 className="text-3xl font-black font-syne text-foreground tracking-tight">12,482</h4>
          </div>
        </div>
        <div className="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft space-y-4">
          <div className="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500">
            <Mail size={24} />
          </div>
          <div>
            <div className="flex items-center gap-2">
              <p className="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">New This Month</p>
              <span className="text-[10px] font-bold text-blue-500">+420</span>
            </div>
            <h4 className="text-3xl font-black font-syne text-foreground tracking-tight">1,894</h4>
          </div>
        </div>
        <div className="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft space-y-4">
          <div className="w-12 h-12 bg-green-50 rounded-2xl flex items-center justify-center text-success">
            <Heart size={24} />
          </div>
          <div>
            <div className="flex items-center gap-2">
              <p className="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Active Rate</p>
              <span className="text-[10px] font-bold text-success">Stable</span>
            </div>
            <h4 className="text-3xl font-black font-syne text-foreground tracking-tight">94.2%</h4>
          </div>
        </div>
        <div className="bg-gradient-to-br from-[#B23B11] to-orange-400 p-8 rounded-[40px] shadow-xl text-white relative overflow-hidden group">
          <div className="relative z-10 space-y-4">
            <p className="text-white/60 text-[10px] font-black uppercase tracking-widest">Health Score</p>
            <h4 className="text-3xl font-black font-syne">Excellent</h4>
            <div className="h-1.5 w-full bg-white/20 rounded-full overflow-hidden">
              <div className="h-full bg-white w-4/5" />
            </div>
          </div>
        </div>
      </div>

      {/* Subscriber List */}
      <div className="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div className="p-8 border-b border-border-soft flex items-center justify-between">
          <h3 className="text-xl font-black font-syne">Subscriber Directory</h3>
          <button className="flex items-center gap-2 px-6 py-2.5 bg-gray-50 rounded-xl text-[10px] font-black text-muted-text uppercase tracking-widest hover:bg-gray-100 transition-all">
            <Download size={16} /> Export
          </button>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-left">
            <thead>
              <tr className="bg-gray-50/50">
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">SR. NO</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">EMAIL ADDRESS</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">ACTION</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-border-soft">
              {subscribers.map((sub, idx) => (
                <tr key={idx} className="group hover:bg-gray-50/30 transition-colors">
                  <td className="py-6 px-10 text-sm font-bold text-muted-text opacity-40">{sub.sr}</td>
                  <td className="py-6 px-10">
                    <div className="flex items-center gap-4">
                      <div className="w-8 h-8 rounded-full bg-primary/5 flex items-center justify-center text-[10px] font-black text-primary uppercase">
                        {sub.email[0]}
                      </div>
                      <span className="text-sm font-bold text-foreground">{sub.email}</span>
                    </div>
                  </td>
                  <td className="py-6 px-10">
                    <div className="flex items-center gap-4 opacity-40 group-hover:opacity-100 transition-opacity">
                      <button className="p-2 text-muted-text hover:text-primary"><Eye size={18} /></button>
                      <button className="p-2 text-muted-text hover:text-danger"><Trash2 size={18} /></button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>

        <div className="p-8 bg-gray-50/50 border-t border-border-soft flex items-center justify-between">
          <p className="text-sm font-bold text-muted-text">Showing 1 - 5 of 1,248</p>
          <div className="flex items-center gap-2">
            <button className="p-2 text-muted-text hover:text-primary"><ChevronLeft size={20} /></button>
            {[1, 2, 3].map(p => (
              <button key={p} className={`w-8 h-8 rounded-full text-xs font-black transition-all ${p === 1 ? 'bg-primary text-white' : 'text-muted-text hover:text-primary'}`}>
                {p}
              </button>
            ))}
            <button className="p-2 text-muted-text hover:text-primary"><ChevronRight size={20} /></button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Subscribers;
