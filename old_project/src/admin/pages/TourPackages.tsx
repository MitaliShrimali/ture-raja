"use client";

import React from "react";
import { Plus, Search, Edit3, Trash2, Eye, MapPin, Calendar, Clock } from "lucide-react";
import StatusBadge from "../components/common/StatusBadge";

const packages = [
  { id: "01", title: "Bali Tropical Paradise", location: "Indonesia", price: "₹1,200", duration: "7 Days", status: "Active", stock: "12 Left" },
  { id: "02", title: "Swiss Alps Adventure", location: "Switzerland", price: "₹2,500", duration: "10 Days", status: "Active", stock: "05 Left" },
  { id: "03", title: "Goa Beach Retreat", location: "India", price: "₹450", duration: "4 Days", status: "Draft", stock: "20 Left" },
  { id: "04", title: "Dubai Luxury Escape", location: "UAE", price: "₹3,100", duration: "6 Days", status: "Active", stock: "08 Left" },
];

const TourPackages = () => {
  return (
    <div className="space-y-10 pb-12">
      <div className="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div className="space-y-2">
          <p className="text-xs font-bold text-primary uppercase tracking-widest">Subscription Oversight</p>
          <h1 className="text-5xl font-black font-syne text-foreground tracking-tight">Tour Packages</h1>
          <p className="text-muted-text font-medium">Review and approve global travel packages.</p>
        </div>
        <button className="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3">
          <Plus size={20} /> Add New Package
        </button>
      </div>

      <div className="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div className="p-8 border-b border-border-soft flex flex-col md:flex-row md:items-center justify-between gap-6">
          <div className="relative group w-full md:w-96">
            <Search className="absolute left-5 top-1/2 -translate-y-1/2 text-muted-text group-focus-within:text-primary transition-colors" size={18} />
            <input 
              type="text" 
              placeholder="Search packages by title or location..." 
              className="w-full bg-gray-50 border-none rounded-2xl py-4 pl-14 pr-6 outline-none focus:ring-2 focus:ring-primary/10 transition-all font-medium text-sm"
            />
          </div>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-left">
            <thead>
              <tr className="bg-gray-50/50">
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">ID</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">PACKAGE TITLE</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">DURATION</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">PRICE</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">STOCK</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTIONS</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-border-soft">
              {packages.map((pkg, idx) => (
                <tr key={idx} className="group hover:bg-gray-50/30 transition-colors">
                  <td className="py-6 px-8 text-sm font-bold text-muted-text opacity-60">{pkg.id}</td>
                  <td className="py-6 px-8">
                    <div className="space-y-1">
                      <p className="text-sm font-black text-foreground">{pkg.title}</p>
                      <div className="flex items-center gap-1.5 text-xs text-muted-text">
                        <MapPin size={12} className="text-primary" />
                        <span>{pkg.location}</span>
                      </div>
                    </div>
                  </td>
                  <td className="py-6 px-8">
                    <div className="flex items-center gap-2 text-sm font-bold text-foreground">
                      <Clock size={14} className="text-muted-text" />
                      {pkg.duration}
                    </div>
                  </td>
                  <td className="py-6 px-8 text-sm font-black text-foreground">{pkg.price}</td>
                  <td className="py-6 px-8 text-sm font-bold text-orange-500">{pkg.stock}</td>
                  <td className="py-6 px-8">
                    <StatusBadge label={pkg.status} type={pkg.status === "Active" ? "success" : "neutral"} />
                  </td>
                  <td className="py-6 px-8">
                    <div className="flex items-center justify-end gap-2">
                      <button className="p-2.5 text-muted-text hover:text-primary hover:bg-primary/5 rounded-xl transition-all">
                        <Eye size={18} />
                      </button>
                      <button className="p-2.5 text-muted-text hover:text-primary hover:bg-primary/5 rounded-xl transition-all">
                        <Edit3 size={18} />
                      </button>
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

export default TourPackages;
