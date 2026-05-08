"use client";

import React from "react";
import { Plus, Edit3, Trash2 } from "lucide-react";
import StatusBadge from "@/admin/components/common/StatusBadge";

const plans = [
  { id: "PLN-101", name: "Standard Tier", price: "₹49/mo", duration: "1 Month", status: "Active" },
  { id: "PLN-102", name: "Premium Tier", price: "₹99/mo", duration: "1 Month", status: "Active" },
  { id: "PLN-103", name: "Enterprise Tier", price: "₹299/mo", duration: "1 Month", status: "Draft" },
];

export default function PlansPage() {
  return (
    <div className="space-y-10 pb-12 max-w-7xl mx-auto">
      <div className="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div className="space-y-2">
          <h1 className="text-5xl font-black font-syne text-foreground tracking-tight">Subscription Plans</h1>
          <p className="text-muted-text font-medium">Manage pricing tiers and subscription options for agents.</p>
        </div>
        <button className="bg-primary text-white px-8 py-4 rounded-2xl font-black text-sm shadow-xl flex items-center gap-3">
          <Plus size={20} /> Add New Plan
        </button>
      </div>

      <div className="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left">
            <thead>
              <tr className="bg-gray-50/50">
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">PLAN ID</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">NAME</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">PRICE</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">DURATION</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTION</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-border-soft">
              {plans.map((plan, idx) => (
                <tr key={idx} className="group hover:bg-gray-50/30">
                  <td className="py-6 px-10 text-sm font-bold text-muted-text opacity-40">{plan.id}</td>
                  <td className="py-6 px-10 text-sm font-black text-foreground">{plan.name}</td>
                  <td className="py-6 px-10 text-sm font-bold text-primary">{plan.price}</td>
                  <td className="py-6 px-10 text-sm font-medium text-muted-text">{plan.duration}</td>
                  <td className="py-6 px-10"><StatusBadge label={plan.status} type={plan.status === 'Active' ? 'success' : 'neutral'} /></td>
                  <td className="py-6 px-10 text-right">
                    <div className="flex items-center justify-end gap-2">
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
}
