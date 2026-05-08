import React from "react";
import Image from "next/image";
import Link from "next/link";
import { Star, Clock, Heart, MapPin, ArrowRight } from "lucide-react";
import { Package } from "@/data/packages";

interface PackageCardProps {
  pkg: Package;
  horizontal?: boolean;
}

const PackageCard = ({ pkg, horizontal = false }: PackageCardProps) => {
  return (
    <Link href={`/package/${pkg.id}/`} className="group block h-full">
      <div className={`bg-white rounded-[24px] md:rounded-[32px] overflow-hidden shadow-soft hover:shadow-premium transition-all duration-500 hover:-translate-y-2 border border-border-soft flex flex-col h-full`}>
        {/* Image Section */}
        <div className={`relative overflow-hidden w-full aspect-[4/3]`}>
          <Image
            src={pkg.image}
            alt={pkg.title}
            fill
            unoptimized
            className="object-cover transition-transform duration-700 group-hover:scale-110"
          />
          {pkg.badge && (
            <div className="absolute top-4 left-4 bg-primary text-white text-[10px] font-black uppercase tracking-wider px-4 py-2 rounded-full shadow-lg">
              {pkg.badge}
            </div>
          )}
          <button className="absolute top-4 right-4 w-10 h-10 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white hover:bg-white hover:text-red-500 transition-all shadow-lg group/heart">
            <Heart size={18} className="group-hover/heart:fill-current" />
          </button>
        </div>

        {/* Content Section */}
        <div className="p-6 md:p-8 flex flex-col flex-grow space-y-4">
          <div className="flex items-center justify-between">
            <div className="flex items-center gap-2 text-muted-text text-xs font-bold uppercase tracking-widest">
              <MapPin size={14} className="text-primary" />
              <span>{pkg.location}</span>
            </div>
            <div className="flex items-center gap-1 text-orange-500 bg-orange-50 px-3 py-1 rounded-full">
              <Star size={14} fill="currentColor" />
              <span className="font-black text-xs">{pkg.rating}</span>
            </div>
          </div>
          
          <h3 className="text-xl md:text-2xl font-black text-foreground font-syne leading-tight group-hover:text-primary transition-colors line-clamp-2">
            {pkg.title}
          </h3>

          <div className="flex items-center gap-4 text-muted-text">
            <div className="flex items-center gap-1.5">
              <Clock size={16} />
              <span className="text-xs font-bold">{pkg.duration.split(',')[0]}</span>
            </div>
            <div className="w-1 h-1 bg-gray-200 rounded-full" />
            <span className="text-xs font-bold">{pkg.reviews} Reviews</span>
          </div>

          <div className="mt-auto pt-6 border-t border-border-soft flex items-center justify-between">
            <div className="space-y-0.5">
              <p className="text-muted-text text-[10px] font-black uppercase tracking-widest">Starting at</p>
              <div className="flex items-baseline gap-1">
                <span className="text-2xl md:text-3xl font-black text-foreground font-syne">₹{pkg.price}</span>
                <span className="text-muted-text text-sm font-medium">/person</span>
              </div>
            </div>
            <div className="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-foreground group-hover:bg-primary group-hover:text-white transition-all duration-300">
              <ArrowRight size={20} />
            </div>
          </div>
        </div>
      </div>
    </Link>
  );
};

export default PackageCard;
