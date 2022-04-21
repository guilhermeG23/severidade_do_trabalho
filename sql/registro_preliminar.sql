create database if not exists formularios;

use formularios;

create table if not exists registro_preliminar(
    id_formulario_preliminar int(20) not null auto_increment primary key,
    id_usuario int(32) not null,
    cnpj_empresa varchar(32) not null,
    razao_empresa varchar(120) not null,
    data_registro date not null,
    unidade_planta_empresa varchar(120) not null,
    empresa_setor varchar(120) not null,
    empresa_cargo varchar(120) not null,
    empresa_funcao varchar(120) not null,
    empresa_numero_total int(7) not null,
    select_document varchar(120) not null,
    empresa_comprovante_documento varchar(120) not null,
    empresa_posto_trabalho varchar(120) not null,
    empresa_posto_trabalho_desc varchar(120) not null,
    empresa_posto_trabalho_ativ_desc_1 varchar(120) not null, 
    empresa_posto_trabalho_ativ_desc_2 varchar(120),
    empresa_posto_trabalho_ativ_desc_3 varchar(120),
    empresa_posto_trabalho_ativ_desc_4 varchar(120),
    empresa_posto_trabalho_ativ_desc_5 varchar(120),
    empresa_posto_trabalho_ativ_desc_6 varchar(120),
    empresa_posto_trabalho_ativ_desc_7 varchar(120),
    empresa_posto_trabalho_ativ_desc_8 varchar(120),
    empresa_posto_trabalho_ativ_desc_9 varchar(120),
    empresa_posto_trabalho_ativ_desc_10 varchar(120)
);