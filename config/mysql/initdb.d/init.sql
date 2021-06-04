DROP TABLE IF EXISTS message;

create table board(
    id int not null auto_increment primary key,
    board_name varchar(100) not null,
    create_date datetime not null
    )charset = utf8;

create table message(
    id int not null auto_increment primary key,
    view_name varchar(100) not null,
    message text not null,
    post_date datetime not null,
    board_id int not null,
    foreign key fk_board_id(board_id) references board(id) on delete cascade on update cascade
    )charset = utf8;

insert into board (board_name,create_date) values ("スレッド1",now()),("スレッド2",now()),("スレッド3",now());

insert into message
(view_name,message,post_date,board_id)
values
("まさお","こんにちは",now(),1),
("太郎","こんばんわ",now(),1),
("jom","うえーーい",now(),2),
("kid","hello",now(),2),
("一花","やあ",now(),3),
("二乃","てすと",now(),3),
("三玖","こんにちは",now(),3),
("四葉","ヤッホー",now(),3),
("五月","こんばんは",now(),3),
("太郎","ああああああ",now(),3);
