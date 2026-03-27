CREATE TABLE users (
	id INT AUTO_INCREMENT PRIMARY KEY ,
	username VARCHAR(100) NOT NULL,
	email VARCHAR(150) UNIQUE ,
	password VARCHAR(255) NOT NULL
);

CREATE TABLE categories (
	id INT AUTO_INCREMENT PRIMARY KEY ,
    name VARCHAR(150) NOT NULL 
);

CREATE TABLE prompts (
	id INT AUTO_INCREMENT PRIMARY KEY ,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL ,
    user_id INT NOT NULL,
    category_id INT NOT NULL
);

ALTER TABLE prompts
ADD CONSTRAINT fk_user
FOREIGN KEY (user_id) REFERENCES users(id)
ON DELETE CASCADE;

ALTER TABLE prompts
ADD CONSTRAINT fk_category
FOREIGN KEY (category_id) REFERENCES categories(id)
ON DELETE CASCADE;

INSERT INTO categories (name)
VALUES 
('Code'),
('Marketing'),
('DevOps'),
('SQL'),
('Testing');

ALTER TABLE users 
ADD role ENUM('user', 'admin') NOT NULL DEFAULT 'user';


UPDATE users 
SET role = 'admin' 
WHERE email = 'admin@gmail.com';