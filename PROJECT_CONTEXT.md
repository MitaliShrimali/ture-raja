# Tour-Raja (Ture-Raja) - In-Depth Project Context & Technical Architecture

## 1. Executive Summary
**Tour-Raja** is a multi-tenant, full-featured Travel & B2B/B2C Tour Package & Agent Booking Platform. It enables travel agencies and independent tour operators to list, manage, and sell holiday packages, manage customer leads, handle bookings, manage branch offices and stay inventories, while giving administrators total oversight, package approval controls, payment/subscription management, and CMS configuration.

---

## 2. Technology Stack & Environment

| Layer | Technology / Tool | Description |
| :--- | :--- | :--- |
| **Backend Framework** | **Laravel 12.x** (PHP 8.2+) | MVC pattern with Eloquent ORM, custom artisan commands, queue listener, database-backed sessions/cache. |
| **Frontend UI** | **Blade Templates + Alpine.js v3** | Reactive client-side interactivity using Alpine.js (`alpinejs`, `@alpinejs/collapse`). |
| **Styling** | **Tailwind CSS v4** (`@tailwindcss/vite`) | Modern CSS framework processed via PostCSS / Vite. |
| **Build Tooling** | **Vite v8** | `laravel-vite-plugin`, `concurrently` for running dev server & queue worker together. |
| **Database** | **MySQL / SQLite** | Schema migrations covering 82 migration files, indexed tables for packages, agents, users, leads, stays, and pricing. |
| **Payments** | **PayU SDK & Razorpay** | Custom `PayUService` service class & `razorpay/razorpay` PHP SDK for subscription & booking transactions. |
| **Auth System** | Multi-guard / Custom Auth | Distinct flows for **Customers/Users**, **Agents**, and **Admins**, with OTP mobile authentication (`OtpController`). |

---

## 3. System Architecture & Key Portals

### A. Public Customer Portal
- **Routes File**: `routes/web.php`
- **Primary Controllers**:
  - `app/Http/Controllers/UserController.php`: Manages homepage feeds, category/city search, user signup/login, user profile, password change, booking requests, package reviews, wishlist toggles, and career submissions.
  - `app/Http/Controllers/ListingController.php`: Package discovery, holiday filter listings, filtering by domestic/international/theme/price/duration.
  - `app/Http/Controllers/PackageController.php` & dynamic route `/packages/{slug}`: Package details, itineraries, included/excluded lists, hotel stays, photo galleries, agent contact cards, and view counter increment.
  - `app/Http/Controllers/OtpController.php`: Mobile OTP verification logic for rapid user registration/authentication.

### B. Agent Portal (`/agent/...`)
- **Controller**: `app/Http/Controllers/AgentController.php` (~84 KB)
- **Middleware Protection**: `agent.auth`, `agent.profile_complete`
- **Key Capabilities**:
  1. **Profile & Branding**: Business card generation, agency logo upload, banner customization, profile image gallery (`AgentProfileImage`), "Why Us" story.
  2. **Package Management**: Create (`/agent/packages/create`), edit (`/agent/packages/edit/{id}`), toggle package visibility. Rich attributes: price, old price, currency, departure location, duration, itineraries, included/excluded, hotel stays, theme, and validity.
  3. **Lead & Inquiries Hub**: Track customer leads (`/agent/leads`), update inquiry status, record response notes.
  4. **Branch & Hotel Management**: Manage branch offices (`branches` table) and associated hotels/stays (`hotels` table).
  5. **Subscription & Monetization**: Plan upgrade workflows, PayU checkout processing (`/agent/checkout`), payment failure/success callbacks, invoice generation & PDF/print downloads (`/agent/invoice`).
  6. **Feedback & Gallery**: Showcase customer testimonials (`AgentFeedback`) and agency media assets (`AgentMedia`).

### C. Platform Admin Panel (`/admin/...`)
- **Controller**: `app/Http/Controllers/AdminController.php` (~207 KB)
- **Middleware Protection**: `auth`, `admin.permission`
- **Key Capabilities**:
  1. **Package Moderation Queue**: Review and approve/decline packages submitted by agents (`/admin/packages/pending`).
  2. **Agent Oversight**: Monitor agent registrations, edit agent details, toggle active/suspended state, assign subscription plans.
  3. **Subscription & Addon Management**: Define subscription plans (`plans`), addon pricing (`addon_pricings`), manage payments and GST invoicing.
  4. **Master Settings & Preferences**: Full CRUD over master lookup tables:
     - **Themes**: `app/Models/Theme.php`
     - **Amenities, Holiday Types, Activities, Transits, Durations**
     - **Geographic Data**: Countries, States, Cities
     - **Hotel Categories**
  5. **CMS & Marketing**: Home page banner editor, offer stickers, CMS static page manager, transit background music manager (`transit_music`).
  6. **Communication Settings**: SMTP mail setup, email templates, WhatsApp notification templates, automated package expiration reminders.
  7. **Career Management**: Job position postings (`OpenPosition`), department management (`JobDepartment`), location setup (`JobLocation`), job application review (`CareerApplication`).
  8. **Reports & Exports**: CSV reports for customer inquiries, leads, and payment transactions.

---

## 4. Domain Models & Database Schema

### Key Eloquent Models (`app/Models/`)
- `Package.php`: Represents tour packages, handling JSON casting for itineraries, inclusions, exclusions, and gallery images.
- `User.php`: Handles standard user and administrative credentials.
- `AddonPricing.php`: Pricing options for package boosts and agent addons.
- `AgentFeedback.php`: Agent-level reviews and customer feedback.
- `AgentMedia.php`: Uploaded promotional media for agent portfolios.
- `AgentProfileImage.php`: Agent branding and profile photo management.
- `HomePackage.php`: Curated packages featured on the homepage layout.
- `OfferSticker.php`: Promotional badges (e.g. "Top Rated", "25% Off", "Best Seller").
- `Review.php`: Rating & review entities.
- `CareerApplication.php`, `OpenPosition.php`, `JobDepartment.php`, `JobLocation.php`: Recruitment portal entities.

---

## 5. Key File Layout & Directory Structure

```
tour-raja/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AdminController.php      # Main Admin controller (207KB)
│   │       ├── AgentController.php      # Agent Portal controller (84KB)
│   │       ├── HomeController.php       # Home page controller
│   │       ├── ListingController.php    # Tour search & filter listings
│   │       ├── PackageController.php    # Package detail logic
│   │       ├── UserController.php       # Auth & customer dashboard logic
│   │       └── OtpController.php        # Mobile OTP service
│   ├── Models/                          # 14 Eloquent Models
│   └── Services/
│       └── PayUService.php              # Payment gateway integration
├── config/                              # Laravel configuration files
├── database/
│   ├── migrations/                      # 82 Migration files defining DB tables
│   └── seeders/                         # Initial seeders
├── public/                              # Public web root (CSS, JS, audio, uploads)
├── resources/
│   ├── js/                              # JS assets handled by Vite
│   └── views/
│       ├── admin/                       # 66 Blade views for Admin Panel
│       ├── agent/                       # Agent dashboard & feature views
│       ├── package/ & packages/         # Public package detail views
│       ├── components/ & layouts/       # Shared UI components
│       └── welcome.blade.php / listing  # Main public frontend templates
├── routes/
│   ├── web.php                          # All Web, Admin, and Agent routes (891 lines)
│   └── console.php                      # Artisan command routes
├── composer.json & package.json         # PHP and Node dependencies
└── vite.config.js                       # Vite bundler configuration
```
