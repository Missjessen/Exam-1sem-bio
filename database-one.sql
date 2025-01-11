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
    birthdate DATE NOT NULL
);


-- Opret tabel for film
CREATE TABLE IF NOT EXISTS `movies` (
    id CHAR(36) PRIMARY KEY,       -- UUID
    slug VARCHAR(255) UNIQUE,      -- Slug
    title VARCHAR(255), -- Title for the movie
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

CREATE TRIGGER after_booking_delete
AFTER DELETE ON bookings
FOR EACH ROW
BEGIN
    UPDATE showings
    SET available_spots = available_spots + OLD.spots_reserved
    WHERE id = OLD.showing_id;
END $$

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



INSERT INTO `movies` (`id`, `slug`, `title`, `director`, `release_year`, `length`, `age_limit`, `description`, `status`, `premiere_date`, `language`, `poster`)
VALUES
(UUID(), 'pulp-fiction-1994', 'Pulp Fiction', 'Quentin Tarantino', 1994, 154, 'R', 'The lives of two mob hitmen, a boxer, a gangster, and his wife intertwine in a series of tales.', 'Released', '1994-10-14', 'English', '/path/to/poster/pulp-fiction.jpg'),
(UUID(), 'parasite-2019', 'Parasite', 'Bong Joon Ho', 2019, 132, 'R', 'A poor family schemes to infiltrate a wealthy household.', 'Released', '2019-05-30', 'Korean', '/path/to/poster/parasite.jpg'),
(UUID(), 'spirited-away-2001', 'Spirited Away', 'Hayao Miyazaki', 2001, 125, 'PG', 'A girl discovers a magical world while her parents are transformed into pigs.', 'Released', '2001-07-20', 'Japanese', '/path/to/poster/spirited-away.jpg'),
(UUID(), 'joker-2019', 'Joker', 'Todd Phillips', 2019, 122, 'R', 'A mentally troubled comedian descends into insanity and becomes the infamous villain.', 'Released', '2019-10-04', 'English', '/path/to/poster/joker.jpg'),
(UUID(), 'frozen-2013', 'Frozen', 'Jennifer Lee', 2013, 102, 'PG', 'A princess sets out on a journey to find her sister with the help of an iceman, a reindeer, and a snowman.', 'Released', '2013-11-27', 'English', '/path/to/poster/frozen.jpg'),
(UUID(), 'matrix-resurrections-2021', 'The Matrix Resurrections', 'Lana Wachowski', 2021, 148, 'R', 'Return to the Matrix with Neo and Trinity.', 'Released', '2021-12-22', 'English', '/path/to/poster/matrix-resurrections.jpg'),
(UUID(), 'the-dark-knight-2008', 'The Dark Knight', 'Christopher Nolan', 2008, 152, 'PG-13', 'When the menace known as the Joker wreaks havoc on Gotham, Batman must accept one of the greatest psychological and physical tests of his ability to fight injustice.', 'Released', '2008-07-18', 'English', '/path/to/poster/the-dark-knight.jpg'),
(UUID(), 'inception-2010', 'Inception', 'Christopher Nolan', 2010, 148, 'PG-13', 'A thief who steals corporate secrets through the use of dream-sharing technology is given the inverse task of planting an idea into the mind of a CEO.', 'Released', '2010-07-16', 'English', '/path/to/poster/inception.jpg'),
(UUID(), 'avatar-3-2025', 'Avatar 3', 'James Cameron', 2025, 162, 'PG-13', 'The next installment in the Avatar saga.', 'Upcoming', '2025-12-20', 'English', '/path/to/poster/avatar-3.jpg'),
(UUID(), 'dune-part-2-2024', 'Dune: Part Two', 'Denis Villeneuve', 2024, 155, 'PG-13', 'The epic continuation of Paul Atreides’ journey.', 'Upcoming', '2024-11-15', 'English', '/path/to/poster/dune-part-2.jpg'),
(UUID(), 'the-marvels-2024', 'The Marvels', 'Nia DaCosta', 2024, 130, 'PG-13', 'Captain Marvel teams up with Ms. Marvel.', 'Upcoming', '2024-07-14', 'English', '/path/to/poster/the-marvels.jpg'),
(UUID(), 'mission-impossible-8-2024', 'Mission: Impossible – Dead Reckoning Part Two', 'Christopher McQuarrie', 2024, 165, 'PG-13', 'Ethan Hunt faces his most challenging mission yet.', 'Upcoming', '2024-06-28', 'English', '/path/to/poster/mi-8.jpg'),
('37544480-9eb6-11ef-8235-8e1b80f870b1', 'test-movie', 'Test Movie', 'John Doe', 2023, 120, 'PG', 'A test movie description.', 'Released', '2023-01-01', 'English', '/path/to/poster/test-movie.jpg');

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
(10, 'Natalie Portman'),
(11, 'Samuel L. Jackson'),
(12, 'Uma Thurman'),
(13, 'Brad Pitt'),
(14, 'Scarlett Johansson'),
(15, 'Joaquin Phoenix'),
(16, 'Emma Watson'),
(17, 'Tom Cruise'),
(18, 'Zendaya'),
(19, 'Keanu Reeves'),
(20, 'Meryl Streep');

-- Insert sample data into genres table
INSERT INTO genres (name) VALUES 
('Action'),
('Comedy'),
('Drama'),
('Horror'),
('Sci-Fi'),
('Adventure'),
('Fantasy'),
('Thriller'),
('Romance'),
('Animation'),
('Documentary'),
('Western'),
('Musical'),
('Crime'),
('Mystery');

INSERT INTO `showings` (`movie_id`, `screen`, `show_date`, `show_time`, `total_spots`)
VALUES
((SELECT id FROM movies WHERE slug = 'pulp-fiction-1994'), 'Lille', '2025-12-05', '19:00:00', 50),
((SELECT id FROM movies WHERE slug = 'parasite-2019'), 'Stor', '2025-12-10', '20:00:00', 50),
((SELECT id FROM movies WHERE slug = 'spirited-away-2001'), 'Lille', '2025-12-15', '18:00:00', 50),
((SELECT id FROM movies WHERE slug = 'joker-2019'), 'Stor', '2025-12-20', '21:30:00', 50),
((SELECT id FROM movies WHERE slug = 'frozen-2013'), 'Lille', '2025-12-25', '16:00:00', 50),
((SELECT id FROM movies WHERE slug = 'matrix-resurrections-2021'), 'Stor', '2025-12-25', '20:00:00', 50);






INSERT INTO site_settings (setting_key, setting_value) VALUES
('site_title', 'Drive-In Bio'),
('contact_email', 'kontakt@driveinbio.dk'),
('opening_hours', 'Mandag-Søndag: 18:00 - 23:00'),
('about_content', 'Drive-In Bio tilbyder en unik filmoplevelse i det fri. Kom og nyd en aften med de nyeste film fra komforten af din egen bil.');


INSERT INTO `movie_genre` (`movie_id`, `genre_id`)
VALUES
((SELECT id FROM movies WHERE slug = 'pulp-fiction-1994'), (SELECT id FROM genres WHERE name = 'Crime')),
((SELECT id FROM movies WHERE slug = 'pulp-fiction-1994'), (SELECT id FROM genres WHERE name = 'Thriller')),
((SELECT id FROM movies WHERE slug = 'parasite-2019'), (SELECT id FROM genres WHERE name = 'Drama')),
((SELECT id FROM movies WHERE slug = 'parasite-2019'), (SELECT id FROM genres WHERE name = 'Thriller')),
((SELECT id FROM movies WHERE slug = 'spirited-away-2001'), (SELECT id FROM genres WHERE name = 'Animation')),
((SELECT id FROM movies WHERE slug = 'spirited-away-2001'), (SELECT id FROM genres WHERE name = 'Fantasy')),
((SELECT id FROM movies WHERE slug = 'joker-2019'), (SELECT id FROM genres WHERE name = 'Crime')),
((SELECT id FROM movies WHERE slug = 'joker-2019'), (SELECT id FROM genres WHERE name = 'Drama')),
((SELECT id FROM movies WHERE slug = 'frozen-2013'), (SELECT id FROM genres WHERE name = 'Animation')),
((SELECT id FROM movies WHERE slug = 'matrix-resurrections-2021'), (SELECT id FROM genres WHERE name = 'Sci-Fi'));


INSERT INTO `movie_actor` (`movie_id`, `actor_id`)
VALUES
((SELECT id FROM movies WHERE slug = 'pulp-fiction-1994'), 11),
((SELECT id FROM movies WHERE slug = 'pulp-fiction-1994'), 12),
((SELECT id FROM movies WHERE slug = 'parasite-2019'), 15),
((SELECT id FROM movies WHERE slug = 'spirited-away-2001'), 16),
((SELECT id FROM movies WHERE slug = 'joker-2019'), 15),
((SELECT id FROM movies WHERE slug = 'frozen-2013'), 14),
((SELECT id FROM movies WHERE slug = 'matrix-resurrections-2021'), 19);






 

