"use client";

import React, { useState } from "react";
import { useRouter } from "next/navigation";
import { motion } from "framer-motion";
import { Mail, Lock, ArrowRight, ShieldCheck } from "lucide-react";
import Link from "next/link";

const AdminLogin = () => {
  const router = useRouter();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [isLoading, setIsLoading] = useState(false);
  const [error, setError] = useState("");

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);
    setError("");

    // Simulate login logic
    setTimeout(() => {
      if (email === "admin@tourraja.com" && password === "admin123") {
        router.push("/admin/dashboard");
      } else {
        setError("Invalid credentials. Use admin@tourraja.com / admin123");
        setIsLoading(false);
      }
    }, 1000);
  };

  return (
    <div className="min-h-screen bg-background flex flex-col items-center justify-center p-6 font-dm-sans">
      <motion.div 
        initial={{ opacity: 0, y: 20 }}
        animate={{ opacity: 1, y: 0 }}
        className="w-full max-w-md space-y-8"
      >
        {/* Logo Section */}
        <div className="text-center space-y-4">
          <Link href="/" className="inline-flex items-center gap-3">
            <div className="w-14 h-14 bg-primary rounded-[20px] flex items-center justify-center shadow-xl shadow-primary/30">
              <span className="text-white font-black text-2xl">TR</span>
            </div>
          </Link>
          <div className="space-y-1">
            <h1 className="text-4xl font-black font-syne text-foreground tracking-tight">
              Admin Access
            </h1>
            <p className="text-muted-text font-bold uppercase text-[10px] tracking-widest flex items-center justify-center gap-2">
              <ShieldCheck size={14} className="text-primary" />
              Secure Control Center
            </p>
          </div>
        </div>

        {/* Login Card */}
        <div className="bg-white p-10 rounded-[40px] shadow-premium border border-border-soft space-y-8">
          <form onSubmit={handleSubmit} className="space-y-6">
            <div className="space-y-2">
              <label className="text-xs font-black text-muted-text uppercase tracking-widest pl-2">
                Work Email
              </label>
              <div className="relative group">
                <div className="absolute inset-y-0 left-5 flex items-center pointer-events-none text-muted-text group-focus-within:text-primary transition-colors">
                  <Mail size={18} />
                </div>
                <input 
                  type="email" 
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  placeholder="admin@tourraja.com"
                  className="w-full bg-gray-50 border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 pl-14 pr-6 outline-none transition-all font-bold text-foreground placeholder:text-muted-text/30"
                  required
                />
              </div>
            </div>

            <div className="space-y-2">
              <label className="text-xs font-black text-muted-text uppercase tracking-widest pl-2">
                Access Password
              </label>
              <div className="relative group">
                <div className="absolute inset-y-0 left-5 flex items-center pointer-events-none text-muted-text group-focus-within:text-primary transition-colors">
                  <Lock size={18} />
                </div>
                <input 
                  type="password" 
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  placeholder="••••••••"
                  className="w-full bg-gray-50 border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 pl-14 pr-6 outline-none transition-all font-bold text-foreground placeholder:text-muted-text/30"
                  required
                />
              </div>
            </div>

            {error && (
              <p className="text-danger text-xs font-bold bg-danger/5 p-3 rounded-xl border border-danger/10">
                {error}
              </p>
            )}

            <div className="flex items-center justify-between px-2">
              <label className="flex items-center gap-2 cursor-pointer group">
                <input type="checkbox" className="w-5 h-5 rounded-lg border-2 border-gray-200 checked:bg-primary checked:border-primary transition-all cursor-pointer" />
                <span className="text-sm font-bold text-muted-text group-hover:text-foreground transition-colors">Remember me</span>
              </label>
              <a href="#" className="text-sm font-bold text-primary hover:underline">Forgot?</a>
            </div>

            <button 
              type="submit"
              disabled={isLoading}
              className="w-full bg-primary hover:bg-primary-hover text-white rounded-2xl py-5 font-black text-lg shadow-xl shadow-primary/20 transition-all flex items-center justify-center gap-3 disabled:opacity-70 group"
            >
              {isLoading ? (
                <div className="w-6 h-6 border-4 border-white/30 border-t-white rounded-full animate-spin" />
              ) : (
                <>
                  <span>Sign In to Dashboard</span>
                  <ArrowRight size={20} className="group-hover:translate-x-1 transition-transform" />
                </>
              )}
            </button>
          </form>
        </div>

        {/* Footer info */}
        <p className="text-center text-[10px] font-bold text-muted-text uppercase tracking-widest leading-loose">
          Authorized personnel only. All access attempts are logged. <br />
          TourRaja v2.4.0 — Premium Admin Node
        </p>
      </motion.div>
    </div>
  );
};

export default AdminLogin;
