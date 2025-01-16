-- Opret databasen, hvis den ikke findes
CREATE DATABASE IF NOT EXISTS `cjsfkt3sf_cruisenightscinema`;
USE `cjsfkt3sf_cruisenightscinema`;


CREATE TABLE IF NOT EXISTS `customers` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CHECK (name <> ''),
    CHECK (email LIKE '%_@__%.__%')
);



CREATE TABLE IF NOT EXISTS `employees` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL UNIQUE,
    role VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    CHECK (role <> ''),
    CHECK (LENGTH(phone) BETWEEN 8 AND 15)
);

-- Create genres table
CREATE TABLE genres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

-- Create actors table
CREATE TABLE actors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    birthdate DATE DEFAULT NULL
);


-- Opret tabel for film
CREATE TABLE IF NOT EXISTS `movies` (
    id CHAR(36) PRIMARY KEY,     
    slug VARCHAR(255) UNIQUE,     
    title VARCHAR(255), 
    director VARCHAR(255),
    release_year INT,
    age_limit ENUM('G', 'PG', 'PG-13', 'R', 'NC-17', '18+', 'U') DEFAULT 'U', 
    booking_count INT DEFAULT 0,
    description TEXT,
    length TIME,
    status ENUM('available', 'archived', 'coming_soon') NOT NULL DEFAULT 'available',
    premiere_date DATE,
    language ENUM('Engelsk', 'Dansk', 'Tysk') NOT NULL DEFAULT 'Engelsk',
    poster VARCHAR(255)
    
);

-- Create movie_genre relationship table
CREATE TABLE movie_genre (
    movie_id CHAR(36),
    genre_id INT,
    PRIMARY KEY (movie_id, genre_id),
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
    FOREIGN KEY (genre_id) REFERENCES genres(id) ON DELETE CASCADE
);

-- Create movie_actor relationship table
CREATE TABLE movie_actor (
    movie_id CHAR(36),
    actor_id INT,
    PRIMARY KEY (movie_id, actor_id),
    FOREIGN KEY (movie_id) REFERENCES movies(id) ON DELETE CASCADE,
    FOREIGN KEY (actor_id) REFERENCES actors(id) ON DELETE CASCADE
);



CREATE TABLE showings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    movie_id CHAR(36),
    screen ENUM('Lille', 'Stor') NOT NULL,
    show_date DATE NOT NULL,
    show_time TIME NOT NULL,
    total_spots INT NOT NULL CHECK (total_spots > 0),
    available_spots INT NOT NULL DEFAULT 50 CHECK (available_spots >= 0),
    price_per_ticket DECIMAL(10, 2) NOT NULL DEFAULT 100.00 CHECK (price_per_ticket > 0),
    FOREIGN KEY (`movie_id`) REFERENCES `movies`(`id`) ON DELETE CASCADE
);

-- Opret tabel for bookinger
CREATE TABLE IF NOT EXISTS `bookings` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NULL,
    showing_id INT NOT NULL,
    spots_reserved INT NOT NULL DEFAULT 1 CHECK (spots_reserved > 0),
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    price_per_ticket DECIMAL(10, 2) NOT NULL CHECK (price_per_ticket > 0),
    total_price DECIMAL(10, 2) NOT NULL CHECK (total_price > 0),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    order_number VARCHAR(22) NOT NULL
    FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`showing_id`) REFERENCES `showings`(`id`) ON DELETE CASCADE
);


CREATE TABLE site_settings (
    setting_key VARCHAR(255) PRIMARY KEY,
    setting_value TEXT NOT NULL
);


CREATE OR REPLACE VIEW upcoming_movies AS
SELECT 
    id,
    title,
    poster,
    premiere_date AS release_date
FROM 
    movies
WHERE 
    premiere_date > CURDATE()
ORDER BY 
    premiere_date ASC;

CREATE OR REPLACE VIEW news_movies AS
SELECT 
    id,
    title,
    poster,
    premiere_date AS release_date
FROM 
    movies
WHERE 
    premiere_date <= CURDATE()
ORDER BY 
    premiere_date DESC
LIMIT 5;



CREATE OR REPLACE VIEW daily_showings AS
SELECT 
    m.id AS movie_id,
    m.title,
    g.name AS genre, 
    m.poster AS image,
    s.show_date,
    s.show_time
FROM 
    movies m
JOIN 
    showings s ON m.id = s.movie_id
LEFT JOIN 
    movie_genre mg ON m.id = mg.movie_id
LEFT JOIN 
    genres g ON mg.genre_id = g.id
WHERE 
    s.show_date = CURDATE() 
GROUP BY 
    m.id, m.title, m.poster, s.show_date, s.show_time;





CREATE OR REPLACE VIEW genre_movies AS
SELECT 
    m.id AS movie_id,
    m.title,
    m.poster,
    g.name AS genre
FROM 
    movies m
JOIN 
    movie_genre mg ON m.id = mg.movie_id
JOIN 
    genres g ON mg.genre_id = g.id;

DELIMITER $$

CREATE TRIGGER after_booking_insert
AFTER INSERT ON bookings
FOR EACH ROW
BEGIN
    UPDATE showings
    SET available_spots = available_spots - NEW.spots_reserved
    WHERE id = NEW.showing_id;
END $$

DELIMITER ;


DELIMITER $$

CREATE TRIGGER after_booking_delete
AFTER DELETE ON bookings
FOR EACH ROW
BEGIN
    UPDATE showings
    SET available_spots = available_spots + OLD.spots_reserved
    WHERE id = OLD.showing_id;
END $$

DELIMITER ;

DELIMITER $$

CREATE TRIGGER after_booking_update
AFTER UPDATE ON bookings
FOR EACH ROW
BEGIN
    -- Hvis antal reserverede pladser ændres
    IF OLD.spots_reserved != NEW.spots_reserved THEN
        UPDATE showings
        SET available_spots = available_spots + OLD.spots_reserved
        WHERE id = OLD.showing_id;

        UPDATE showings
        SET available_spots = available_spots - NEW.spots_reserved
        WHERE id = NEW.showing_id;
    END IF;

    -- Hvis showing ændres
    IF OLD.showing_id != NEW.showing_id THEN
        UPDATE showings
        SET available_spots = available_spots + OLD.spots_reserved
        WHERE id = OLD.showing_id;

        UPDATE showings
        SET available_spots = available_spots - NEW.spots_reserved
        WHERE id = NEW.showing_id;
    END IF;
END $$

DELIMITER ;











 

