"use client";

import React from "react";
import MetricCard from "../components/cards/MetricCard";
import { 
  BarChart3, 
  Users, 
  Package, 
  Globe, 
  ArrowUpRight, 
  CheckCircle2, 
  Clock, 
  ExternalLink 
} from "lucide-react";
import dashboardData from "../data/adminDashboard.json";
import { motion } from "framer-motion";

const Dashboard = () => {
  const { metrics, recentActivities } = dashboardData;

  return (
    <div className="space-y-10 pb-12">
      {/* Hero Header */}
      <section className="space-y-2">
        <h1 className="text-5xl font-black font-syne text-foreground tracking-tight">
          Global Command Center
        </h1>
        <p className="text-lg text-muted-text font-medium">
          Monitoring platform performance across 12 regions • <span className="text-primary">Live Data</span>
        </p>
      </section>

      {/* Metrics Grid */}
      <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <MetricCard 
          title="Total Revenue" 
          value={metrics.totalRevenue} 
          growth={metrics.revenueGrowth}
          icon={<BarChart3 size={16} />}
        />
        <MetricCard 
          title="Verified Agents" 
          value={metrics.activeAgents} 
          growth={metrics.agentGrowth}
          icon={<Users size={16} />}
          color="#3B82F6"
        />
        <MetricCard 
          title="Active Packages" 
          value={metrics.activePackages} 
          growth={metrics.packageGrowth}
          icon={<Package size={16} />}
          color="#10B981"
        />
        <MetricCard 
          title="Total Subscribers" 
          value={metrics.totalSubscribers} 
          growth={metrics.subscriberGrowth}
          icon={<Globe size={16} />}
          color="#F59E0B"
        />
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {/* Subscription Oversight Table Mock */}
        <div className="lg:col-span-2 bg-white rounded-[32px] shadow-soft p-8 space-y-6">
          <div className="flex items-center justify-between">
            <div>
              <h3 className="text-2xl font-black font-syne text-foreground">Recent Subscriptions</h3>
              <p className="text-sm text-muted-text font-medium">Tracking the latest 10 premium activations</p>
            </div>
            <button className="flex items-center gap-2 text-xs font-bold text-primary uppercase tracking-widest hover:gap-3 transition-all">
              View All <ArrowUpRight size={14} />
            </button>
          </div>

          <div className="overflow-x-auto">
            <table className="w-full text-left">
              <thead>
                <tr className="border-b border-border-soft">
                  <th className="pb-4 text-[10px] font-black text-muted-text uppercase tracking-widest">User / Agent</th>
                  <th className="pb-4 text-[10px] font-black text-muted-text uppercase tracking-widest">Plan Type</th>
                  <th className="pb-4 text-[10px] font-black text-muted-text uppercase tracking-widest">Status</th>
                  <th className="pb-4 text-[10px] font-black text-muted-text uppercase tracking-widest">Amount</th>
                  <th className="pb-4 text-[10px] font-black text-muted-text uppercase tracking-widest">Date</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-border-soft">
                {[1, 2, 3, 4, 5].map((i) => (
                  <tr key={i} className="group hover:bg-gray-50/50 transition-colors">
                    <td className="py-5">
                      <div className="flex items-center gap-3">
                        <div className="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center font-bold text-muted-text">
                          {String.fromCharCode(64 + i)}
                        </div>
                        <div>
                          <p className="text-sm font-bold text-foreground">User_{i}</p>
                          <p className="text-[10px] text-muted-text font-medium">user{i}@example.com</p>
                        </div>
                      </div>
                    </td>
                    <td className="py-5">
                      <span className="px-3 py-1 rounded-full bg-primary/5 text-primary text-[10px] font-black uppercase tracking-wider">
                        Premium Plus
                      </span>
                    </td>
                    <td className="py-5">
                      <div className="flex items-center gap-2 text-success">
                        <CheckCircle2 size={14} />
                        <span className="text-xs font-bold">Active</span>
                      </div>
                    </td>
                    <td className="py-5">
                      <p className="text-sm font-bold text-foreground">₹199.00</p>
                    </td>
                    <td className="py-5">
                      <p className="text-xs text-muted-text font-medium">Oct 24, 2024</p>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>

        {/* Global Activity Feed */}
        <div className="bg-white rounded-[32px] shadow-soft p-8 space-y-8 h-fit">
          <div>
            <h3 className="text-2xl font-black font-syne text-foreground">Live Activity</h3>
            <p className="text-sm text-muted-text font-medium">Real-time platform updates</p>
          </div>

          <div className="space-y-6">
            {recentActivities.map((activity, idx) => (
              <div key={idx} className="flex gap-4 relative">
                {idx !== recentActivities.length - 1 && (
                  <div className="absolute left-5 top-10 bottom-0 w-[1px] bg-border-soft" />
                )}
                <div className={`shrink-0 w-10 h-10 rounded-xl flex items-center justify-center shadow-sm ${
                  activity.status === 'completed' ? 'bg-success/10 text-success' : 'bg-warning/10 text-warning'
                }`}>
                  {activity.status === 'completed' ? <CheckCircle2 size={20} /> : <Clock size={20} />}
                </div>
                <div className="space-y-1">
                  <p className="text-sm font-bold text-foreground leading-snug">
                    {activity.user} <span className="font-medium text-muted-text">{activity.action}</span>
                  </p>
                  <p className="text-[10px] font-bold text-muted-text uppercase tracking-widest opacity-60">
                    {activity.time}
                  </p>
                </div>
              </div>
            ))}
          </div>

          <button className="w-full py-4 rounded-2xl bg-gray-50 text-xs font-black text-muted-text uppercase tracking-widest hover:bg-gray-100 transition-colors flex items-center justify-center gap-2">
            View All Reports <ExternalLink size={14} />
          </button>

          {/* Micro-element Badge */}
          <div className="p-4 rounded-2xl bg-primary/5 border border-primary/10">
            <p className="text-xs font-bold text-primary leading-relaxed">
              ✨ <span className="uppercase tracking-tighter">Growth Tip:</span> Premium leads convert 2x faster than standard inquiries.
            </p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Dashboard;
