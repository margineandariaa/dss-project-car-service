CREATE DATABASE IF NOT EXISTS carservice;
USE carservice;

-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Categories table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    icon VARCHAR(100)
);

-- Services table
CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    duration_minutes INT NOT NULL,
    image VARCHAR(255),
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);

-- Bookings table
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    service_id INT NOT NULL,
    car_make VARCHAR(100) NOT NULL,
    car_model VARCHAR(100) NOT NULL,
    car_year YEAR NOT NULL,
    car_plate VARCHAR(20),
    booking_date DATE NOT NULL,
    booking_time TIME NOT NULL,
    notes TEXT,
    status ENUM('pending','confirmed','completed','cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);

-- Seed: Categories
INSERT INTO categories (name, description, icon) VALUES
('Engine & Oil', 'Engine diagnostics, oil changes, and general engine maintenance.', 'fa-cogs'),
('Tires & Wheels', 'Tire fitting, balancing, rotation, and wheel alignment.', 'fa-circle-notch'),
('Brakes', 'Brake pad replacement, disc resurfacing, and full brake system checks.', 'fa-stop-circle'),
('Electrical & Diagnostics', 'Battery, alternator, wiring, and full computer diagnostics.', 'fa-bolt'),
('Bodywork & Paint', 'Dent repair, scratch removal, and full respray services.', 'fa-paint-brush'),
('Air Conditioning', 'AC recharge, leak detection, and full climate system service.', 'fa-snowflake');

-- Seed: Services
INSERT INTO services (category_id, name, description, price, duration_minutes) VALUES
-- Engine & Oil
(1, 'Standard Oil Change', 'Drain and replace engine oil with a new filter. Includes top-up of all fluids.', 49.99, 30),
(1, 'Full Engine Diagnostics', 'Computer scan of all engine systems. Detailed fault report provided.', 79.99, 60),
(1, 'Timing Belt Replacement', 'Remove and replace timing belt, tensioner, and water pump.', 299.99, 180),
(1, 'Engine Flush', 'Full flush of the engine lubrication system before oil change.', 39.99, 45),

-- Tires & Wheels
(2, 'Tire Fitting (x4)', 'Fit and balance four new tires. Old tires disposed of responsibly.', 59.99, 60),
(2, 'Wheel Alignment', 'Four-wheel alignment check and adjustment using laser equipment.', 49.99, 45),
(2, 'Tire Rotation', 'Rotate all four tires to ensure even tread wear.', 29.99, 30),

-- Brakes
(3, 'Front Brake Pad Replacement', 'Replace front brake pads and inspect discs.', 89.99, 60),
(3, 'Full Brake System Check', 'Inspect pads, discs, callipers, fluid, and brake lines.', 49.99, 45),
(3, 'Brake Fluid Replacement', 'Flush and replace brake fluid to manufacturer spec.', 39.99, 30),

-- Electrical & Diagnostics
(4, 'Battery Replacement', 'Test and replace car battery. Includes disposal of old unit.', 99.99, 30),
(4, 'Full Electrical Diagnostic', 'Scan all electronic modules, identify fault codes.', 89.99, 60),
(4, 'Alternator Replacement', 'Remove and replace failing alternator unit.', 199.99, 120),

-- Bodywork & Paint
(5, 'Minor Dent Repair (PDR)', 'Paintless dent repair for small dents without repainting.', 79.99, 60),
(5, 'Scratch Removal & Polish', 'Remove light surface scratches and apply protective polish.', 59.99, 60),
(5, 'Panel Respray', 'Full sanding and respray of a single body panel.', 249.99, 240),

-- Air Conditioning
(6, 'AC Gas Recharge', 'Recharge air conditioning refrigerant to correct level.', 69.99, 45),
(6, 'AC System Service', 'Full inspection, leak test, recharge, and cabin filter replacement.', 119.99, 90);
