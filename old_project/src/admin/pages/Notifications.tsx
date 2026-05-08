"use client";

import React, { useState } from "react";
import { Plus, Search, Bell, Users, BarChart, Send, Filter, Download, MoreVertical, Layout, Target, Eye } from "lucide-react";
import StatusBadge from "../components/common/StatusBadge";
import ComposeNotification from "./ComposeNotification";

const dispatches = [
  { id: "NOT-9021", title: "Update: Seasonal Commission Shift", group: "All Agents", date: "Oct 24, 2023", openRate: 82, status: "Delivered" },
  { id: "NOT-8542", title: "Premium Tier Bonus Announcement", group: "Premium Only", date: "Oct 22, 2023", openRate: 95, status: "Delivered" },
  { id: "NOT-8261", title: "Maintenance Alert: Dashboard API", group: "All Agents", date: "Oct 20, 2023", openRate: 44, status: "Draft" },
  { id: "NOT-8020", title: "New Compliance Policy Requirements", group: "All Agents", date: "Oct 18, 2023", openRate: 61, status: "Sending..." },
];

const Notifications = () => {
  const [showCompose, setShowCompose] = useState(false);

  if (showCompose) {
    return (
      <div className="relative">
        <button 
          onClick={() => setShowCompose(false)}
          className="absolute top-0 right-0 z-50 p-3 bg-gray-100 rounded-2xl text-muted-text hover:text-foreground transition-all"
        >
          Close
        </button>
        <ComposeNotification />
      </div>
    );
  }

  return (
    <div className="space-y-10 pb-12">
      <div className="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div className="space-y-2">
          <h1 className="text-5xl font-black font-syne text-foreground tracking-tight">Notifications Management</h1>
          <p className="text-muted-text font-medium">Overview of communication performance and agent reach across the platform.</p>
        </div>
        <button 
          onClick={() => setShowCompose(true)}
          className="bg-[#B23B11] hover:bg-[#96320D] text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl shadow-primary/20 flex items-center gap-3 group"
        >
          <Plus size={20} className="group-hover:rotate-90 transition-transform" />
          New Notification
        </button>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div className="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft flex items-center gap-6 relative overflow-hidden group">
          <div className="w-16 h-16 bg-primary/5 rounded-3xl flex items-center justify-center text-primary relative z-10 transition-colors group-hover:bg-primary group-hover:text-white">
            <Send size={28} />
          </div>
          <div className="relative z-10">
            <div className="flex items-center gap-2">
              <p className="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Total Sent</p>
              <span className="text-[10px] font-bold text-success">+12.5%</span>
            </div>
            <h4 className="text-3xl font-black font-syne text-foreground tracking-tight">2,842</h4>
            <p className="text-[10px] text-muted-text font-medium uppercase mt-1">Lifetime platform broadcasts</p>
          </div>
        </div>
        <div className="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft flex items-center gap-6 relative overflow-hidden group">
          <div className="w-16 h-16 bg-blue-50 rounded-3xl flex items-center justify-center text-blue-500 relative z-10 transition-colors group-hover:bg-blue-500 group-hover:text-white">
            <Bell size={28} />
          </div>
          <div className="relative z-10">
            <div className="flex items-center gap-2">
              <p className="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Active Alerts</p>
              <span className="text-[10px] font-bold text-orange-500">High Priority</span>
            </div>
            <h4 className="text-3xl font-black font-syne text-foreground tracking-tight">14</h4>
            <p className="text-[10px] text-muted-text font-medium uppercase mt-1">System-wide critical updates</p>
          </div>
        </div>
        <div className="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft flex items-center gap-6 relative overflow-hidden group">
          <div className="w-16 h-16 bg-gray-50 rounded-3xl flex items-center justify-center text-muted-text relative z-10 transition-colors group-hover:bg-foreground group-hover:text-white">
            <Users size={28} />
          </div>
          <div className="relative z-10">
            <div className="flex items-center gap-2">
              <p className="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Agent Reach</p>
              <span className="text-[10px] font-bold text-muted-text">98% Coverage</span>
            </div>
            <h4 className="text-3xl font-black font-syne text-foreground tracking-tight">856</h4>
            <p className="text-[10px] text-muted-text font-medium uppercase mt-1">Active agents currently reached</p>
          </div>
        </div>
      </div>

      {/* Dispatches Table */}
      <div className="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div className="p-8 border-b border-border-soft flex items-center justify-between">
          <h3 className="text-xl font-black font-syne">Recent Dispatches</h3>
          <div className="flex items-center gap-3">
            <button className="flex items-center gap-2 px-6 py-2.5 bg-gray-50 rounded-xl text-[10px] font-black text-muted-text uppercase tracking-widest hover:bg-gray-100 transition-all">
              Filter
            </button>
            <button className="flex items-center gap-2 px-6 py-2.5 bg-gray-50 rounded-xl text-[10px] font-black text-muted-text uppercase tracking-widest hover:bg-gray-100 transition-all">
              Export CSV
            </button>
          </div>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-left">
            <thead>
              <tr className="bg-gray-50/50 border-b border-border-soft">
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">NOTIFICATION TITLE</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">TARGET GROUP</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">DATE SENT</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">OPEN RATE</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest"></th>
              </tr>
            </thead>
            <tbody className="divide-y divide-border-soft">
              {dispatches.map((dispatch, idx) => (
                <tr key={idx} className="group hover:bg-gray-50/30 transition-colors">
                  <td className="py-8 px-10">
                    <div className="space-y-1">
                      <p className="text-sm font-black text-foreground group-hover:text-primary transition-colors">{dispatch.title}</p>
                      <p className="text-[10px] font-bold text-muted-text uppercase tracking-tighter">ID: {dispatch.id}</p>
                    </div>
                  </td>
                  <td className="py-8 px-10">
                    <span className="px-4 py-1.5 bg-[#F5F5F5] rounded-full text-[10px] font-black text-muted-text uppercase">
                      {dispatch.group}
                    </span>
                  </td>
                  <td className="py-8 px-10">
                    <p className="text-xs font-bold text-muted-text leading-tight">{dispatch.date}</p>
                    <p className="text-[10px] font-medium text-muted-text/60 mt-1">09:45 AM</p>
                  </td>
                  <td className="py-8 px-10">
                    <div className="flex items-center gap-4">
                      <div className="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden w-24">
                        <div className="h-full bg-[#B23B11] rounded-full" style={{ width: `${dispatch.openRate}%` }} />
                      </div>
                      <span className="text-xs font-black text-foreground">{dispatch.openRate}%</span>
                    </div>
                  </td>
                  <td className="py-8 px-10">
                    <StatusBadge label={dispatch.status} type={dispatch.status === 'Delivered' ? 'success' : dispatch.status === 'Draft' ? 'neutral' : 'orange'} />
                  </td>
                  <td className="py-8 px-10 text-right">
                    <button className="p-2 text-muted-text hover:text-foreground"><MoreVertical size={18} /></button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {/* Engagement Insights */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div className="bg-white p-10 rounded-[40px] shadow-premium border border-border-soft space-y-6">
          <h4 className="text-2xl font-black font-syne text-foreground">Engagement Insights</h4>
          <p className="text-sm text-muted-text font-medium leading-relaxed">
            Notifications sent during early morning (08:00 - 10:00) see a 15% higher open rate among registered agents. Consider scheduling critical policy updates during this window for maximum visibility.
          </p>
          <div className="grid grid-cols-2 gap-6 pt-4">
            <div className="flex items-center gap-4">
              <div className="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-primary">
                <BarChart size={20} />
              </div>
              <div>
                <p className="text-[10px] font-black text-muted-text uppercase">Highest Open Rate</p>
                <p className="text-xs font-bold text-foreground">Tuesday Mornings</p>
              </div>
            </div>
            <div className="flex items-center gap-4">
              <div className="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500">
                <Layout size={20} />
              </div>
              <div>
                <p className="text-[10px] font-black text-muted-text uppercase">Device Split</p>
                <p className="text-xs font-bold text-foreground">72% Mobile App</p>
              </div>
            </div>
          </div>
        </div>

        <div className="bg-[#1A1A24] p-10 rounded-[40px] shadow-2xl text-white relative overflow-hidden group">
          <div className="relative z-10 h-full flex flex-col justify-between">
            <div className="space-y-4">
              <div className="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center">
                <Target size={28} className="text-primary" />
              </div>
              <h4 className="text-3xl font-black font-syne leading-tight">Advanced Audience <br />Segmentation</h4>
              <p className="text-white/60 text-sm font-medium max-w-sm">
                Target specific tiers, regions, or activity levels to deliver more relevant updates.
              </p>
            </div>
            <button className="w-fit flex items-center gap-3 px-8 py-4 bg-white text-foreground rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-primary hover:text-white transition-all">
              <Eye size={18} /> View Full Analytics Report
            </button>
          </div>
          {/* Abstract Image Placeholder Replacement */}
          <div className="absolute right-0 top-0 w-1/2 h-full bg-gradient-to-l from-primary/10 to-transparent" />
        </div>
      </div>
    </div>
  );
};

export default Notifications;
