import React from "react";
import Image from "next/image";
import { Search, MapPin, Calendar, Users } from "lucide-react";

const Hero = () => {
  return (
    <section className="relative h-[90vh] min-h-[600px] flex items-center justify-center overflow-hidden">
      {/* Background Image with Overlay */}
      <div className="absolute inset-0 z-0">
        <Image
          src="/tourex/hero-bg.png"
          alt="Travel Hero"
          fill
          unoptimized
          className="object-cover"
        />
        <div className="absolute inset-0 bg-black/30 backdrop-blur-[2px]" />
      </div>

      {/* Content */}
      <div className="container-custom relative z-10 text-center text-white space-y-6 md:space-y-8 animate-in fade-in zoom-in duration-1000 px-4">
        <div className="space-y-4">
          <span className="inline-block px-4 py-2 bg-white/20 backdrop-blur-md rounded-full text-[10px] md:text-sm font-semibold tracking-wider uppercase">
            Discover the World with Us
          </span>
          <h1 className="text-4xl sm:text-5xl md:text-7xl font-black leading-[1.1] max-w-4xl mx-auto drop-shadow-2xl font-syne">
            Escape the Ordinary, <br className="hidden sm:block" />
            <span className="text-primary">Explore the World</span>
          </h1>
          <p className="text-base md:text-xl text-white/90 max-w-2xl mx-auto font-medium px-4">
            Discover the most beautiful places on earth with our exclusive travel packages tailored just for you.
          </p>
        </div>

        {/* Floating Search Bar */}
        <div className="max-w-5xl mx-auto mt-8 md:mt-12 bg-white rounded-[24px] md:rounded-[32px] shadow-2xl p-2 md:p-4 flex flex-col lg:flex-row items-center gap-2">
          <div className="flex-1 w-full grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-gray-100">
            <div className="flex items-center gap-4 px-6 py-3 md:py-4">
              <MapPin className="text-primary shrink-0" size={22} />
              <div className="text-left flex-1">
                <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Location</p>
                <input 
                  type="text" 
                  placeholder="Where are you going?" 
                  className="w-full bg-transparent text-foreground font-bold focus:outline-none placeholder:text-gray-300 text-sm md:text-base"
                />
              </div>
            </div>
            <div className="flex items-center gap-4 px-6 py-3 md:py-4">
              <Calendar className="text-primary shrink-0" size={22} />
              <div className="text-left flex-1">
                <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Date</p>
                <input 
                  type="text" 
                  placeholder="When to go?" 
                  className="w-full bg-transparent text-foreground font-bold focus:outline-none placeholder:text-gray-300 text-sm md:text-base"
                />
              </div>
            </div>
            <div className="flex items-center gap-4 px-6 py-3 md:py-4">
              <Users className="text-primary shrink-0" size={22} />
              <div className="text-left flex-1">
                <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Travelers</p>
                <input 
                  type="text" 
                  placeholder="How many?" 
                  className="w-full bg-transparent text-foreground font-bold focus:outline-none placeholder:text-gray-300 text-sm md:text-base"
                />
              </div>
            </div>
          </div>
          <button className="w-full lg:w-auto px-8 md:px-10 py-4 md:py-5 bg-primary hover:bg-primary-hover text-white rounded-[18px] md:rounded-[24px] font-bold text-base md:text-lg transition-all shadow-lg shadow-primary/30 flex items-center justify-center gap-3 group">
            <Search size={20} className="group-hover:scale-110 transition-transform" />
            <span>Search Now</span>
          </button>
        </div>
      </div>
    </section>
  );
};

export default Hero;
