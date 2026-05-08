import Navbar from "@/components/Navbar";
import Footer from "@/components/Footer";
import { Mail, Phone, MapPin, MessageSquare, Clock } from "lucide-react";

export default function ContactPage() {
  return (
    <main className="bg-background min-h-screen">
      <Navbar />
      
      <div className="pt-40 pb-20">
        <div className="container-custom">
          <div className="text-center max-w-3xl mx-auto mb-16 space-y-4">
            <span className="text-primary font-bold tracking-widest uppercase text-sm">Contact Us</span>
            <h1 className="text-5xl font-black">Let's Plan Your <span className="text-primary">Next Adventure</span></h1>
            <p className="text-xl text-gray-500 font-medium">
              Have questions about our packages or need a custom itinerary? Our travel experts are here to help you 24/7.
            </p>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-3 gap-12">
            {/* Contact Info Cards */}
            <div className="lg:col-span-1 space-y-6">
              {[
                { icon: Phone, title: "Call Us", content: "+1 (234) 567-890", sub: "Mon-Fri from 8am to 8pm" },
                { icon: Mail, title: "Email Us", content: "hello@tourraja.com", sub: "We'll respond within 24 hours" },
                { icon: MapPin, title: "Visit Us", content: "123 Travel St, World City", sub: "Come say hello in person" },
              ].map((item, i) => (
                <div key={i} className="bg-white p-8 rounded-[32px] shadow-soft border border-gray-50 flex items-start gap-6 hover:shadow-card transition-all">
                  <div className="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shrink-0">
                    <item.icon size={28} />
                  </div>
                  <div className="space-y-1">
                    <h4 className="text-lg font-bold">{item.title}</h4>
                    <p className="text-xl font-black text-foreground">{item.content}</p>
                    <p className="text-gray-400 text-sm font-medium">{item.sub}</p>
                  </div>
                </div>
              ))}
              
              <div className="bg-foreground rounded-[32px] p-8 text-white space-y-6 relative overflow-hidden">
                <div className="relative z-10 space-y-4">
                  <h4 className="text-2xl font-bold">Need Instant Help?</h4>
                  <p className="text-white/60">Chat with our AI assistant or a human agent right now.</p>
                  <button className="bg-primary hover:bg-primary-hover text-white px-8 py-4 rounded-full font-bold transition-all flex items-center gap-3">
                    <MessageSquare size={20} />
                    <span>Start Live Chat</span>
                  </button>
                </div>
                <div className="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full blur-2xl" />
              </div>
            </div>

            {/* Contact Form */}
            <div className="lg:col-span-2 bg-white rounded-[40px] p-8 md:p-16 shadow-soft border border-gray-50">
              <form className="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div className="space-y-3">
                  <label className="text-sm font-bold uppercase tracking-widest text-gray-400">Full Name</label>
                  <input type="text" placeholder="John Doe" className="w-full bg-background border border-gray-100 rounded-2xl py-5 px-6 font-medium focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" />
                </div>
                <div className="space-y-3">
                  <label className="text-sm font-bold uppercase tracking-widest text-gray-400">Email Address</label>
                  <input type="email" placeholder="john@example.com" className="w-full bg-background border border-gray-100 rounded-2xl py-5 px-6 font-medium focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" />
                </div>
                <div className="space-y-3">
                  <label className="text-sm font-bold uppercase tracking-widest text-gray-400">Phone Number</label>
                  <input type="text" placeholder="+1 (234) 567-890" className="w-full bg-background border border-gray-100 rounded-2xl py-5 px-6 font-medium focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all" />
                </div>
                <div className="space-y-3">
                  <label className="text-sm font-bold uppercase tracking-widest text-gray-400">Subject</label>
                   <select className="w-full bg-background border border-gray-100 rounded-2xl py-5 px-6 font-medium focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none">
                    <option>General Inquiry</option>
                    <option>Booking Problem</option>
                    <option>Custom Package</option>
                    <option>Partner With Us</option>
                  </select>
                </div>
                <div className="md:col-span-2 space-y-3">
                  <label className="text-sm font-bold uppercase tracking-widest text-gray-400">Your Message</label>
                  <textarea rows={6} placeholder="Tell us about your dream trip..." className="w-full bg-background border border-gray-100 rounded-2xl py-5 px-6 font-medium focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none"></textarea>
                </div>
                <div className="md:col-span-2">
                  <button className="w-full bg-primary hover:bg-primary-hover text-white py-6 rounded-[24px] font-black text-xl transition-all shadow-lg shadow-primary/30 flex items-center justify-center gap-4">
                    Send Message Now
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <Footer />
    </main>
  );
}
