import React from "react";
import Link from "next/link";
import { Mail, Phone, MapPin } from "lucide-react";

// Social Icons as simple SVG components
const FacebookIcon = () => (
  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
);
const InstagramIcon = () => (
  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" x2="17.51" y1="6.5" y2="6.5"/></svg>
);
const TwitterIcon = () => (
  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
);

const Footer = () => {
  return (
    <footer className="bg-primary text-white pt-20 pb-10">
      <div className="container-custom">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
          {/* Brand Column */}
          <div className="space-y-6">
            <Link href="/" className="flex items-center gap-2">
              <div className="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-primary font-bold text-xl shadow-lg">
                TR
              </div>
              <span className="text-2xl font-bold tracking-tight">
                Tour<span className="text-white">Raja</span>
              </span>
            </Link>
            <p className="text-white/80 leading-relaxed">
              Your ultimate travel partner for discovering the world's most beautiful destinations. We provide premium packages with authentic experiences.
            </p>
            <div className="flex gap-4">
              {[
                { Icon: FacebookIcon, label: "Facebook" },
                { Icon: InstagramIcon, label: "Instagram" },
                { Icon: TwitterIcon, label: "Twitter" }
              ].map((item, i) => (
                <a key={i} href="#" aria-label={item.label} className="w-10 h-10 bg-white/10 hover:bg-white/20 rounded-full flex items-center justify-center transition-colors">
                  <item.Icon />
                </a>
              ))}
            </div>
          </div>

          {/* Quick Links */}
          <div className="space-y-6">
            <h4 className="text-xl font-bold">Company</h4>
            <ul className="space-y-4 text-white/80">
              <li><Link href="/about/" className="hover:text-white transition-colors">About Us</Link></li>
              <li><Link href="/listing/" className="hover:text-white transition-colors">Destinations</Link></li>
              <li><Link href="/contact/" className="hover:text-white transition-colors">Contact</Link></li>
              <li><Link href="#" className="hover:text-white transition-colors">Privacy Policy</Link></li>
              <li><Link href="/admin/login/" className="text-white/40 hover:text-white transition-colors text-xs font-bold uppercase tracking-widest mt-2 block">Admin Access</Link></li>
            </ul>
          </div>

          {/* Categories */}
          <div className="space-y-6">
            <h4 className="text-xl font-bold">Categories</h4>
            <ul className="space-y-4 text-white/80">
              <li><Link href="#" className="hover:text-white transition-colors">Adventure Travel</Link></li>
              <li><Link href="#" className="hover:text-white transition-colors">Luxury Escape</Link></li>
              <li><Link href="#" className="hover:text-white transition-colors">Romantic Getaway</Link></li>
              <li><Link href="#" className="hover:text-white transition-colors">Family Packages</Link></li>
            </ul>
          </div>

          {/* Contact Info */}
          <div className="space-y-6">
            <h4 className="text-xl font-bold">Contact Us</h4>
            <ul className="space-y-4 text-white/80">
              <li className="flex gap-3">
                <MapPin className="shrink-0" size={20} />
                <span>123 Travel Street, Adventure City, World 45678</span>
              </li>
              <li className="flex gap-3">
                <Phone className="shrink-0" size={20} />
                <span>+1 (234) 567-890</span>
              </li>
              <li className="flex gap-3">
                <Mail className="shrink-0" size={20} />
                <span>hello@tourraja.com</span>
              </li>
            </ul>
          </div>
        </div>

        <div className="pt-8 border-t border-white/10 text-center text-white/60 text-sm">
          <p>© {new Date().getFullYear()} Tour Raja. All rights reserved.</p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
