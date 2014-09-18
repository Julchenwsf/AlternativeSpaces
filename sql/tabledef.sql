CREATE TABLE photos (
	photo_id INT NOT NULL AUTO_INCREMENT,
	location POINT NOT NULL,
	photo_title VARCHAR(250) NOT NULL,
	upload_time INT NOT NULL,
	rating DECIMAL(10,2),
	PRIMARY KEY photo_id (photo_id),
	SPATIAL KEY location (location)
) ENGINE=MYISAM