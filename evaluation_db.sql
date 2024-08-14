-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2024 at 10:31 AM
-- Server version: 10.1.39-MariaDB
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `evaluation_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `criteria_list`
--

CREATE TABLE `criteria_list` (
  `id` int(11) NOT NULL,
  `criteria` varchar(200) NOT NULL,
  `type` varchar(200) NOT NULL,
  `education_level` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `criteria_list`
--

INSERT INTO `criteria_list` (`id`, `criteria`, `type`, `education_level`) VALUES
(1, 'Professional Performance', 'Rate', 'College'),
(2, 'Professional Department', 'Rate', 'College'),
(3, 'Management of Student Learning', 'Rate', 'College'),
(4, 'Classroom Management', 'Rate', 'College'),
(5, 'Instructional Delivery', 'Rate', 'College'),
(6, 'Comments', 'Comments', 'College'),
(7, 'Teaching Effectiveness', 'Rate', 'Highschool'),
(8, 'Course Design and Management', 'Rate', 'Highschool'),
(9, 'Interaction and Communication', 'Rate', 'Highschool'),
(10, 'Professionalism and Ethical Conduct', 'Rate', 'Highschool'),
(11, 'Classroom Management', 'Rate', 'Highschool'),
(12, 'Comments', 'Comments', 'Highschool'),
(13, 'How well does the teacher teach the core subject?', 'Rate', 'Elementary'),
(14, 'How well does the teacher model the core values through how he/she behaves with students and with other staff persons?', 'Rate', 'Elementary'),
(15, 'Comments', 'Comments', 'Elementary');

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_answers`
--

CREATE TABLE `evaluation_answers` (
  `evaluation_id` int(30) NOT NULL,
  `question_id` int(30) NOT NULL,
  `criteria` int(30) NOT NULL,
  `rate` int(39) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `evaluation_answers`
--

INSERT INTO `evaluation_answers` (`evaluation_id`, `question_id`, `criteria`, `rate`) VALUES
(73, 1, 1, 4),
(73, 2, 1, 4),
(73, 3, 1, 4),
(73, 4, 1, 4),
(73, 5, 1, 4),
(73, 6, 1, 4),
(73, 7, 2, 4),
(73, 8, 2, 4),
(73, 9, 2, 4),
(73, 10, 2, 4),
(73, 11, 2, 4),
(73, 12, 3, 4),
(73, 13, 3, 4),
(73, 14, 3, 4),
(73, 15, 3, 4),
(73, 16, 3, 4),
(73, 17, 4, 4),
(73, 18, 4, 4),
(73, 19, 4, 4),
(73, 20, 4, 4),
(73, 21, 4, 4),
(73, 22, 4, 4),
(73, 23, 5, 4),
(73, 24, 5, 4),
(73, 25, 5, 4),
(74, 1, 1, 3),
(74, 2, 1, 4),
(74, 3, 1, 4),
(74, 4, 1, 4),
(74, 5, 1, 3),
(74, 6, 1, 4),
(74, 7, 2, 4),
(74, 8, 2, 3),
(74, 9, 2, 3),
(74, 10, 2, 3),
(74, 11, 2, 4),
(74, 12, 3, 3),
(74, 13, 3, 4),
(74, 14, 3, 4),
(74, 15, 3, 4),
(74, 16, 3, 4),
(74, 17, 4, 4),
(74, 18, 4, 4),
(74, 19, 4, 4),
(74, 20, 4, 3),
(74, 21, 4, 3),
(74, 22, 4, 4),
(74, 23, 5, 3),
(74, 24, 5, 4),
(74, 25, 5, 4),
(75, 1, 1, 4),
(75, 2, 1, 3),
(75, 3, 1, 4),
(75, 4, 1, 4),
(75, 5, 1, 4),
(75, 6, 1, 4),
(75, 7, 2, 4),
(75, 8, 2, 4),
(75, 9, 2, 4),
(75, 10, 2, 3),
(75, 11, 2, 4),
(75, 12, 3, 3),
(75, 13, 3, 4),
(75, 14, 3, 3),
(75, 15, 3, 3),
(75, 16, 3, 4),
(75, 17, 4, 3),
(75, 18, 4, 4),
(75, 19, 4, 4),
(75, 20, 4, 4),
(75, 21, 4, 4),
(75, 22, 4, 3),
(75, 23, 5, 4),
(75, 24, 5, 4),
(75, 25, 5, 3),
(76, 1, 1, 3),
(76, 2, 1, 4),
(76, 3, 1, 4),
(76, 4, 1, 4),
(76, 5, 1, 3),
(76, 6, 1, 3),
(76, 7, 2, 3),
(76, 8, 2, 4),
(76, 9, 2, 4),
(76, 10, 2, 4),
(76, 11, 2, 4),
(76, 12, 3, 3),
(76, 13, 3, 4),
(76, 14, 3, 4),
(76, 15, 3, 4),
(76, 16, 3, 4),
(76, 17, 4, 3),
(76, 18, 4, 4),
(76, 19, 4, 4),
(76, 20, 4, 4),
(76, 21, 4, 3),
(76, 22, 4, 3),
(76, 23, 5, 3),
(76, 24, 5, 4),
(76, 25, 5, 4),
(77, 1, 1, 3),
(77, 2, 1, 3),
(77, 3, 1, 4),
(77, 4, 1, 4),
(77, 5, 1, 4),
(77, 6, 1, 4),
(77, 7, 2, 3),
(77, 8, 2, 4),
(77, 9, 2, 4),
(77, 10, 2, 4),
(77, 11, 2, 3),
(77, 12, 3, 3),
(77, 13, 3, 4),
(77, 14, 3, 4),
(77, 15, 3, 4),
(77, 16, 3, 4),
(77, 17, 4, 3),
(77, 18, 4, 4),
(77, 19, 4, 4),
(77, 20, 4, 4),
(77, 21, 4, 4),
(77, 22, 4, 4),
(77, 23, 5, 3),
(77, 24, 5, 4),
(77, 25, 5, 4),
(78, 1, 1, 3),
(78, 2, 1, 2),
(78, 3, 1, 2),
(78, 4, 1, 2),
(78, 5, 1, 2),
(78, 6, 1, 3),
(78, 7, 2, 2),
(78, 8, 2, 2),
(78, 9, 2, 1),
(78, 10, 2, 2),
(78, 11, 2, 3),
(78, 12, 3, 2),
(78, 13, 3, 2),
(78, 14, 3, 1),
(78, 15, 3, 3),
(78, 16, 3, 2),
(78, 17, 4, 2),
(78, 18, 4, 2),
(78, 19, 4, 2),
(78, 20, 4, 1),
(78, 21, 4, 1),
(78, 22, 4, 3),
(78, 23, 5, 2),
(78, 24, 5, 3),
(78, 25, 5, 3),
(79, 1, 1, 3),
(79, 2, 1, 4),
(79, 3, 1, 3),
(79, 4, 1, 3),
(79, 5, 1, 4),
(79, 6, 1, 4),
(79, 7, 2, 4),
(79, 8, 2, 4),
(79, 9, 2, 4),
(79, 10, 2, 3),
(79, 11, 2, 3),
(79, 12, 3, 3),
(79, 13, 3, 4),
(79, 14, 3, 4),
(79, 15, 3, 4),
(79, 16, 3, 3),
(79, 17, 4, 3),
(79, 18, 4, 4),
(79, 19, 4, 4),
(79, 20, 4, 4),
(79, 21, 4, 3),
(79, 22, 4, 4),
(79, 23, 5, 3),
(79, 24, 5, 4),
(79, 25, 5, 4),
(80, 1, 1, 3),
(80, 2, 1, 4),
(80, 3, 1, 4),
(80, 4, 1, 4),
(80, 5, 1, 4),
(80, 6, 1, 3),
(80, 7, 2, 3),
(80, 8, 2, 4),
(80, 9, 2, 4),
(80, 10, 2, 4),
(80, 11, 2, 4),
(80, 12, 3, 4),
(80, 13, 3, 3),
(80, 14, 3, 4),
(80, 15, 3, 4),
(80, 16, 3, 3),
(80, 17, 4, 3),
(80, 18, 4, 4),
(80, 19, 4, 4),
(80, 20, 4, 4),
(80, 21, 4, 4),
(80, 22, 4, 3),
(80, 23, 5, 3),
(80, 24, 5, 4),
(80, 25, 5, 4),
(81, 27, 7, 4),
(81, 28, 7, 4),
(81, 29, 7, 4),
(81, 30, 7, 4),
(81, 31, 7, 4),
(81, 32, 7, 4),
(81, 33, 8, 4),
(81, 34, 8, 4),
(81, 35, 8, 4),
(81, 36, 8, 4),
(81, 37, 8, 4),
(81, 38, 8, 4),
(81, 39, 9, 4),
(81, 40, 9, 4),
(81, 41, 9, 4),
(81, 42, 9, 4),
(81, 43, 9, 4),
(81, 44, 9, 4),
(81, 45, 10, 4),
(81, 46, 10, 4),
(81, 47, 10, 4),
(81, 48, 10, 4),
(81, 49, 10, 4),
(81, 50, 11, 4),
(81, 51, 11, 4),
(81, 52, 11, 4),
(81, 53, 11, 4),
(82, 55, 13, 5),
(82, 56, 13, 5),
(82, 57, 13, 5),
(82, 58, 13, 5),
(82, 59, 13, 5),
(82, 60, 13, 5),
(82, 61, 13, 5),
(82, 62, 13, 5),
(82, 63, 13, 5),
(82, 64, 13, 5),
(82, 65, 13, 5),
(82, 66, 13, 5),
(82, 67, 13, 5),
(82, 68, 13, 5),
(82, 69, 13, 5),
(82, 70, 14, 5),
(82, 71, 14, 5),
(82, 72, 14, 5),
(82, 73, 14, 5),
(82, 74, 14, 5),
(82, 75, 14, 5),
(82, 76, 14, 5),
(82, 77, 14, 5),
(82, 78, 14, 5),
(82, 79, 14, 5),
(82, 80, 14, 5),
(82, 81, 14, 5),
(82, 82, 14, 5),
(83, 55, 13, 4),
(83, 56, 13, 4),
(83, 57, 13, 4),
(83, 58, 13, 4),
(83, 59, 13, 4),
(83, 60, 13, 4),
(83, 61, 13, 4),
(83, 62, 13, 4),
(83, 63, 13, 4),
(83, 64, 13, 4),
(83, 65, 13, 4),
(83, 66, 13, 4),
(83, 67, 13, 4),
(83, 68, 13, 4),
(83, 69, 13, 4),
(83, 70, 14, 3),
(83, 71, 14, 3),
(83, 72, 14, 3),
(83, 73, 14, 3),
(83, 74, 14, 3),
(83, 75, 14, 3),
(83, 76, 14, 3),
(83, 77, 14, 3),
(83, 78, 14, 3),
(83, 79, 14, 3),
(83, 80, 14, 3),
(83, 81, 14, 3),
(83, 82, 14, 3);

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_comments`
--

CREATE TABLE `evaluation_comments` (
  `evaluation_id` int(30) NOT NULL,
  `question` varchar(200) NOT NULL,
  `response` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `evaluation_comments`
--

INSERT INTO `evaluation_comments` (`evaluation_id`, `question`, `response`) VALUES
(73, '26', 'Sana pumasa na sa thesis'),
(74, '26', 'Galing ni sir magcode'),
(75, '26', ''),
(76, '26', ''),
(77, '26', ''),
(78, '26', ''),
(79, '26', ''),
(80, '26', ''),
(81, '54', ''),
(82, '83', 'I dont know'),
(82, '84', 'None'),
(83, '83', 'Wala po'),
(83, '84', 'Wala po');

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_list`
--

CREATE TABLE `evaluation_list` (
  `evaluation_id` int(30) NOT NULL,
  `schoolyear_id` int(30) NOT NULL,
  `class_id` int(30) NOT NULL,
  `faculty_id` int(11) NOT NULL,
  `studentclass_id` int(30) NOT NULL,
  `level` varchar(200) NOT NULL,
  `department` text NOT NULL,
  `branch` text NOT NULL,
  `date_taken` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `evaluation_list`
--

INSERT INTO `evaluation_list` (`evaluation_id`, `schoolyear_id`, `class_id`, `faculty_id`, `studentclass_id`, `level`, `department`, `branch`, `date_taken`) VALUES
(73, 2, 41, 9, 202, 'College', 'Computer Science', 'Main', '2024-04-25 18:26:38'),
(74, 2, 48, 14, 231, 'College', 'Computer Science', 'Main', '2024-05-05 14:12:42'),
(75, 2, 44, 10, 203, 'College', 'Computer Science', 'Main', '2024-05-05 14:13:07'),
(76, 2, 43, 12, 223, 'College', 'Arts and Science', 'Main', '2024-05-05 14:13:30'),
(77, 2, 47, 15, 226, 'College', 'Arts and Science', 'Main', '2024-05-05 14:13:57'),
(78, 2, 49, 13, 237, 'College', 'Computer Science', 'Main', '2024-05-05 14:16:50'),
(79, 2, 50, 18, 236, 'College', 'Computer Science', 'Main', '2024-05-05 14:17:08'),
(80, 2, 46, 11, 238, 'College', 'Arts and Science', 'Main', '2024-05-05 14:18:39'),
(81, 2, 51, 10, 239, 'Highschool', 'Computer Science', 'Main', '2024-05-05 14:24:25'),
(82, 2, 52, 11, 240, 'Elementary', 'Arts and Science', 'Main', '2024-05-05 14:25:04'),
(83, 2, 52, 11, 241, 'Elementary', 'Arts and Science', 'Main', '2024-05-05 14:26:14');

-- --------------------------------------------------------

--
-- Table structure for table `questionnaire_list`
--

CREATE TABLE `questionnaire_list` (
  `id` int(11) NOT NULL,
  `questiondesc` text NOT NULL,
  `criteria` text NOT NULL,
  `type` varchar(200) NOT NULL,
  `education_level` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `questionnaire_list`
--

INSERT INTO `questionnaire_list` (`id`, `questiondesc`, `criteria`, `type`, `education_level`) VALUES
(1, 'Shows mastery of the subject matter', '1', 'Rate', 'College'),
(2, 'Follows the syllabus and enriches it through additional readings', '1', 'Rate', 'College'),
(3, 'Shows evidence of preparedness in class', '1', 'Rate', 'College'),
(4, 'Exhibits awareness of recent educational trends and developments. Relates the subject matter to relevant local and global issues', '1', 'Rate', 'College'),
(5, 'Integrates value formation with the subject matter', '1', 'Rate', 'College'),
(6, 'Gives grades which present fair appraisal of student\'s performance', '1', 'Rate', 'College'),
(7, 'Treats students with respect and courtesy', '2', 'Rate', 'College'),
(8, 'Manifest approachability and willingness to assist students', '2', 'Rate', 'College'),
(9, 'Shows confidence in handling the class', '2', 'Rate', 'College'),
(10, 'Leads by example by coming to class regularly and punctually', '2', 'Rate', 'College'),
(11, 'Conducts self with propriety and respectability in clothes, manner, and behavior', '2', 'Rate', 'College'),
(12, 'Creates learning opportunities through student involvement and participation', '3', 'Rate', 'College'),
(13, 'Holds consultations with students outside the class', '3', 'Rate', 'College'),
(14, 'Makes judicious use of learning aids like audio-visual presentations, examples, references, etc', '3', 'Rate', 'College'),
(15, 'Periodically assesses student performance through tests, term papers, projects, and other activities', '3', 'Rate', 'College'),
(16, 'Gives individualized attention to particular learning needs of each student', '3', 'Rate', 'College'),
(17, 'Maintains classroom discipline in consonance with sound and democratic practices', '4', 'Rate', 'College'),
(18, 'Keeps record of daily attendance and enforces University rules on punctuality', '4', 'Rate', 'College'),
(19, 'Recognizes student initiative and encourages and monitors independent work and performance', '4', 'Rate', 'College'),
(20, 'Personally rates examinations and other requirements submitted by students', '4', 'Rate', 'College'),
(21, 'Explains system of grading very clearly to students. Students are informed of basis for rating and are promptly given back test results, periodic ratings, and feedback to help students monitor their progress and improve performance', '4', 'Rate', 'College'),
(22, 'Manages time effectively by engaging students with meaningful activities, avoiding loose talk and unrelated topics, and providing activities that lead to the attainment of objective', '4', 'Rate', 'College'),
(23, 'Shows depth of knowledge in the discipline and/or are of assignment', '5', 'Rate', 'College'),
(24, 'Speaks clearly and communicated in a manner understood by students', '5', 'Rate', 'College'),
(25, 'Communicates in correct grammar and use fluent phrasing in any medium or language', '5', 'Rate', 'College'),
(26, 'Comments', '6', 'Comment', 'College'),
(27, 'Shows mastery of the subject matter and exhibits depth of knowledge in the discipline through explaining complex ideas in comprehensible manner.', '7', 'Rate', 'Highschool'),
(28, 'Shows evidence of preparedness in class.', '7', 'Rate', 'Highschool'),
(29, 'Exhibits awareness of recent educational trends and developments (relates the subject matter to relevant local and global issues).', '7', 'Rate', 'Highschool'),
(30, 'Integrates value formation with the subject matter.', '7', 'Rate', 'Highschool'),
(31, 'Communicates clearly the concepts and instructions.', '7', 'Rate', 'Highschool'),
(32, 'Creates learning opportunities through student involvement and participation.', '7', 'Rate', 'Highschool'),
(33, 'Follows the syllabus and enriches it through additional reference and learning resources.', '8', 'Rate', 'Highschool'),
(34, 'Ensures that assessment tasks are aligned with the learning outcomes/objectives and activities of the course content/topic.', '8', 'Rate', 'Highschool'),
(35, 'Makes use of learning aids like audio-visual presentations, real-life examples, references, online resources, etc.', '8', 'Rate', 'Highschool'),
(36, 'Periodically assess student performance through tests, reflection papers, research works, projects, online works and other activities.', '8', 'Rate', 'Highschool'),
(37, 'Explains system of grading very clearly to students (students are informed of basis for rating).', '8', 'Rate', 'Highschool'),
(38, 'Gives grades which represent fair appraisal of studentsâ€™ performance (personally rates examinations and other requirements submitted by students).', '8', 'Rate', 'Highschool'),
(39, 'Holds consultations with students outside the class hours.', '9', 'Rate', 'Highschool'),
(40, 'Manifest approachability and willingness to assist students.', '9', 'Rate', 'Highschool'),
(41, 'Gives individualized attention to particular learning needs of each student.', '9', 'Rate', 'Highschool'),
(42, 'Speaks clearly and communicates in a manner understood by students in both written and oral.', '9', 'Rate', 'Highschool'),
(43, 'Communicates in correct grammar and use fluent phrasing in any medium or language.', '9', 'Rate', 'Highschool'),
(44, 'Promptly given back test results, periodic ratings, and feedback to help students monitor their progress and improve performance.', '9', 'Rate', 'Highschool'),
(45, 'Treats diverse learners with respect and courtesy.', '10', 'Rate', 'Highschool'),
(46, 'Shows confidence in handling the class.', '10', 'Rate', 'Highschool'),
(47, 'Leads by example by coming to class regularly and punctually.', '10', 'Rate', 'Highschool'),
(48, 'Conducts self professionally and respectability in clothes, manner, and behavior.', '10', 'Rate', 'Highschool'),
(49, 'Adheres to academic integrity standards.', '10', 'Rate', 'Highschool'),
(50, 'Maintains order and discipline in the classroom.', '11', 'Rate', 'Highschool'),
(51, 'Keeps records of daily attendance and enforces University rules on punctuality.', '11', 'Rate', 'Highschool'),
(52, 'Recognizes student initiative and encourages and monitors independent work performance.', '11', 'Rate', 'Highschool'),
(53, 'Manages time effectively by engaging students with meaningful activities, avoiding loose talk and unrelated topics, and providing activities that lead to the attainment of objective.', '11', 'Rate', 'Highschool'),
(54, 'Comments', '12', 'Comment', 'Highschool'),
(55, 'Teacher is prepared for class.', '13', 'Rate', 'Elementary'),
(56, 'Teacher knows his/her subject.', '13', 'Rate', 'Elementary'),
(57, 'Teacher is organized and neat.', '13', 'Rate', 'Elementary'),
(58, 'Teacher plans class time and assignments that help students to problem solve and think critically. Teacher provides activities that make subject matter meaningful.', '13', 'Rate', 'Elementary'),
(59, 'Teacher is flexible in accommodating for individual student needs.', '13', 'Rate', 'Elementary'),
(60, 'Teacher is clear in giving directions and on explaining what is expected on assignments and tests.', '13', 'Rate', 'Elementary'),
(61, 'Teacher allows you to be active in the classroom learning environment.', '13', 'Rate', 'Elementary'),
(62, 'Teacher manages the time well.', '13', 'Rate', 'Elementary'),
(63, 'Teacher returns homework in a timely manner.', '13', 'Rate', 'Elementary'),
(64, 'Teacher has clear classroom procedures so students donâ€™t waste time.', '13', 'Rate', 'Elementary'),
(65, 'Teacher grades fairly.', '13', 'Rate', 'Elementary'),
(66, 'Teacher has taught me a lot about this subject.', '13', 'Rate', 'Elementary'),
(67, 'Teacher gives me good feedback on homework and projects so that I can improve.', '13', 'Rate', 'Elementary'),
(68, 'Teacher uses different learning materials and strategies (games, storytelling, etc.) in developing activities and lessons.', '13', 'Rate', 'Elementary'),
(69, 'Teacher encourages students to speak up and be active in the class.', '13', 'Rate', 'Elementary'),
(70, 'Teacher follows through on what he/she says. You can count on the teacherâ€™s word.', '14', 'Rate', 'Elementary'),
(71, 'Teacher respects the opinions and decisions of students. He/She listens and understands studentsâ€™ point of view; he/she may not agree, but students feel understood.', '14', 'Rate', 'Elementary'),
(72, 'Teacher is willing to accept responsibility for his/her own mistakes.', '14', 'Rate', 'Elementary'),
(73, 'Teacher is willing to learn from students.', '14', 'Rate', 'Elementary'),
(74, 'Teacher is sensitive to the needs of the students.', '14', 'Rate', 'Elementary'),
(75, 'Teacherâ€™s words and actions match.', '14', 'Rate', 'Elementary'),
(76, 'Teacher is fun to be with.', '14', 'Rate', 'Elementary'),
(77, 'Teacher likes and respects students.', '14', 'Rate', 'Elementary'),
(78, 'Teacher helps you when you ask for help.', '14', 'Rate', 'Elementary'),
(79, 'Teacher is consistent and fair in discipline.', '14', 'Rate', 'Elementary'),
(80, 'Teacher is trustworthy.', '14', 'Rate', 'Elementary'),
(81, 'Teacher tries to model what teacher expects of students.', '14', 'Rate', 'Elementary'),
(82, 'Teacher is fair and firm in discipline without being to strict.', '14', 'Rate', 'Elementary'),
(83, 'What is one thing that your teacher does well?', '15', 'Comment', 'Elementary'),
(84, 'What is one thing that you can suggest to help this teacher improve?', '15', 'Comment', 'Elementary');

-- --------------------------------------------------------

--
-- Table structure for table `table_class`
--

CREATE TABLE `table_class` (
  `id` int(30) NOT NULL,
  `days` varchar(30) NOT NULL,
  `time` varchar(50) NOT NULL,
  `room` text NOT NULL,
  `subjcode` text NOT NULL,
  `instructor` text NOT NULL,
  `branch` text NOT NULL,
  `level` varchar(200) NOT NULL,
  `department` varchar(200) NOT NULL,
  `schoolyear` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `table_class`
--

INSERT INTO `table_class` (`id`, `days`, `time`, `room`, `subjcode`, `instructor`, `branch`, `level`, `department`, `schoolyear`) VALUES
(41, 'TUE', 'Am 07:00 - 12:00', 'PH 210', '75', '9', 'Main', 'College', 'Computer Science', '2'),
(43, 'Saturday', '01:00 - 03:00 PM', 'DOME', '78', '12', 'Main', 'College', 'Arts and Science', '2'),
(44, 'Friday', '01:00 - 04:00 PM', 'PH 204', '74', '10', 'Main', 'College', 'Computer Science', '2'),
(45, 'Monday', '07:00 - 09:00 AM', 'DOME', '78', '12', 'Main', 'College', 'Arts and Science', '2'),
(46, 'Tuesday', '10:30 - 01:30 PM', 'PH 415', '79', '11', 'Main', 'College', 'Arts and Science', '2'),
(47, 'TUE', 'Am 07:00 - 12:00', 'PH 210', '80', '15', 'Main', 'College', 'Arts and Science', '2'),
(48, 'TUE', 'Am 07:00 - 12:00', 'PH 210', '65', '14', 'Main', 'College', 'Computer Science', '2'),
(49, 'TUE', 'Am 07:00 - 12:00', 'PH 210', '73', '13', 'Main', 'College', 'Computer Science', '2'),
(50, 'TUE', 'Am 07:00 - 12:00', 'PH 210', '49', '18', 'Main', 'College', 'Computer Science', '2'),
(51, 'TUE', 'Am 07:00 - 12:00', 'PH 210', '40', '10', 'Main', 'Highschool', 'Computer Science', '2'),
(52, 'TUE', 'Am 07:00 - 12:00', 'PH 210', '79', '11', 'Main', 'Elementary', 'Arts and Science', '2');

-- --------------------------------------------------------

--
-- Table structure for table `table_faculty`
--

CREATE TABLE `table_faculty` (
  `id` int(30) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `middlename` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `department` varchar(200) NOT NULL,
  `level` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `table_faculty`
--

INSERT INTO `table_faculty` (`id`, `firstname`, `middlename`, `lastname`, `department`, `level`) VALUES
(9, 'Geraldine', 'M', 'Rilles', 'Computer Science', 'College'),
(10, 'Dan Micheal', '', 'Francisco', 'Computer Science', 'College'),
(11, 'Edward', 'L', 'Padama', 'Arts and Science', 'College'),
(12, 'Mauricia', 'M', 'Francisco', 'Arts and Science', 'College'),
(13, 'Al', '', 'Santiago', 'Computer Science', 'College'),
(14, 'Jay R', '', 'Torres', 'Computer Science', 'College'),
(15, 'Andreline', 'D', 'Ansula', 'Arts and Science', 'College'),
(17, 'Mercado', ' ', 'Ryan', 'Computer Science', 'College'),
(18, 'Amelita', ' ', 'Delos Reyes', 'Computer Science', 'College'),
(19, 'Archie', ' ', 'Santiago', 'Computer Science', 'College'),
(20, 'Christopher', ' ', 'Pamiloza', 'Computer Science', 'College'),
(21, 'Cristina', ' ', 'Revilla', 'Computer Science', 'College'),
(22, 'Jennifer', ' ', 'Carpio', 'Computer Science', 'College'),
(23, 'Ryan', ' ', 'Fadrigo', 'Computer Science', 'College');

-- --------------------------------------------------------

--
-- Table structure for table `table_schedule`
--

CREATE TABLE `table_schedule` (
  `id` int(11) NOT NULL,
  `student_id` varchar(200) NOT NULL,
  `classes_id` varchar(200) NOT NULL,
  `schoolyear` varchar(200) NOT NULL,
  `eval_status` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `table_schedule`
--

INSERT INTO `table_schedule` (`id`, `student_id`, `classes_id`, `schoolyear`, `eval_status`) VALUES
(202, '1', '41', '2', '1'),
(203, '1', '44', '2', '1'),
(218, '2', '44', '2', '0'),
(219, '3', '44', '2', '0'),
(220, '4', '44', '2', '0'),
(221, '5', '44', '2', '0'),
(222, '2', '41', '2', '0'),
(223, '1', '43', '2', '1'),
(224, '2', '45', '2', '0'),
(225, '2', '43', '2', '0'),
(226, '1', '47', '2', '1'),
(227, '2', '47', '2', '0'),
(228, '3', '47', '2', '0'),
(229, '4', '47', '2', '0'),
(230, '5', '47', '2', '0'),
(231, '1', '48', '2', '1'),
(232, '2', '48', '2', '0'),
(233, '3', '48', '2', '0'),
(234, '4', '48', '2', '0'),
(235, '5', '48', '2', '0'),
(236, '1', '50', '2', '1'),
(237, '1', '49', '2', '1'),
(238, '1', '46', '2', '1'),
(239, '6', '51', '2', '1'),
(240, '7', '52', '2', '1'),
(241, '8', '52', '2', '1');

-- --------------------------------------------------------

--
-- Table structure for table `table_schoolyear`
--

CREATE TABLE `table_schoolyear` (
  `id` int(30) NOT NULL,
  `schoolyear` varchar(200) NOT NULL,
  `semester` varchar(10) NOT NULL,
  `isDefault` int(1) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `table_schoolyear`
--

INSERT INTO `table_schoolyear` (`id`, `schoolyear`, `semester`, `isDefault`, `status`) VALUES
(2, '2023-2024', '1st', 1, 1),
(4, '2023-2024', '3rd', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `table_student`
--

CREATE TABLE `table_student` (
  `id` int(11) NOT NULL,
  `student_id` varchar(100) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `middlename` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `level` varchar(200) NOT NULL,
  `course` varchar(200) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `table_student`
--

INSERT INTO `table_student` (`id`, `student_id`, `firstname`, `middlename`, `lastname`, `password`, `level`, `course`, `status`) VALUES
(1, '18-00423', 'College', ' ', 'Student', '202cb962ac59075b964b07152d234b70', 'College', 'Computer Science', 1),
(2, '24-00006', 'College 1', '', '', '202cb962ac59075b964b07152d234b70', 'College', '', 1),
(3, '24-00007', 'College 2', '', '', '202cb962ac59075b964b07152d234b70', 'College', '', 1),
(4, '24-00008', 'College 3', '', '', '202cb962ac59075b964b07152d234b70', 'College', '', 1),
(5, '24-00009', 'College 4', '', '', '202cb962ac59075b964b07152d234b70', 'College', '', 1),
(6, '18-00001', 'Highschool', ' ', 'Student', '202cb962ac59075b964b07152d234b70', 'Highschool', '', 1),
(7, '18-00002', 'Elementary', '', 'Student', '202cb962ac59075b964b07152d234b70', 'Elementary', '', 1),
(8, '18-00003', 'Elementary', '', 'Student', '202cb962ac59075b964b07152d234b70', 'Elementary', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `table_subject`
--

CREATE TABLE `table_subject` (
  `id` int(30) NOT NULL,
  `code` varchar(50) NOT NULL,
  `subject` text NOT NULL,
  `level` varchar(200) NOT NULL,
  `department` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `table_subject`
--

INSERT INTO `table_subject` (`id`, `code`, `subject`, `level`, `department`) VALUES
(40, 'ITC 110', 'Introduction to Computing', 'College', 'Computer Science'),
(41, 'ITC 111', 'Computer Programming 1', 'College', 'Computer Science'),
(42, 'ITC 112', 'Intro to Graphics and Design', 'College', 'Computer Science'),
(43, 'ITC 120', 'Computer Programming 2', 'College', 'Computer Science'),
(44, 'ITC 121', 'Operating Systems', 'College', 'Computer Science'),
(45, 'ITC 122', 'Intro to Web Design', 'College', 'Computer Science'),
(46, 'ITC 123', 'Applications Dev\'t and Emerging Tech', 'College', 'Computer Science'),
(47, 'ITC 124', 'Fundamentals of Database Systems', 'College', 'Computer Science'),
(48, 'ITC 125 ', 'Data Structures and Algorithms', 'College', 'Computer Science'),
(49, 'CS 210 ', 'Discrete Structures 1', 'College', 'Computer Science'),
(50, 'CS 211', 'Object-oriented Programming', 'College', 'Computer Science'),
(51, 'STAT 01C', 'Probability and Statistics', 'College', 'Computer Science'),
(52, 'ITC 126', 'Information Management', 'College', 'Computer Science'),
(53, 'ITC 127', 'Advance Database Systems', 'College', 'Computer Science'),
(54, 'CS 221', 'Digital Design and Electronics', 'College', 'Computer Science'),
(55, 'CS 222', 'Computer Architecture', 'College', 'Computer Science'),
(56, 'CS 223', 'Discrete Structures 2', 'College', 'Computer Science'),
(57, 'CS 224', 'Networks and Communication', 'College', 'Computer Science'),
(58, 'ITC 129', 'Computer Org (w/ Assembly Lang.)', 'College', 'Computer Science'),
(59, 'CS 310', 'Software Engineering 1', 'College', 'Computer Science'),
(60, 'CS 311', 'Computer Programming 3', 'College', 'Computer Science'),
(61, 'CS 312', 'Algorithms and Complexity', 'College', 'Computer Science'),
(62, 'CS 313', 'Elective 1', 'College', 'Computer Science'),
(63, 'CS 314', 'Linear Algebra', 'College', 'Computer Science'),
(64, 'ITC 130', 'Computer Accounting (w/ SAP)', 'College', 'Computer Science'),
(65, 'CS 320', 'Software Engineering 2', 'College', 'Computer Science'),
(66, 'CS 321', 'Programming Languages', 'College', 'Computer Science'),
(67, 'CS 322', 'Elective 2', 'College', 'Computer Science'),
(68, 'CS 323', 'Math Analysis', 'College', 'Computer Science'),
(69, 'CS 330', 'Computer Practicum', 'College', 'Computer Science'),
(70, 'ITC 128', 'Social Issues and Professional Practice', 'College', 'Computer Science'),
(71, 'CS 411', 'Thesis 1', 'College', 'Computer Science'),
(72, 'CS 412', 'Elective 3', 'College', 'Computer Science'),
(73, 'CS 413', 'Automata Theory & Formal Languages', 'College', 'Computer Science'),
(74, 'CS 420', 'Information Assurance and Security', 'College', 'Computer Science'),
(75, 'CS 421', 'Thesis 2', 'College', 'Computer Science'),
(76, 'CS 422', 'Human Computer Interaction', 'College', 'Computer Science'),
(77, 'CS 423', 'Elective 4', 'College', 'Computer Science'),
(78, 'GCAS 18', 'PE-4 Team Sports', 'College', 'Arts and Science'),
(79, 'GCAS 14', 'World Literature', 'College', 'Arts and Science'),
(80, 'GCAS IR', 'Community Development', 'College', 'Arts and Science'),
(81, 'GCAS 17', 'PE-3 Swimming/Dance', 'College', 'Arts and Science');

-- --------------------------------------------------------

--
-- Table structure for table `table_users`
--

CREATE TABLE `table_users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `table_users`
--

INSERT INTO `table_users` (`id`, `firstname`, `lastname`, `email`, `password`) VALUES
(1, 'admin', ' ', 'guidance@admin', '202cb962ac59075b964b07152d234b70');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `criteria_list`
--
ALTER TABLE `criteria_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluation_list`
--
ALTER TABLE `evaluation_list`
  ADD PRIMARY KEY (`evaluation_id`);

--
-- Indexes for table `questionnaire_list`
--
ALTER TABLE `questionnaire_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `table_class`
--
ALTER TABLE `table_class`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `table_faculty`
--
ALTER TABLE `table_faculty`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `table_schedule`
--
ALTER TABLE `table_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `table_schoolyear`
--
ALTER TABLE `table_schoolyear`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `table_student`
--
ALTER TABLE `table_student`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `table_subject`
--
ALTER TABLE `table_subject`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `table_users`
--
ALTER TABLE `table_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `criteria_list`
--
ALTER TABLE `criteria_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `evaluation_list`
--
ALTER TABLE `evaluation_list`
  MODIFY `evaluation_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `questionnaire_list`
--
ALTER TABLE `questionnaire_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `table_class`
--
ALTER TABLE `table_class`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `table_faculty`
--
ALTER TABLE `table_faculty`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `table_schedule`
--
ALTER TABLE `table_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=242;

--
-- AUTO_INCREMENT for table `table_schoolyear`
--
ALTER TABLE `table_schoolyear`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `table_student`
--
ALTER TABLE `table_student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `table_subject`
--
ALTER TABLE `table_subject`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `table_users`
--
ALTER TABLE `table_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
