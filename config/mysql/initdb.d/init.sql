DROP TABLE IF EXISTS message;

create table message(
    id int not null auto_increment primary key,
    view_name varchar(100) not null,
    message text not null,
    post_date datetime not null
    )charset = utf8;

insert into message (view_name,message,post_date) values ("まさお","こんにちは",now());
