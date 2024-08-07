CREATE DATABASE cc;

-- Sử dụng cơ sở dữ liệu vừa tạo
USE cc;
-- Tạo bảng chứng chỉ
CREATE TABLE certificates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    certificate_number VARCHAR(15) NOT NULL,
    -- email VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    birth_year INT NOT NULL,
    gender VARCHAR(10) NOT NULL,
    training_course VARCHAR(100) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    issue_date VARCHAR(10) NOT NULL,
    CertificatePicture MEDIUMBLOB
);

-- Tạo bảng thông tin
CREATE TABLE information (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(100) NOT NULL,
    issuing_institution VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    -- phone VARCHAR(10) NOT NULL,
    email VARCHAR(100) NOT NULL,
    issue_date DATE
);



INSERT INTO certificates (certificate_number, full_name, birth_year, gender, training_course, start_date, end_date, issue_date)
VALUES ('NTU-00001-TT34', 'Nguyễn Văn A', 1990, 'Nam', 'Kỹ năng Công nghệ thông tin', '2024-03-12', '2024-05-05', '2024-07-15');
-- nhận từ form index
-- INSERT INTO information (student_name, issuing_institution, address, email, email_verified, issue_date)
-- VALUES ('Nguyễn Văn A', 'CTy Nhã Thành UNIVERSE', 'Phường Xuân Khánh, Quận Ninh Kiều, TP Cần Thơ', 'honkaiimpact968@gmail.com', FALSE, '2024-07-15');

-- INSERT INTO information (student_name, issuing_institution, address, phone, email, issue_date)
--     VALUES ('Nguyễn Văn A','CTy Nhã Thành UNIVERSE','Phường Xuân Khánh, Quận Ninh Kiều, TP Cần Thơ','','ttp6889@gmail.com','');

-- UPDATE information SET email_verified = TRUE WHERE email = 'honkaiimpact968@gmail.com' LIMIT 1;

-- ALTER TABLE email_verification ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE certificates ADD COLUMN email VARCHAR(255) NOT NULL;
ALTER TABLE information ADD phone VARCHAR(10) NOT NULL;


UPDATE `cc`.`certificates` SET `email` = 'ttp6889@gmail.com' WHERE (`id` = '1');


-- thêm ảnh 
UPDATE certificates
SET CertificatePicture = LOAD_FILE('C:/xampp/htdocs/qlvbcc/image/cc.jpg')
WHERE id = 1;






-- UPDATE information
-- SET email_verified = TRUE
-- WHERE email = 'honkaiimpact968@gmail.com';
