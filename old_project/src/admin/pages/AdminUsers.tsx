"use client";

import React from "react";
import { Plus, Search, Edit3, Trash2, ChevronLeft, ChevronRight } from "lucide-react";
import StatusBadge from "../components/common/StatusBadge";
import MetricCard from "../components/cards/MetricCard";
import { motion } from "framer-motion";

const users = [
  { sr: "01", name: "Rian Jatmiko", email: "rian_j@tourraja.id", role: "SUPER ADMIN", initials: "RJ", color: "bg-orange-100 text-orange-600" },
  { sr: "02", name: "Siti Wahyuni", email: "siti.w@tourraja.id", role: "MANAGER", initials: "SW", color: "bg-blue-100 text-blue-600" },
  { sr: "03", name: "Budi Antoro", email: "budi.a@tourraja.id", role: "EDITOR", initials: "BA", color: "bg-green-100 text-green-600" },
  { sr: "04", name: "Dewi Anggraeni", email: "dewi.a@tourraja.id", role: "EDITOR", initials: "DA", color: "bg-purple-100 text-purple-600" },
  { sr: "05", name: "Hendra Rusli", email: "hendra.r@tourraja.id", role: "MANAGER", initials: "HR", color: "bg-pink-100 text-pink-600" },
];

const AdminUsers = () => {
  return (
    <div className="space-y-10 pb-12">
      {/* Header */}
      <div className="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div className="space-y-2">
          <p className="text-xs font-bold text-primary uppercase tracking-widest">Admin / User Management</p>
          <h1 className="text-5xl font-black font-syne text-foreground tracking-tight">Admin User</h1>
          <p className="text-muted-text font-medium">Manage and delegate access to your platform team.</p>
        </div>
        <button className="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3 group">
          <Plus size={20} className="group-hover:rotate-90 transition-transform" />
          Add Admin User
        </button>
      </div>

      {/* Main Table Card */}
      <div className="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div className="p-8 border-b border-border-soft flex flex-col md:flex-row md:items-center justify-between gap-6">
          <div className="flex items-center gap-4 text-sm font-bold text-muted-text">
            <span>Show</span>
            <select className="bg-gray-50 border-none rounded-xl px-3 py-2 outline-none focus:ring-2 focus:ring-primary/20">
              <option>10</option>
              <option>25</option>
              <option>50</option>
            </select>
            <span>entries</span>
          </div>

          <div className="relative group w-full md:w-96">
            <Search className="absolute left-5 top-1/2 -translate-y-1/2 text-muted-text group-focus-within:text-primary transition-colors" size={18} />
            <input 
              type="text" 
              placeholder="Search user by name or email..." 
              className="w-full bg-gray-50 border-none rounded-2xl py-4 pl-14 pr-6 outline-none focus:ring-2 focus:ring-primary/10 transition-all font-medium text-sm"
            />
          </div>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-left">
            <thead>
              <tr className="bg-gray-50/50">
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">SR. NO</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">NAME</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">EMAIL</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">ROLE</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTIONS</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-border-soft">
              {users.map((user, idx) => (
                <tr key={idx} className="group hover:bg-gray-50/30 transition-colors">
                  <td className="py-6 px-8 text-sm font-bold text-muted-text opacity-60">{user.sr}</td>
                  <td className="py-6 px-8">
                    <div className="flex items-center gap-4">
                      <div className={`w-10 h-10 rounded-xl ${user.color} flex items-center justify-center font-black text-xs`}>
                        {user.initials}
                      </div>
                      <span className="text-sm font-black text-foreground">{user.name}</span>
                    </div>
                  </td>
                  <td className="py-6 px-8 text-sm font-medium text-muted-text">{user.email}</td>
                  <td className="py-6 px-8">
                    <StatusBadge label={user.role} type={user.role === "SUPER ADMIN" ? "orange" : "neutral"} />
                  </td>
                  <td className="py-6 px-8">
                    <div className="flex items-center justify-end gap-2">
                      <button className="p-2.5 text-muted-text hover:text-primary hover:bg-primary/5 rounded-xl transition-all">
                        <Edit3 size={18} />
                      </button>
                      <button className="p-2.5 text-muted-text hover:text-danger hover:bg-danger/5 rounded-xl transition-all">
                        <Trash2 size={18} />
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>

        <div className="p-8 bg-gray-50/50 border-t border-border-soft flex flex-col md:flex-row items-center justify-between gap-6">
          <p className="text-sm font-bold text-muted-text">Showing 1 to 5 of 48 entries</p>
          <div className="flex items-center gap-2">
            <button className="p-2 text-muted-text hover:text-primary transition-colors"><ChevronLeft size={20} /></button>
            {[1, 2, 3, "...", 10].map((p, i) => (
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
      <div className="space-y-6">
        <h3 className="text-2xl font-black font-syne text-foreground flex items-center gap-3">
          <div className="w-1.5 h-6 bg-primary rounded-full" />
          Access Distribution
        </h3>
        <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
          <div className="bg-white p-8 rounded-[32px] shadow-soft border border-border-soft space-y-2">
            <p className="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Total Admins</p>
            <div className="flex items-end gap-3">
              <h4 className="text-4xl font-black font-syne">48</h4>
              <span className="text-xs font-bold text-success mb-1">+2 this month</span>
            </div>
          </div>
          <div className="bg-white p-8 rounded-[32px] shadow-soft border border-border-soft space-y-2">
            <p className="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Active Now</p>
            <div className="flex items-center gap-3">
              <h4 className="text-4xl font-black font-syne">12</h4>
              <div className="w-2.5 h-2.5 bg-success rounded-full animate-pulse" />
            </div>
          </div>
          <div className="bg-white p-8 rounded-[32px] shadow-soft border border-border-soft space-y-2">
            <p className="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Permissions Audit</p>
            <div className="flex items-end gap-3">
              <h4 className="text-4xl font-black font-syne">100%</h4>
              <span className="text-[10px] font-bold text-muted-text uppercase mb-1">Secure</span>
            </div>
          </div>
          <div className="bg-[#1A1A24] p-8 rounded-[32px] shadow-xl text-white space-y-4 relative overflow-hidden group">
            <div className="relative z-10 space-y-1">
              <p className="text-white/60 text-xs font-medium">Need custom roles for your expanding team?</p>
              <button className="flex items-center gap-2 text-primary font-black uppercase text-[10px] tracking-widest pt-2 group-hover:gap-3 transition-all">
                Manage Roles <ChevronRight size={14} />
              </button>
            </div>
            {/* Abstract Background Shape */}
            <div className="absolute -bottom-10 -right-10 w-32 h-32 bg-primary/20 blur-3xl rounded-full transition-transform group-hover:scale-150" />
          </div>
        </div>
      </div>
    </div>
  );
};

export default AdminUsers;
