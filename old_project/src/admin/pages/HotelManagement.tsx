"use client";

import React from "react";
import { Plus, Building2, MapPin, Star, Edit3, Trash2, Search, Filter } from "lucide-react";
import StatusBadge from "../components/common/StatusBadge";

const hotels = [
  { sr: "01", name: "The Grand Palace", location: "Jaipur, India", category: "Luxury Resort", rating: 5, status: "Published" },
  { sr: "02", name: "Alpine View Inn", location: "Zermatt, Switzerland", category: "Boutique Hotel", rating: 4, status: "Published" },
  { sr: "03", name: "Coastal Sands Resort", location: "Goa, India", category: "Beachfront", rating: 4, status: "Draft" },
  { sr: "04", name: "Desert Rose Oasis", location: "Dubai, UAE", category: "Ultra Luxury", rating: 5, status: "Published" },
];

const HotelManagement = () => {
  return (
    <div className="space-y-10 pb-12">
      <div className="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div className="space-y-2">
          <h1 className="text-5xl font-black font-syne text-foreground tracking-tight">Hotel Management</h1>
          <p className="text-muted-text font-medium">Manage partner properties, availability, and premium stays.</p>
        </div>
        <button className="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3">
          <Plus size={20} /> Add New Hotel
        </button>
      </div>

      <div className="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div className="p-8 border-b border-border-soft flex flex-col md:flex-row md:items-center justify-between gap-6">
          <div className="relative group w-full md:w-96">
            <Search className="absolute left-5 top-1/2 -translate-y-1/2 text-muted-text" size={18} />
            <input type="text" placeholder="Search hotels by name or location..." className="w-full bg-gray-50 border-none rounded-2xl py-4 pl-14 pr-6 outline-none text-sm font-bold" />
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
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">HOTEL NAME & CATEGORY</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">LOCATION</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">RATING</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTION</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-border-soft">
              {hotels.map((hotel, idx) => (
                <tr key={idx} className="group hover:bg-gray-50/30 transition-colors">
                  <td className="py-6 px-10 text-sm font-bold text-muted-text opacity-40">{hotel.sr}</td>
                  <td className="py-6 px-10">
                    <div className="space-y-1">
                      <p className="text-sm font-black text-foreground">{hotel.name}</p>
                      <p className="text-[10px] font-bold text-primary uppercase tracking-widest">{hotel.category}</p>
                    </div>
                  </td>
                  <td className="py-6 px-10">
                    <div className="flex items-center gap-1.5 text-sm font-medium text-muted-text">
                      <MapPin size={14} className="text-muted-text/60" />
                      {hotel.location}
                    </div>
                  </td>
                  <td className="py-6 px-10">
                    <div className="flex items-center gap-1">
                      {Array.from({ length: 5 }).map((_, i) => (
                        <Star key={i} size={12} className={i < hotel.rating ? "text-yellow-400 fill-yellow-400" : "text-gray-200"} />
                      ))}
                    </div>
                  </td>
                  <td className="py-6 px-10">
                    <StatusBadge label={hotel.status} type={hotel.status === 'Published' ? 'success' : 'neutral'} />
                  </td>
                  <td className="py-6 px-10 text-right">
                    <div className="flex items-center justify-end gap-2">
                      <button className="p-2.5 text-muted-text hover:text-primary hover:bg-primary/5 rounded-xl transition-all"><Edit3 size={18} /></button>
                      <button className="p-2.5 text-muted-text hover:text-danger hover:bg-danger/5 rounded-xl transition-all"><Trash2 size={18} /></button>
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

export default HotelManagement;
