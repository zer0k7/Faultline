USE faultline;

INSERT INTO users (id, username, password, password_md5, email, full_name, role, balance, phone, national_id, address, bio, private_note, security_question, security_answer, avatar, api_token) VALUES
(1, 'admin', 'admin123', MD5('admin123'), 'admin@faultline.test', 'Portal Administrator', 'admin', 0.00, '+1 555 0100', 'FL-ADMIN-0000', '1 Administration Walk, Faultline', 'Campus operations account.', 'Operations mailbox is checked each morning.', 'What is the building code?', 'F-01', NULL, 'admin-api-token-0001'),
(2, 'maya.okafor', 'faculty123', NULL, 'maya.okafor@faultline.test', 'Dr. Maya Okafor', 'faculty', 0.00, '+1 555 0101', 'FL-FAC-0001', '8 Science Row, Faultline', 'Teaches distributed systems and secure engineering.', 'Labs are booked through the faculty office.', 'What is your pet name?', 'Pepper', NULL, 'fac-token-0001'),
(3, 'daniel.reyes', 'faculty123', NULL, 'daniel.reyes@faultline.test', 'Prof. Daniel Reyes', 'faculty', 0.00, '+1 555 0102', 'FL-FAC-0002', '14 Science Row, Faultline', 'Teaches discrete mathematics and logic.', 'Office hours are Tuesday and Thursday.', 'What is your pet name?', 'Mango', NULL, 'fac-token-0002'),
(4, 'ines.haddad', 'faculty123', NULL, 'ines.haddad@faultline.test', 'Dr. Ines Haddad', 'faculty', 0.00, '+1 555 0103', 'FL-FAC-0003', '21 Science Row, Faultline', 'Teaches physics and instrumentation.', 'Laboratory access requires a safety briefing.', 'What is your pet name?', 'Nori', NULL, 'fac-token-0003'),
(5, 'student1', 'student123', NULL, 'ava.mensah@faultline.test', 'Ava Mensah', 'student', 0.00, '+1 555 0201', 'FL-0000-0001', '3 North Hall, Faultline', 'Second year computer science student.', 'No outstanding holds.', 'What is your pet name?', 'Biscuit', NULL, 'stu-token-0001'),
(6, 'student2', 'student123', NULL, 'noah.kim@faultline.test', 'Noah Kim', 'student', 0.00, '+1 555 0202', 'FL-0000-0002', '4 North Hall, Faultline', 'Second year mathematics student.', 'Library card renewed.', 'What is your pet name?', 'Juno', NULL, 'stu-token-0002'),
(7, 'student3', 'student123', NULL, 'ama.boateng@faultline.test', 'Ama Boateng', 'student', -120.00, '+1 555 0203', 'FL-0000-0003', '5 North Hall, Faultline', 'Third year computer science student.', 'FLT{idor_private_note_3f9a}', 'What is your pet name?', 'Rex', NULL, 'stu-token-0003'),
(8, 'student4', 'student123', NULL, 'liam.chen@faultline.test', 'Liam Chen', 'student', 40.00, '+1 555 0204', 'FL-0000-0004', '6 North Hall, Faultline', 'First year physics student.', 'Lab safety briefing complete.', 'What is your pet name?', 'Coco', NULL, 'stu-token-0004'),
(9, 'student5', 'student123', NULL, 'sofia.rossi@faultline.test', 'Sofia Rossi', 'student', 0.00, '+1 555 0205', 'FL-0000-0005', '7 South Hall, Faultline', 'Second year chemistry student.', 'No outstanding holds.', 'What is your pet name?', 'Luna', NULL, 'stu-token-0005'),
(10, 'student6', 'student123', NULL, 'ethan.patel@faultline.test', 'Ethan Patel', 'student', 15.00, '+1 555 0206', 'FL-0000-0006', '8 South Hall, Faultline', 'Third year biology student.', 'Field trip form pending.', 'What is your pet name?', 'Ollie', NULL, 'stu-token-0006'),
(11, 'student7', 'student123', NULL, 'zara.ali@faultline.test', 'Zara Ali', 'student', 0.00, '+1 555 0207', 'FL-0000-0007', '9 South Hall, Faultline', 'First year computer science student.', 'No outstanding holds.', 'What is your pet name?', 'Simba', NULL, 'stu-token-0007'),
(12, 'student8', 'student123', NULL, 'mateo.silva@faultline.test', 'Mateo Silva', 'student', 0.00, '+1 555 0208', 'FL-0000-0008', '10 South Hall, Faultline', 'Second year mathematics student.', 'Tutoring schedule shared.', 'What is your pet name?', 'Toby', NULL, 'stu-token-0008'),
(13, 'student9', 'student123', NULL, 'hana.sato@faultline.test', 'Hana Sato', 'student', 25.00, '+1 555 0209', 'FL-0000-0009', '11 East Hall, Faultline', 'Third year physics student.', 'Research assistant application filed.', 'What is your pet name?', 'Mochi', NULL, 'stu-token-0009'),
(14, 'student10', 'student123', NULL, 'omar.farouk@faultline.test', 'Omar Farouk', 'student', 0.00, '+1 555 0210', 'FL-0000-0010', '12 East Hall, Faultline', 'First year chemistry student.', 'No outstanding holds.', 'What is your pet name?', 'Rocky', NULL, 'stu-token-0010'),
(15, 'student11', 'student123', NULL, 'lily.novak@faultline.test', 'Lily Novak', 'student', 0.00, '+1 555 0211', 'FL-0000-0011', '13 East Hall, Faultline', 'Second year biology student.', 'No outstanding holds.', 'What is your pet name?', 'Daisy', NULL, 'stu-token-0011'),
(16, 'student12', 'student123', NULL, 'ben.adeyemi@faultline.test', 'Ben Adeyemi', 'student', 10.00, '+1 555 0212', 'FL-0000-0012', '14 East Hall, Faultline', 'Third year computer science student.', 'Capstone topic approved.', 'What is your pet name?', 'Ziggy', NULL, 'stu-token-0012');

INSERT INTO legacy_accounts (username, password_md5, note) VALUES
('legacy.advisor', MD5('legacy123'), 'Imported from the old records system.'),
('legacy.registrar', MD5('legacy123'), 'Imported from the old records system.'),
('legacy.finance', MD5('legacy123'), 'Imported from the old records system.');

INSERT INTO students (id, user_id, student_no, program, academic_year, gpa, advisor_id, api_token, private_note) VALUES
(1, 5, 'FU-2026-001', 'BSc Computer Science', 2, 3.42, 1, 'stu-token-0001', 'Advisor meeting on Thursday.'),
(2, 6, 'FU-2026-002', 'BSc Mathematics', 2, 3.71, 2, 'stu-token-0002', 'Library card renewed.'),
(3, 7, 'FU-2026-003', 'BSc Computer Science', 3, 3.18, 1, 'stu-token-0003', 'FLT{idor_private_note_3f9a}'),
(4, 8, 'FU-2026-004', 'BSc Physics', 1, 3.05, 3, 'stu-token-0004', 'Lab safety briefing complete.'),
(5, 9, 'FU-2026-005', 'BSc Chemistry', 2, 3.55, 3, 'stu-token-0005', 'No outstanding holds.'),
(6, 10, 'FU-2026-006', 'BSc Biology', 3, 3.63, 3, 'stu-token-0006', 'Field trip form pending.'),
(7, 11, 'FU-2026-007', 'BSc Computer Science', 1, 2.94, 1, 'stu-token-0007', 'No outstanding holds.'),
(8, 12, 'FU-2026-008', 'BSc Mathematics', 2, 3.30, 2, 'stu-token-0008', 'Tutoring schedule shared.'),
(9, 13, 'FU-2026-009', 'BSc Physics', 3, 3.82, 3, 'stu-token-0009', 'Research assistant application filed.'),
(10, 14, 'FU-2026-010', 'BSc Chemistry', 1, 2.88, 3, 'stu-token-0010', 'No outstanding holds.'),
(11, 15, 'FU-2026-011', 'BSc Biology', 2, 3.44, 3, 'stu-token-0011', 'No outstanding holds.'),
(12, 16, 'FU-2026-012', 'BSc Computer Science', 3, 3.59, 1, 'stu-token-0012', 'Capstone topic approved.');

INSERT INTO faculty (id, user_id, name, department, email, office, phone, bio) VALUES
(1, 2, 'Dr. Maya Okafor', 'Computer Science', 'maya.okafor@faultline.test', 'Science 204', '+1 555 0101', 'Distributed systems, secure engineering and student mentoring.'),
(2, 3, 'Prof. Daniel Reyes', 'Mathematics', 'daniel.reyes@faultline.test', 'Science 118', '+1 555 0102', 'Discrete mathematics, logic and probability.'),
(3, 4, 'Dr. Ines Haddad', 'Physics', 'ines.haddad@faultline.test', 'Science 310', '+1 555 0103', 'Modern physics, instrumentation and laboratory safety.');

INSERT INTO courses (id, code, title, description, credits, capacity, enrolled, price, instructor_id) VALUES
(1, 'CS101', 'Introduction to Computer Science', 'Algorithms, data structures and the shape of modern software.', 3, 40, 12, 450.00, 1),
(2, 'MATH201', 'Discrete Mathematics', 'Sets, graphs, proof techniques and combinatorial reasoning.', 3, 35, 9, 420.00, 2),
(3, 'PHYS301', 'Modern Physics', 'Relativity, quantum behaviour and experimental methods.', 4, 30, 7, 480.00, 3),
(4, 'CHEM101', 'General Chemistry', 'Atomic structure, reactions and laboratory practice.', 3, 30, 5, 440.00, 3),
(5, 'BIO105', 'Foundations of Biology', 'Cells, genetics and the diversity of living systems.', 3, 30, 6, 430.00, 3),
(6, 'SEC410', 'Web Security Lab', 'Hands on review of web application security failures.', 4, 20, 20, 520.00, 1),
(7, 'ENG110', 'Academic Writing', 'Clear argument, research habits and revision practice.', 2, 25, 4, 300.00, 2);

INSERT INTO enrollments (user_id, course_id, status) VALUES
(5, 1, 'active'), (5, 2, 'active'), (5, 3, 'active'), (5, 5, 'active'), (5, 7, 'active'),
(6, 1, 'active'), (6, 2, 'active'), (6, 7, 'active'),
(7, 1, 'active'), (7, 6, 'active'),
(8, 3, 'active'), (8, 4, 'active'),
(9, 4, 'active'), (9, 5, 'active'),
(10, 5, 'active'), (10, 6, 'active'),
(11, 1, 'active'), (11, 2, 'active'),
(12, 2, 'active'), (12, 7, 'active'),
(13, 3, 'active'), (13, 6, 'active'),
(14, 4, 'active'), (14, 5, 'active'),
(15, 5, 'active'), (15, 7, 'active'),
(16, 1, 'active'), (16, 6, 'active');

INSERT INTO grades (student_id, course_id, score, letter) VALUES
(1, 1, 88.00, 'B+'), (1, 2, 91.00, 'A-'), (1, 3, 79.00, 'B'), (1, 5, 85.00, 'B+'), (1, 7, 94.00, 'A'),
(2, 1, 76.00, 'B-'), (2, 2, 89.00, 'B+'), (2, 7, 92.00, 'A-'),
(3, 1, 81.00, 'B-'), (3, 6, 87.00, 'B+'),
(4, 3, 73.00, 'C+'), (4, 4, 80.00, 'B'),
(5, 4, 90.00, 'A-'), (5, 5, 84.00, 'B+'),
(6, 5, 77.00, 'B-'), (6, 6, 83.00, 'B'),
(7, 1, 68.00, 'C'), (7, 2, 75.00, 'B-'),
(8, 2, 86.00, 'B+'), (8, 7, 88.00, 'B+'),
(9, 3, 95.00, 'A'), (9, 6, 91.00, 'A-'),
(10, 4, 72.00, 'C'), (10, 5, 78.00, 'B-'),
(11, 5, 84.00, 'B+'), (11, 7, 90.00, 'A-'),
(12, 1, 87.00, 'B+'), (12, 6, 89.00, 'B+');

INSERT INTO fees (id, user_id, description, amount, status, coupon) VALUES
(1, 5, 'Spring semester tuition', 1200.00, 'unpaid', NULL),
(2, 6, 'Spring semester tuition', 900.00, 'paid', NULL),
(3, 7, 'Laboratory fee', 600.00, 'unpaid', NULL),
(4, 8, 'Library and records fee', 150.00, 'partial', NULL),
(5, 9, 'Spring semester tuition', 1100.00, 'paid', NULL),
(6, 10, 'Field trip contribution', 75.00, 'unpaid', NULL);

INSERT INTO payments (user_id, fee_id, amount, method, coupon) VALUES
(6, 2, 900.00, 'card', NULL),
(9, 5, 1100.00, 'bank', NULL),
(8, 4, 100.00, 'cash', NULL);

INSERT INTO coupons (code, discount_percent, uses, max_uses) VALUES
('FAULTLINE50', 50, 0, 99),
('WELCOME10', 10, 0, 100),
('LAB25', 25, 3, 50);

INSERT INTO news (id, title, content, author) VALUES
(1, 'Semester opens with a new advising desk', 'The advising desk moves to the ground floor of North Hall. Drop in hours run from 09:00 to 16:00 on weekdays.', 'Campus Desk'),
(2, 'Library extends evening hours', 'The main library stays open until 22:00 during the assessment period. Group rooms can be booked at the front desk.', 'Library Services'),
(3, 'Security lab adds a second session', 'SEC410 adds a Thursday session in Science 204. Enrollment is capped so bring your student card.', 'Computer Science'),
(4, 'Transit passes available at the student office', 'Discounted transit passes are available while stock lasts. Bring a photo ID and your student number.', 'Student Office'),
(5, 'Research showcase call for entries', 'Students may submit a short abstract for the spring research showcase. The deadline is the last Friday of the month.', 'Research Office'),
(6, 'Health centre changes appointment process', 'Same day appointments can be requested by phone from 08:00. Walk in visits remain available for urgent cases.', 'Health Centre');

INSERT INTO feedback (user_id, course_id, comment) VALUES
(5, 1, 'The lab exercises connect the lectures to real code.'),
(6, 2, 'More worked examples before the problem sets would help.'),
(9, 3, 'The instrument sessions are well paced.');

INSERT INTO messages (sender_id, recipient_id, subject, body) VALUES
(1, 5, 'Advising reminder', 'Your advising meeting is on Thursday at 10:00 in Science 204.'),
(2, 5, 'Assignment feedback', 'The first assignment is graded. Review the notes before the next class.'),
(5, 1, 'Lab access', 'I need access to the security lab before the Thursday session.'),
(3, 7, 'Grade review', 'The grade review window opens next week.');

INSERT INTO contact_messages (name, email, message) VALUES
('Ava Mensah', 'ava.mensah@faultline.test', 'How do I request a transcript?'),
('Noah Kim', 'noah.kim@faultline.test', 'The library booking page did not load on Tuesday.');

INSERT INTO admin_notes (note, visibility) VALUES
('FLT{admin_no_role_b7c2}', 'admin'),
('Rotate the demo mail password before the next review.', 'admin'),
('Backup window is Sunday at 02:00.', 'admin');

INSERT INTO secrets (name, value) VALUES
('faultline_vault', 'FLT{union_select_secret_3e9b}'),
('mail_relay_key', 'demo-relay-key-4412'),
('backup_passphrase', 'not-a-real-passphrase');

INSERT INTO id_cards (user_id, student_no, payload, token) VALUES
(5, 'FU-2026-001', 'student1|Ava Mensah|FL-0000-0001|BSc Computer Science|FLT{base64_not_crypto_7a3e}', TO_BASE64('student1|Ava Mensah|FL-0000-0001|BSc Computer Science|FLT{base64_not_crypto_7a3e}'));

INSERT INTO app_settings (name, value) VALUES
('update_url', 'http://internal/updates/latest.json'),
('cdn_widget', 'http://internal/cdn/widget.js'),
('internal_base', 'http://internal'),
('support_email', 'helpdesk@faultline.test');

INSERT INTO flags (module_key, flag_hash, points) VALUES
('a01-idor', 'ad99d4fbe7d16959ee1a106b84284d881394b37d79c82178fc08fe497c0c0147', 10),
('a01-admin', 'd776b97663f67dccc936fd66b8c74b50b15fdcb6954c242d99d68e4451eeeb97', 10),
('a01-ssrf', 'f4ae66ecf16871850b708b4e4f1bd928819e3306d9317ff564aa693c19fbf38e', 15),
('a02-exposure', '3c75b1392b53fb8a413fa9a19b5e8db03534d94720ea56367bcfe4f4450756ad', 10),
('a02-xxe', '3f796728b99151a9960eb7d9795c3ad354fa9af926a73b3f2ca638db0ce2ec51', 15),
('a02-cors', '775e9a6dcb03a8b7799b345b7fb5b688c0d34ab2209b52d3636c36784b4f5ff2', 10),
('a03-installer', '71c27b2514b8149d1caae33fe121d1d23212c8df479d2ad78887c0724d9e73d1', 20),
('a03-supply', '6df2de1aa0854b84e41a37222aabfbe3a9f31f369aefa86d1dddce5f12d1da81', 15),
('a04-reset', 'c9be65c62ab12c763e3e7e30e6c57b9842ed1be8ee4fde9a75d5957c554fb886', 15),
('a04-idcard', '235d69d0c239dc2d9f559041763c99098016b916f592a753f385f3ae19c160a4', 10),
('a05-sqli', 'e4b2ca4f0738edc614334e3678a5b85fc8612aae7197d00f0ef066ca8c1a683d', 15),
('a05-xss', 'f41ed9302b0b2fe990e1ed48511f4be0897c1d43dd99ffd9cb21aadf4329979f', 15),
('a05-rce', 'e8d054d4849d886e65117ef08e3a82cfc6d99934a97abf386fa43ad2ec33c9d3', 25),
('a05-lfi', '8d4b0cdfbbbc2b01b44e382bf40ab2b58d65dd8144175f4be6da8fe61fcaeacf', 15),
('a06-logic', '16e32c5561e629a6626c433bc28b1e6de4acf1e5675a04022d167dd31d1eb789', 15),
('a06-upload', '82d523a2e9883f7f570d3e9ff9fb8c5c7475070a66c0cb2cfc277dce2d8987c0', 25),
('a07-jwt', '93dbf36356241193472058c295fd50b083766162f5ce43234e8c729d21a6f326', 20),
('a07-session', '53f6e9f2386773b5f91372da90e6da1024e04c2ef857c4bbe849fcb53d13fffd', 15),
('a08-unserialize', 'ca6faa8f5319d459c90f1866dcecd158eb3d78b9646702034fd8d0b4cb81c50a', 20),
('a08-integrity', '05fc5c5da33b566f926e4b3db5af50e27a104d4337f890ae93fae372648394a5', 15),
('a09-log', '52d7d95497cc1312feee41be2b4d914c2476179cd797f46a35c58cbd5c12804b', 10),
('a09-logxss', '948b8660d6fc3d34bce749aa17de5e2f8190c5ac80c489c37050004c00a07513', 15),
('a10-failopen', 'd23d273e08937b153f2ed8793277155397be406a7e4b8a415abe7a515d9bfbde', 20),
('a10-errors', '8c26b5d42101b413324331e95cefc3797cb307341f214f88058227611eead73c', 10);
