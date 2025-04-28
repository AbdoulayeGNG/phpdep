ALTER TABLE utilisateurs
ADD COLUMN remember_token VARCHAR(64) NULL,
ADD COLUMN token_expiry DATETIME NULL;