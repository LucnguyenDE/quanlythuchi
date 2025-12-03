CREATE TABLE Users (
    Id INT IDENTITY(1,1) PRIMARY KEY,
    Username NVARCHAR(50) NOT NULL UNIQUE,
    PasswordHash NVARCHAR(255) NOT NULL,  -- Lưu hash mật khẩu
    CreatedAt DATETIME DEFAULT GETDATE()
);

INSERT INTO Users (Username, PasswordHash)
VALUES ('ADMIN', '$2y$10$5Skuk2tx54E5LiKQ50RNqOucW.zKz37Km.Kc9PAWawXnrBsJDYUYS');

cài composer https://getcomposer.org/download/

bỏ vào php.ini
extension=gd
extension=zip

vào cmd:
cd C:\xampp\htdocs\dashboard\quanlythuchi

composer require phpoffice/phpspreadsheet
