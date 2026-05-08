"use client";

import React, { useState, useEffect } from "react";
import Link from "next/link";
import { Search, User, Menu, X, Heart, Globe } from "lucide-react";

const Navbar = () => {
  const [isScrolled, setIsScrolled] = useState(false);
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);

  useEffect(() => {
    const handleScroll = () => {
      setIsScrolled(window.scrollY > 20);
    };
    window.addEventListener("scroll", handleScroll);
    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  return (
    <nav
      className={`fixed top-0 left-0 right-0 z-50 transition-all duration-300 ${
        isScrolled ? "glass py-3 shadow-soft" : "bg-transparent py-5"
      }`}
    >
      <div className="container-custom flex items-center justify-between">
        {/* Logo */}
        <Link href="/" className="flex items-center gap-2">
          <div className="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-white font-bold text-xl shadow-lg">
            TR
          </div>
          <span className={`text-2xl font-bold tracking-tight ${isScrolled ? "text-foreground" : "text-white"}`}>
            Tour<span className="text-primary">Raja</span>
          </span>
        </Link>

        {/* Desktop Links */}
        <div className={`hidden md:flex items-center gap-8 font-medium ${isScrolled ? "text-foreground/80" : "text-white/90"}`}>
          <Link href="/" className="hover:text-primary transition-colors">Home</Link>
          <Link href="/listing/" className="hover:text-primary transition-colors">Destinations</Link>
          <Link href="/about/" className="hover:text-primary transition-colors">About Us</Link>
          <Link href="/contact/" className="hover:text-primary transition-colors">Contact</Link>
        </div>

        {/* Desktop Actions */}
        <div className="hidden md:flex items-center gap-5">
          <Link href="/admin/login/" className={`text-sm font-bold uppercase tracking-wider hover:text-primary transition-colors ${isScrolled ? "text-muted-text" : "text-white/70"}`}>
            Admin Access
          </Link>
          <button className={`p-2 rounded-full hover:bg-black/5 transition-colors ${isScrolled ? "text-foreground" : "text-white"}`}>
            <Search size={22} />
          </button>
          
          {/* User Dropdown */}
          <div className="relative group">
            <button className="flex items-center justify-center w-10 h-10 bg-primary hover:bg-primary-hover text-white rounded-full transition-all shadow-lg shadow-primary/20">
              <User size={18} />
            </button>
            
            {/* Dropdown Menu */}
            <div className="absolute right-0 top-full mt-2 w-48 bg-white rounded-2xl shadow-premium border border-border-soft opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform origin-top-right group-hover:translate-y-0 translate-y-2 z-50 overflow-hidden">
              <div className="py-2">
                <Link href="#" className="block px-4 py-2 text-sm font-bold text-foreground hover:bg-gray-50 hover:text-primary transition-colors">
                  Log In
                </Link>
                <Link href="#" className="block px-4 py-2 text-sm font-bold text-foreground hover:bg-gray-50 hover:text-primary transition-colors">
                  Sign Up
                </Link>
                <div className="h-px bg-border-soft my-1" />
                <Link href="/profile/" className="block px-4 py-2 text-sm font-bold text-foreground hover:bg-gray-50 hover:text-primary transition-colors">
                  My Profile
                </Link>
              </div>
            </div>
          </div>
        </div>

        {/* Mobile Menu Button */}
        <button 
          className={`md:hidden p-2 ${isScrolled ? "text-foreground" : "text-white"}`}
          onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
        >
          {isMobileMenuOpen ? <X size={28} /> : <Menu size={28} />}
        </button>
      </div>

      {/* Mobile Menu Overlay */}
      {isMobileMenuOpen && (
        <div className="fixed inset-0 top-[70px] bg-white z-40 md:hidden animate-in slide-in-from-right duration-300">
          <div className="flex flex-col p-6 gap-6 text-xl font-semibold">
            <Link href="/" onClick={() => setIsMobileMenuOpen(false)}>Home</Link>
            <Link href="/listing/" onClick={() => setIsMobileMenuOpen(false)}>Destinations</Link>
            <Link href="/about/" onClick={() => setIsMobileMenuOpen(false)}>About Us</Link>
            <Link href="/contact/" onClick={() => setIsMobileMenuOpen(false)}>Contact</Link>
            <hr className="border-gray-100" />
            <Link href="/profile/" className="flex items-center gap-3 text-primary" onClick={() => setIsMobileMenuOpen(false)}>
              <User /> Profile
            </Link>
            <Link href="/admin/login/" className="flex items-center gap-3 text-gray-400 text-sm font-bold uppercase tracking-widest mt-4" onClick={() => setIsMobileMenuOpen(false)}>
              Admin Access
            </Link>
          </div>
        </div>
      )}
    </nav>
  );
};

export default Navbar;
