import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import { Shield, Globe, Users, Trophy } from "lucide-react";

export default function AboutPage() {
  return (
    <main className="bg-background min-h-screen">
      <Navbar />
      
      {/* Hero */}
      <div className="relative pt-40 pb-32 overflow-hidden bg-foreground">
        <div className="absolute inset-0 z-0 opacity-20">
          <img src="/tourex/hero-bg.png" alt="About Hero" className="w-full h-full object-cover" />
        </div>
        <div className="container-custom relative z-10 text-center text-white">
          <h1 className="text-5xl md:text-7xl font-black mb-6 animate-in fade-in slide-in-from-bottom duration-700">Our Story</h1>
          <p className="text-xl text-white/60 max-w-3xl mx-auto font-medium">
            Redefining travel experiences since 2010. We don't just plan trips; we craft memories that stay with you forever.
          </p>
        </div>
      </div>

      {/* Content */}
      <section className="section-spacing bg-white">
        <div className="container-custom">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
            <div className="space-y-8">
              <span className="text-primary font-bold tracking-widest uppercase text-sm">Who We Are</span>
              <h2 className="text-4xl md:text-5xl font-black leading-tight text-foreground">
                We are a team of <span className="text-primary">passionate travelers</span> and storytellers.
              </h2>
              <p className="text-lg text-gray-500 leading-relaxed">
                Tour Raja started with a simple idea: travel should be authentic, seamless, and unforgettable. Over the last decade, we have grown from a small group of enthusiasts to a global marketplace, connecting thousands of travelers with the best local agents and experiences.
              </p>
              <p className="text-lg text-gray-500 leading-relaxed">
                Our mission is to empower local agents while providing travelers with the most transparent and premium booking experience possible.
              </p>
              
              <div className="grid grid-cols-2 gap-8 pt-6">
                {[
                  { label: "Happy Customers", value: "50k+" },
                  { label: "Destinations", value: "200+" },
                  { label: "Tour Guides", value: "500+" },
                  { label: "Awards Won", value: "15+" },
                ].map((stat, i) => (
                  <div key={i} className="space-y-1">
                    <p className="text-4xl font-black text-primary">{stat.value}</p>
                    <p className="text-gray-400 font-bold uppercase tracking-wider text-xs">{stat.label}</p>
                  </div>
                ))}
              </div>
            </div>
            
            <div className="relative">
              <div className="rounded-[40px] overflow-hidden shadow-2xl rotate-3 hover:rotate-0 transition-transform duration-500">
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&q=80&w=800" alt="Team" className="w-full h-[600px] object-cover" />
              </div>
              <div className="absolute -top-10 -left-10 w-40 h-40 bg-primary/10 rounded-full blur-3xl" />
              <div className="absolute -bottom-10 -right-10 w-64 h-64 bg-primary/5 rounded-full blur-3xl" />
            </div>
          </div>
        </div>
      </section>

      {/* Values */}
      <section className="section-spacing bg-background">
        <div className="container-custom">
          <div className="text-center max-w-3xl mx-auto mb-20 space-y-4">
            <span className="text-primary font-bold tracking-widest uppercase text-sm">Our Values</span>
            <h2 className="text-4xl font-black">What Drives Us Every Day</h2>
          </div>
          
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            {[
              { icon: Globe, title: "Authenticity", desc: "We provide real experiences, not just tourist traps." },
              { icon: Shield, title: "Trust & Safety", desc: "Your safety and security are our top priorities." },
              { icon: Users, title: "Community", desc: "Supporting local economies and agents everywhere." },
              { icon: Trophy, title: "Excellence", desc: "Setting the gold standard for travel services." },
            ].map((value, i) => (
              <div key={i} className="bg-white p-10 rounded-[32px] shadow-soft hover:shadow-card transition-all group">
                <div className="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform">
                  <value.icon size={32} />
                </div>
                <h4 className="text-xl font-bold mb-4">{value.title}</h4>
                <p className="text-gray-500 leading-relaxed">{value.desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      <Footer />
    </main>
  );
}
