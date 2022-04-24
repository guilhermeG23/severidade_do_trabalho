use formularios;
create table if not exists formularios (
    ID_Formulario int not null auto_increment primary key,
    PK_User varchar(35) not null,
    PK_CNPJ int(14) not null,
    Setor varchar(255) not null,
    Date_Registre datetime not null,
    File_CSV varchar(255) not null,
    File_JSON varchar(255) not null
);