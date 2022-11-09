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
