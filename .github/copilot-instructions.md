# Community Security System - AI Copilot Instructions

## Project Overview

**ระบบรักษาความปลอดภัยในหมู่บ้าน** (Community Security System) is a residential security dashboard written in PHP with Bootstrap 5. The system tracks resident access/exit, vehicle registrations, security logs, and emergency alerts. It's a server-side rendered application running on Apache/XAMPP.

### Architecture
- **Stack**: PHP 7+, Bootstrap 5.3.2, HTML5, CSS3
- **Deployment**: XAMPP (Apache + MySQL)
- **Entry Points**: index.php (home), user.php (access control), security.php (security logs)
- **Shared Components**: master/navbar.php (included in all pages)
- **Language**: Thai (ไทย) - all UI text, comments, and placeholders in Thai

---

## Key Files & Their Roles

| File | Purpose |
|------|---------|
| [index.php](index.php) | Dashboard homepage - displays KPIs (287 daily entries, 15,420 vehicle scans, 75% traffic level, 3 emergencies) |
| [user.php](user.php) | Access control & entry/exit tracking module |
| [security.php](security.php) | Security logs and incident recording interface |
| [master/navbar.php](master/navbar.php) | Shared navigation bar (included in all pages) |
| [image/logo.jpg](image/logo.jpg) | Brand logo for navbar |

---

## Code Patterns & Conventions

### Page Structure Template
Every main page (index.php, user.php, security.php) follows this pattern:
1. PHP include for shared navbar: `<?php include('master/navbar.php'); ?>`
2. Bootstrap responsive grid layout (col-sm-12, col-md-6, col-lg-3)
3. `.content-section` wrapper for consistent padding/margins
4. `.data-card` components for info boxes (used in index.php KPIs)
5. Bootstrap 5 JS bundle script at end: `<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>`

**Example data card (from index.php)**:
```php
<div class="col-sm-12 col-md-6 col-lg-3 text-center">
    <div class="data-card bg-primary text-white">
        <h1>287</h1>
        <h4>ผู้เข้าออกวันนี้</h4>
    </div>
</div>
```

### Styling Approach
- Bootstrap utility classes for layout (container, row, col, text-center, bg-primary, text-white)
- Custom `.data-card` class: border + shadow + padding for elevated UI elements
- Color palette: bg-primary, bg-info, bg-warning, bg-danger (Bootstrap defaults)
- Responsive breakpoints: sm (mobile), md (tablet), lg (desktop)

### Navigation Convention
The [master/navbar.php](master/navbar.php) defines site structure with 4 main sections:
- หน้าหลัก (Home/index.php)
- ด่านตรวจผู้เข้าออก (Access Control/user.php)
- บันทึกรักษาความปลอดภัย (Security Logs/security.php)
- รายงาน (Reports/index.php placeholder)

When adding new pages, add corresponding `<li>` items to the navbar.

---

## Thai Language Localization

All content is in Thai. When adding new features:
- Use Thai labels/headings for UI elements
- Use Thai comments (prefixed with `//`) for code explanations
- Follow existing terminology:
  - ผู้เข้าออก = entry/exit (access)
  - ทะเบียนรถ = vehicle registration
  - บันทึก = logs/records
  - ระบบแจ้งเตือน = alert system
  - เหตุฉุกเฉิน = emergency incident

---

## Development Workflow

### Running the Application
1. Place project in `C:\xampp\htdocs\security\`
2. Start Apache via XAMPP Control Panel
3. Access http://localhost/security/index.php

### Adding New Features
1. Create new .php file in root (or subdirectory like `modules/`)
2. Include navbar at top: `<?php include('master/navbar.php'); ?>`
3. Use `.content-section` wrapper for consistent spacing
4. Update navbar navigation if creating a primary module
5. Link assets: Bootstrap CSS in `<head>`, Bootstrap JS at `</body>`

### Data Storage (Future Integration)
- Structure prepared for MySQL (XAMPP included)
- No database queries currently visible in codebase
- Use prepared statements when implementing DB features (security against SQL injection)

---

## Common Tasks

### Adding a New Dashboard Metric
Follow the data-card pattern in [index.php](index.php) (lines 42-56):
- Wrap in `<div class="col-sm-12 col-md-6 col-lg-3 text-center">`
- Use `.data-card` with color class (bg-primary, bg-info, bg-warning, bg-danger)
- Include large number `<h1>` and descriptive `<h4>` in Thai

### Adding a Form or Table
Use Bootstrap 5 components. Example for data tables:
- Wrap tables in `<div class="data-table-container">` for responsive overflow
- Use Bootstrap table classes: `table`, `table-striped`, `table-hover`

### Modifying Navbar
Edit [master/navbar.php](master/navbar.php) - update `<ul class="navbar-nav">` section. Remember:
- Keep links relative: `href="index.php"` not full URLs
- Maintain active state: only first link has `class="nav-link active"`

---

## Important Notes

- **No Backend Database Seen**: Current files are frontend-only with hardcoded metrics. Expect database integration in user.php and security.php
- **Bootstrap Version**: 5.3.2 via CDN - no Node.js/npm build step required
- **Charset**: UTF-8 encoding required for Thai text
- **Meta Viewport**: All pages include responsive viewport meta tag

---

## Quick Reference: Files to Edit for Common Scenarios

| Scenario | Primary Files |
|----------|---------------|
| Update dashboard metrics | index.php |
| Add access control logic | user.php, consider master/navbar.php update |
| Add security alert form | security.php |
| Shared styling across all pages | master/navbar.php, index.php (style tag) |
| Add new main section | Create .php file, update master/navbar.php |
