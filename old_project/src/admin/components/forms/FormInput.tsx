"use client";

import React from "react";

interface FormInputProps extends React.InputHTMLAttributes<HTMLInputElement> {
  label: string;
  icon?: React.ReactNode;
}

const FormInput: React.FC<FormInputProps> = ({ label, icon, ...props }) => {
  return (
    <div className="space-y-2">
      <label className="text-xs font-black text-muted-text uppercase tracking-widest pl-2">
        {label}
      </label>
      <div className="relative group">
        {icon && (
          <div className="absolute inset-y-0 left-5 flex items-center pointer-events-none text-muted-text group-focus-within:text-primary transition-colors">
            {icon}
          </div>
        )}
        <input 
          {...props}
          className={`w-full bg-[#F5F5F5] border-2 border-transparent focus:border-primary/20 focus:bg-white rounded-2xl py-4 ${icon ? 'pl-14' : 'px-6'} pr-6 outline-none transition-all font-bold text-foreground placeholder:text-muted-text/30 text-sm`}
        />
      </div>
    </div>
  );
};

export default FormInput;
