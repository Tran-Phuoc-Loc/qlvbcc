CREATE DATABASE cc;

-- Sử dụng cơ sở dữ liệu vừa tạo
USE ktcc;
-- Tạo bảng chứng chỉ
CREATE TABLE certificates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    certificate_number VARCHAR(15) NOT NULL,
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
    email VARCHAR(100) NOT NULL,
    email_verified BOOLEAN DEFAULT FALSE,
    issue_date DATE
);

INSERT INTO certificates (certificate_number, full_name, birth_year, gender, training_course, start_date, end_date, issue_date)
VALUES ('NTU-00001-TT34', 'Nguyễn Văn A', 1990, 'Nam', 'Kỹ năng Công nghệ thông tin', '2024-03-12', '2024-05-05', '2024-07-15');

INSERT INTO information (student_name, issuing_institution, address, email, email_verified, issue_date)
VALUES ('Nguyễn Văn A', 'CTy Nhã Thành UNIVERSE', 'Phường Xuân Khánh, Quận Ninh Kiều, TP Cần Thơ', 'honkaiimpact968@gmail.com', FALSE, '2024-07-15');

UPDATE degrees
SET email_verified = TRUE
WHERE email = 'tranphuocloctinphat@gmail.com';
