-- Saffron & Salt — database setup
-- Run once on your MySQL server via phpMyAdmin or CLI

CREATE DATABASE IF NOT EXISTS saffron_salt CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE saffron_salt;

DROP TABLE IF EXISTS contact_messages;
DROP TABLE IF EXISTS reservations;
DROP TABLE IF EXISTS menu_items;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE menu_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  category ENUM('starter','grill','pot','sweet') NOT NULL,
  name VARCHAR(100) NOT NULL,
  description TEXT,
  price DECIMAL(5,2) NOT NULL,
  allergens VARCHAR(200),
  image VARCHAR(255),
  featured TINYINT(1) DEFAULT 0
);

CREATE TABLE reservations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT DEFAULT NULL,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  phone VARCHAR(20),
  date DATE NOT NULL,
  time TIME NOT NULL,
  guests TINYINT NOT NULL,
  notes TEXT,
  status ENUM('confirmed','cancelled') DEFAULT 'confirmed',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE contact_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100),
  email VARCHAR(150),
  subject VARCHAR(200),
  message TEXT,
  sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seed menu items
INSERT INTO menu_items (category, name, description, price, allergens, image, featured) VALUES
-- Starters
('starter', 'Pomegranate & Walnut Salad', 'Bitter leaves, toasted walnuts, pomegranate seeds, fresh mint, sherry vinaigrette', 8.50, 'nuts', 'images/pomegranate-salad.jpg', 1),
('starter', 'Kashk-e Bademjan', 'Roasted aubergine whipped with kashk (whey), caramelised onion, dried mint oil, served with warm flatbread', 9.00, 'dairy, gluten', 'images/kashk.jpg', 0),
('starter', 'Herb Frittata (Kuku Sabzi)', 'Persian-style herb frittata with fenugreek, parsley, dill, dried barberries — served at room temperature', 7.50, 'eggs', 'images/kuku.jpg', 0),
('starter', 'Smoked Hummus', 'Home-made chickpea hummus, smoked paprika oil, dukkah, warm sesame flatbread', 7.00, 'sesame, gluten', 'images/hummus.jpg', 0),

-- Grill
('grill', 'Saffron Chicken Skewers', 'Free-range Yorkshire chicken marinated overnight in saffron, lemon and yoghurt — grilled over charcoal, served with saffron rice and grilled tomato', 18.50, 'dairy', 'images/chicken-skewers.jpg', 1),
('grill', 'Koobideh Lamb', 'Hand-minced Dales lamb with grated onion, turmeric and cinnamon, formed on flat skewers — served with flatbread, sumac onion and herb butter rice', 21.00, 'gluten', 'images/koobideh.jpg', 0),
('grill', 'Grilled Sea Bass', 'Whole sea bass stuffed with preserved lemon and fresh tarragon, grilled and finished with chermoula', 24.00, 'fish', 'images/sea-bass.jpg', 0),
('grill', 'Halloumi & Vegetable Skewers', 'Grilled halloumi, courgette, red pepper and red onion on skewers — pomegranate molasses glaze, walnut pesto', 15.50, 'dairy, nuts', 'images/halloumi-skewers.jpg', 0),

-- From the Pot
('pot', 'Lamb Khoresh', 'Slow-braised Dales lamb shoulder in a rich sauce of caramelised onion, turmeric, dried lemon and fenugreek — served with saffron-crusted rice', 22.00, NULL, 'images/khoresh.jpg', 1),
('pot', 'Ghormeh Sabzi', 'Iran\'s most beloved stew: herb-heavy, dark and deeply savoury with kidney beans, dried limes and tender lamb — a two-day cook', 22.50, NULL, 'images/ghormeh.jpg', 0),
('pot', 'Chickpea & Spinach Tagine', 'North African-spiced chickpea and spinach stew with preserved lemon and harissa, served with couscous and yoghurt', 16.00, 'dairy, gluten', 'images/tagine.jpg', 0),

-- Sweets & Drinks
('sweet', 'Persian Love Cake', 'Cardamom and rose water almond cake with whipped labneh, crystallised rose petals and a drizzle of saffron honey', 8.00, 'nuts, dairy, eggs, gluten', 'images/love-cake.jpg', 0),
('sweet', 'Saffron & Rosewater Ice Cream', 'House-churned ice cream with saffron, rosewater and pistachio — served in a chilled bowl', 6.50, 'dairy, nuts', 'images/ice-cream.jpg', 0),
('sweet', 'Rose Lemonade', 'Fresh lemon juice, house-made rose syrup, sparkling water — served over crushed ice', 4.50, NULL, 'images/rose-lemonade.jpg', 0),
('sweet', 'Doogh', 'Traditional Persian yoghurt drink, lightly salted with dried mint — still or sparkling', 3.50, 'dairy', 'images/doogh.jpg', 0);
