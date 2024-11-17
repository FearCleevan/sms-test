USE `samson-management-system`;

CREATE TABLE enrollments (
    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(30) NOT NULL,
    grade_level VARCHAR(50) NOT NULL,
    track VARCHAR(50) DEFAULT NULL,
    course VARCHAR(50) DEFAULT NULL,
    course_level VARCHAR(50) DEFAULT NULL,
    lrn VARCHAR(255) DEFAULT NULL,
    profile BLOB DEFAULT NULL,
    first_name VARCHAR(255) NOT NULL,
    middle_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    province VARCHAR(100) NOT NULL,
    zip_code VARCHAR(10) NOT NULL,
    city VARCHAR(100) NOT NULL,
    emergency_name VARCHAR(100) NOT NULL,
    emergency_phone VARCHAR(20) NOT NULL,
    relation VARCHAR(50) NOT NULL,
    enroll_date DATE DEFAULT NULL,
    enroll_time TIME DEFAULT NULL,
    session VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
