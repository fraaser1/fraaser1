create database db_istj;
use db_istj;
create table benutzer (id int unsigned not null auto_increment primary key,
	vorname varchar(30),
	nachname varchar(50),
	benutzername varchar(50),
	email varchar(100),
	timestamp datetime,
	bild varchar(100),
	geschlecht char(1));

create table score (id tinyint unsigned not null primary key,
	spruch varchar(1000));

insert into table score values
(0,"Da ist aber noch nachholbedarf"),
(1,"Oh Oh war wohl nicht dein Tag"),
(2,"Da ist aber noch Luft nach oben"),
(3,"Weiter so, aber könnte noch besser sein"),
(4,"Langsam wird's"),
(5,"Na also, geht doch");