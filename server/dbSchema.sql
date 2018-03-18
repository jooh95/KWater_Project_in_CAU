CREATE DATABASE kwater;
USE kwater;

ALTER DATABASE kwater DEFAULT CHARACTER SET utf8 collate utf8_general_ci;

create table location(
	idlocation varchar(45) primary key,
	location1 varchar(45) not null,
	location2 varchar(45),
	location3 varchar(45),
	location4 varchar(45)
) ENGINE=INNODB;

create table station(
	idstation varchar(45) primary key,
	`desc` varchar(20),
	idlocation varchar(45) not null
) ENGINE=INNODB;

create table datapoint(
	iddatapoint varchar(45) primary key,
	timestamp varchar(20) not null,
	value double not null,
	flag varchar(1) not null,
	idstation varchar(45) not null,
	idcriteria varchar(45) not null	
)ENGINE=INNODB;

create table criteria(
	idcriteria varchar(45) primary key,
	value1 double not null,
	value2 double,
	`desc` varchar(45),
	unit varchar(10) not null
)ENGINE=INNODB;

ALTER TABLE datapoint ADD FOREIGN KEY(idstation) REFERENCES station (idstation) on delete cascade on update cascade;
ALTER TABLE datapoint ADD FOREIGN KEY(idcriteria) REFERENCES criteria (idcriteria) on delete cascade on update cascade;
ALTER TABLE station ADD FOREIGN KEY(idlocation) REFERENCES location (idlocation) on delete cascade on update cascade;