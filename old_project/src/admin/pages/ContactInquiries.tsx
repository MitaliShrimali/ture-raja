"use client";

import React from "react";
import { MessageSquare, Clock, CheckCircle, Download, Search, Filter, Mail, Phone, MoreHorizontal } from "lucide-react";
import StatusBadge from "../components/common/StatusBadge";

const inquiries = [
  { sr: "01", name: "Ankit Sharma", email: "ankit.s@gmail.com", phone: "+91 98765 43210", subject: "Custom Package Query", status: "Pending", time: "2h ago" },
  { sr: "02", name: "Priya Patel", email: "priya.p@outlook.com", phone: "+91 91234 56789", subject: "Refund Request", status: "Replied", time: "5h ago" },
  { sr: "03", name: "Rahul Verma", email: "rahul.v@yahoo.com", phone: "+91 99988 77766", subject: "Agent Registration", status: "Closed", time: "1d ago" },
  { sr: "04", name: "Sneha Reddy", email: "sneha.r@gmail.com", phone: "+91 94433 22110", subject: "Hotel Availability", status: "Pending", time: "3d ago" },
];

const ContactInquiries = () => {
  return (
    <div className="space-y-10 pb-12">
      <div className="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div className="space-y-2">
          <h1 className="text-5xl font-black font-syne text-foreground tracking-tight">Contact Inquiries</h1>
          <p className="text-muted-text font-medium">Manage traveler queries and support tickets efficiently.</p>
        </div>
        <button className="bg-foreground text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl flex items-center gap-3">
          <Download size={20} /> Download Inquiry Report
        </button>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div className="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft flex items-center gap-6">
          <div className="w-16 h-16 bg-orange-50 rounded-3xl flex items-center justify-center text-primary">
            <MessageSquare size={32} />
          </div>
          <div>
            <p className="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Pending Response</p>
            <h4 className="text-3xl font-black font-syne text-foreground tracking-tight">24</h4>
            <p className="text-[10px] text-danger font-bold uppercase mt-1">High priority tickets</p>
          </div>
        </div>
        <div className="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft flex items-center gap-6">
          <div className="w-16 h-16 bg-blue-50 rounded-3xl flex items-center justify-center text-blue-500">
            <Clock size={32} />
          </div>
          <div>
            <p className="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Avg. Response Time</p>
            <h4 className="text-3xl font-black font-syne text-foreground tracking-tight">4.2h</h4>
            <p className="text-[10px] text-success font-bold uppercase mt-1">Faster than last week</p>
          </div>
        </div>
        <div className="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft flex items-center gap-6">
          <div className="w-16 h-16 bg-green-50 rounded-3xl flex items-center justify-center text-success">
            <CheckCircle size={32} />
          </div>
          <div>
            <p className="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Resolved Today</p>
            <h4 className="text-3xl font-black font-syne text-foreground tracking-tight">18</h4>
            <p className="text-[10px] text-muted-text font-bold uppercase mt-1">86% satisfaction rate</p>
          </div>
        </div>
      </div>

      {/* Inquiries Table */}
      <div className="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div className="p-8 border-b border-border-soft flex items-center justify-between">
          <h3 className="text-xl font-black font-syne">Recent Inquiries</h3>
          <div className="flex items-center gap-4">
            <div className="relative group">
              <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-muted-text" size={16} />
              <input type="text" placeholder="Search by name or email..." className="bg-gray-100 rounded-xl py-2.5 pl-12 pr-6 outline-none text-xs font-bold" />
            </div>
            <button className="p-2.5 text-muted-text hover:text-foreground"><Filter size={20} /></button>
          </div>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-left">
            <thead>
              <tr className="bg-gray-50/50">
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">SR. NO</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">CUSTOMER</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">SUBJECT</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">RECEIVED</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest">STATUS</th>
                <th className="py-6 px-10 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">ACTION</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-border-soft">
              {inquiries.map((inq, idx) => (
                <tr key={idx} className="group hover:bg-gray-50/30 transition-colors">
                  <td className="py-6 px-10 text-sm font-bold text-muted-text opacity-40">{inq.sr}</td>
                  <td className="py-6 px-10">
                    <div className="space-y-1">
                      <p className="text-sm font-black text-foreground">{inq.name}</p>
                      <div className="flex items-center gap-3 text-[10px] text-muted-text font-medium">
                        <span className="flex items-center gap-1"><Mail size={10} /> {inq.email}</span>
                        <span className="flex items-center gap-1"><Phone size={10} /> {inq.phone}</span>
                      </div>
                    </div>
                  </td>
                  <td className="py-6 px-10 text-sm font-bold text-foreground">{inq.subject}</td>
                  <td className="py-6 px-10 text-sm font-medium text-muted-text">{inq.time}</td>
                  <td className="py-6 px-10">
                    <StatusBadge label={inq.status} type={inq.status === 'Pending' ? 'warning' : inq.status === 'Replied' ? 'success' : 'neutral'} />
                  </td>
                  <td className="py-6 px-10 text-right">
                    <button className="p-2 text-muted-text hover:text-primary"><MoreHorizontal size={18} /></button>
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

export default ContactInquiries;
