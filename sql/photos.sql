CREATE TABLE photos (
    photo_id int(11) NOT NULL AUTO_INCREMENT,
    location point NOT NULL,
    photo_title varchar(250) NOT NULL,
    upload_time int(11) NOT NULL,
    rating decimal(10,2) DEFAULT NULL,
    interests text NOT NULL,
    PRIMARY KEY (photo_id),
    SPATIAL KEY location (location),
    FULLTEXT KEY interests (interests)
) ENGINE=MyISAM