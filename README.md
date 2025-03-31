
Note: The folder must be located in htdocs folder. If placed in another folder, the path in the code must be changed.
1. Clone code folder into htdocs folder
2. CLient: http://localhost/booking-hotel-php/
3. Admin: http://localhost/booking-hotel-php/admin/
4. Use code mySQL create database

***********************************************************************
# Create database

CREATE DATABASE IF NOT EXISTS db_booking;
USE db_booking;

CREATE TABLE IF NOT EXISTS admin_cred (
    sr_no INT AUTO_INCREMENT PRIMARY KEY,
    admin_name VARCHAR(50) NOT NULL,
    admin_pass VARCHAR(255) NOT NULL
);

INSERT INTO admin_cred (admin_name, admin_pass) VALUES
('admin', '123456'),

CREATE TABLE IF NOT EXISTS settings (
    sr_co INT AUTO_INCREMENT PRIMARY KEY,
    site_title VARCHAR(50) NOT NULL,
    site_about VARCHAR(255) NOT NULL,
    shutdown BOOLEAN NOT NULL DEFAULT FALSE
);

***********************************************************************
