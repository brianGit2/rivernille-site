# RiverNille Construction - Website Documentation

**Developer:** Brian Obonyo  
**Date:** 6/02/2026  
**Domain:** rivernilleconstruction.co.ke  
**Version:** 1.0 (Production Ready)

---

## 📋 Table of Contents
1. [Website Overview](#website-overview)
2. [Pages & Structure](#pages--structure)
3. [Core Features](#core-features)
4. [Forms & Functionality](#forms--functionality)
5. [Design & Branding](#design--branding)
6. [Technology Stack](#technology-stack)
7. [Security Features](#security-features)
8. [Mobile Responsiveness](#mobile-responsiveness)
9. [Database & Backend](#database--backend)
10. [File Structure](#file-structure)

---

## 🌐 Website Overview

**RiverNille Construction** is a professional construction and building services marketing website designed to showcase company expertise, attract customers, and facilitate quote requests and newsletter subscriptions.

### Key Purpose
- Present construction services and projects
- Generate leads through quote requests
- Build customer relationship via email newsletter
- Provide company information and team details
- Showcase completed projects and testimonials

---

## 📄 Pages & Structure

### Main Pages (14 Total)

#### 1. **index.html** - Homepage
- Hero section with call-to-action
- Services overview
- Recent projects showcase
- Testimonials carousel
- Newsletter subscription section
- Featured company info

#### 2. **about-us.html** - Company Story
- Company mission and vision
- Company history and background
- Team overview
- Company values and achievements
- Contact information

#### 3. **about-company.html** - Company Details
- Detailed company information
- Services offered
- Years of experience
- Client testimonials
- Company statistics

#### 4. **services-1.html** - Services Page 1
- Complete list of construction services
- Service descriptions and benefits
- Image gallery per service
- Quote request button for each service
- Service highlights and features

#### 5. **services-2.html** - Services Page 2
- Alternative services layout
- Additional service offerings
- Detailed service breakdowns
- Project portfolio related to services
- Service pricing guidelines

#### 6. **projects.html** - Projects Portfolio
- Grid/list of completed projects
- Project categories/filters
- Project thumbnails and descriptions
- Quick links to individual projects
- Project statistics (value, timeline, team size)

#### 7. **project-single.html** - Project Detail
- Full project case study
- Project timeline and phases
- High-resolution project images
- Project challenges and solutions
- Team members involved
- Client testimonial for project
- Related projects section

#### 8. **team.html** - Team Members
- Company team roster
- Team member profiles with photos
- Job titles and specialties
- Team member bios
- Social media links
- Team statistics and experience

#### 9. **testimonial.html** - Client Testimonials
- Client reviews and ratings
- Customer quotes and feedback
- Client company logos
- Star ratings display
- Testimonial carousel/slider
- Success stories

#### 10. **blog-classic.html** - Blog Classic Layout
- Blog posts in classic layout (one column)
- Post titles, dates, and authors
- Excerpt previews
- Read more links to full posts
- Blog category tags
- Search/filter functionality

#### 11. **blog-grid.html** - Blog Grid Layout
- Blog posts in grid format (2-3 columns)
- Post cards with images
- Post metadata (date, author, category)
- Hover effects for interactivity
- Pagination for multiple posts
- Featured posts highlighted

#### 12. **blog-single.html** - Blog Post Detail
- Full blog post content
- Post title, author, date, category
- Featured image and gallery
- Related posts recommendations
- Comment section (if enabled)
- Social sharing buttons
- Newsletter signup CTA

#### 13. **contact.html** - Contact Page
- Contact information display
- Contact form with AJAX submission
- Company location/map
- Phone numbers and emails
- Business hours
- Social media links
- Quick response guarantee

#### 14. **404.html** - Error Page
- 404 Not Found error message
- User-friendly error explanation
- Navigation menu to main pages
- Home button
- Search box to find content

---

## ⭐ Core Features

### 1. **Quote Request System**
- **Location:** Modal popup on every page
- **Trigger:** "Request a Quote" button in header
- **Fields:** Name, Email, Phone, Message
- **Functionality:**
  - Real-time form validation
  - AJAX submission (no page reload)
  - Loading state indicator
  - Success/error messages
  - Database storage in `quotes` table
  - Email notification to admin

### 2. **Newsletter Subscription**
- **Location:** Footer section on every page
- **Input:** Email address
- **Functionality:**
  - Email format validation
  - Duplicate prevention (one email per subscriber)
  - AJAX submission
  - Success confirmation message
  - Database storage in `subscribers` table
  - Optional confirmation email

### 3. **WhatsApp Widget**
- **Location:** Floating button (bottom-right corner)
- **Design:** Green circle with pulsing animation
- **Functionality:**
  - Direct link to WhatsApp conversation
  - Phone number: Configured to company number
  - Always visible on all pages
  - Mobile-friendly tapping

### 4. **Responsive Navigation**
- **Desktop:** Full horizontal menu with dropdown
- **Mobile:** Hamburger menu icon that toggles sidebar menu
- **Menu Items:**
  - Home (link to index.html)
  - About (dropdown with About Us & About Company)
  - Services (dropdown with Services 1 & Services 2)
  - Pages (dropdown with other pages)
  - Blog (dropdown with Blog layouts & posts)
  - Contact (link to contact.html)

### 5. **Sticky Header**
- **Behavior:** Header follows user scroll
- **Library:** Headroom.js
- **Effect:** Slides up when scrolling down, slides down when scrolling up
- **Mobile:** Hamburger menu always accessible

### 6. **Image Gallery & Lightbox**
- **Library:** VenoBox
- **Functionality:**
  - Click image to enlarge in modal
  - Navigation between images (prev/next)
  - Close button and ESC key to exit
  - Used on project and blog pages

### 7. **Project Carousel/Slider**
- **Library:** Owl Carousel & Slick.js
- **Functionality:**
  - Auto-scrolling through projects
  - Previous/Next buttons
  - Dot indicators for navigation
  - Responsive slides (4 → 2 → 1 column)

### 8. **Testimonials Carousel**
- **Library:** Owl Carousel
- **Display:** Cycles through client testimonials
- **Content:** Client name, quote, company, rating
- **Interaction:** Manual navigation via arrows

### 9. **Animation Effects**
- **Library:** WOW.js & Animate.css
- **Effects:**
  - Fade-in animations on scroll
  - Slide-in animations for sections
  - Bounce effects on buttons
  - Parallax scrolling (optional)

### 10. **Counter Animation**
- **Library:** Odometer.js
- **Usage:** Company statistics (years, projects, clients, awards)
- **Effect:** Numbers animate upward when section scrolls into view

---

## 📝 Forms & Functionality

### Quote Request Form
```
Field          | Type       | Validation
---|---|---
Name           | Text       | Required, min 2 chars
Email          | Email      | Required, valid format
Phone          | Tel        | Required, numeric
Message        | Textarea   | Required, min 10 chars
```

**Actions:**
1. User submits form via "Request a Quote" button
2. JavaScript validates form fields
3. AJAX sends data to `handlers.php`
4. Server validates and sanitizes input
5. Data stored in `quotes` database table
6. Email sent to `ADMIN_EMAIL`
7. Success message shown to user
8. Database logs submission timestamp

### Newsletter Subscribe Form
```
Field          | Type       | Validation
---|---|---
Email          | Email      | Required, valid format, unique
```

**Actions:**
1. User enters email in footer subscription box
2. JavaScript validates email format
3. AJAX sends data to `handlers.php`
4. Server checks for duplicate email
5. Data stored in `subscribers` table
6. Optional confirmation email sent
7. Success message displayed
8. Button shows "Thank you for subscribing!"

---

## 🎨 Design & Branding

### Color Scheme
- **Primary Color:** #ff7607 (Orange)
- **Secondary Color:** #263a4f (Dark Blue)
- **Text Color:** #222222 (Dark Gray/Black)
- **Accent Color:** #666666 (Medium Gray)
- **Background:** #ffffff (White)

### Typography
- **Headings:** Modern sans-serif (Bootstrap default)
- **Body Text:** Clean, readable sans-serif
- **Font Sizes:**
  - H1: 48px
  - H2: 36px
  - Body: 14-16px
  - Small: 12-13px

### Logo
- **Format:** PNG image (`img/logo-dark.png`)
- **Size (Desktop):** Max-width 180px, max-height 60px
- **Size (Footer):** Max-width 150px
- **Location:** Header and footer on all pages

### Branding Comment
All HTML pages include branding attribution:
```html
<!-- Designed & Developed by Brian Obonyo | 6/02/2026 | RiverNille Construction -->
```

### Icons & Assets
- **Font Awesome:** 5.x icons for social media, features, UI elements
- **Flaticon:** Custom icons for services and features
- **Elegant Icons:** Line icons for decorative elements
- **Themify Icons:** Additional icon set

---

## 💻 Technology Stack

### Frontend
- **HTML5:** Semantic markup
- **CSS3:** Advanced styling, animations, flexbox, grid
- **JavaScript (ES6):** Vanilla JS + jQuery
- **Bootstrap 4:** Responsive grid system, components
- **jQuery:** DOM manipulation, AJAX requests

### Backend
- **PHP 7+:** Server-side logic, form processing
- **PDO:** Database abstraction layer
- **Sessions:** User session management

### Database
- **SQLite:** Default (local development)
- **MySQL:** Cloud deployments
- **PostgreSQL:** Cloud alternative

### Libraries & Plugins
| Category | Library | Purpose |
|---|---|---|
| Animation | WOW.js, Animate.css | Scroll animations |
| Carousel | Owl Carousel, Slick.js | Image & content sliders |
| Lightbox | VenoBox | Image modal gallery |
| Counter | Odometer.js | Number animations |
| Scroll | Headroom.js | Sticky header behavior |
| Smooth Scroll | smooth-scroll.min.js | Anchor link scrolling |
| Utilities | Modernizr | Feature detection |
| Layout | Tether | Tooltip positioning |

---

## 🔒 Security Features

### Input Validation
- **Client-side:** JavaScript validation on form submission
- **Server-side:** PHP regex and format validation
- **Sanitization:** `htmlspecialchars()` and `trim()` on all inputs

### SQL Injection Prevention
- **Prepared Statements:** All queries use PDO parameter binding
- **No Dynamic SQL:** Parameterized queries prevent SQL injection

### Rate Limiting
- **Quote Requests:** 10 per minute per IP address
- **Newsletter:** 6 per minute per IP address
- **Storage:** Database-backed (rate_limits table)
- **Automatic Cleanup:** Expired entries removed on each check

### Data Protection
- **HTTPS:** Enable SSL certificate on server
- **Error Logging:** Exceptions logged without exposing sensitive data
- **CORS Headers:** Cross-origin requests properly handled

### CSRF Protection (Optional)
- **Toggle:** `APP_CSRF_ENABLED` environment variable
- **Token Generation:** Session-based CSRF tokens
- **Validation:** Token verified on form submission

---

## 📱 Mobile Responsiveness

### Breakpoints
- **Desktop:** ≥ 1200px (lg)
- **Tablet:** 768px - 1199px (md)
- **Mobile:** < 768px (sm/xs)

### Responsive Elements

#### Header
- **Desktop:** Full horizontal menu
- **Tablet (≥ 768px):** Full menu
- **Mobile (< 768px):** Hamburger menu with sidebar

#### Navigation
- **Menu Toggle:** Click burger icon to show/hide menu
- **Animation:** Slides in from left (300ms duration)
- **Dropdown:** Sub-menus expand on click

#### Grid System
- **Services:** 4 columns (desktop) → 2 columns (tablet) → 1 column (mobile)
- **Projects:** 3 columns (desktop) → 2 columns (tablet) → 1 column (mobile)
- **Team:** 4 columns (desktop) → 2 columns (tablet) → 1 column (mobile)
- **Blog:** 3 columns (desktop) → 2 columns (tablet) → 1 column (mobile)

#### Forms
- **Input Fields:** Full width on mobile, 50% on tablet, auto on desktop
- **Buttons:** Full width on mobile for easy tapping
- **Padding:** Increased on mobile for touch-friendly interaction

#### Images
- **Scaling:** `max-width: 100%` for responsive sizing
- **Logo:** Reduces proportionally on smaller screens
- **Gallery:** Full-width images on mobile, thumbnail grid on desktop

### Testing
- ✅ Tested on 320px, 375px, 768px, 1024px, 1920px viewports
- ✅ Hamburger menu functional on all mobile sizes
- ✅ Forms responsive and submission works
- ✅ Images load and scale correctly

---

## 🗄️ Database & Backend

### Database Tables

#### 1. **quotes** Table
```sql
CREATE TABLE quotes (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  message TEXT NOT NULL,
  submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### 2. **subscribers** Table
```sql
CREATE TABLE subscribers (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  email VARCHAR(100) UNIQUE NOT NULL,
  subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

#### 3. **rate_limits** Table
```sql
CREATE TABLE rate_limits (
  id INTEGER PRIMARY KEY AUTO_INCREMENT,
  ip VARCHAR(45) NOT NULL,
  action VARCHAR(50) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### API Endpoints

#### POST `/handlers.php?action=quote`
- **Request Body:** `{ name, email, phone, message }`
- **Response:** `{ success: true/false, message: "..." }`
- **Rate Limit:** 10 per minute per IP
- **Processing:**
  1. Validate form fields
  2. Check rate limit
  3. Sanitize inputs
  4. Insert into `quotes` table
  5. Send email notification
  6. Return success message

#### POST `/handlers.php?action=subscribe`
- **Request Body:** `{ email }`
- **Response:** `{ success: true/false, message: "..." }`
- **Rate Limit:** 6 per minute per IP
- **Processing:**
  1. Validate email format
  2. Check rate limit
  3. Check for duplicate email
  4. Sanitize input
  5. Insert into `subscribers` table
  6. Send confirmation email (optional)
  7. Return success message

### Environment Variables
```bash
DB_DRIVER=sqlite              # sqlite, mysql, or pgsql
DB_HOST=localhost             # Database host
DB_NAME=app_db                # Database name
DB_USER=username              # Database user
DB_PASS=password              # Database password
ADMIN_EMAIL=info@rivernille.co.ke  # Admin email for notifications
APP_CSRF_ENABLED=1            # Enable CSRF protection (0 or 1)
```

### Configuration
- **SQLite (Default):** Auto-creates `data.db` in project root
- **MySQL:** Requires database setup on shared hosting
- **PostgreSQL:** Alternative for cloud deployments

---

## 📂 File Structure

```
RiverNille-Construction-building-html-template/
├── index.html                    # Homepage
├── about-us.html                 # About Us page
├── about-company.html            # Company Info page
├── services-1.html               # Services page 1
├── services-2.html               # Services page 2
├── projects.html                 # Projects portfolio
├── project-single.html           # Project detail
├── team.html                     # Team page
├── testimonial.html              # Testimonials page
├── blog-classic.html             # Blog classic layout
├── blog-grid.html                # Blog grid layout
├── blog-single.html              # Blog post detail
├── contact.html                  # Contact page
├── 404.html                      # Error page
├── handlers.php                  # Backend form processor
├── data.db                       # SQLite database (auto-created)
│
├── css/
│   ├── bootstrap.min.css         # Bootstrap 4 framework
│   ├── main.css                  # Custom styles
│   ├── responsive.css            # Mobile responsive styles
│   ├── slider.css                # Slider styles
│   ├── animate.min.css           # Animate.css library
│   ├── owl.carousel.css          # Carousel styles
│   ├── slick.css                 # Slick slider styles
│   ├── fontawesome.min.css       # Font Awesome icons
│   ├── flaticon.css              # Flaticon icons
│   ├── elegant-*.css             # Elegant icon fonts
│   ├── themify-icons.css         # Themify icons
│   ├── grid.css                  # Grid layout
│   ├── footer.css                # Footer styles
│   ├── image-modal.css           # Image modal styles
│   ├── odometer.min.css          # Counter animation
│   └── venobox/
│       └── venobox.css           # Lightbox gallery
│
├── js/
│   ├── main.js                   # Custom JavaScript
│   ├── image-modal.js            # Image modal handler
│   └── vendor/
│       ├── bootstrap.min.js      # Bootstrap JS
│       ├── jquery-1.12.4.min.js  # jQuery library
│       ├── owl.carousel.min.js   # Carousel plugin
│       ├── slick.min.js          # Slick slider
│       ├── wow.min.js            # WOW animation library
│       ├── animate.min.js        # Animate effects
│       ├── odometer.min.js       # Counter animation
│       ├── headroom.min.js       # Sticky header
│       ├── smooth-scroll.min.js  # Smooth scroll
│       ├── waypoints.min.js      # Scroll triggers
│       ├── venobox.min.js        # Lightbox gallery
│       ├── tether.min.js         # Tooltip positioning
│       └── modernizr-*.js        # Feature detection
│
├── img/
│   ├── logo-dark.png             # Company logo
│   ├── hero-*.jpg                # Hero section images
│   ├── service-*.jpg             # Service images
│   ├── project-*.jpg             # Project images
│   ├── team-*.jpg                # Team member photos
│   ├── testimonial-*.jpg         # Testimonial images
│   └── [other images]
│
├── fonts/
│   ├── fa-*.html                 # Font Awesome fonts
│   ├── Flaticon.html             # Flaticon fonts
│   └── [other font files]
│
├── tools/
│   └── generate_favicons.py      # Favicon generator script
│
├── .htaccess                     # Server configuration (optional)
├── .env                          # Environment variables (optional)
├── data/                         # Data directory (auto-created, if using SQLite)
│
├── DEPLOYMENT_CHECKLIST.md       # Deployment guide
└── WEBSITE_DOCUMENTATION.md      # This file

---

## ✅ Pre-deployment Checklist (Quick)

- **Full guide:** See `DEPLOYMENT_CHECKLIST.md` in the repository for the complete deployment procedure.
- **Quick steps before pushing to production:**
  1. Close all editors and terminals that may have the project open (VS Code, shells, background scripts).
  2. Run the automated audits from `pyTools`:
    - Activate the virtual env (if present): `.venv\Scripts\Activate.ps1` (PowerShell)
    - `python pyTools\final_audit.py`
    - `python pyTools\verify_security_setup.py`
  3. Ensure environment variables are set in `.env` or hosting dashboard: `ADMIN_EMAIL`, DB connection details, `APP_CSRF_ENABLED`.
  4. Verify `handlers.php` endpoint configuration and that mail is routed correctly for notifications.
  5. Ensure `data/` (or uploads) directory writable by the web server and not world-writable.
  6. Enable SSL and redirect HTTP → HTTPS at the server or CDN.
  7. Update and validate `sitemap.xml` and `robots.txt` for the production domain.
  8. Run a smoke test on a staging environment: submit quote and subscribe to newsletter to confirm end-to-end flow.
  9. Backup current production database and files before deploying.
 10. Deploy assets (minified CSS/JS), then clear CDN and server caches.
 11. Monitor logs (web server, PHP) and analytics for errors after deploy.

- **Windows rename note (if needed):** If Windows prevents renaming the project folder because a file is open, close VS Code and any terminals using the folder. To rename from PowerShell once closed:

```powershell
Rename-Item -Path "RiverNille-Construction-building-html-template" -NewName "rivernille"
```

---
```

---

## 🚀 Key Features Summary

| Feature | Status | Notes |
|---|---|---|
| Multi-page website | ✅ Complete | 14 pages with consistent design |
| Quote request system | ✅ Complete | AJAX form with DB storage |
| Newsletter subscription | ✅ Complete | Email validation & duplicate prevention |
| WhatsApp widget | ✅ Complete | Floating button with animation |
| Responsive design | ✅ Complete | Mobile hamburger menu functional |
| Image gallery | ✅ Complete | VenoBox lightbox integration |
| Project carousel | ✅ Complete | Owl Carousel auto-rotation |
| Testimonials slider | ✅ Complete | Manual navigation carousel |
| Animations | ✅ Complete | WOW.js scroll animations |
| Counter animations | ✅ Complete | Odometer.js for statistics |
| Sticky header | ✅ Complete | Headroom.js behavior |
| Rate limiting | ✅ Complete | DB-backed spam prevention |
| Security | ✅ Complete | Sanitization, prepared statements, CORS |
| SSL/HTTPS ready | ✅ Complete | Environment variable support |
| Cloud DB support | ✅ Complete | MySQL & PostgreSQL drivers |
| Error logging | ✅ Complete | Server-side logging configured |

---

## 📞 Support & Maintenance

### Common Tasks

**Adding a New Project**
1. Add project images to `/img/`
2. Create new project card on `projects.html`
3. Update `project-single.html` with details
4. Link from projects page

**Updating Services**
1. Edit `services-1.html` or `services-2.html`
2. Add/remove service cards
3. Update descriptions and images

**Blog Post Management**
1. Add new post to `blog-classic.html` or `blog-grid.html`
2. Create detailed post on `blog-single.html`
3. Update metadata (date, author, category)

**Form Submissions Review**
```bash
# Login to shared hosting
# Query database
SELECT * FROM quotes ORDER BY submitted_at DESC LIMIT 10;
SELECT * FROM subscribers ORDER BY subscribed_at DESC;
```

---

## 📊 Analytics & Tracking (Optional)

To enable analytics:
1. Add Google Analytics script to page header
2. Track form submissions and newsletter signups
3. Monitor popular pages and bounce rates
4. Identify conversion opportunities

---

## 🔄 Version History

| Version | Date | Changes |
|---|---|---|
| 1.0 | 6/02/2026 | Initial deployment-ready release |

---

**Website Status:** ✅ **PRODUCTION READY**

**Last Updated:** 6/02/2026  
**Developed by:** Brian Obonyo  
**For:** RiverNille Construction (rivernilleconstruction.co.ke)
