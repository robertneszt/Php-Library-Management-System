DROP DATABASE IF EXISTS library_management_system;
CREATE DATABASE library_management_system;

USE library_management_system;

DROP TABLE IF EXISTS books;
CREATE TABLE books (
    book_id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    publisher VARCHAR(255) NOT NULL,
    date_of_publication INT NOT NULL,
    isbn VARCHAR(13) NOT NULL,
    num_copies INT NOT NULL
);

DROP TABLE IF EXISTS users;
CREATE TABLE users (
    user_id INT(11) PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    address VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    role VARCHAR(10) NOT NULL,
    registration_date DATE NOT NULL,
    birth_date DATE NULL
);

CREATE TABLE IF NOT EXISTS `loanhistory` (
  `id` int(11) PRIMARY KEY AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `book_title` varchar(250) NOT NULL,
  `isbn` bigint(20) NOT NULL,
  `authors` varchar(250) NOT NULL,
  `publisher` varchar(250) NOT NULL,
  `publish_date` date NOT NULL,
   FOREIGN KEY (user_id) REFERENCES users (user_id)
);

INSERT INTO `books` (`book_id`, `title`, `author`, `publisher`, `date_of_publication`, `isbn`) VALUES
(1, 'The Great Gatsby', 'F. Scott Fitzgerald', 'Charles Scribner\'s Sons', '0000-00-00', '9780743273565'),
(2, 'To Kill a Mockingbird', 'Harper Lee', 'J. B. Lippincott & Co.', '0000-00-00', '9780060935467'),
(3, '1984', 'George Orwell', 'Secker and Warburg', '0000-00-00', '9780452284234'),
(4, 'The Catcher in the Rye', 'J.D. Salinger', 'Little, Brown and Company', '0000-00-00', '9780316769488'),
(5, 'One Hundred Years of Solitude', 'Gabriel Garcia Marquez', 'Harper & Row', '0000-00-00', '9780060883287'),
(6, 'The Hobbit', 'J.R.R. Tolkien', 'Allen & Unwin', '0000-00-00', '9780261103344'),
(7, 'Pride and Prejudice', 'Jane Austen', 'T. Egerton, Whitehall', '0000-00-00', '9780486284736'),
(8, 'The Lord of the Rings', 'J.R.R. Tolkien', 'Allen & Unwin', '0000-00-00', '9780544003415'),
(9, 'Animal Farm', 'George Orwell', 'Secker and Warburg', '0000-00-00', '9780452284241'),
(10, 'Brave New World', 'Aldous Huxley', 'Chatto & Windus', '0000-00-00', '9780061767647'),
(11, 'THE LITTLE BOOK OF REAL ESTATE INVESTING IN CANADA', 'Don R. Campbell', 'John Wiley & Sons', '2013-01-29', '9781118464106'),
(12, 'PHP DEVELOPMENT TOOL ESSENTIALS', 'Chad Russell', 'Apress', '2016-07-15', '9781484206836'),
(13, 'PHP DEVELOPMENT TOOL ESSENTIALS', 'Chad Russell', 'Apress', '2016-07-15', '9781484206836'),
(14, 'ESSENTIAL PHP FAST', 'Simon Stobart', 'Springer Science & Business Media', '2002-06-28', '1852335785');

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `address`, `phone`, `role`, `registration_date`, `birth_date`) VALUES
(1, 'Shubh', 'shubh@shubh.com', 'Asd@1234', '123 Elm St, Whatever USA', '555-5555', 'user', '2022-01-10', NULL),
(2, 'Jane Smith', 'janesmith@yahoo.com', 'Asd@1234', '456 Elm St, Anycity USA', '555-5678', 'user', '2022-01-15', NULL),
(3, 'Bob Johnson', 'bjohnson@hotmail.com', 'Asd@1234', '789 Oak Ave, Anyville USA', '555-2468', 'user', '2022-02-01', NULL),
(4, 'Alice Brown', 'abrown@gmail.com', 'Asd@1234', '321 Pine Dr, Anystate USA', '555-1357', 'user', '2022-02-15', NULL),
(5, 'Tom Wilson', 'twilson@yahoo.com', 'Asd@1234', '654 Birch Blvd, Anytown USA', '555-3698', 'user', '2022-03-01', NULL),
(6, 'Sara Lee', 'slee@hotmail.com', 'Asd@1234', '987 Maple St, Anycity USA', '555-8024', 'user', '2022-03-15', NULL),
(7, 'Mike Davis', 'mdavis@gmail.com', 'Asd@1234', '246 Cedar Ave, Anyville USA', '555-4682', 'user', '2022-04-01', NULL),
(8, 'Karen Lee', 'klee@yahoo.com', 'Asd@1234', '369 Spruce St, Anystate USA', '555-2357', 'user', '2022-04-15', NULL),
(9, 'Steve Martin', 'smartin@hotmail.com', 'Asd@1234', '802 Oakwood Dr, Anytown USA', '555-9876', 'user', '2022-05-01', NULL),
(11, 'Administrator', 'admin@library.city', 'Asd@1234', '100 Main St, Anycity USA', '555-8000', 'admin', '2021-09-15', NULL),
(12, 'Robert', 'robert@geemail.com', 'Asd@1234', '123 John Abbott', '+15145555551', 'admin', '2023-03-06', NULL),
(13, 'Nadia', 'nadia@geemail.com', 'Asd@1234', '234 John Abbott', '+15145555552', 'admin', '2023-03-06', NULL),
(14, 'Ruiqing', 'ruiqing@geemail.com', 'Asd@1234', '345 John Abbott', '+15145555553', 'admin', '2023-03-06', NULL),
(15, 'Shubhparteek', 'Shubhparteek@geemail.com', 'Asd@1234', '456 John Abbott', '+15145555554', 'admin', '2023-03-06', NULL),
(22, 'Robert Neszt', 'robertneszt1@gmail.com', '$2y$10$rxGXdb073qoAKziknymDze32xyIqrahGc.W3G/3u5BtAHYW./fXwu', '6231 Rue Alma', '+51466612345', 'admin', '2023-03-07', '1980-06-19'),
(24, 'Robert Neszt', 'user@user.com', '$2y$10$2BErc5R3VVA0G8F3c2p6FuPNAB90ier2YaIFDADecLLP7kd7TJ33a', '6231 Rue Alma', '+12345678910', 'user', '2023-03-07', '1980-06-06'),
(25, 'Robert Neszt', 'robertneszt@gmail.com', '$2y$10$FAQkfTscmPf3Bi5OmZ.o5eSfh5dy1GxvKFMwNhGkUzb5ZPPyuwzHW', '6231 Rue Alma', '+15146688562', 'user', '2023-03-07', '1980-12-01'),
(26, 'shubh', 'shubhdhami@gmail.com', '$2y$10$oTx85KNm5LOvJYRF5B6X4.71iE2qFTg57xs42MUfflOS44jFmH6mm', '14 shubh', '+15148150899', 'user', '2023-03-08', '1998-07-18'),
(27, 'Victor', 'shubh@haha.com', '$2y$10$6Yai7sQCG.mnbj5uuVVNZeUfRaAx3MzmCwZeDzvoxffg9itMiBgfq', '14 dg', '+15148150899', 'user', '2023-03-08', '2010-02-02'),
(28, 'Rob', 'rob@live.ca', 'Qsd@1234', '14 shubh', '+15148150800', 'user', '2023-03-08', '1991-06-04'),
(29, 'Nadia', 'nadia@live.ca', '$2y$10$yQFveWqptkptkZgRnykph.cwqU66T/B7sy34/s8HXt5bYSSzSzaaS', '25 shubh', '+15148150900', 'user', '2023-03-08', '1999-02-02'),
(30, 'james', 'james@haha.ca', '$2y$10$cNrl5RBPAQZkaNgndRF1gekSAfyxoMrvysn6Jd8wRPozeG/kKW0JC', '2884 montcalm', '+15148150900', 'user', '2023-03-08', '1985-03-07'),
(31, 'jonny', 'jonny@live.ca', '$2y$10$AWZhxAlPSmtwdGPP/.9Jj.HVk9vP9La0Ge1Jmp9VXhZpV2Sap/sou', '3000 rue clark', '+15148150899', 'user', '2023-03-08', '1978-08-25'),
(32, 'jessica', 'jessica@live.ca', '$2y$10$hP9WutYYaKZtQWVQ8avyHe1Sbe962vNd5eOGgJz6xs2n0giQp5OUi', '15 harwood', '+15148150900', 'user', '2023-03-08', '2003-02-06');

INSERT INTO `loanhistory` (`id`, `user_id`, `user_name`, `book_title`, `isbn`, `authors`, `publisher`, `publish_date`) VALUES
(1, 1, 'Shubh', 'THE WISDOM OF GOD', 9781433526350, 'Nancy Guthrie', 'Crossway', '2012-02-29'),
(2, 1, 'Shubh', 'IKIGAI', 9781473539600, 'Héctor García,Francesc Miralles', 'Random House', '2017-09-07'),
(4, 1, 'Shubh', 'CINDERELLA (DISNEY PRINCESS)', 736423621, 'RH Disney', 'Golden/Disney', '2005-08-23'),
(5, 1, 'Shubh', 'SILVER', 9781407136790, 'Chris Wooding', 'Scholastic UK', '2013-05-01'),
(6, 1, 'Shubh', 'IKIGAI', 9780143130727, 'Héctor García,Francesc Miralles', 'Penguin', '2017-08-29'),
(14, 1, 'Shubh', 'THINK HAPPY', 9781607749622, 'Karen Salmansohn', 'Ten Speed Press', '2016-08-09'),
(16, 1, 'Shubh', 'THE LITTLE BOOK OF IKIGAI', 178747027, 'Ken Mogi', 'undefined', '2018-09-20'),
(17, 1, 'Shubh', 'THINK AND GROW RICH', 449214923, 'Napoleon Hill', 'Ballantine Books', '1987-05-12'),
(19, 1, 'Shubh', 'DATA STRUCTURES AND ABSTRACTIONS WITH JAVA', 133744051, 'Frank M. Carrano,Timothy M. Henry', 'Prentice Hall', '2014-08-13'),
(21, 1, 'Shubh', 'THINK JAVA', 9781491929537, 'Allen B. Downey,Chris Mayfield', '', '2016-05-06'),
(22, 1, 'Shubh', 'FINDING CINDERELLA', 9781476783284, 'Colleen Hoover', 'Simon and Schuster', '2014-03-18'),
(23, 1, 'Shubh', 'THE CINDERELLA DEAL', 9780345530660, 'Jennifer Crusie', 'Bantam', '2011-10-25'),
(24, 1, 'Shubh', 'CINDERELLA', 893751200, 'Charles Perrault', 'Troll Communications Llc', '0000-00-00'),
(25, 1, 'Shubh', 'IKIGAI FOR TEENS: FINDING YOUR REASON FOR BEING', 1338670832, 'undefined', 'Scholastic Press', '2021-04-20'),
(26, 1, 'Shubh', 'THINKING IN JAVA', 131002872, 'Bruce Eckel', 'Prentice Hall Professional', '0000-00-00'),
(27, 1, 'Shubh', 'CINDERELLA (DISNEY CLASSIC)', 736421513, 'Jane Werner', 'Golden/Disney', '2002-11-26'),
(28, 1, 'Shubh', 'HARDCORE JAVA', 596005687, 'Robert Simmons Jr,Robert Jr', '', '0000-00-00'),
(30, 1, 'Shubh', 'KEY JAVA', 3540762590, 'John Hunt,Alexander G. McManus', 'Springer Science & Business Media', '1998-07-10'),
(31, 1, 'Shubh', 'THINKING IN JAVA', 9780131872486, 'Bruce Eckel', 'Pearson Education', '0000-00-00'),
(33, 1, 'Shubh', 'HECTOR AND THE SEARCH FOR HAPPINESS', 9780143118398, 'Francois Lelord', 'Penguin', '2010-08-31'),
(37, 1, 'Shubh', 'FLIGHT TO CANADA', 9780684847504, 'Ishmael Reed', 'Simon and Schuster', '1998-06-02'),
(38, 1, 'Shubh', 'CANADA ALWAYS', 9780771059773, 'Arthur Milnes', 'McClelland & Stewart', '2016-10-25'),
(39, 1, 'Shubh', 'CANADA ABC', 1443448842, 'Paul Covello', 'HarperCollins', '2016-09-20'),
(40, 1, 'Shubh', 'CANADA CLOSE UP: CANADIAN MONEY', 9781443104371, 'Elizabeth MacLeod', 'Scholastic Canada', '0000-00-00'),
(41, 1, 'Shubh', 'DIGITAL MOSAIC', 9781442608863, 'David Taras', 'University of Toronto Press', '2015-01-01'),
(42, 28, '', 'CAMELOT AND CANADA', 9780190605056, 'Asa McKercher', 'Oxford University Press', '0000-00-00'),
(43, 28, 'Rob', 'THE IRISHMAN IN CANADA', 0, 'Nicholas Flood Davin', 'London : S. Low, Marston ; Toronto : Maclear', '0000-00-00'),
(44, 29, 'Nadia', 'HOW TO IKIGAI', 9781633539013, 'Tim Tamashiro', 'Mango Media Inc.', '2019-01-31'),
(45, 29, 'Nadia', 'THE LITTLE BOOK OF REAL ESTATE INVESTING IN CANADA', 9781118464106, 'Don R. Campbell', 'John Wiley & Sons', '2013-01-29'),
(46, 2, 'Jane Smith', 'PHP DEVELOPMENT TOOL ESSENTIALS', 9781484206836, 'Chad Russell', 'Apress', '2016-07-15'),
(47, 29, 'Nadia', 'ESSENTIAL PHP FAST', 1852335785, 'Simon Stobart', 'Springer Science & Business Media', '2002-06-28');
