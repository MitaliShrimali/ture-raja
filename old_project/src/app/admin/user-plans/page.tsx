"use client";

import React from "react";
import { Search, Filter, RefreshCw, XCircle } from "lucide-react";
import StatusBadge from "@/admin/components/common/StatusBadge";

const userPlans = [
  { sr: "01", user: "Nomad Ventures", plan: "Premium Tier", startDate: "Oct 01, 2024", endDate: "Nov 01, 2024", status: "Active" },
  { sr: "02", user: "Azure Horizons", plan: "Standard Tier", startDate: "Sep 15, 2024", endDate: "Oct 15, 2024", status: "Expired" },
  { sr: "03", user: "Globe Trotters", plan: "Premium Tier", startDate: "Oct 20, 2024", endDate: "Nov 20, 2024", status: "Active" },
];

export default function UserPlansPage() {
  return (
    <div className="space-y-10 pb-12 max-w-7xl mx-auto">
      <div className="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div className="space-y-2">
          <h1 className="text-5xl font-black font-syne text-foreground tracking-tight">User Plans</h1>
          <p className="text-muted-text font-medium">Monitor active subscriptions and renewals across the platform.</p>
        </div>
      </div>

      <div className="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div className="p-8 border-b border-border-soft flex items-center justify-between">
          <div className="relative group w-full md:w-96">
            <Search className="absolute left-5 top-1/2 -translate-y-1/2 text-muted-text" size={18} />
            <input type="text" placeholder="Search by user or plan..." className="w-full bg-gray-50 rounded-2xl py-4 pl-14 pr-6 outline-none text-sm font-bold" />
          </div>
          <button className="flex items-center gap-2 px-6 py-3 bg-gray-50 rounded-xl text-[10px] font-black text-muted-text uppercase tracking-widest hover:bg-gray-100 transition-all">
            <Filter size={16} /> Filters
          </button>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-left">
            <thead>
              <tr className="bg-gray-50/50">
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">SR. NO</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">USER / AGENCY</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">PLAN NAME</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">START DATE</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">END DATE</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTION</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-border-soft">
              {userPlans.map((sub, idx) => (
                <tr key={idx} className="group hover:bg-gray-50/30">
                  <td className="py-6 px-10 text-sm font-bold text-muted-text opacity-40">{sub.sr}</td>
                  <td className="py-6 px-10 text-sm font-black text-foreground">{sub.user}</td>
                  <td className="py-6 px-10 text-sm font-bold text-primary">{sub.plan}</td>
                  <td className="py-6 px-10 text-sm font-medium text-muted-text">{sub.startDate}</td>
                  <td className="py-6 px-10 text-sm font-medium text-muted-text">{sub.endDate}</td>
                  <td className="py-6 px-10"><StatusBadge label={sub.status} type={sub.status === 'Active' ? 'success' : 'danger'} /></td>
                  <td className="py-6 px-10 text-right">
                    <div className="flex items-center justify-end gap-2">
                      <button className="p-2 text-muted-text hover:text-success" title="Renew"><RefreshCw size={18} /></button>
                      <button className="p-2 text-muted-text hover:text-danger" title="Cancel"><XCircle size={18} /></button>
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
}
