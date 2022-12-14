CREATE TABLE IF NOT EXISTS bo_office
(
    ID      int auto_increment  primary key,
    NAME    varchar(255)        not null,
    ACTIVE  char(1)             not null,
    CODE    varchar(255)        not null,
    FLOOR   varchar(255)        not null,
    FILE    int                 null,
    ADDRESS text                null,
    SORT    int default 500     null
);
