-- Faultline University Portal
-- Snapshot taken during the spring maintenance window.

CREATE TABLE users (
  id int NOT NULL,
  username varchar(64) NOT NULL,
  password varchar(255) NOT NULL,
  role varchar(16) NOT NULL
);

INSERT INTO users VALUES
(1,'admin','admin123','admin'),
(5,'student1','student123','student'),
(6,'student2','student123','student'),
(7,'student3','student123','student');
