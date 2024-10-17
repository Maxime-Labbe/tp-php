DROP TABLE IF EXISTS comments;

DROP TABLE IF EXISTS likes;

DROP TABLE IF EXISTS posts;

DROP TABLE IF EXISTS users;

CREATE TABLE Users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100),
    email VARCHAR(100),
    password VARCHAR(255),
    media_object VARCHAR(255),
    created_at DATETIME,
    last_connection DATETIME
);

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    description VARCHAR(255),
    media_object VARCHAR(255),
    user_id INT,
    created_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES Users (id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    description VARCHAR(255),
    user_id INT,
    post_id INT,
    created_at,
    FOREIGN KEY (user_id) REFERENCES Users (id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (post_id) REFERENCES posts (id) ON UPDATE CASCADE ON DELETE CASCADE
);