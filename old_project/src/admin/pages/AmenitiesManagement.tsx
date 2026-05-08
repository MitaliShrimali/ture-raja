"use client";

import React from "react";
import { Plus, Wifi, Coffee, Car, Wind, Tv, Trash2, Edit3, Search } from "lucide-react";
import StatusBadge from "../components/common/StatusBadge";

const amenities = [
  { sr: "01", name: "High-Speed WiFi", icon: <Wifi size={18} />, category: "Connectivity", status: "Active" },
  { sr: "02", name: "Breakfast Included", icon: <Coffee size={18} />, category: "Dining", status: "Active" },
  { sr: "03", name: "Free Parking", icon: <Car size={18} />, category: "Transport", status: "Active" },
  { sr: "04", name: "Air Conditioning", icon: <Wind size={18} />, category: "Comfort", status: "Active" },
  { sr: "05", name: "Flat-Screen TV", icon: <Tv size={18} />, category: "Entertainment", status: "Inactive" },
];

const AmenitiesManagement = () => {
  return (
    <div className="space-y-10 pb-12 max-w-5xl mx-auto">
      <div className="flex items-center justify-between">
        <div className="space-y-2">
          <h1 className="text-5xl font-black font-syne text-foreground tracking-tight">Amenities</h1>
          <p className="text-muted-text font-medium">Manage global property features and traveler perks.</p>
        </div>
        <button className="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3">
          <Plus size={20} /> Add Amenity
        </button>
      </div>

      <div className="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left">
            <thead>
              <tr className="bg-gray-50/50">
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">SR. NO</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">ICON & NAME</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">CATEGORY</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTION</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-border-soft">
              {amenities.map((item, idx) => (
                <tr key={idx} className="group hover:bg-gray-50/30 transition-colors">
                  <td className="py-6 px-10 text-sm font-bold text-muted-text opacity-40">{item.sr}</td>
                  <td className="py-6 px-10">
                    <div className="flex items-center gap-4">
                      <div className="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-muted-text group-hover:text-primary transition-colors">
                        {item.icon}
                      </div>
                      <span className="text-sm font-black text-foreground">{item.name}</span>
                    </div>
                  </td>
                  <td className="py-6 px-10 text-sm font-bold text-muted-text">{item.category}</td>
                  <td className="py-6 px-10">
                    <StatusBadge label={item.status} type={item.status === 'Active' ? 'success' : 'neutral'} />
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

export default AmenitiesManagement;
