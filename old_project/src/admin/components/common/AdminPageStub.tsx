"use client";

import React from "react";
import { motion } from "framer-motion";
import { Hammer } from "lucide-react";

interface AdminPageStubProps {
  title: string;
}

const AdminPageStub: React.FC<AdminPageStubProps> = ({ title }) => {
  return (
    <div className="h-[60vh] flex flex-col items-center justify-center space-y-6 text-center">
      <div className="w-20 h-20 bg-primary/5 rounded-[24px] flex items-center justify-center text-primary">
        <Hammer size={40} />
      </div>
      <div className="space-y-2">
        <h1 className="text-4xl font-black font-syne text-foreground tracking-tight">{title}</h1>
        <p className="text-muted-text font-bold uppercase text-[10px] tracking-widest">
          This section is currently being provisioned
        </p>
      </div>
      <div className="p-6 bg-white rounded-3xl shadow-soft border border-border-soft max-w-md">
        <p className="text-sm font-medium text-muted-text leading-relaxed">
          The <span className="text-foreground font-bold">{title}</span> module is part of the TourRaja v2.4.0 expansion. Our engineers are finalizing the data models for this section.
        </p>
      </div>
    </div>
  );
};

export default AdminPageStub;
