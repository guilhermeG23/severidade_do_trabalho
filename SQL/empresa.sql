use Empresa;
create table if not exists Empresa (
    ID_CPNJ int(14) not null primary key,
    Empresa_Desc varchar(128) not null,
    Licencas int(3) not null,
    PK_User varchar(35) not null
);

insert into Empresa values ('1111100011', 'Admin Teste', 10, '1647787366');
