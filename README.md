# BMSL – Brethren Mining Solution Limited

**Reliable Mining, Industrial & HSE Solutions**

A lightweight, high-performance corporate website for Brethren Mining Solution Limited — a Tanzanian mining, industrial, engineering, construction, and HSE solutions provider.

---

## Tech Stack

| Layer      | Technology                          |
|------------|-------------------------------------|
| Frontend   | HTML5, CSS3, Vanilla JavaScript     |
| Backend    | PHP 8+, PDO                         |
| Database   | MySQL (minimal — single table)      |
| Deployment | GitHub Actions → FTP/SFTP           |
| Hosting    | Apache, shared hosting, 1GB SSD     |

**Zero heavy dependencies.** No Node.js, React, Vue, Bootstrap, Tailwind, or jQuery.

---

## Project Goal

Deliver a production-ready corporate website that:

- Loads extremely fast (Lighthouse 95+ across all categories)
- Looks modern and professional
- Requires almost zero maintenance
- Deploys automatically via GitHub Actions
- Runs on inexpensive shared hosting
- Accurately represents BMSL as a trusted industry partner

---

## Design Philosophy

Professionalism, reliability, and industrial excellence. No flashy effects, no unnecessary animations, no oversized images. Clean typography (Inter), generous spacing, minimal color palette, rounded corners, soft shadows.

| Color           | Code      |
|-----------------|-----------|
| Deep Navy       | `#0B1F33` |
| Safety Orange   | `#F7941D` |
| Industrial Yell.| `#FFC107` |
| White           | `#FFFFFF` |
| Section BG      | `#F8F9FA` |
| Dark Gray       | `#2E2E2E` |
| Muted           | `#6B7280` |

---

## Sections (Single Page)

1. **Header** — Sticky, transparent→solid, hamburger on mobile
2. **Hero** — Full-width background, overlay, CTA buttons
3. **About** — Company background (Tanzania, mining, industrial, engineering)
4. **Vision, Mission & Values** — Three cards (Integrity, Honesty, Ownership, Innovation, Safety, Teamwork)
5. **Why Choose Us** — Six cards with icons
6. **Services** — Eight service cards (PPE, Maintenance, Construction, Fabrication, Electrical, Branding, HSE, Mining Support)
7. **Team** — Photo, name, role, experience, bio
8. **Commitment** — Centered section with CTA
9. **Legal Compliance** — Certificate of Incorporation & TRA Registration previews (modal on click)
10. **Contact** — Company info, embedded Google Map, contact form
11. **Footer** — Logo, quick links, social, copyright, back-to-top

---

## Folder Structure

```
/
├── index.php
├── assets/
│   ├── css/
│   ├── js/
│   ├── images/
│   ├── icons/
│   └── fonts/
├── includes/
│   ├── config.php
│   ├── database.php
│   ├── header.php
│   └── footer.php
├── uploads/
├── database/
│   └── schema.sql
├── .github/workflows/
│   └── deploy.yml
├── .htaccess
├── robots.txt
├── sitemap.xml
└── README.md
```

---

## Database

Minimal. One table for contact messages:

**`contact_messages`** — `id`, `name`, `email`, `phone`, `company`, `message`, `created_at`

All queries use prepared statements. No sensitive credentials in the repository — `.env` configuration.

---

## Performance Targets

| Category       | Target |
|----------------|--------|
| Performance    | 95+    |
| Accessibility  | 95+    |
| SEO            | 95+    |
| Best Practices | 95+    |
| First Load     | < 2MB  |

WebP images (max 1600px, 250KB), lazy loading, compressed assets, deferred JavaScript, minimal HTTP requests.

---

## Security

- Escaped output
- Prepared statements / PDO
- CSRF tokens on forms
- Rate-limited contact form
- Server-side + client-side validation
- Spam protection
- `.env` for credentials (never committed)

---

## CI/CD — GitHub Actions

On push to `main`:

1. Validate HTML
2. Validate CSS
3. PHP lint
4. Minify CSS & JS
5. Deploy via FTP/SFTP
6. Preserves `uploads/` directory

---

## Accessibility & SEO

- Semantic HTML5, heading hierarchy, ARIA labels, keyboard navigation, high contrast, alt text on all images
- Unique title, meta description, Open Graph tags, Twitter cards, canonical URL
- Organization Schema + Local Business Schema (JSON-LD)
- `robots.txt` + `sitemap.xml`

---

## Future Expansion

Architecture supports adding Projects, Gallery, News, Blog, Admin Dashboard, Client Portal, and Quotation System without major restructuring.

---

## License

Proprietary — Brethren Mining Solution Limited. All rights reserved.
