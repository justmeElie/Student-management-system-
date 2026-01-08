-- Student Management DB schema
CREATE DATABASE IF NOT EXISTS student_mgmt CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE student_mgmt;

-- Students
CREATE TABLE IF NOT EXISTS students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(200) NOT NULL,
  email VARCHAR(200),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Courses
CREATE TABLE IF NOT EXISTS courses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(50) NOT NULL,
  title VARCHAR(200) NOT NULL,
  day_set VARCHAR(100) DEFAULT '', -- e.g. Mon,Wed,Fri
  time_start TIME DEFAULT NULL,
  time_end TIME DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Enrollments (many-to-many)
CREATE TABLE IF NOT EXISTS enrollments (
  student_id INT NOT NULL,
  course_id INT NOT NULL,
  PRIMARY KEY(student_id, course_id),
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
  FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Attendance
CREATE TABLE IF NOT EXISTS attendance (
  id INT AUTO_INCREMENT PRIMARY KEY,
  student_id INT NOT NULL,
  `date` DATE NOT NULL,
  time_in DATETIME DEFAULT NULL,
  time_out DATETIME DEFAULT NULL,
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Sample data
INSERT INTO students (name, email) VALUES ('Alice Johnson', 'alice@example.com'), ('Bob Smith', 'bob@example.com');
INSERT INTO courses (code, title, day_set, time_start, time_end) VALUES
('MATH101', 'Calculus I', 'Mon,Wed,Fri', '09:00:00', '10:30:00'),
('CS101', 'Introduction to Programming', 'Tue,Thu', '11:00:00', '12:30:00');

INSERT INTO enrollments (student_id, course_id) VALUES (1,1),(1,2),(2,2);
