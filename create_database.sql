use scout;
drop database scout;
create database scout;
use scout;

CREATE TABLE IF NOT EXISTS users(
    user_id INT auto_increment,
    firstname VARCHAR(50) NOT NULL,
    lastname VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    password CHAR(128) NOT NULL,
    salt CHAR(128) NOT NULL,
    date_of_birth DATETIME NULL,
    info TEXT NULL,
    photo BLOB NULL,
    authentication_link CHAR(128),
    change_password_link CHAR(128) DEFAULT NULL,
    options TINYINT(4) DEFAULT 0,
    PRIMARY KEY (user_id)
) ENGINE=INNODB;

INSERT INTO users (firstname, lastname, username, email, password, salt, date_of_birth, info)
VALUES
       (
        'Filip',
        'Matuszczak',
        'filimatu',
        'chips1331@gmail.com',
        'E501E9428A10F5F485A8F6DDFFF1076C7E51D37AF0A650C15BB81CD8C2638543C61AF90754A122C5CE3238506022801A982F09B1C054327F084E73AED940D908',
        'qwer1234',
        DATE('1970-01-01 00:00:01'),
        'I like trains'
        ),
       (
           'Ewa',
           'Dziembakowska',
           'ewadziem',
           'qwer@qwer.com',
           'E501E9428A10F5F485A8F6DDFFF1076C7E51D37AF0A650C15BB81CD8C2638543C61AF90754A122C5CE3238506022801A982F09B1C054327F084E73AED940D908',
           'qwer1234',
           DATE('1997-01-01 00:00:01'),
           'I dislike trains'
       ),
       (
           'Bartosz',
           'Mila',
           'bartmila',
           'rewq@rewq.com',
           'E501E9428A10F5F485A8F6DDFFF1076C7E51D37AF0A650C15BB81CD8C2638543C61AF90754A122C5CE3238506022801A982F09B1C054327F084E73AED940D908',
           'qwer1234',
           DATE('2000-01-01 00:00:01'),
           'I like prolog'
       ),
       (
           'Stanislaw',
           'Wasik',
           'stanwasi',
           'erwq@erwq.com',
           'E501E9428A10F5F485A8F6DDFFF1076C7E51D37AF0A650C15BB81CD8C2638543C61AF90754A122C5CE3238506022801A982F09B1C054327F084E73AED940D908',
           'qwer1234',
           DATE('1999-01-01 00:00:01'),
           'I like haskell'
       );

CREATE TABLE IF NOT EXISTS technologies_requests(
    request_id INT auto_increment,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    PRIMARY KEY (request_id),
    name VARCHAR(50) NOT NULL,
    description_proposition TEXT NOT NULL,
    timestamp DATETIME NOT NULL,
    options TINYINT(4) DEFAULT 0
);

INSERT INTO technologies_requests (user_id, name, description_proposition, timestamp)
VALUES(
       1,
       'Prolog',
       'The best programming language',
       NOW()
      ),
      (
       2,
       'Haskell',
       'Second best programming language',
       NOW()
      ),
      (
        3,
       'Erlang',
       'Third best programming language',
       NOW()
      ),
      (
       3,
       'C#',
       'Worst programming language',
       NOW()
      );

CREATE TABLE IF NOT EXISTS languages(
    language_id INT auto_increment,
    name varchar(50) not null,
    PRIMARY KEY(language_id)
);

insert into languages(name)
value ('Polish'), ('English'), ('German'), ('French');

CREATE TABLE IF NOT EXISTS users_languages(
    language_id int not null,
    user_id int not null,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (language_id) REFERENCES languages(language_id),
    PRIMARY KEY (language_id, user_id)
);

insert into users_languages values (1, 2), (2, 1), (1, 3), (3,1), (4,4);

create table if not exists messages(
    message_id int auto_increment,
    sender_id int not null,
    receiver_id int not null,
    text TEXT not null,
    timestamp DATETIME NOT NULL,
    PRIMARY KEY (message_id),
    FOREIGN KEY (sender_id) REFERENCES users(user_id),
    FOREIGN KEY (receiver_id) REFERENCES users(user_id)
);

insert into messages (sender_id, receiver_id, text, timestamp) values
(1,3,'siema', (NOW()-interval 1 DAY)),
(2,4, 'Co tam?', (NOW()-interval 3 DAY)),
(3,1, 'No elo', (NOW())),
(2,4, 'Czesc', (NOW()-interval 3 DAY)),
(2,1, 'siemano', (NOW()-interval 3 DAY));

create table if not exists projects(
project_id INT auto_increment,
title VARCHAR(50) NOT NULL,
description TEXT NULL,
created_date DATETIME NOT NULL,
primary key (project_id)
);

insert into projects (title, description, created_date) VALUES
('gra w kolko i krzyzyk', 'taka fajna gra', NOW()),
('lepszy fejsbuk', 'nie podoba nam sie fb wiec robimy swoj', NOW()),
('gorszy twitter', 'chcemy udowodnic, ze da sie zrobic cos gorszego niz twitter', NOW());

 create table if not exists add_user_to_project_requests(
     request_id int auto_increment,
     user_id INT not null ,
     project_id INT not null,
     text TEXT NULL,
     timestamp DATETIME NOT NULL,
     options tinyint(4) default 0,
     primary key (request_id),
     FOREIGN KEY (user_id) REFERENCES users(user_id),
     FOREIGN KEY (project_id) REFERENCES projects(project_id)
 );

create table if not exists users_projects(
    user_id INT not null ,
    project_id INT not null,
    options tinyint(4) default 0,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (project_id) REFERENCES projects(project_id),
    primary key (user_id, project_id)
);

insert into users_projects (user_id, project_id) values (1,2), (1,3), (2,1);

create table if not exists countries (
    country_id int auto_increment,
    name varchar(50) not null,
    status tinyint(4) default 0,
    primary key(country_id)
);

create table if not exists users_countries(
    user_id INT not null ,
    country_id INT not null,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (country_id) REFERENCES countries(country_id),
    primary key (user_id, country_id)
);

insert into countries (name) values ('England'), ('Poland'), ('USA'),('France'),('Germany');

insert into users_countries (user_id, country_id) VALUES (1,1), (2,2), (3,3), (4,4);

create table if not exists cities(
    city_id int auto_increment,
    country_id int not null,
    name varchar(50) not null,
    FOREIGN KEY (country_id) REFERENCES countries(country_id),
    primary key (city_id)
);

insert into cities (name, country_id) values ('London',1), ('Warsaw',2), ('New York',3),('Paris',4),('Berlin',5);



create table if not exists users_cities(
    user_id INT not null ,
    city_id INT not null,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (city_id) REFERENCES cities(city_id),
    primary key (user_id, city_id)
);

insert into users_cities (user_id, city_id) VALUES (1,1), (2,2), (3,3), (4,4);



 create table if not exists photos(
     photo_id int auto_increment,
     user_id INT not null ,
     project_id INT null,
     photo BLOB NOT NULL,
     timestamp DATETIME not null,
     description text not null,
     primary key (photo_id),
     FOREIGN KEY (user_id) REFERENCES users(user_id),
     FOREIGN KEY (project_id) REFERENCES projects(project_id)
 );

create table if not exists posts(
    post_id int auto_increment,
    user_id INT not null ,
    project_id INT null,
    timestamp DATETIME not null,
    text text not null,
    primary key (post_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (project_id) REFERENCES projects(project_id)
);

insert into posts (user_id, project_id, timestamp, text) VALUES
(1, 2, NOW(), 'lubie placki'),
(2, null, NOW(), 'szukam pracy'),
(3, null, NOW(), 'XD'),
(2, 1, NOW(), 'Lubie kodzic');

 
create table if not exists reports(
    report_id int auto_increment,
    user_id INT not null,
    post_id INT null,
    photo_id INT null,
    reason TEXT NOT NULL,
    timestamp DATETIME NOT NULL,
    options TINYINT(4) DEFAULT 0,
    primary key (report_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (post_id) REFERENCES posts(post_id),
    FOREIGN KEY (photo_id) REFERENCES photos(photo_id)
);

insert into reports (user_id, post_id, photo_id, reason, timestamp) VALUES
(1,2,null,'i dont like it',now()),
(2,1,null,'its offensive',now()),
(1,3,null,'please ban it',now());

create table if not exists technologies(
    technology_id int auto_increment,
    name VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    options TINYINT(4) DEFAULT 0,
    primary key (technology_id)
);

insert into technologies (name, description) VALUES
('Java', 'Jezyk programowania'),
('CPP', 'Jezyk programowania'),
('Visual Studio', 'Srodowisko programowania');

create table if not exists users_technologies(
                                                 technology_id int not null,
                                                 user_id int not null,
                                                 FOREIGN KEY (user_id) REFERENCES users(user_id),
                                                 FOREIGN KEY (technology_id) REFERENCES technologies(technology_id),
                                                 PRIMARY KEY (technology_id, user_id)
);

insert into users_technologies (technology_id, user_id) VALUES (1,2),(1,1),(3,2),(3,1);

create table if not exists projects_technologies(
                                                 technology_id int not null,
                                                 project_id int not null,
                                                 FOREIGN KEY (project_id) REFERENCES projects(project_id),
                                                 FOREIGN KEY (technology_id) REFERENCES technologies(technology_id),
                                                 PRIMARY KEY (technology_id, project_id)
);

insert into projects_technologies (technology_id, project_id) VALUES (1,2),(1,1),(3,2),(3,1);