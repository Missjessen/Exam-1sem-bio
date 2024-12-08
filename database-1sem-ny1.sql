-- Opret database, hvis den ikke findes
-- Slet databasen, hvis den allerede findes
DROP DATABASE IF EXISTS `drive-in-1sem`;

-- Opret databasen, hvis den ikke findes
CREATE DATABASE IF NOT EXISTS `drive-in-1sem`;
USE `drive-in-1sem`;



CREATE TABLE IF NOT EXISTS `customers` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL
    
);

CREATE TABLE IF NOT EXISTS `employees` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    role VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL
   
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
    birthdate DATE NOT NULL
);


-- Opret tabel for film
CREATE TABLE IF NOT EXISTS `movies` (
     id CHAR(36) PRIMARY KEY,       -- UUID
     slug VARCHAR(255) UNIQUE,      -- Slug
      title VARCHAR(255), -- Title for the movie
    director VARCHAR(255),
    release_year INT,
    runtime INT,
    age_limit VARCHAR(50),
    description TEXT,
    length TIME,
    status VARCHAR(50),
    premiere_date DATE,
    language VARCHAR(50),
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




-- Opret tabel for parkeringspladser
CREATE TABLE IF NOT EXISTS `spots` (
    `spot_id` INT AUTO_INCREMENT PRIMARY KEY,
    `spot_number` INT NOT NULL UNIQUE,
    `status` ENUM('available', 'booked') DEFAULT 'available'
);


CREATE TABLE showings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    movie_id CHAR(36),
    screen ENUM('small', 'large') NOT NULL,
    show_date DATE NOT NULL,
    show_time TIME NOT NULL,
    total_spots INT NOT NULL,
    available_spots INT NOT NULL,
    repeat_pattern ENUM('none', 'daily', 'weekly') DEFAULT 'none',
    repeat_until DATE DEFAULT NULL,
    FOREIGN KEY (movie_id) REFERENCES movies(id)
);

CREATE TABLE parking_prices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    screen ENUM('small', 'large') NOT NULL,
    row_type ENUM('front', 'middle', 'back') NOT NULL,
    price_per_spot DECIMAL(10, 2) NOT NULL
);

-- Opret tabel for bookinger
CREATE TABLE IF NOT EXISTS `bookings` (
    `booking_id` INT AUTO_INCREMENT PRIMARY KEY,
    `movie_id` CHAR(36) NOT NULL,          -- Opdateret til CHAR(36) for at matche UUID-formatet
    `spot_id` INT NOT NULL,
    `customer_id` INT,
    `booking_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `price` INT,
    FOREIGN KEY (`movie_id`) REFERENCES `movies`(`id`) ON DELETE CASCADE,   -- Refererer til `id` i `movies`
    FOREIGN KEY (`spot_id`) REFERENCES `spots`(`spot_id`)
);


CREATE TABLE IF NOT EXISTS `pages` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    `pages` VARCHAR(50) UNIQUE NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT,
    css_file VARCHAR(255),
    template_file VARCHAR(255)
);
CREATE TABLE site_settings (
    setting_key VARCHAR(255) PRIMARY KEY,
    setting_value TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS `showtimes` (
    `showtime_id` INT AUTO_INCREMENT PRIMARY KEY,
    `movie_id` CHAR(36) NOT NULL, -- Matchet med CHAR(36)
    `show_date` DATE NOT NULL,
    `show_time` TIME NOT NULL,
    FOREIGN KEY (`movie_id`) REFERENCES `movies`(`id`) ON DELETE CASCADE
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
    GROUP_CONCAT(g.name SEPARATOR ', ') AS genres, 
    m.poster AS image,
    CONCAT(s.show_date, ' ', s.show_time) AS showing_time
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

CREATE TRIGGER update_booking_price
AFTER INSERT ON bookings
FOR EACH ROW
BEGIN
    DECLARE total_price DECIMAL(10, 2);

    -- Beregn totalprisen baseret på antallet af pladser
    SET total_price = (SELECT COUNT(*) * 50 FROM seats WHERE seat_id IN (SELECT seat_id FROM booking_seats WHERE booking_id = NEW.booking_id));

    -- Opdater booking med den beregnede pris
    UPDATE bookings SET total_price = total_price WHERE booking_id = NEW.booking_id;
END $$

DELIMITER ;



INSERT INTO `movies` (`id`, `slug`, `title`, `director`, `release_year`, `runtime`, `age_limit`, `description`, `length`, `status`, `premiere_date`, `language`, `poster`)
VALUES
(UUID(), 'the-dark-knight-2008', 'The Dark Knight', 'Christopher Nolan', 2008, 152, 'PG-13', 'When the menace known as the Joker wreaks havoc on Gotham, Batman must accept one of the greatest psychological and physical tests of his ability to fight injustice.', '02:32:00', 'Released', '2008-07-18', 'English', '/path/to/poster/the-dark-knight.jpg'),
(UUID(), 'inception-2010', 'Inception', 'Christopher Nolan', 2010, 148, 'PG-13', 'A thief who steals corporate secrets through the use of dream-sharing technology is given the inverse task of planting an idea into the mind of a CEO.', '02:28:00', 'Released', '2010-07-16', 'English', '/path/to/poster/inception.jpg'),
(UUID(), 'interstellar-2014', 'Interstellar', 'Christopher Nolan', 2014, 169, 'PG-13', 'A team of explorers travel through a wormhole in space in an attempt to ensure humanity’s survival.', '02:49:00', 'Released', '2014-11-07', 'English', '/path/to/poster/interstellar.jpg'),
(UUID(), 'avatar-3-2025', 'Avatar 3', 'James Cameron', 2025, 162, 'PG-13', 'The next installment in the Avatar saga.', '02:42:00', 'Upcoming', '2025-12-20', 'English', '/path/to/poster/avatar-3.jpg'),
(UUID(), 'dune-part-2-2024', 'Dune: Part Two', 'Denis Villeneuve', 2024, 155, 'PG-13', 'The epic continuation of Paul Atreides’ journey.', '02:35:00', 'Upcoming', '2024-11-15', 'English', '/path/to/poster/dune-part-2.jpg'),
(UUID(), 'the-marvels-2024', 'The Marvels', 'Nia DaCosta', 2024, 130, 'PG-13', 'Captain Marvel teams up with Ms. Marvel.', '02:10:00', 'Upcoming', '2024-07-14', 'English', '/path/to/poster/the-marvels.jpg'),
(UUID(), 'mission-impossible-8-2024', 'Mission: Impossible – Dead Reckoning Part Two', 'Christopher McQuarrie', 2024, 165, 'PG-13', 'Ethan Hunt faces his most challenging mission yet.', '02:45:00', 'Upcoming', '2024-06-28', 'English', '/path/to/poster/mi-8.jpg'),
('37544480-9eb6-11ef-8235-8e1b80f870b1', 'test-movie', 'Test Movie', 'John Doe', 2023, 120, 'PG', 'A test movie description.', '02:00:00', 'Released', '2023-01-01', 'English', '/path/to/poster/test-movie.jpg');

INSERT INTO `actors` (`id`, `name`)
VALUES
(1, 'Christian Bale'),
(2, 'Heath Ledger'),
(3, 'Leonardo DiCaprio'),
(4, 'Matthew McConaughey'),
(5, 'Anne Hathaway'),
(6, 'Robert Downey Jr.'),
(7, 'Scarlett Johansson'),
(8, 'Chris Hemsworth'),
(9, 'Tom Hanks'),
(10, 'Natalie Portman');

-- Insert sample data into genres table
INSERT INTO genres (name) VALUES 
('Action'),
('Comedy'),
('Drama'),
('Horror'),
('Sci-Fi');

INSERT INTO `showings` (`movie_id`, `screen`, `show_date`, `show_time`, `total_spots`, `available_spots`, `repeat_pattern`, `repeat_until`)
VALUES
((SELECT id FROM movies WHERE slug = 'the-dark-knight-2008'), 'large', '2023-12-01', '18:00:00', 50, 50, 'none', NULL),
((SELECT id FROM movies WHERE slug = 'inception-2010'), 'small', '2023-12-15', '20:30:00', 30, 30, 'none', NULL);


INSERT INTO employees (name, email, phone, role, address) VALUES
('Michael Scott', 'michael@dundermifflin.com', '5551234', 'Manager', '1725 Slough Avenue'),
('Dwight Schrute', 'dwight@dundermifflin.com', '5555678', 'Assistant to the Regional Manager', '168 Beet Lane'),
('Jim Halpert', 'jim@dundermifflin.com', '5552468', 'Sales Representative', '54 Maple Street'),
('Pam Beesly', 'pam@dundermifflin.com', '5551357', 'Receptionist', '12 Park Drive'),
('Stanley Hudson', 'stanley@dundermifflin.com', '5557890', 'Sales Representative', '98 Elm Street');

INSERT INTO `customers` (name, email, phone) VALUES
('John Doe', 'john@example.com', '12345678'),
('Jane Smith', 'jane@example.com', '87654321'),
('Alice Johnson', 'alice@example.com', '11223344'),
('Bob Brown', 'bob@example.com', '99887766'),
('Charlie Green', 'charlie@example.com', '33445566'),
('Diana White', 'diana@example.com', '55667788');

-- Tilføj eksempeldata i `spots`-tabellen
INSERT INTO `spots` (`spot_number`, `status`) VALUES 
(1, 'available'), (2, 'available'), (3, 'available'), (4, 'available'), (5, 'available'),
(6, 'available'), (7, 'available'), (8, 'available'), (9, 'available'), (10, 'available'),
(11, 'available'), (12, 'available'), (13, 'available'), (14, 'available'), (15, 'available'),
(16, 'available'), (17, 'available'), (18, 'available'), (19, 'available'), (20, 'available'),
(21, 'available'), (22, 'available'), (23, 'available'), (24, 'available'), (25, 'available'),
(26, 'available'), (27, 'available'), (28, 'available'), (29, 'available'), (30, 'available'),
(31, 'available'), (32, 'available'), (33, 'available'), (34, 'available'), (35, 'available'),
(36, 'available'), (37, 'available'), (38, 'available'), (39, 'available'), (40, 'available'),
(41, 'available'), (42, 'available'), (43, 'available'), (44, 'available'), (45, 'available'),
(46, 'available'), (47, 'available'), (48, 'available'), (49, 'available'), (50, 'available');

-- Tilføj eksempeldata i `showtimes`-tabellen
INSERT INTO `showtimes` (`movie_id`, `show_date`, `show_time`)
VALUES
((SELECT id FROM movies WHERE slug = 'the-dark-knight-2008'), '2023-12-01', '18:00:00'),
((SELECT id FROM movies WHERE slug = 'the-dark-knight-2008'), '2023-12-01', '20:30:00'),
((SELECT id FROM movies WHERE slug = 'inception-2010'), '2023-12-15', '18:00:00'),
((SELECT id FROM movies WHERE slug = 'inception-2010'), '2023-12-15', '20:30:00');


INSERT INTO site_settings (setting_key, setting_value) VALUES
('site_title', 'Drive-In Bio'),
('contact_email', 'kontakt@driveinbio.dk'),
('opening_hours', 'Mandag-Søndag: 18:00 - 23:00'),
('about_content', 'Drive-In Bio tilbyder en unik filmoplevelse i det fri. Kom og nyd en aften med de nyeste film fra komforten af din egen bil.');


INSERT INTO `pages` (pages, title, content, css_file, template_file) VALUES
('home', 'Hjem - Drive-In Bio', 'Velkommen til Drive-In Bio.', 'assets/css/homePage.css', 'app/view/user/homePage.php'),
('program', 'Program - Drive-In Bio', 'Film for alle.', 'assets/css/program.css', 'app/view/user/program.php'),
('movie', 'Film - Drive-In Bio', 'Her er vores filmoversigt.', 'assets/css/movie.css', '/app/view/user/movie.php'),
('spots', 'Pladser - Drive-In Bio', 'Find din parkering.', 'assets/css/spots.css', 'app/view/user/spots.php'),
('ticket', 'Køb din spot - Drive-In Bio', 'Køb plads til den næste forestilling.', 'assets/css/ticket.css', 'app/view/user/ticket.php'),
('about', 'Om Os - Drive-In Bio', 'Lær mere om vores Drive-In biograf.', 'assets/css/about.css', 'app/view/user/about.php'),
('admin_movie', 'Om Os - Drive-In Bio', 'Lær mere om vores Drive-In biograf.', 'assets/css/admin_movie.css', 'app/view/admin/admin_movie.php'),
('admin_dashboard', 'Dashboard - Drive-In Bio', 'Dashboard for admin.', 'assets/css/admin_dashboard.css', 'app/view/admin/admin_dashboard.php'),
('admin_ManageUser', 'Manage User - Drive-In Bio', 'Manage User for admin.', 'assets/css/admin_ManageUser.css', 'app/view/admin/admin_ManageUser.php'),
('admin_settings', 'Settings - Drive-In Bio', 'Settings for admin.', 'assets/css/admin_settings.css', 'app/view/admin/admin_settings.php');



 

