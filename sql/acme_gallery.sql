CREATE DATABASE IF NOT EXISTS acme_gallery;

USE acme_gallery;

CREATE TABLE IF NOT EXISTS paintings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    artist VARCHAR(255) NOT NULL,
    year INT NOT NULL,
    image VARCHAR(255) NOT NULL
);

INSERT INTO paintings (title, artist, year, image) VALUES
('Starry Night', 'Vincent van Gogh', 1889, 'painting1.jpg'),
('Mona Lisa', 'Leonardo da Vinci', 1503, 'painting2.jpg'),
('The Persistence of Memory', 'Salvador Dalí', 1931, 'painting3.jpg');
