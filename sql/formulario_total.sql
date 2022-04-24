create database if not exists formularios;

use formularios;

create table if not exists registro_total(
    id_formulario_total int(20) not null auto_increment primary key,
    registro_trabalho varchar(255) not null,
    id_formulario_preliminar int(20) not null,
    descricao_atividade varchar(200) not null,
    id_image_preliminar varchar(255) not null,
    gradacao_perguntas_mult_123 int(1) not null,
    gradacao_respostas_mult_1 int(1),
    gradacao_respostas_mult_2 int(1),
    gradacao_respostas_mult_3 int(1),
    gradacao_respostas_mult_4 int(1) not null,
    gradacao_respostas_mult_5 int(1) not null,
    result_gradacao_respostas_mult  int(1) not null
);