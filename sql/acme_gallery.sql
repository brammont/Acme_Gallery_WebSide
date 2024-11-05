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
('The Persistence of Memory', 'Salvador Dalí', 1931, 'painting3.jpg')
('The Scream', 'Edvard Munch', 1893, 'painting4.jpg'),
('Girl with a Pearl Earring', 'Johannes Vermeer', 1665, 'painting5.jpg'),
('The Birth of Venus', 'Sandro Botticelli', 1486, 'painting6.jpg'),
('Guernica', 'Pablo Picasso', 1937, 'painting7.jpg'),
('American Gothic', 'Grant Wood', 1930, 'painting8.jpg'),
('The Last Supper', 'Leonardo da Vinci', 1498, 'painting9.jpg'),
('The Kiss', 'Gustav Klimt', 1907, 'painting10.jpg')