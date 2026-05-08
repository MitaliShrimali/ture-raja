"use client";

import React from "react";
import { Download, CreditCard, ArrowUpRight, ArrowDownLeft, Filter } from "lucide-react";
import StatusBadge from "../components/common/StatusBadge";

const transactions = [
  { id: "TX1092", user: "Himalayan Treks", date: "Oct 24, 2024", amount: "₹4,200.00", method: "Stripe", status: "Completed", type: "in" },
  { id: "TX1091", user: "Goa Beach Travels", date: "Oct 23, 2024", amount: "₹1,500.00", method: "PayPal", status: "Completed", type: "in" },
  { id: "TX1090", user: "Refund: User_12", date: "Oct 22, 2024", amount: "₹199.00", method: "Bank Transfer", status: "Pending", type: "out" },
  { id: "TX1089", user: "Global Travels", date: "Oct 21, 2024", amount: "₹12,800.00", method: "Stripe", status: "Completed", type: "in" },
];

const FinancialReports = () => {
  return (
    <div className="space-y-10 pb-12">
      <div className="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div className="space-y-2">
          <p className="text-xs font-bold text-primary uppercase tracking-widest">Financial Reports</p>
          <h1 className="text-5xl font-black font-syne text-foreground tracking-tight">Payments</h1>
          <p className="text-muted-text font-medium">Monitoring platform revenue and transaction history.</p>
        </div>
        <button className="bg-foreground text-white px-8 py-4 rounded-2xl font-black text-sm transition-all shadow-xl flex items-center gap-3">
          <Download size={20} /> Export Reports
        </button>
      </div>

      {/* Financial Overview Metrics */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div className="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft flex items-center gap-6">
          <div className="w-16 h-16 bg-success/10 rounded-3xl flex items-center justify-center text-success">
            <ArrowUpRight size={32} />
          </div>
          <div>
            <p className="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Monthly Revenue</p>
            <h4 className="text-3xl font-black font-syne text-foreground tracking-tight">₹128,430</h4>
          </div>
        </div>
        <div className="bg-white p-8 rounded-[40px] shadow-soft border border-border-soft flex items-center gap-6">
          <div className="w-16 h-16 bg-danger/10 rounded-3xl flex items-center justify-center text-danger">
            <ArrowDownLeft size={32} />
          </div>
          <div>
            <p className="text-[10px] font-black text-muted-text uppercase tracking-widest opacity-60">Total Payouts</p>
            <h4 className="text-3xl font-black font-syne text-foreground tracking-tight">₹42,100</h4>
          </div>
        </div>
        <div className="bg-primary p-8 rounded-[40px] shadow-xl text-white flex items-center gap-6 relative overflow-hidden">
          <div className="w-16 h-16 bg-white/20 rounded-3xl flex items-center justify-center text-white relative z-10">
            <CreditCard size={32} />
          </div>
          <div className="relative z-10">
            <p className="text-white/60 text-[10px] font-black uppercase tracking-widest">Available Balance</p>
            <h4 className="text-3xl font-black font-syne tracking-tight">₹86,330</h4>
          </div>
          <div className="absolute -right-8 -top-8 w-32 h-32 bg-white/10 blur-3xl rounded-full" />
        </div>
      </div>

      <div className="bg-white rounded-[40px] shadow-premium border border-border-soft overflow-hidden">
        <div className="p-8 border-b border-border-soft flex flex-col md:flex-row md:items-center justify-between gap-6">
          <h3 className="text-xl font-black font-syne">Transaction History</h3>
          <button className="flex items-center gap-2 text-xs font-bold text-muted-text hover:text-foreground transition-all">
            <Filter size={16} /> Filters
          </button>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-left">
            <thead>
              <tr className="bg-gray-50/50">
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">TRANS ID</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">USER / AGENCY</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">DATE</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">METHOD</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest">AMOUNT</th>
                <th className="py-6 px-8 text-[10px] font-black text-muted-text uppercase tracking-widest text-right">STATUS</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-border-soft">
              {transactions.map((tx, idx) => (
                <tr key={idx} className="group hover:bg-gray-50/30 transition-colors">
                  <td className="py-6 px-8 text-sm font-bold text-primary">{tx.id}</td>
                  <td className="py-6 px-8 text-sm font-black text-foreground">{tx.user}</td>
                  <td className="py-6 px-8 text-sm font-medium text-muted-text">{tx.date}</td>
                  <td className="py-6 px-8 text-sm font-bold text-muted-text">{tx.method}</td>
                  <td className="py-6 px-8">
                    <span className={`text-sm font-black ${tx.type === 'in' ? 'text-success' : 'text-danger'}`}>
                      {tx.type === 'in' ? '+' : '-'}{tx.amount}
                    </span>
                  </td>
                  <td className="py-6 px-8 text-right">
                    <StatusBadge label={tx.status} type={tx.status === "Completed" ? "success" : "warning"} />
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

export default FinancialReports;
