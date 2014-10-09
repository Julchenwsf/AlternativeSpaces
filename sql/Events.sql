CREATE TABLE Events (
    event_id int(9) NOT NULL AUTO_INCREMENT,
    eventname varchar(30) NOT NULL,
    Location varchar(30) NOT NULL,
     DAYOFMONTH(date) NOT NULL,
     MONTH NOT NULL,
     YEAR NOT NULL,
    HOUR NOT NULL,
    MINUTE NOT NULL
    PRIMARY KEY (event_id)
) ENGINE=MyISAM
