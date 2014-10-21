CREATE TABLE events (
    event_id int(9) NOT NULL AUTO_INCREMENT,
    location text NOT NULL,
    event_time int(11) NOT NULL,
    event_name varchar(30) NOT NULL,
    no_of_people varchar(11) NOT NULL,
    description text NOT NULL,
    PRIMARY KEY (event_id)
) ENGINE=MyISAM
