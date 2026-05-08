"use client";

import React from "react";
import { Search, Bell, Settings, Info, Menu } from "lucide-react";

interface AdminHeaderProps {
  onMenuClick?: () => void;
  title?: string;
}

const AdminHeader: React.FC<AdminHeaderProps> = ({ onMenuClick, title = "Dashboard" }) => {
  return (
    <header className="h-24 bg-white/80 backdrop-blur-md border-b border-border-soft flex items-center justify-between px-8 sticky top-0 z-40">
      <div className="flex items-center gap-6">
        <button 
          onClick={onMenuClick}
          className="lg:hidden p-2 hover:bg-gray-100 rounded-xl transition-colors"
        >
          <Menu size={24} />
        </button>
        
        <div className="hidden md:flex flex-col">
          <p className="text-[10px] font-bold text-muted-text uppercase tracking-widest">Platform Admin</p>
          <h2 className="text-xl font-black font-syne text-foreground">{title}</h2>
        </div>
      </div>

      <div className="flex items-center gap-8">
        {/* Search Bar */}
        <div className="hidden lg:flex items-center gap-3 bg-[#F5F5F5] px-5 py-3 rounded-full w-80 border border-transparent focus-within:border-primary/20 transition-all">
          <Search size={18} className="text-muted-text" />
          <input 
            type="text" 
            placeholder="Search leads, packages, agents..." 
            className="bg-transparent border-none outline-none text-sm font-medium w-full placeholder:text-muted-text/50"
          />
        </div>

        {/* Icons Group */}
        <div className="flex items-center gap-2">
          <button className="p-3 text-muted-text hover:text-primary hover:bg-primary/5 rounded-full transition-all relative">
            <Bell size={20} />
            <span className="absolute top-2.5 right-2.5 w-2 h-2 bg-primary rounded-full border-2 border-white" />
          </button>
          <button className="p-3 text-muted-text hover:text-primary hover:bg-primary/5 rounded-full transition-all">
            <Settings size={20} />
          </button>
          <button className="p-3 text-muted-text hover:text-primary hover:bg-primary/5 rounded-full transition-all">
            <Info size={20} />
          </button>
        </div>

        {/* User Profile */}
        <div className="flex items-center gap-4 pl-4 border-l border-border-soft">
          <div className="text-right hidden sm:block">
            <p className="text-sm font-bold text-foreground leading-none">Super Admin</p>
            <p className="text-[10px] font-bold text-primary uppercase tracking-tighter mt-1">TourRaja HQ</p>
          </div>
          <div className="w-12 h-12 rounded-2xl bg-gradient-to-tr from-primary to-orange-400 p-[2px] shadow-lg shadow-primary/20">
            <div className="w-full h-full rounded-[14px] bg-white p-1 overflow-hidden">
              <img 
                src="https://api.dicebear.com/7.x/avataaars/svg?seed=Admin" 
                alt="Avatar" 
                className="w-full h-full object-cover rounded-xl"
              />
            </div>
          </div>
        </div>
      </div>
    </header>
  );
};

export default AdminHeader;
