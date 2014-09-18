CREATE TABLE users (
    user_id int(9) NOT NULL AUTO_INCREMENT,
    username varchar(30) NOT NULL,
    password varchar(30) NOT NULL,
    first_name varchar(30) NOT NULL,
    last_name varchar(30) NOT NULL,
    picture varchar(100) NOT NULL,
    gender varchar(10) NOT NULL,
    PRIMARY KEY (user_id)
) ENGINE=MyISAM
