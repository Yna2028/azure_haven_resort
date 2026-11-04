# Azure Haven Resort — website prototype

A responsive PHP + MySQL resort website designed as a Figma-ready prototype. Includes public landing page, guest sign-up/login, guest booking dashboard, and admin reservation management.

## Requirements
- XAMPP (Apache + PHP + MySQL/MariaDB)
- Browser
- Optional: Figma for recreating the UI as editable design frames

## Run locally
1. Copy the `azure_haven_resort` folder into `C:\xampp\htdocs\`.
2. Start **Apache** and **MySQL** in the XAMPP Control Panel.
3. Open `http://localhost/phpmyadmin`.
4. Import `database.sql` (select the file from this project). It creates the `azure_haven` database, sample rooms, and demo admin.
5. Confirm `config.php` uses your local MySQL credentials (default XAMPP is user `root`, blank password).
6. Open `http://localhost/azure_haven_resort/`.

## Demo admin
- Email: `admin@azurehaven.test`
- Password: `Admin123!`

If the demo admin login does not work on your PHP/MySQL environment, create the admin password hash locally with PHP:
`php -r "echo password_hash('Admin123!', PASSWORD_DEFAULT), PHP_EOL;"`
Then replace the `password` value for the admin row in phpMyAdmin. For safety, change the demo password before deploying publicly.

## Main routes
- `index.php` — public resort landing page
- `signup.php` — guest registration
- `login.php` — shared login; routes admins to admin dashboard
- `dashboard.php` — guest reservation requests and booking status
- `admin/dashboard.php` — booking overview and status management
- `logout.php` — sign out

## Figma prototype plan
Create a Figma file named **Azure Haven Resort** and add pages:
1. **Design System** — colors: Deep Navy `#173B4C`, Teal `#6D9B9A`, Sand `#F4F0E8`, Ink `#1C3038`; fonts: DM Sans + Playfair Display.
2. **Guest Website** — Desktop 1440×900 and Mobile 390×844 frames. Build Header, Hero, Experience cards, Room cards, Quote CTA, Footer.
3. **Guest Portal** — Sign Up, Login, Guest Dashboard, Reservation Submitted, Reservation History.
4. **Admin Portal** — Admin Login, Dashboard KPI cards, Reservation Table, Status Update state.
5. **Prototype Flow** — connect Book your stay → Sign Up → Guest Dashboard; Log in → Guest Dashboard; Admin Login → Admin Dashboard; status dropdown/save → updated status state. Use Smart Animate for menu/confirmation states.

Use Auto Layout for navigation, cards, forms, tables, and responsive stacks. The photos in the PHP prototype are remote Unsplash demo images; replace with licensed resort imagery before launch.

## Important prototype limitations
- This is a local demo, not production hosting.
- Payment gateway is not connected.
- Booking overlap/room availability validation, email verification, password reset, CSRF protection, rate limiting, and detailed admin role management should be added before public launch.
- The SQL demo admin hash may need replacement using the PHP command above if login fails.
