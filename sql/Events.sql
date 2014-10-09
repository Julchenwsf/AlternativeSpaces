CREATE TABLE Events (
    event_id int(9) NOT NULL AUTO_INCREMENT,
    eventname varchar(30) NOT NULL,
    Location varchar(30) NOT NULL,
     DATE int(4) NOT NULL,
     MONTH  int(4)NOT NULL,
     YEAR int(4)NOT NULL,
    HOUR int(4)NOT NULL,
    MINUTE int(4)NOT NULL
    PRIMARY KEY (event_id)
) ENGINE=MyISAM
