/** Ordre de supression :
DROP TABLE token_reset;
DROP TABLE current2user;
DROP TABLE favorite2user;
DROP TABLE comment2user;
DROP TABLE user2genre;
DROP TABLE user;
DROP TABLE genre;
**/



create table user (
    id integer(11) NOT NULL auto_increment,
    email varchar(25) NOT NULL,
    pass varchar(255) NOT NULL,
    valid boolean DEFAULT false,
    nom varchar(50) NULL,
    prenom varchar(50) NULL,
    date_birth date NULL,
    parental_authorisation boolean,

    primary key (id)
);

create table genre(
    id integer(11) NOT NULL,
    libelle varchar(40) NOT NULL,

    CONSTRAINT pk_genre PRIMARY KEY(id)
);

create table user2genre (
    iduser integer(11) NOT NULL,
    idgenre integer(11) NOT NULL,

    CONSTRAINT pk_user2genre PRIMARY KEY (iduser, idgenre),
    CONSTRAINT fk_user2genre_user_iduser FOREIGN KEY (iduser) REFERENCES user(id),
    CONSTRAINT fk_user2genre_genre_idgenre FOREIGN KEY (idgenre) REFERENCES genre(id)
);

create table token_reset (
    token varchar(255) not null unique,
    iduser integer(11) not null,
    date_expiration datetime,
    type_token varchar(1) CHECK(type_token IN('R', 'V')),

    CONSTRAINT pk_token primary key(token, idUser),
    CONSTRAINT fk_token_reset_user_iduser foreign key (iduser) REFERENCES user(id)
);

create table favorite2user (

    iduser integer(11) not null,
    idserie integer(11) not null,

    CONSTRAINT pk_favorite2user primary key(idserie, iduser),
    CONSTRAINT fk_favorite2user_user_iduser foreign key (iduser) REFERENCES user(id),
    CONSTRAINT fk_serie_idserie foreign key (idserie) REFERENCES serie(id)

);

create table current2user (
    iduser integer(11) not null,
    idserie integer(11) not null,

    CONSTRAINT pk_current2user primary key (iduser, idserie),
    CONSTRAINT fk_current2user_user_iduser foreign key (iduser) REFERENCES user(id),
    CONSTRAINT fk_current2user_serie_idserie foreign key (idserie) REFERENCES serie(id)

);

create table comment2user (
    idserie integer(11) not null,
    iduser integer(11) not null,
    note integer(1) CHECK(note IN(1,2,3,4,5)),
    commentaire tinytext,

    CONSTRAINT pk_comment2user primary key(idserie, iduser),
    CONSTRAINT fk_comment2user_user_iduser foreign key (iduser) REFERENCES user(id),
    CONSTRAINT fk_comment2user_serie_idserie foreign key(idserie) REFERENCES serie(id)

);

UPDATE serie SET img = 'ressources/img/seriesThumbnails/1.png' WHERE id=1;
UPDATE serie SET img = 'ressources/img/seriesThumbnails/2.png' WHERE id=2;
UPDATE serie SET img = 'ressources/img/seriesThumbnails/3.png' WHERE id=3;
UPDATE serie SET img = 'ressources/img/seriesThumbnails/4.png' WHERE id=4;
UPDATE serie SET img = 'ressources/img/seriesThumbnails/5.png' WHERE id=5;
UPDATE serie SET img = 'ressources/img/seriesThumbnails/6.png' WHERE id=6;



Alter table episode add img varchar(40);

update episode Set img = 'ressources/img/episodes/1/1.png' where id= 1;
update episode Set img = 'ressources/img/episodes/1/2.png' where id= 2;
update episode Set img = 'ressources/img/episodes/1/3.png' where id= 3;
update episode Set img = 'ressources/img/episodes/1/4.png' where id= 4;
update episode Set img = 'ressources/img/episodes/1/5.png' where id= 5;
update episode Set img = 'ressources/img/episodes/2/1.png' where id= 6;
update episode Set img = 'ressources/img/episodes/2/2.png' where id= 7;
update episode Set img = 'ressources/img/episodes/2/3.png' where id= 8;
update episode Set img = 'ressources/img/episodes/2/4.png' where id= 9;
update episode Set img = 'ressources/img/episodes/2/5.png' where id= 10;
update episode Set img = 'ressources/img/episodes/3/1.png' where id= 11;
update episode Set img = 'ressources/img/episodes/3/2.png' where id= 12;
update episode Set img = 'ressources/img/episodes/3/3.png' where id= 13;
update episode Set img = 'ressources/img/episodes/4/1.png' where id= 14;
update episode Set img = 'ressources/img/episodes/4/2.png' where id= 15;
update episode Set img = 'ressources/img/episodes/4/3.png' where id= 16;
update episode Set img = 'ressources/img/episodes/5/1.png' where id= 17;
update episode Set img = 'ressources/img/episodes/5/2.png' where id= 18;
update episode Set img = 'ressources/img/episodes/5/3.png' where id= 19;
update episode Set img = 'ressources/img/episodes/6/1.png' where id= 20;
update episode Set img = 'ressources/img/episodes/6/2.png' where id= 21;

insert into genre values (1, 'Action');
insert into genre values (2, 'Aventure');
insert into genre values (3, 'Comédie');
insert into genre values (4, 'Drame');
insert into genre values (5, 'Fantastique');
insert into genre values (6, 'Horreur');
insert into genre values (7, 'Policier');
insert into genre values (8, 'Romance');
insert into genre values (9, 'Science-Fiction');
insert into genre values (10, 'Thriller');
insert into genre values (11,'Enfant');

ALTER TABLE `current2user` ADD `currentEpisode` INT(11) NULL AFTER `currentEpisode`;