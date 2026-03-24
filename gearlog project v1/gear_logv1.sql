CREATE DATABASE gear_logv1;

USE gear_logv1;

CREATE TABLE categories(
	id INT PRIMARY KEY AUTO_INCREMENT ,
    name VARCHAR(100)
);

INSERT INTO categories(name) VALUES 
('laptop'),
('monitor'),
('printer'),
('server');

CREATE TABLE assets (
	id INT PRIMARY KEY AUTO_INCREMENT ,
    serial_number VARCHAR(100) UNIQUE ,
	device_name VARCHAR (100) NOT NULL ,
	price DECIMAL(10,2),
    status ENUM ('Available', 'Deployed', 'Under Repair') ,
    category_id INT ,
    FOREIGN KEY(category_id) REFERENCES categories(id)
);

INSERT INTO assets (serial_number, device_name, price, status, category_id) VALUES 
('SN12345', 'Dell Laptop', 800.00, 'Available', 1),
('SN67890', 'HP Monitor', 300.00, 'Deployed', 2),
('SN11111', 'IBM Server', 1500.00, 'Under Repair', 4);

