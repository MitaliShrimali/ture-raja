import Navbar from "@/components/Navbar";
import Hero from "@/components/Hero";
import PackageCard from "@/components/PackageCard";
import Footer from "@/components/Footer";
import { packages } from "@/data/packages";
import { Globe, Plane, Shield, CreditCard, ChevronRight } from "lucide-react";
import Link from "next/link";

export default function Home() {
  return (
    <main className="relative">
      <Navbar />
      <Hero />

      {/* Popular Categories */}
      <section className="section-spacing bg-white">
        <div className="container-custom">
          <div className="flex items-end justify-between mb-12">
            <div className="space-y-4">
              <span className="text-primary font-bold tracking-widest uppercase text-sm">Destinations</span>
              <h2 className="text-4xl font-black text-foreground">Explore Popular Categories</h2>
            </div>
            <Link href="/listing/" className="flex items-center gap-2 text-primary font-bold hover:gap-3 transition-all">
              View All <ChevronRight size={20} />
            </Link>
          </div>

          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            {[
              { icon: Globe, label: "International", count: "120+ Places" },
              { icon: Plane, label: "Adventure", count: "80+ Places" },
              { icon: Shield, label: "Safe Travel", count: "200+ Places" },
              { icon: CreditCard, label: "Luxury", count: "45+ Places" },
              { icon: Plane, label: "Domestic", count: "150+ Places" },
              { icon: Globe, label: "Beach", count: "95+ Places" },
            ].map((cat, i) => (
              <div key={i} className="group p-8 rounded-[24px] bg-background hover:bg-primary transition-all duration-300 text-center space-y-4 cursor-pointer hover:shadow-xl hover:shadow-primary/20">
                <div className="w-16 h-16 mx-auto bg-white rounded-2xl flex items-center justify-center text-primary group-hover:scale-110 transition-transform shadow-soft">
                  <cat.icon size={32} />
                </div>
                <div>
                  <h4 className="font-bold text-foreground group-hover:text-white transition-colors">{cat.label}</h4>
                  <p className="text-sm text-gray-400 group-hover:text-white/80 transition-colors">{cat.count}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Featured Packages */}
      <section className="section-spacing bg-background">
        <div className="container-custom">
          <div className="flex items-end justify-between mb-12">
            <div className="space-y-4">
              <span className="text-primary font-bold tracking-widest uppercase text-sm">Best Offers</span>
              <h2 className="text-4xl font-black text-foreground">Featured Travel Packages</h2>
            </div>
            <div className="flex gap-4">
              <Link href="/listing/" className="px-6 py-3 rounded-full border border-gray-200 font-bold hover:bg-white transition-all">
                See More
              </Link>
            </div>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            {packages.map((pkg) => (
              <PackageCard key={pkg.id} pkg={pkg} />
            ))}
          </div>
        </div>
      </section>

      {/* Why Choose Us */}
      <section className="section-spacing bg-white">
        <div className="container-custom">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <div className="relative">
              <div className="rounded-[40px] overflow-hidden shadow-2xl">
                <img 
                  src="https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&q=80&w=800" 
                  alt="Why Choose Us" 
                  className="w-full h-[600px] object-cover"
                />
              </div>
              <div className="absolute -bottom-10 -right-10 bg-primary p-10 rounded-[32px] text-white shadow-2xl hidden md:block animate-bounce-subtle">
                <p className="text-5xl font-black mb-2">15+</p>
                <p className="text-lg font-bold opacity-80 uppercase tracking-widest">Years of Experience</p>
              </div>
            </div>

            <div className="space-y-10">
              <div className="space-y-4">
                <span className="text-primary font-bold tracking-widest uppercase text-sm">Expertise</span>
                <h2 className="text-4xl md:text-5xl font-black text-foreground leading-tight">
                  Why Choose <br /><span className="text-primary">Tour Raja</span> For Your Trip?
                </h2>
                <p className="text-lg text-gray-500 leading-relaxed">
                  We believe that travel is more than just a destination; it's about the stories you tell and the memories you create. We're here to make every moment count.
                </p>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                {[
                  { title: "Best Price Guarantee", desc: "We offer the most competitive prices in the market." },
                  { title: "Expert Guides", desc: "Our guides are highly experienced and knowledgeable." },
                  { title: "Premium Services", desc: "We provide top-notch services for all our clients." },
                  { title: "24/7 Support", desc: "We are always here to help you during your journey." },
                ].map((item, i) => (
                  <div key={i} className="space-y-3">
                    <div className="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center text-primary">
                      <Shield size={24} />
                    </div>
                    <h4 className="text-xl font-bold">{item.title}</h4>
                    <p className="text-gray-500 leading-relaxed">{item.desc}</p>
                  </div>
                ))}
              </div>
              
              <Link href="/about/" className="inline-flex items-center gap-3 bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-full font-bold transition-all shadow-lg shadow-primary/20">
                Explore More About Us
              </Link>
            </div>
          </div>
        </div>
      </section>

      {/* Newsletter */}
      <section className="section-spacing bg-foreground text-white overflow-hidden relative">
        <div className="container-custom relative z-10 text-center space-y-10 py-10">
          <div className="space-y-4 max-w-3xl mx-auto">
            <h2 className="text-4xl md:text-6xl font-black leading-tight">
              Get <span className="text-primary">Exclusive Offers</span> <br />Directly To Your Inbox
            </h2>
            <p className="text-lg text-white/60 font-medium">
              Join our community of 50k+ travelers and get the best deals before anyone else.
            </p>
          </div>

          <div className="max-w-2xl mx-auto flex flex-col md:flex-row items-center gap-4 bg-white/10 backdrop-blur-md p-3 rounded-[32px] border border-white/10">
            <input 
              type="email" 
              placeholder="Enter your email address" 
              className="flex-1 w-full bg-transparent px-6 py-4 text-white font-bold focus:outline-none placeholder:text-white/40"
            />
            <button className="w-full md:w-auto px-10 py-4 bg-primary hover:bg-primary-hover text-white rounded-[24px] font-bold text-lg transition-all shadow-lg shadow-primary/30">
              Subscribe Now
            </button>
          </div>
        </div>
        
        {/* Decorative elements */}
        <div className="absolute top-0 left-0 w-64 h-64 bg-primary/10 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2" />
        <div className="absolute bottom-0 right-0 w-96 h-96 bg-primary/5 rounded-full blur-3xl translate-x-1/3 translate-y-1/3" />
      </section>

      <Footer />
    </main>
  );
}
