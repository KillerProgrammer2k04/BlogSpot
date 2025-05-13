CREATE TABLE users
(
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL UNIQUE,
    firstname VARCHAR(255) NOT NULL,
    lastname VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    user_image VARCHAR(255) NOT NULL,
    role ENUM("admin", "subscriber"),
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE categories
(
    category_id INT PRIMARY KEY AUTO_INCREMENT,
    category_name VARCHAR(255)
);

CREATE TABLE subcategories
(
    subcategory_id INT PRIMARY KEY AUTO_INCREMENT,
    category_id INT,
    subcategory_name VARCHAR(255),
    FOREIGN KEY (category_id) REFERENCES categories (category_id)
);

CREATE TABLE tags
(
    tag_id INT PRIMARY KEY AUTO_INCREMENT,
    tag_name VARCHAR(255)
);

CREATE TABLE articles
(
    article_id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    article_image VARCHAR(255),
    user_id INT,
    create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE TABLE comments
(
    comment_id INT PRIMARY KEY AUTO_INCREMENT,
    description TEXT,
    user_id INT,
    article_id INT,
    FOREIGN KEY (user_id) REFERENCES users (user_id),
    FOREIGN KEY (article_id) REFERENCES articles (article_id)
);

CREATE TABLE articles_categories
(
    article_category_id INT PRIMARY KEY AUTO_INCREMENT,
    article_id INT,
    category_id INT,
    subcategory_id INT,
    FOREIGN KEY (article_id) REFERENCES articles (article_id),
    FOREIGN KEY (category_id) REFERENCES categories (category_id),
    FOREIGN KEY (subcategory_id) REFERENCES subcategories (subcategory_id)
);

CREATE TABLE articles_tags
(
    article_tag_id INT PRIMARY KEY AUTO_INCREMENT,
    article_id INT,
    tag_id INT,
    FOREIGN KEY (article_id) REFERENCES articles (article_id),
    FOREIGN KEY (tag_id) REFERENCES tags (tag_id)
);