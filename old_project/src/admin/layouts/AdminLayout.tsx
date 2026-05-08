"use client";

import React, { useState } from "react";
import AdminSidebar from "../components/layout/AdminSidebar";
import AdminHeader from "../components/layout/AdminHeader";
import { motion, AnimatePresence } from "framer-motion";

interface AdminLayoutProps {
  children: React.ReactNode;
}

const AdminLayout: React.FC<AdminLayoutProps> = ({ children }) => {
  const [isMobileSidebarOpen, setIsMobileSidebarOpen] = useState(false);

  return (
    <div className="min-h-screen bg-background font-dm-sans">
      {/* Sidebar for Desktop */}
      <div className="hidden lg:block fixed left-0 top-0 h-screen w-72 z-50">
        <AdminSidebar />
      </div>

      {/* Mobile Sidebar Overlay */}
      <AnimatePresence>
        {isMobileSidebarOpen && (
          <>
            <motion.div 
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              exit={{ opacity: 0 }}
              onClick={() => setIsMobileSidebarOpen(false)}
              className="fixed inset-0 bg-black/40 backdrop-blur-sm z-[60] lg:hidden"
            />
            <motion.div 
              initial={{ x: "-100%" }}
              animate={{ x: 0 }}
              exit={{ x: "-100%" }}
              transition={{ type: "spring", damping: 25, stiffness: 200 }}
              className="fixed inset-y-0 left-0 w-72 z-[70] lg:hidden"
            >
              <AdminSidebar />
            </motion.div>
          </>
        )}
      </AnimatePresence>

      <div className="lg:pl-72 flex flex-col min-h-screen">
        <AdminHeader onMenuClick={() => setIsMobileSidebarOpen(true)} />
        
        <main className="flex-1 p-8 overflow-x-hidden">
          <motion.div
            initial={{ opacity: 0, y: 10 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.4 }}
          >
            {children}
          </motion.div>
        </main>

        <footer className="px-8 py-6 border-t border-border-soft flex flex-col sm:flex-row items-center justify-between text-xs font-bold text-muted-text uppercase tracking-widest gap-4">
          <p>© 2026 TOURRAJA SUPER ADMIN PANEL</p>
          <div className="flex items-center gap-6">
            <a href="#" className="hover:text-primary transition-colors">Privacy Policy</a>
            <a href="#" className="hover:text-primary transition-colors">Terms of Service</a>
            <a href="#" className="hover:text-primary transition-colors">Support Center</a>
          </div>
        </footer>
      </div>
    </div>
  );
};

export default AdminLayout;
