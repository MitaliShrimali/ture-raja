import React from "react";
import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import { packages } from "@/data/packages";
import { Star, Clock, MapPin, Check, ChevronRight, Phone, MessageCircle, Calendar, Users, Share2, Heart } from "lucide-react";

export function generateStaticParams() {
  return packages.map((pkg) => ({
    id: pkg.id,
  }));
}

export default async function PackageDetailPage({ params }: { params: Promise<{ id: string }> }) {
  const resolvedParams = await params;
  const pkg = packages.find(p => p.id === resolvedParams.id) || packages[0];

  return (
    <main className="bg-background min-h-screen">
      <Navbar />
      
      <div className="pt-24 pb-16">
        <div className="container-custom">
          {/* Breadcrumbs */}
          <div className="flex items-center gap-2 text-gray-400 text-sm mb-8 font-medium">
            <span className="hover:text-primary cursor-pointer">Home</span>
            <ChevronRight size={14} />
            <span className="hover:text-primary cursor-pointer">Destinations</span>
            <ChevronRight size={14} />
            <span className="text-foreground font-bold">{pkg.title}</span>
          </div>

          <div className="flex flex-col lg:flex-row gap-12">
            {/* Left Content */}
            <div className="flex-1 space-y-12">
              {/* Image Gallery */}
              <div className="space-y-4">
                <div className="relative aspect-[16/9] rounded-[40px] overflow-hidden shadow-2xl">
                  <img src={pkg.image} alt={pkg.title} className="w-full h-full object-cover" />
                  <div className="absolute top-6 right-6 flex gap-3">
                    <button className="w-12 h-12 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white hover:bg-white hover:text-red-500 transition-all shadow-lg">
                      <Heart size={24} />
                    </button>
                    <button className="w-12 h-12 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white hover:bg-white hover:text-primary transition-all shadow-lg">
                      <Share2 size={24} />
                    </button>
                  </div>
                </div>
                <div className="grid grid-cols-4 gap-4">
                   {[1, 2, 3, 4].map((i) => (
                     <div key={i} className="aspect-square rounded-2xl overflow-hidden cursor-pointer hover:opacity-80 transition-opacity shadow-soft">
                       <img src={pkg.image} alt={`Gallery ${i}`} className="w-full h-full object-cover" />
                     </div>
                   ))}
                </div>
              </div>

              {/* Title & Info */}
              <div className="bg-white rounded-[32px] p-8 md:p-12 shadow-soft space-y-8 border border-gray-50">
                <div className="flex flex-col md:flex-row md:items-start justify-between gap-6">
                  <div className="space-y-4">
                    <div className="flex items-center gap-2 text-primary font-bold">
                      <MapPin size={18} />
                      <span className="tracking-widest uppercase text-sm">{pkg.location}</span>
                    </div>
                    <h1 className="text-4xl md:text-5xl font-black text-foreground leading-tight">{pkg.title}</h1>
                    <div className="flex items-center gap-6">
                      <div className="flex items-center gap-2 text-orange-500 font-bold">
                        <Star size={20} fill="currentColor" />
                        <span>{pkg.rating} ({pkg.reviews} Reviews)</span>
                      </div>
                      <div className="flex items-center gap-2 text-gray-400 font-medium">
                        <Clock size={20} />
                        <span>{pkg.duration}</span>
                      </div>
                    </div>
                  </div>
                  <div className="text-right">
                    <p className="text-gray-400 text-sm font-bold uppercase tracking-widest mb-1">Total Price</p>
                    <div className="flex items-baseline justify-end gap-1">
                      <span className="text-5xl font-black text-primary">₹{pkg.price}</span>
                      <span className="text-gray-400 font-medium">/person</span>
                    </div>
                  </div>
                </div>

                <div className="pt-8 border-t border-gray-100">
                  <h3 className="text-2xl font-bold mb-6">Tour Overview</h3>
                  <p className="text-gray-500 leading-relaxed text-lg">
                    Experience the ultimate luxury with our {pkg.title} package. This carefully curated journey takes you through the most stunning landscapes and cultural landmarks, ensuring every moment is filled with wonder. From premium accommodations to expert-guided tours, we've handled all the details so you can focus on creating memories that last a lifetime.
                  </p>
                </div>
                
                <div className="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6 pt-8">
                  <h3 className="text-2xl font-bold col-span-full mb-2">What's Included</h3>
                  {[
                    "Luxury Accommodation",
                    "Daily Gourmet Breakfast",
                    "Expert Local Guides",
                    "Private Transportation",
                    "Entrance Fees to All Sites",
                    "Welcome Dinner Party",
                    "24/7 Concierge Service",
                    "Photography Sessions"
                  ].map((item, i) => (
                    <div key={i} className="flex items-center gap-4 text-gray-600 font-medium bg-background/50 p-4 rounded-2xl border border-gray-100">
                      <div className="w-8 h-8 bg-green-500/10 rounded-lg flex items-center justify-center text-green-500">
                        <Check size={18} strokeWidth={3} />
                      </div>
                      <span>{item}</span>
                    </div>
                  ))}
                </div>
              </div>

              {/* Itinerary */}
              <div className="bg-white rounded-[32px] p-8 md:p-12 shadow-soft space-y-10 border border-gray-50">
                <h3 className="text-3xl font-black">Tour Itinerary</h3>
                <div className="space-y-12 relative before:absolute before:left-6 before:top-4 before:bottom-4 before:w-0.5 before:bg-gray-100">
                  {[
                    { day: "Day 01", title: "Arrival & Welcome Dinner", desc: "Arrive at the airport and transfer to your luxury villa. Enjoy a traditional welcome dinner with the team." },
                    { day: "Day 02", title: "Cultural Heritage Tour", desc: "Visit the most famous landmarks and learn about the local history from our expert guides." },
                    { day: "Day 03", title: "Nature & Adventure", desc: "Explore the natural wonders of the region with a mix of hiking and relaxation." },
                    { day: "Day 04", title: "Free Day & Shopping", desc: "A day for you to explore at your own pace or relax by the pool. Evening shopping tour included." },
                    { day: "Day 05", title: "Farewell & Departure", desc: "Enjoy a final breakfast before your private transfer back to the airport." },
                  ].map((item, i) => (
                    <div key={i} className="relative pl-16 space-y-2 group">
                      <div className="absolute left-0 top-1 w-12 h-12 bg-white border-4 border-gray-100 rounded-full flex items-center justify-center text-primary font-bold shadow-soft group-hover:border-primary transition-colors z-10">
                        {i + 1}
                      </div>
                      <span className="text-primary font-bold uppercase tracking-widest text-sm">{item.day}</span>
                      <h4 className="text-2xl font-black text-foreground">{item.title}</h4>
                      <p className="text-gray-500 text-lg leading-relaxed">{item.desc}</p>
                    </div>
                  ))}
                </div>
              </div>
            </div>

            {/* Right Sidebar */}
            <div className="lg:w-[400px] space-y-8">
              {/* Agent Card */}
              <div className="bg-white rounded-[32px] p-8 shadow-soft border border-gray-50 sticky top-28 space-y-8">
                <div className="flex items-center gap-5 pb-6 border-b border-gray-100">
                  <img src={pkg.agent?.logo} alt={pkg.agent?.name} className="w-16 h-16 rounded-2xl shadow-lg" />
                  <div>
                    <h4 className="text-xl font-bold">{pkg.agent?.name}</h4>
                    <p className="text-gray-400 font-medium">Verified Agent</p>
                  </div>
                </div>
                
                <div className="space-y-4">
                  <button className="w-full flex items-center justify-center gap-3 bg-foreground hover:bg-black text-white py-5 rounded-[20px] font-bold transition-all shadow-xl">
                    <Phone size={22} />
                    <span>Call Now</span>
                  </button>
                  <button className="w-full flex items-center justify-center gap-3 bg-green-500 hover:bg-green-600 text-white py-5 rounded-[20px] font-bold transition-all shadow-xl">
                    <MessageCircle size={22} />
                    <span>WhatsApp Message</span>
                  </button>
                </div>

                <div className="space-y-6 pt-6 border-t border-gray-100">
                  <h4 className="text-xl font-bold">Inquiry Form</h4>
                  <div className="space-y-4">
                    <input type="text" placeholder="Your Name" className="w-full bg-background border border-gray-100 rounded-xl py-4 px-5 font-medium focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" />
                    <input type="email" placeholder="Your Email" className="w-full bg-background border border-gray-100 rounded-xl py-4 px-5 font-medium focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" />
                    <textarea placeholder="Message" rows={4} className="w-full bg-background border border-gray-100 rounded-xl py-4 px-5 font-medium focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none"></textarea>
                    <button className="w-full bg-primary hover:bg-primary-hover text-white py-5 rounded-[20px] font-bold transition-all shadow-lg shadow-primary/30">
                      Send Inquiry
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <Footer />
    </main>
  );
}
