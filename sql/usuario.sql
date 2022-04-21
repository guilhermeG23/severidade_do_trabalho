use formularios;
create table if not exists User (
    User_ID varchar(35) not null primary key,
    User_Email varchar(128) not null,
    User_Name varchar(64) not null,
    User_Password varchar(255) not null,
    User_Chave varchar(255) not null
);

/*User: admin e Pass: admin*/
insert into User values ('1647787366', 'admin@teste.com', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'a1da3b5da321f735cbc7fe9522f9e9bd');