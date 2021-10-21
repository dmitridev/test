use test;

create table users(
	id int not null auto_increment, 
    email varchar(50) not null, 
    password varchar(50) not null, 
    photo varchar(50),
    status int,
    activation_code varchar(50),
    `key` varchar(50),
    primary key(id)
)