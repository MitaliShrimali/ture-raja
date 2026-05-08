"use client";

import React from "react";
import { Plus, Umbrella, Mountain, Tent, Ship, Edit3, Trash2, Search } from "lucide-react";
import StatusBadge from "../components/common/StatusBadge";

const holidayTypes = [
  { sr: "01", name: "Beach & Island", icon: <Umbrella size={18} />, count: "124 Packages", status: "Active" },
  { sr: "02", name: "Mountain Trekking", icon: <Mountain size={18} />, count: "86 Packages", status: "Active" },
  { sr: "03", name: "Wildlife Safari", icon: <Tent size={18} />, count: "42 Packages", status: "Active" },
  { sr: "04", name: "Cruise & Sailing", icon: <Ship size={18} />, count: "15 Packages", status: "Active" },
];

const HolidayTypes = () => {
  return (
    <div className="space-y-10 pb-12 max-w-5xl mx-auto">
      <div className="flex items-center justify-between">
        <div className="space-y-2">
          <h1 className="text-5xl font-black font-syne text-foreground tracking-tight">Holiday Types</h1>
          <p className="text-muted-text font-medium">Categorize your travel offerings for better discovery.</p>
        </div>
        <button className="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3">
          <Plus size={20} /> Add Category
        </button>
      </div>

      <div className="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left">
            <thead>
              <tr className="bg-gray-50/50">
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">SR. NO</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">CATEGORY NAME</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">INVENTORY</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTION</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-border-soft">
              {holidayTypes.map((item, idx) => (
                <tr key={idx} className="group hover:bg-gray-50/30 transition-colors">
                  <td className="py-6 px-10 text-sm font-bold text-muted-text opacity-40">{item.sr}</td>
                  <td className="py-6 px-10">
                    <div className="flex items-center gap-4">
                      <div className="w-10 h-10 bg-primary/5 rounded-xl flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                        {item.icon}
                      </div>
                      <span className="text-sm font-black text-foreground">{item.name}</span>
                    </div>
                  </td>
                  <td className="py-6 px-10 text-sm font-bold text-muted-text">{item.count}</td>
                  <td className="py-6 px-10">
                    <StatusBadge label={item.status} type="success" />
                  </td>
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
};

export default HolidayTypes;
