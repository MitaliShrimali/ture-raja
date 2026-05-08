"use client";

import React from "react";
import { Plus, Search, Filter, RotateCcw, ChevronLeft, ChevronRight, Check, X } from "lucide-react";
import StatusBadge from "../components/common/StatusBadge";
import MetricCard from "../components/cards/MetricCard";

const agents = [
  { sr: "01", name: "Nomad Ventures", email: "contact@nomadventures.com", mobile: "+91 98765 43210", guaranteed: true, plan: "Premium", pending: "03", approved: "12", status: "active" },
  { sr: "02", name: "Azure Horizons", email: "hello@azurehorizons.travel", mobile: "+91 91234 56789", guaranteed: false, plan: "Standard", pending: "08", approved: "05", status: "active" },
  { sr: "03", name: "Globe Trotters Co", email: "support@globetrotters.org", mobile: "+91 99988 77766", guaranteed: true, plan: "Premium", pending: "01", approved: "24", status: "inactive" },
  { sr: "04", name: "Alpine Escape", email: "info@alpine-escape.com", mobile: "+91 94433 22110", guaranteed: false, plan: "Standard", pending: "11", approved: "02", status: "active" },
];

const PaidUsers = () => {
  return (
    <div className="space-y-10 pb-12">
      {/* Header */}
      <div className="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div className="space-y-2">
          <p className="text-xs font-bold text-primary uppercase tracking-widest">Admin / Management</p>
          <h1 className="text-5xl font-black font-syne text-foreground tracking-tight">Paid User</h1>
        </div>
        <button className="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3">
          <Plus size={20} />
          Add User
        </button>
      </div>

      {/* Filters Section */}
      <div className="bg-white rounded-[40px] shadow-premium border border-border-soft p-10 space-y-8">
        <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
          <div className="space-y-2">
            <label className="text-[10px] font-black text-muted-text uppercase tracking-widest pl-2">From Date</label>
            <input type="date" className="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none text-sm font-bold text-foreground" />
          </div>
          <div className="space-y-2">
            <label className="text-[10px] font-black text-muted-text uppercase tracking-widest pl-2">To Date</label>
            <input type="date" className="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none text-sm font-bold text-foreground" />
          </div>
          <div className="space-y-2">
            <label className="text-[10px] font-black text-muted-text uppercase tracking-widest pl-2">Search Country</label>
            <select className="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none text-sm font-bold text-foreground">
              <option>All Countries</option>
            </select>
          </div>
          <div className="space-y-2">
            <label className="text-[10px] font-black text-muted-text uppercase tracking-widest pl-2">Search State</label>
            <select className="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none text-sm font-bold text-foreground">
              <option>Select State</option>
            </select>
          </div>
          <div className="space-y-2">
            <label className="text-[10px] font-black text-muted-text uppercase tracking-widest pl-2">Search City</label>
            <select className="w-full bg-[#F5F5F5] border-none rounded-2xl py-4 px-6 outline-none text-sm font-bold text-foreground">
              <option>Select City</option>
            </select>
          </div>
        </div>
        <div className="flex justify-end">
          <button className="flex items-center gap-2 px-8 py-3 bg-gray-200 text-muted-text rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-gray-300 transition-all">
            Reset Filters
          </button>
        </div>
      </div>

      {/* Table Card */}
      <div className="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left">
            <thead>
              <tr className="bg-gray-50/50">
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">#</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">TRAVEL AGENT NAME</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">EMAIL</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">MOBILE</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-center">GUARANTEED</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-center">PLAN</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-center text-orange-500">PENDING</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-center text-success">APPROVED</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-center">STATUS</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTIONS</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-border-soft">
              {agents.map((agent, idx) => (
                <tr key={idx} className="group hover:bg-gray-50/30 transition-colors">
                  <td className="py-6 px-8 text-sm font-bold text-muted-text opacity-60">{agent.sr}</td>
                  <td className="py-6 px-8 text-sm font-black text-primary">{agent.name}</td>
                  <td className="py-6 px-8 text-sm font-medium text-muted-text">{agent.email}</td>
                  <td className="py-6 px-8 text-sm font-bold text-foreground whitespace-nowrap">{agent.mobile}</td>
                  <td className="py-6 px-8 text-center">
                    <span className={`px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider ${agent.guaranteed ? 'bg-success/10 text-success' : 'bg-gray-100 text-muted-text opacity-50'}`}>
                      {agent.guaranteed ? 'YES' : 'NO'}
                    </span>
                  </td>
                  <td className="py-6 px-8 text-center">
                    <div className="flex items-center justify-center gap-2">
                      <div className={`w-2 h-2 rounded-full ${agent.plan === 'Premium' ? 'bg-orange-400' : 'bg-gray-300'}`} />
                      <span className="text-xs font-bold">{agent.plan}</span>
                    </div>
                  </td>
                  <td className="py-6 px-8 text-center">
                    <span className="text-lg font-black text-orange-500 font-syne">{agent.pending}</span>
                  </td>
                  <td className="py-6 px-8 text-center">
                    <span className="text-lg font-black text-success font-syne">{agent.approved}</span>
                  </td>
                  <td className="py-6 px-8 text-center">
                    <label className="relative inline-flex items-center cursor-pointer">
                      <input type="checkbox" className="sr-only peer" defaultChecked={agent.status === 'active'} />
                      <div className="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                    </label>
                  </td>
                  <td className="py-6 px-8">
                    <div className="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                      <button className="p-2 text-muted-text hover:text-primary transition-all"><Search size={18} /></button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>

        <div className="p-8 bg-gray-50/50 border-t border-border-soft flex flex-col md:flex-row items-center justify-between gap-6">
          <p className="text-sm font-bold text-muted-text">Showing 1 to 10 of 63 entries</p>
          <div className="flex items-center gap-2">
            <button className="p-2 text-muted-text hover:text-primary transition-colors"><ChevronLeft size={20} /></button>
            {[1, 2, 3, "...", 7].map((p, i) => (
              <button 
                key={i} 
                className={`w-10 h-10 rounded-full text-sm font-black transition-all ${p === 1 ? "bg-primary text-white shadow-lg shadow-primary/20" : "text-muted-text hover:bg-white hover:text-primary"}`}
              >
                {p}
              </button>
            ))}
            <button className="p-2 text-muted-text hover:text-primary transition-colors"><ChevronRight size={20} /></button>
          </div>
        </div>
      </div>

      {/* Metrics Section */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
        {[
          { label: "New Agents", value: "12", icon: "👤", color: "bg-orange-50 text-orange-600" },
          { label: "Active Plans", value: "45", icon: "🛡️", color: "bg-green-50 text-green-600" },
          { label: "Pending Approvals", value: "08", icon: "⏳", color: "bg-yellow-50 text-yellow-600" },
          { label: "Total Conversion", value: "84%", icon: "📊", color: "bg-blue-50 text-blue-600" },
        ].map((metric, i) => (
          <div key={i} className="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft flex items-center gap-6">
            <div className={`w-14 h-14 rounded-2xl ${metric.color} flex items-center justify-center text-2xl`}>
              {metric.icon}
            </div>
            <div>
              <p className="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">{metric.label}</p>
              <h4 className="text-3xl font-black font-syne text-foreground tracking-tight">{metric.value}</h4>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default PaidUsers;
