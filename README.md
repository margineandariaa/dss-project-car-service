# AutoServ — Car Service & Repair Booking Platform

A web-based decision support system that allows car owners to browse available services, view details, and book appointments at an auto service center.

Built with **PHP**, **MySQL**, and **Bootstrap 5**. Runs locally using **XAMPP**.

---

## Project Structure

```
carservice/
├── includes/
│   ├── header.php          # Shared navigation and HTML head
│   └── footer.php          # Shared footer
├── index.php               # Home page
├── register.php            # User registration
├── login.php               # User login
├── logout.php              # Session logout
├── categories.php          # Service categories listing
├── services.php            # Services filtered by category
├── service_details.php     # Individual service details page
├── booking.php             # Appointment booking form
├── confirmation.php        # Booking success page
├── my_appointments.php     # User's appointment history
├── db.php                  # Database connection
└── database.sql            # Database schema and seed data
```

---

## Team Members & Responsibilities

### Cristina
**Module: Home Page, Layout & Authentication**

Responsible for the overall look and feel of the platform and the user authentication system.

| File | Description |
|------|-------------|
| `includes/header.php` | Shared navigation bar, HTML head, global CSS styles |
| `includes/footer.php` | Shared footer with contact info and navigation links |
| `index.php` | Home page — hero section, featured services, category overview, stats bar, CTA |
| `register.php` | User registration form with validation |
| `login.php` | User login with session handling |
| `logout.php` | Session destruction and redirect |
| `db.php` | Database connection using PDO |
| `database.sql` | Full database schema (tables: users, categories, services, bookings) and seed data |

---

### Daria
**Module: Services, Booking Flow & Appointments**

Responsible for the core functionality of the platform — browsing services and the complete booking flow.

| File | Description |
|------|-------------|
| `categories.php` | Lists all service categories with service count |
| `services.php` | Displays services filtered by selected category |
| `service_details.php` | Full details page for a single service, includes related services |
| `booking.php` | Appointment booking form — service selection, car details, date & time picker |
| `confirmation.php` | Booking confirmation page with full summary and reference number |
| `my_appointments.php` | Logged-in user's appointment history with cancel functionality |

---

## How to Run Locally

### Requirements
- [XAMPP](https://www.apachefriends.org/) with Apache and MySQL running

### Setup Steps

1. Clone or download this repository into your XAMPP `htdocs` folder:
   ```
   C:\xampp\htdocs\carservice\
   ```

2. Open **phpMyAdmin** at `http://localhost/phpmyadmin`

3. Create a new database named `carservice`

4. Import the `database.sql` file — this creates all tables and adds sample data

5. Open your browser and go to:
   ```
   http://localhost/carservice
   ```

> **Note:** The default database credentials in `db.php` are `root` with no password, which matches the XAMPP default. If your setup is different, update `db.php` accordingly.

---

## Features

- User registration, login, and logout
- Browse service categories (Engine & Oil, Tires, Brakes, Electrical, Bodywork, Air Conditioning)
- View all services within a category with pricing and duration
- Detailed service page with included items and related services
- Appointment booking with car details and time slot selection
- Booking confirmation page with reference number
- "My Appointments" page with booking status and cancel option

---

## Database Tables

| Table | Description |
|-------|-------------|
| `users` | Registered user accounts |
| `categories` | Service categories |
| `services` | Individual services linked to categories |
| `bookings` | Appointment records linked to users and services |

---

## Assignment Info

**Course:** Web Technologies Lab  
**University:** Lucian Blaga University of Sibiu  
**Team Topic:** Car Service & Repair Booking Platform  
**Supervisor:** dumitrualexandru.mara@ulbsibiu.ro
