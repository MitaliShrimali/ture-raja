"use client";

import React from "react";

interface StatusBadgeProps {
  label: string;
  type?: "success" | "warning" | "danger" | "info" | "neutral" | "orange";
}

const StatusBadge: React.FC<StatusBadgeProps> = ({ label, type = "neutral" }) => {
  const styles = {
    success: "bg-success/10 text-success border-success/20",
    warning: "bg-warning/10 text-warning border-warning/20",
    danger: "bg-danger/10 text-danger border-danger/20",
    info: "bg-info/10 text-info border-info/20",
    neutral: "bg-gray-100 text-muted-text border-gray-200",
    orange: "bg-primary/10 text-primary border-primary/20",
  };

  return (
    <span className={`px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider border ${styles[type]}`}>
      {label}
    </span>
  );
};

export default StatusBadge;
