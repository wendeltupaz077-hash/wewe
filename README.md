# BloodMap PH - Smart Blood Bank

BloodMap PH is a centralized blood inventory tracking, component-aware emergency requests, geolocation donor matching, and facility-to-PRC escalation system for blood banks.

## Finalized Scope

**Included Features:**
- Component-aware inventory for whole blood, packed RBC, platelets, plasma, and irradiated units
- Expiry and freshness monitoring per facility
- Emergency request escalation: local facility stock → nearby facility stock → donor network → PRC
- Donor registration, verification status, cooldown, and deferral tracking
- Email verification link for admin onboarding
- GPS/geolocation donor and facility matching
- In-app notification support
- Role-based access for super admin, admin, facility heads/staff, donors, and registered users
- Public stock status view for transparency
- Admin management module (CRUD operations)

**Excluded Features:**
- Patient/family blood replacement obligation tracking
- QR/barcode physical blood bag tracking
- AI demand forecasting
- Public exposure of donor contact details

## Project Structure

```
BLOODPH/
├── bloodmap_api/       # Laravel REST API and web portal backend
├── bloodmap_mobile/    # Flutter mobile application (Android/iOS/Web)
└── README.md           # This file
```

## Architecture

```
INTERNET
    ├── Public Web Site: Laravel (PHP) + Blade
    ├── Admin/Facility Portal: Laravel (PHP) + Blade
    ├── Flutter Mobile App → Laravel REST API → SQLite Database
    └── Laravel REST API → SQLite Database
```

## Core Flows

### 1. Super Admin Onboarding
- Pre-seeded super admin: `superadmin@bloodmap.ph` / `Password123!`
- First login requires password change
- Super admin can manage all admin accounts

### 2. Admin Onboarding
- Admin enters email on login page
- System sends email verification link (signed URL)
- Admin clicks "Yes, it's me" link
- Admin sets password (min 8 characters)
- Account is activated and ready for use

### 3. Admin Management (Super Admin Only)
- View all admin accounts (super admin and admin)
- Create new admins (with or without initial password)
- Edit admin details (name, email, role, status)
- Reset admin password
- Delete admin (cannot delete self)
- Track last login time for each admin

### 4. Portal Dashboard
- Real-time Philippine Standard Time display
- Key metrics: facilities, available units, active requests, donors, near-expiry units
- Recent emergency requests table
- Quick actions for inventory, requests, donors, reports, and public stock view

### 5. Component-Aware Inventory
- Track blood units by type (A+, A-, B+, B-, O+, O-, AB+, AB-)
- Track by component: whole blood, packed RBC, platelets, plasma
- Expiry date tracking with freshness flags (fresh = ≤14 days old)
- Near-expiry alerts (≤7 days)
- FIFO (First In, First Out) sorting by expiry

### 6. Emergency Request Escalation
1. **Local Stock Check:** Check own facility inventory first, prioritize fresh units
2. **Nearby Facilities:** Geolocation search for compatible stock in nearby facilities
3. **Donor Network:** Notify eligible nearby donors (verified first, then unconfirmed)
4. **PRC Chapter:** Formal requisition to Philippine Red Cross if previous tiers insufficient

### 7. Public Stock Status
- Anonymous public view of all partner facilities' current stock
- No donor or patient information exposed
- Stock status indicators: normal, low, critical
- Total available units per facility

### 8. Privacy Compliance
- Donor contact details are never publicly exposed (RA 10173 / Data Privacy Act)
- All requests relayed through system-mediated channels
- Role-based access controls (RBAC) for all portal features

### 9. Phone Registration & OTP Verification
1. **Phone number entry:** Donor enters a Philippine mobile number in the Flutter app.
2. **Backend OTP generation:** Laravel API validates the phone number and saves a 6-digit OTP record with a 5-minute expiration.
3. **SMS dispatch:** The API sends the OTP via SMS using the SMSAPI Android Gateway (`/send` endpoint) with `uid`, `phone`, `message`, and `x-api-key` header.
4. **Real SMS received:** The donor receives the actual OTP code on their phone.
5. **OTP submission:** Donor enters the code in the app verification screen.
6. **Backend verification:** The Laravel API checks the code, marks it used, and completes registration only if the OTP is valid and unexpired.

## Quick Start

### Prerequisites
- PHP 8.2+
- Composer
- SQLite

### Laravel API & Web Portal Setup

```powershell
cd bloodmap_api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve --host=0.0.0.0 --port=8000
```

**Important:** Update `APP_URL` in `.env` with your computer's Wi-Fi IP address (e.g., `http://192.168.100.73:8000`) if testing on physical mobile devices. Also, configure your SMTP settings in `.env` (MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD, MAIL_FROM_ADDRESS).

### Flutter Mobile App Setup

```powershell
cd bloodmap_mobile/smartblood
flutter pub get
flutter run -d chrome  # or your preferred device/emulator
```

**Note:** For physical Android/iOS devices, update `AppConstants.apiBaseUrl` in `lib/config/constants.dart` to use your computer's Wi-Fi IP (e.g., `http://192.168.100.73:8000/api/v1`).


## Recent Updates

- **OTP send/receive fixed:** Backend now calls SMSAPI `/send` endpoint with correct `uid`, `phone`, `message`, and `x-api-key` header so real OTP codes can be delivered.
- **OTP Expiration:** Changed from 5 minutes to 3 minutes
- **CORS Middleware:** Added proper CORS middleware for API requests from Flutter app
- **Fixed Personal Access Tokens Table:** Ran migrations to create `personal_access_tokens` table
- **Phone Number Validation:** Restricted to 10 digits, starts with 9, permanent +63 prefix
- **OTP Input Centering:** Fixed layout for OTP verification screen
- **Updated Email Verification:** `MAIL_FROM_ADDRESS` now matches Gmail authenticated address

### Default Credentials

- **Super Admin:** `superadmin@bloodmap.ph` / `Password123!`
- **Admin:** `admin@smartblood.ph` / `Password123`

## Technologies

| Layer | Technology |
|-------|------------|
| Backend | Laravel 11 (PHP) |
| Database | SQLite |
| Templating | Blade |
| Styling | Custom CSS (Modern Design System) |
| Geolocation | GPS/Geolocation API |
| Notifications | Email SMTP (Gmail) |

## License

Educational / Capstone Project - BloodMap PH

<!-- AUTO_SUMMARY_START -->
**Auto-generated summary (generated on 2026-07-11 14:14:17)**

**Migrations:**
- migrations/0001_01_01_000000_create_users_table.php
- migrations/0001_01_01_000001_create_cache_table.php
- migrations/0001_01_01_000002_create_jobs_table.php
- migrations/2026_07_09_000001_create_smart_blood_schema.php
- migrations/2026_07_09_160035_add_admin_fields_to_users_table.php
- migrations/2026_07_11_000001_create_personal_access_tokens_table.php
- migrations/2026_07_11_000001_make_users_email_nullable.php
- migrations/2026_07_11_120000_add_preferences_to_users.php

**Portal Routes (named):**
- about
- admins.
- api.notifications.latest
- api.notifications.unread-count
- auth-notice
- contact
- create
- dashboard
- destroy
- donors
- download
- edit
- facilities.
- features
- first-login
- first-login.submit
- forgot-password
- forgot-password.submit
- handle-email
- home
- how-it-works
- index
- inventory
- login
- login.submit
- logout
- notifications
- notifications.mark-all-read
- notifications.mark-read
- portal.
- privacy
- reports
- requests
- reset-password
- set-password
- set-password.submit
- settings
- settings.update
- stock
- stock-status
- store
- toggle-lock
- update
- users.
- verify-email

**Portal Controllers:**
- Controllers/Portal/AdminController.php
- Controllers/Portal/AuthController.php
- Controllers/Portal/DashboardController.php
- Controllers/Portal/DonorController.php
- Controllers/Portal/FacilityController.php
- Controllers/Portal/InventoryController.php
- Controllers/Portal/NotificationController.php
- Controllers/Portal/ReportController.php
- Controllers/Portal/RequestController.php
- Controllers/Portal/SettingsController.php
- Controllers/Portal/StockController.php
- Controllers/Portal/UserController.php

**Portal Views:**
- views/portal/about.blade.php
- views/portal/auth-notice.blade.php
- views/portal/dashboard.blade.php
- views/portal/donors.blade.php
- views/portal/facilities.blade.php
- views/portal/first-login.blade.php
- views/portal/forgot-password.blade.php
- views/portal/inventory.blade.php
- views/portal/login.blade.php
- views/portal/notifications.blade.php
- views/portal/privacy.blade.php
- views/portal/reports.blade.php
- views/portal/requests.blade.php
- views/portal/set-password.blade.php
- views/portal/settings.blade.php
- views/portal/stock.blade.php

**Notes:**
- Preferences JSON column added to `users` via migration.
- Settings view updated with preference toggles and dark-mode preview.

<!-- AUTO_SUMMARY_END -->
#   p r a k t e s  
 