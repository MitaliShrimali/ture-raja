"use client";

import React from "react";
import Link from "next/link";
import { usePathname } from "next/navigation";
import { 
  LayoutDashboard, 
  UserRound, 
  Users, 
  Package, 
  CreditCard, 
  Megaphone, 
  Target, 
  ClipboardList, 
  Settings, 
  Home, 
  Bell, 
  FileText, 
  MessageSquare, 
  UserPlus,
  CloudSun,
  HelpCircle,
  LogOut,
  ChevronRight,
  Building2,
  Layout
} from "lucide-react";
import { motion } from "framer-motion";

const menuGroups = [
  {
    label: "ADMIN CENTRAL",
    items: [
      { name: "Global Dashboard", icon: LayoutDashboard, href: "/admin/dashboard/" },
      { name: "Admin User", icon: UserRound, href: "/admin/users/" },
      { name: "Agent Management", icon: Users, href: "/admin/agents/" },
      { name: "Lead Management", icon: Target, href: "/admin/leads/" },
    ]
  },
  {
    label: "INVENTORY & STAYS",
    items: [
      { name: "Hotel Management", icon: Building2, href: "/admin/hotels/" },
      { name: "Amenities", icon: ClipboardList, href: "/admin/amenities/" },
      { name: "Tour Packages", icon: Package, href: "/admin/packages/" },
      { name: "Holiday Types", icon: Layout, href: "/admin/holiday-types/" },
      { name: "Activities", icon: Target, href: "/admin/activities/" },
    ]
  },
  {
    label: "SUBSCRIPTION OVERSIGHT",
    items: [
      { name: "Paid User", icon: UserPlus, href: "/admin/paid-users/" },
      { name: "User Plan", icon: ClipboardList, href: "/admin/user-plans/" },
      { name: "Payment", icon: CreditCard, href: "/admin/payments/" },
      { name: "Advertisement", icon: Megaphone, href: "/admin/ads/" },
      { name: "Plan", icon: ClipboardList, href: "/admin/plans/" },
    ]
  },
  {
    label: "PLATFORM SETTINGS",
    items: [
      { name: "Home Page", icon: Home, href: "/admin/home-editor/" },
      { name: "Notification", icon: Bell, href: "/admin/notifications/" },
      { name: "Pages", icon: FileText, href: "/admin/cms/" },
      { name: "Contact US", icon: MessageSquare, href: "/admin/contact/" },
      { name: "Subscriber", icon: Users, href: "/admin/subscribers/" },
      { name: "Settings", icon: Settings, href: "/admin/settings/" },
    ]
  }
];

const AdminSidebar = () => {
  const pathname = usePathname();

  return (
    <aside className="w-full h-full bg-[#FDFDFD] border-r border-border-soft flex flex-col">
      {/* Logo */}
      <div className="p-8">
        <Link href="/admin/dashboard" className="flex items-center gap-2">
          <div className="w-10 h-10 bg-primary rounded-xl flex items-center justify-center shadow-lg shadow-primary/20">
            <span className="text-white font-black text-xl">TR</span>
          </div>
          <span className="text-2xl font-black font-syne tracking-tight text-foreground">
            TOURRAJA
          </span>
        </Link>
      </div>

      {/* Navigation */}
      <nav className="flex-1 overflow-y-auto px-4 py-4 space-y-8 scrollbar-hide">
        {menuGroups.map((group, idx) => (
          <div key={idx} className="space-y-2">
            <p className="px-4 text-[11px] font-bold text-muted-text uppercase tracking-widest opacity-60">
              {group.label}
            </p>
            <div className="space-y-1">
              {group.items.map((item) => {
                const isActive = pathname === item.href;
                return (
                  <Link 
                    key={item.name} 
                    href={item.href}
                    className={`
                      group flex items-center justify-between px-4 py-3 rounded-2xl transition-all duration-300
                      ${isActive 
                        ? "bg-primary text-white shadow-lg shadow-primary/20" 
                        : "text-muted-text hover:bg-gray-50 hover:text-foreground"}
                    `}
                  >
                    <div className="flex items-center gap-3">
                      <item.icon size={20} strokeWidth={isActive ? 2.5 : 2} />
                      <span className="text-sm font-bold tracking-tight">{item.name}</span>
                    </div>
                    {isActive && (
                      <motion.div 
                        layoutId="active-pill"
                        className="w-1.5 h-1.5 bg-white rounded-full"
                      />
                    )}
                  </Link>
                );
              })}
            </div>
          </div>
        ))}
      </nav>

      {/* Bottom Widgets */}
      <div className="p-4 space-y-3 mt-auto border-t border-border-soft">
        {/* Weather Widget Mock */}
        <div className="bg-gray-50 rounded-2xl p-4 flex items-center justify-between">
          <div className="flex items-center gap-3">
            <div className="p-2 bg-white rounded-xl shadow-sm">
              <CloudSun size={18} className="text-blue-500" />
            </div>
            <div>
              <p className="text-xs font-bold text-foreground">Sunny</p>
              <p className="text-[10px] text-muted-text font-medium">New Delhi, 32°C</p>
            </div>
          </div>
          <ChevronRight size={14} className="text-muted-text" />
        </div>

        <button className="w-full flex items-center gap-3 px-4 py-3 text-muted-text hover:text-foreground transition-colors text-sm font-bold">
          <HelpCircle size={20} />
          <span>Support</span>
        </button>

        <button onClick={() => window.location.href = "/tourex/admin/login/"} className="w-full flex items-center gap-3 px-4 py-3 text-danger hover:bg-danger/5 rounded-2xl transition-all text-sm font-bold">
          <LogOut size={20} />
          <span>Sign Out</span>
        </button>
      </div>
    </aside>
  );
};

export default AdminSidebar;
