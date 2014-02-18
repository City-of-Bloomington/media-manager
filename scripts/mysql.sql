-- @copyright 2014 City of Bloomington, Indiana
-- @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
-- @author Cliff Ingham <inghamn@bloomington.in.gov>
create table departments (
	id int unsigned not null primary key auto_increment,
	name varchar(128) not null
);

create table people (
	id int unsigned not null primary key auto_increment,
	firstname varchar(128) not null,
	lastname  varchar(128) not null,
	email     varchar(255) not null,
	username  varchar(40) unique,
	password  varchar(40),
	authenticationMethod varchar(40),
	role varchar(30),
	department_id int unsigned,
	foreign key (department_id) references departments(id)
);

create table media (
	id int unsigned not null primary key auto_increment,
	internalFilename varchar(50) not null,
	filename   varchar(128) not null,
	mime_type  varchar(128),
	media_type varchar(50),
	title      varchar(128),
	description text,
	md5        varchar(32)  not null,
	uploaded   datetime     not null,
	person_id     int unsigned not null,
	department_id int unsigned not null,
	foreign key (person_id)     references people     (id),
	foreign key (department_id) references departments(id)
);

create table tags (
	id int unsigned not null primary key auto_increment,
	name varchar(128) not null unique
);

create table media_tags (
	media_id int unsigned not null,
	tag_id   int unsigned not null,
	foreign key (media_id) references media(id),
	foreign key (tag_id)   references tags (id)
);
