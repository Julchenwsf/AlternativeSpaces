CREATE TABLE interests (
    interest_id int(11) NOT NULL AUTO_INCREMENT,
    interest_name varchar(40) NOT NULL,
    interest_icon varchar(50) DEFAULT 'empty.png',
    PRIMARY KEY (interest_id)
) ENGINE=MyISAM AUTO_INCREMENT=1000