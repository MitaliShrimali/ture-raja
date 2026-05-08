"use client";

import { useState, useMemo } from "react";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import PackageCard from "@/components/PackageCard";
import FilterSidebar from "@/components/FilterSidebar";
import { packages } from "@/data/packages";
import { SlidersHorizontal, ChevronDown, LayoutGrid, List } from "lucide-react";

export default function ListingPage() {
  const [searchTerm, setSearchTerm] = useState("");
  const [selectedCategories, setSelectedCategories] = useState<string[]>([]);
  const [priceRange, setPriceRange] = useState<number>(5000);
  const [selectedDurations, setSelectedDurations] = useState<string[]>([]);

  const toggleCategory = (cat: string) => {
    setSelectedCategories(prev => 
      prev.includes(cat) ? prev.filter(c => c !== cat) : [...prev, cat]
    );
  };

  const toggleDuration = (dur: string) => {
    setSelectedDurations(prev => 
      prev.includes(dur) ? prev.filter(d => d !== dur) : [...prev, dur]
    );
  };

  const filteredPackages = useMemo(() => {
    return packages.filter(pkg => {
      // search
      if (searchTerm) {
        const term = searchTerm.toLowerCase();
        if (!pkg.title.toLowerCase().includes(term) && !pkg.location.toLowerCase().includes(term)) {
          return false;
        }
      }
      
      // categories
      if (selectedCategories.length > 0) {
        if (!selectedCategories.includes(pkg.category || "")) {
          return false;
        }
      }
      
      // price
      if (pkg.price > priceRange) {
        return false;
      }
      
      // duration mapping (simplified logic)
      if (selectedDurations.length > 0) {
        // Just checking if any selected duration word is in the package duration
        // E.g. "4-7 Days" -> check if '4', '5', '6', '7' are in duration string
        // For a robust implementation, you'd parse numbers, but simple string matching works for demo
        let match = false;
        for (const dur of selectedDurations) {
          if (dur === "1-3 Days" && (pkg.duration.includes("1 Day") || pkg.duration.includes("2 Day") || pkg.duration.includes("3 Day"))) match = true;
          if (dur === "4-7 Days" && (pkg.duration.includes("4 Day") || pkg.duration.includes("5 Day") || pkg.duration.includes("6 Day") || pkg.duration.includes("7 Day"))) match = true;
          if (dur === "8-14 Days" && (pkg.duration.includes("8 Day") || pkg.duration.includes("9 Day") || pkg.duration.includes("10 Day") || pkg.duration.includes("11 Day") || pkg.duration.includes("12 Day") || pkg.duration.includes("13 Day") || pkg.duration.includes("14 Day"))) match = true;
          if (dur === "15+ Days" && pkg.duration.includes("15 Day")) match = true; // simplifying
        }
        if (!match) return false;
      }
      
      return true;
    });
  }, [searchTerm, selectedCategories, priceRange, selectedDurations]);

  return (
    <main className="bg-background min-h-screen">
      <Navbar />
      
      {/* Small Hero Header */}
      <div className="bg-primary pt-32 pb-20">
        <div className="container-custom text-white text-center">
          <h1 className="text-4xl md:text-5xl font-black mb-4">Explore Destinations</h1>
          <p className="text-white/80 max-w-2xl mx-auto font-medium">
            Discover 100+ amazing tour packages across the globe with premium services and best prices.
          </p>
        </div>
      </div>

      <div className="container-custom py-16">
        <div className="flex flex-col lg:flex-row gap-12">
          {/* Sidebar */}
          <div className="lg:w-1/4 shrink-0">
            <FilterSidebar 
              searchTerm={searchTerm}
              setSearchTerm={setSearchTerm}
              selectedCategories={selectedCategories}
              toggleCategory={toggleCategory}
              priceRange={priceRange}
              setPriceRange={setPriceRange}
              selectedDurations={selectedDurations}
              toggleDuration={toggleDuration}
            />
          </div>

          {/* Main Content */}
          <div className="flex-1 space-y-8">
            {/* Top Bar */}
            <div className="bg-white rounded-[24px] p-6 shadow-soft flex flex-col md:flex-row items-center justify-between gap-6">
              <div>
                <p className="text-gray-400 font-bold uppercase tracking-widest text-xs mb-1">Search Results</p>
                <h3 className="text-2xl font-black">Showing <span className="text-primary">{filteredPackages.length}</span> Packages</h3>
              </div>
              
              <div className="flex items-center gap-4 w-full md:w-auto">
                <div className="relative flex-1 md:w-64">
                   <select className="w-full bg-background border border-gray-100 rounded-xl py-3 px-4 font-bold focus:outline-none appearance-none">
                    <option>Recommended</option>
                    <option>Price: Low to High</option>
                    <option>Price: High to Low</option>
                    <option>Top Rated</option>
                  </select>
                  <ChevronDown className="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" size={18} />
                </div>
                <div className="flex bg-background p-1 rounded-xl">
                  <button className="p-2 bg-white shadow-soft rounded-lg text-primary">
                    <LayoutGrid size={20} />
                  </button>
                  <button className="p-2 text-gray-400 hover:text-primary transition-colors">
                    <List size={20} />
                  </button>
                </div>
              </div>
            </div>

            {/* Grid */}
            <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
              {filteredPackages.length > 0 ? (
                filteredPackages.map((pkg) => (
                  <PackageCard key={pkg.id} pkg={pkg} />
                ))
              ) : (
                <div className="col-span-full py-20 text-center space-y-4">
                  <h4 className="text-2xl font-bold">No packages found</h4>
                  <p className="text-gray-500">Try adjusting your filters or search term.</p>
                </div>
              )}
            </div>

            {/* Load More */}
            {filteredPackages.length > 0 && (
              <div className="text-center pt-10">
                <button className="bg-white hover:bg-gray-50 text-foreground px-10 py-5 rounded-full font-bold shadow-soft transition-all border border-gray-100">
                  Load More Results
                </button>
              </div>
            )}
          </div>
        </div>
      </div>

      <Footer />
    </main>
  );
}
