CREATE TABLE files (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    filename VARCHAR(255),
    original_name VARCHAR(255),
    mime_type VARCHAR(100),
    size INT,
    path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

ALTER TABLE users
ADD COLUMN storage_limit BIGINT DEFAULT 104857600; -- 100MB default