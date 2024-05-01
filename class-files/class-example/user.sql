CREATE TABLE `user` (
  `ID` int(11)  PRIMARY KEY AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` char(60) NOT NULL,
  `name` varchar(255) NOT NULL
);


INSERT INTO `user` (`ID`, `email`, `password`, `name`) VALUES (1, 'test@vcu.edu', '$2y$10$m2VseJAjEXWM5MTYb8dfaehQGNzok5eT4GtNFu4nFw5hp6iLZ.yAK', 'Test User');

-- Use https://onlinephp.io/password-hash to generate the hash for the password