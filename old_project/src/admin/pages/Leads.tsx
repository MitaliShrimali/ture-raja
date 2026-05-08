"use client";

import React from "react";
import { Filter, Download, Search, MousePointer2, Target, HelpCircle, ChevronLeft, ChevronRight, RotateCcw, Eye, Edit3 } from "lucide-react";
import StatusBadge from "../components/common/StatusBadge";

const leadData = [
  { sr: "01", name: "Alice Johnson", email: "alice.j@example.com", phone: "+1 555-0101", agent: "Nomad Ventures", package: "Bali Paradise", status: "Booked" },
  { sr: "02", name: "Mark Wilson", email: "mark.w@example.com", phone: "+1 555-0202", agent: "Azure Horizons", package: "Swiss Alps", status: "New" },
  { sr: "03", name: "Sarah Connor", email: "sarah.c@example.com", phone: "+1 555-0303", agent: "Globe Trotters", package: "Goa Retreat", status: "Contacted" },
  { sr: "04", name: "John Wick", email: "john.w@example.com", phone: "+1 555-0404", agent: "Alpine Escape", package: "Dubai Luxury", status: "Lost" },
];

const Leads = () => {
  return (
    <div className="space-y-10 pb-12 max-w-7xl mx-auto">
      {/* Header */}
      <div className="space-y-4">
        <div className="flex items-center gap-2 text-[10px] font-black text-muted-text uppercase tracking-widest">
          <span>Dashboard</span>
          <span className="opacity-40">/</span>
          <span className="text-primary">Lead Management</span>
        </div>
        
        <div className="flex flex-col md:flex-row md:items-end justify-between gap-6">
          <div className="space-y-2">
            <h2 className="text-4xl font-black font-syne text-foreground tracking-tight">Lead Records</h2>
            <p className="text-muted-text font-medium">Manage your prospective travelers and track conversion performance.</p>
          </div>
          <div className="flex items-center gap-3">
            <button className="flex items-center gap-2 px-6 py-3 bg-white border border-border-soft rounded-2xl text-xs font-black text-muted-text uppercase tracking-widest hover:bg-gray-50 transition-all">
              <Filter size={16} /> Filter
            </button>
            <button className="flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-primary-hover transition-all">
              <Download size={16} /> Export List
            </button>
          </div>
        </div>
      </div>

      {/* Table Card */}
      <div className="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left">
            <thead>
              <tr className="bg-gray-50/50">
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">SR. NO</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">TRAVELER NAME</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">AGENT / PACKAGE</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-center">STATUS</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTIONS</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-border-soft">
              {leadData.map((lead, idx) => (
                <tr key={idx} className="group hover:bg-gray-50/30 transition-colors">
                  <td className="py-6 px-10 text-sm font-bold text-muted-text opacity-40">{lead.sr}</td>
                  <td className="py-6 px-10">
                    <div className="space-y-1">
                      <p className="text-sm font-black text-foreground">{lead.name}</p>
                      <p className="text-[10px] text-muted-text font-medium">{lead.email}</p>
                    </div>
                  </td>
                  <td className="py-6 px-10">
                    <div className="space-y-1">
                      <p className="text-sm font-bold text-primary">{lead.agent}</p>
                      <p className="text-[10px] text-muted-text font-black uppercase tracking-widest">{lead.package}</p>
                    </div>
                  </td>
                  <td className="py-6 px-10 text-center">
                    <StatusBadge 
                      label={lead.status} 
                      type={
                        lead.status === 'Booked' ? 'success' : 
                        lead.status === 'New' ? 'orange' : 
                        lead.status === 'Contacted' ? 'info' : 'danger'
                      } 
                    />
                  </td>
                  <td className="py-6 px-10 text-right">
                    <div className="flex items-center justify-end gap-2">
                      <button className="p-2 text-muted-text hover:text-primary transition-all"><Eye size={18} /></button>
                      <button className="p-2 text-muted-text hover:text-primary transition-all"><Edit3 size={18} /></button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>

        <div className="p-8 bg-gray-50/50 border-t border-border-soft flex items-center justify-between">
          <p className="text-sm font-bold text-muted-text">Showing 1 to 4 of 128 leads</p>
          <div className="flex items-center gap-4">
            <ChevronLeft size={20} className="text-muted-text cursor-pointer hover:text-primary transition-colors" />
            <ChevronRight size={20} className="text-muted-text cursor-pointer hover:text-primary transition-colors" />
          </div>
        </div>
      </div>

      {/* Tip Widgets Section */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div className="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft space-y-4">
          <div className="w-12 h-12 bg-primary/5 rounded-2xl flex items-center justify-center text-primary">
            <MousePointer2 size={24} />
          </div>
          <h4 className="text-lg font-black font-syne">Conversion Tip</h4>
          <p className="text-sm text-muted-text font-medium leading-relaxed">
            Follow up with leads within 24 hours to increase conversion rates by up to 40%.
          </p>
        </div>
        <div className="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft space-y-4">
          <div className="w-12 h-12 bg-info/5 rounded-2xl flex items-center justify-center text-info">
            <Target size={24} />
          </div>
          <h4 className="text-lg font-black font-syne">Lead Quality</h4>
          <p className="text-sm text-muted-text font-medium leading-relaxed">
            Your current premium package provides access to verified high-intent leads.
          </p>
        </div>
        <div className="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft space-y-4">
          <div className="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center text-muted-text">
            <HelpCircle size={24} />
          </div>
          <h4 className="text-lg font-black font-syne">Need help?</h4>
          <p className="text-sm text-muted-text font-medium leading-relaxed">
            Contact your dedicated account manager for lead management strategies.
          </p>
        </div>
      </div>
    </div>
  );
};

export default Leads;
