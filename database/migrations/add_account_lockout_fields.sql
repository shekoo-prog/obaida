ALTER TABLE users 
ADD COLUMN login_attempts INT DEFAULT 0,
ADD COLUMN locked_until DATETIME DEFAULT NULL;