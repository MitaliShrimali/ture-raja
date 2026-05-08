"use client";

import React from "react";
import { motion } from "framer-motion";
import { TrendingUp, TrendingDown } from "lucide-react";

interface MetricCardProps {
  title: string;
  value: string;
  growth: string;
  isPositive?: boolean;
  icon?: React.ReactNode;
  color?: string;
}

const MetricCard: React.FC<MetricCardProps> = ({ 
  title, 
  value, 
  growth, 
  isPositive = true, 
  icon,
  color = "#E8460A" 
}) => {
  return (
    <motion.div 
      whileHover={{ y: -5 }}
      className="bg-white p-8 rounded-[32px] shadow-soft hover:shadow-premium transition-all duration-300 relative overflow-hidden group"
    >
      {/* Background Accent Gradient */}
      <div 
        className="absolute bottom-0 left-0 right-0 h-1.5 opacity-20 group-hover:opacity-100 transition-opacity"
        style={{ backgroundColor: color }}
      />
      
      <div className="flex items-start justify-between">
        <div className="space-y-4">
          <div className="flex items-center gap-2">
            <span className="text-[11px] font-black text-muted-text uppercase tracking-widest opacity-60">
              {title}
            </span>
            {icon && <div className="text-muted-text/40">{icon}</div>}
          </div>
          
          <div className="space-y-1">
            <h3 className="text-4xl font-black font-syne text-foreground tracking-tight">
              {value}
            </h3>
            <div className={`flex items-center gap-1.5 ${isPositive ? 'text-success' : 'text-danger'}`}>
              <div className={`p-1 rounded-full ${isPositive ? 'bg-success/10' : 'bg-danger/10'}`}>
                {isPositive ? <TrendingUp size={12} /> : <TrendingDown size={12} />}
              </div>
              <span className="text-sm font-bold">{growth} this month</span>
            </div>
          </div>
        </div>

        {/* Floating Badge Style Icon/Decorative Element */}
        <div 
          className="w-14 h-14 rounded-2xl flex items-center justify-center bg-gray-50 group-hover:scale-110 transition-transform duration-500"
        >
          <div 
            className="w-2 h-2 rounded-full animate-pulse" 
            style={{ backgroundColor: color }}
          />
        </div>
      </div>

      {/* Decorative Blur Circle */}
      <div 
        className="absolute -top-12 -right-12 w-24 h-24 blur-3xl opacity-5 rounded-full"
        style={{ backgroundColor: color }}
      />
    </motion.div>
  );
};

export default MetricCard;
