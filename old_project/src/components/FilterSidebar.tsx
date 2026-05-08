import React from "react";
import { Search, MapPin, SlidersHorizontal, ChevronDown } from "lucide-react";

interface FilterSidebarProps {
  searchTerm: string;
  setSearchTerm: (val: string) => void;
  selectedCategories: string[];
  toggleCategory: (val: string) => void;
  priceRange: number;
  setPriceRange: (val: number) => void;
  selectedDurations: string[];
  toggleDuration: (val: string) => void;
}

const FilterSidebar = ({
  searchTerm,
  setSearchTerm,
  selectedCategories,
  toggleCategory,
  priceRange,
  setPriceRange,
  selectedDurations,
  toggleDuration
}: FilterSidebarProps) => {
  return (
    <aside className="space-y-8 sticky top-28 h-fit">
      {/* Search Input */}
      <div className="relative group">
        <input 
          type="text" 
          placeholder="Search destination or package..." 
          value={searchTerm}
          onChange={(e) => setSearchTerm(e.target.value)}
          className="w-full bg-white border border-border-soft rounded-2xl py-4 pl-12 pr-4 shadow-soft focus:ring-2 focus:ring-primary/10 focus:border-primary transition-all font-bold text-foreground placeholder:text-muted-text/40"
        />
        <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-muted-text group-focus-within:text-primary transition-colors" size={20} />
      </div>

      {/* Categories Filter */}
      <div className="bg-white rounded-[32px] p-8 shadow-soft border border-border-soft space-y-6">
        <h3 className="text-xl font-black font-syne border-b border-border-soft pb-4 tracking-tight">Categories</h3>
        <div className="space-y-4">
          {["Tropical", "Mountains", "City", "Adventure", "International", "Domestic"].map((cat) => (
            <label key={cat} className="flex items-center gap-3 cursor-pointer group">
              <div className="relative flex items-center justify-center">
                <input 
                  type="checkbox" 
                  checked={selectedCategories.includes(cat)}
                  onChange={() => toggleCategory(cat)}
                  className="peer appearance-none w-6 h-6 border-2 border-gray-100 rounded-lg checked:bg-primary checked:border-primary transition-all cursor-pointer" 
                />
                <div className="absolute opacity-0 peer-checked:opacity-100 text-white transition-opacity pointer-events-none">
                  <svg className="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                  </svg>
                </div>
              </div>
              <span className="text-muted-text font-bold group-hover:text-primary transition-colors text-sm">{cat}</span>
            </label>
          ))}
        </div>
      </div>

      {/* Price Range Filter */}
      <div className="bg-white rounded-[32px] p-8 shadow-soft border border-border-soft space-y-6">
        <div className="flex items-center justify-between border-b border-border-soft pb-4">
          <h3 className="text-xl font-black font-syne tracking-tight">Price Range</h3>
          <span className="text-primary font-black text-sm">₹100 - ₹{priceRange}</span>
        </div>
        <div className="px-2">
          <input 
            type="range" 
            min="100" 
            max="5000" 
            step="100"
            value={priceRange}
            onChange={(e) => setPriceRange(Number(e.target.value))}
            className="w-full h-1.5 bg-gray-100 rounded-lg appearance-none cursor-pointer accent-primary" 
          />
        </div>
        <div className="flex justify-between text-[10px] font-black text-muted-text uppercase tracking-widest">
          <span>Min: ₹100</span>
          <span>Max: ₹5000</span>
        </div>
      </div>

      {/* Duration Filter */}
      <div className="bg-white rounded-[32px] p-8 shadow-soft border border-border-soft space-y-6">
        <h3 className="text-xl font-black font-syne border-b border-border-soft pb-4 tracking-tight">Duration</h3>
        <div className="space-y-4">
          {["1-3 Days", "4-7 Days", "8-14 Days", "15+ Days"].map((dur) => (
            <label key={dur} className="flex items-center gap-3 cursor-pointer group">
               <div className="relative flex items-center justify-center">
                <input 
                  type="checkbox" 
                  checked={selectedDurations.includes(dur)}
                  onChange={() => toggleDuration(dur)}
                  className="peer appearance-none w-6 h-6 border-2 border-gray-100 rounded-lg checked:bg-primary checked:border-primary transition-all cursor-pointer" 
                />
                <div className="absolute opacity-0 peer-checked:opacity-100 text-white transition-opacity pointer-events-none">
                  <svg className="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fillRule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clipRule="evenodd" />
                  </svg>
                </div>
              </div>
              <span className="text-muted-text font-bold group-hover:text-primary transition-colors text-sm">{dur}</span>
            </label>
          ))}
        </div>
      </div>
    </aside>
  );
};

export default FilterSidebar;
