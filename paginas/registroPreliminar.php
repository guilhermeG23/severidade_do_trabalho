<?php
header("Cache-Control: no-cache, must-revalidate");
session_start();
if (empty($_SESSION['id_user']) || empty($_SESSION['usuario_banco']) || empty($_SESSION['senha_banco']) || empty($_SESSION['limite'])) {
    header("Location: ./logout.php");
}

if (date_timestamp_get(date_create()) >= $_SESSION['limite']) {
    header("Location: ./logout.php");
}

require_once("../banco/banco.php");

if (!empty($_SESSION['id_user']) && !empty($_SESSION['usuario_banco']) && !empty($_SESSION['senha_banco'])) {
    $usuario_qtd = mysqli_num_rows(mysqli_query($conexao_banco, "select User_ID, User_Name, User_Password from User where User_ID = '{$_SESSION['id_user']}' and User_Name = '{$_SESSION['usuario_banco']}' and User_Password = '{$_SESSION['senha_banco']}' limit 1;"));
    if ($usuario_qtd == 0) {
        header("Location: ./logout.php");
        die();
    }
} else {
    header("Location: ./logout.php");
    die();
}
/*
Note: Falta fazer
* Colocar verficador de campos -> CNPJ, RG, CPF e CREA
*/
?>


<html lang="pt-br">
<head>
	<title>Registros</title>
	<meta charset="utf-8">
	<meta http-equiv="Expires" content="-1">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html;charset=iso-8859-1" />
	<meta Http-Equiv="Cache-Control" Content="no-cache">  
	<meta Http-Equiv="Pragma" Content="no-cache">  
	<meta Http-Equiv="Expires" Content="0">
	<link rel="shortcut icon" href="" type="image/x-icon">
	<link rel="stylesheet" href="../css/reset.css">
	<link rel="stylesheet" href="../css/bootstrap.min.css">
	<link rel="stylesheet" href="../css/cssPessoal.css">
    <style>
        p {
            margin-bottom: 0rem !important;
        }
        img {
            width: 32px;
            height: 32px;
        }
        .dropdown-item.active, .dropdown-item:active {
            background-color: transparent !important;
        }
        button.dropdown-item:hover {
            background-color: transparent !important;    
        }
        a.dropdown-item:hover {
            background-color: transparent !important;
        }
        .menu-ajuste-nav {
            border-bottom: 1px solid black;
        }
        .footer-nav-formulario {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            border-top: 1px solid black;
        }
        .footer-nav {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            border-top: 1px solid black;
        }
        .caixa-login {
            border: 1px solid gray;
            border-radius: 10px;
            padding: 20px;
        }
        .ajuste-input-login {
            margin: auto;
            margin-bottom: 10px;
        }
        .ajuste-center-nav {
            margin: auto;
        }
        .ajuste-left-nav {
            margin: auto;
            margin-left: 0px;
        }
        .ajuste-link-login {
            width: 100%;
            margin-top: 10px;
            display: inline-flex;
        }
        .ajuste-margin-login-link {
            margin: auto;
            text-align: center;
        }
        .container {
            margin-bottom: 10% !important;
        }
        .class-perguntas {
            display: flex;
        }
        .pergunta {
            margin-right: auto;
            margin-bottom: 10px !important;
        }
        .icones-pergunta {
            margin-left: auto;
            padding: 4px;
        }
        .caixa-titulo {
            margin-top: 10px;
            /*margin-bottom: 40px;*/
        }
        .espacamento-img-table {
            margin-right: 10px;
        }
        .div-table-buttons {
            display: inline-flex;
        }
        .btn-margin-bk {
            background: transparent !important;
            border: 0px solid transparent;
        }
        .btn-enable-disable-field {
            margin-left: 10px;
            width: 36px;
        }
        .d-flex {
            margin-left: auto;
        }
        @media only screen and (max-width: 991px) {
            .dropdown-item {
                text-align: center;
            }
        }
        @media only screen and (max-width: 600px) {
            .container {
                margin-bottom: 120px !important;
            }
        }

    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light menu-ajuste-nav">
    <div class="container-fluid">
        <a class="navbar-brand" href='./perfil.php'>Nome do software</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <form class="d-flex">
                <a class='dropdown-item' href='./perfil.php'><img src='../imagens/user.png' class='img'/></a>
                <a class='dropdown-item' data-bs-toggle="modal" data-bs-target="#menuModalHelp"><img src='../imagens/help.png' class='img'/></a>
                <a class='dropdown-item' href='./logout.php'><img src='../imagens/logout.png' class='img'/></a>
            </form>
        </div>
    </div>
</nav>

<div class="modal fade" id="menuModalHelp" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ajuda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            </div>
        </div>
    </div>
</div>

<div class='container box'>

    <form action='realizarRegistroPreliminar.php' method='post' enctype='multipart/form-data'>

        <div id='analise_ergonomica' class='container_now container'><!--style='display: none;'>-->
                        
            <?php if (!empty($_SESSION['erro_cadastro'])) { ?>
                <div class="alert alert-danger" role="alert" id="mensagem_erro_cadastro">
                    <p><?=$_SESSION['erro_cadastro']?>
                </div>
            <?php
            }
            unset($_SESSION['erro_cadastro']);
            //echo var_dump($_SESSION) . "<br>";
            //echo var_dump($_POST) . "<br>";
            ?>

            <div class='caixa-titulo'>
                <h4>Análise Ergonômica Preliminar</h4>
            </div>
            <div class="alert alert-primary" role="alert">
                <p>Preencha todos os campos obrigatórios para poder realizar todo o registro ergonomico, o registro preliminar fica disposto no menu de perfil de usuário, descrito pelo CPNJ e razão social da empresa a ser trabalha</p>
            </div>

            <hr>

            <!--Campo para edicao de preformularios-->
            <input type='hidden' name='id_formulario_preliminar' id='id_formulario_preliminar' readonly></input>


            <div class='caixa-titulo'>

                <div class='form-control'>
                    <div class='class-perguntas'>
                        <div class='pergunta'>
                            <label>Empresa - CNPJ</label>
                        </div>
                        <div class='icones-pergunta'>
                            <img src='../imagens/exclamacao.png' onclick='mostrarCampo("cnpj_empresa_obrigatorio")'/>
                            <img src='../imagens/help.png' onclick='mostrarCampo("cnpj_empresa_ajuda")'/>
                        </div>
                    </div>
                    <div style='display: flex; margin-top: 10px; margin-bottom: 10px;'>
                        <input type='text' class='form-control' style='width: 100%;' name='cnpj_empresa' id='cnpj_empresa' required></input>
                    </div> 
                    <div id='cnpj_empresa_obrigatorio' style='display: none;'>
                        <div class='alert alert-warning' role='alert'>
                            <p>* Campo Obrigatório</p>
                        </div>
                    </div>
                    <div id='cnpj_empresa_ajuda' style='display: none;'>
                        <div class='alert alert-info' role='alert'>
                            <p>* Campo Ajuda</p>
                        </div>
                    </div>
                </div>
                <br>

                <div class='form-control'>
                    <div class='class-perguntas'>
                        <div class='pergunta'>
                            <label>Empresa - Razão social</label>
                        </div>
                        <div class='icones-pergunta'>
                            <img src='../imagens/exclamacao.png' onclick='mostrarCampo("razao_empresa_obrigatorio")'/>
                            <img src='../imagens/help.png' onclick='mostrarCampo("razao_empresa_ajuda")'/>
                        </div>
                    </div>
                    <div style='display: flex; margin-top: 10px; margin-bottom: 10px;'>
                        <input type='text' class='form-control' style='width: 100%;' name='razao_empresa' id='razao_empresa' required></input>
                    </div> 
                    <div id='razao_empresa_obrigatorio' style='display: none;'>
                        <div class='alert alert-warning' role='alert'>
                            <p>* Campo Obrigatório</p>
                        </div>
                    </div>
                    <div id='razao_empresa_ajuda' style='display: none;'>
                        <div class='alert alert-info' role='alert'>
                            <p>* Campo Ajuda</p>
                        </div>
                    </div>
                </div>
                <br>

                <div class='form-control'>
                    <div class='class-perguntas'>
                        <div class='pergunta'>
                            <label>Data do registro</label>
                        </div>
                        <div class='icones-pergunta'>
                            <img src='../imagens/exclamacao.png' onclick='mostrarCampo("data_registro_obrigatorio")'/>
                            <img src='../imagens/help.png' onclick='mostrarCampo("data_registro_ajuda")'/>
                        </div>
                    </div>
                    <div style='display: flex; margin-top: 10px; margin-bottom: 10px;'>
                        <input type='date' class='form-control' style='width: 100%;' name='data_registro' id='data_registro' required></input>
                    </div> 
                    <div id='data_registro_obrigatorio' style='display: none;'>
                        <div class='alert alert-warning' role='alert'>
                            <p>* Campo Obrigatório</p>
                        </div>
                    </div>
                    <div id='data_registro_ajuda' style='display: none;'>
                        <div class='alert alert-info' role='alert'>
                            <p>* Campo Ajuda</p>
                        </div>
                    </div>
                </div>
                <br>

                <div class='form-control'>
                    <div class='class-perguntas'>
                        <div class='pergunta'>
                            <label>Empresa - UNIDADE/PLANTA</label>
                        </div>
                        <div class='icones-pergunta'>
                            <img src='../imagens/exclamacao.png' onclick='mostrarCampo("unidade_planta_empresa_obrigatorio")'/>
                            <img src='../imagens/help.png' onclick='mostrarCampo("unidade_planta_empresa_ajuda")'/>
                        </div>
                    </div>
                    <div style='display: flex; margin-top: 10px; margin-bottom: 10px;'>
                        <input type='text' class='form-control' style='width: 100%;' name='unidade_planta_empresa' id='unidade_planta_empresa' required></input>
                    </div> 
                    <div id='unidade_planta_empresa_obrigatorio' style='display: none;'>
                        <div class='alert alert-warning' role='alert'>
                            <p>* Campo Obrigatório</p>
                        </div>
                    </div>
                    <div id='unidade_planta_empresa_ajuda' style='display: none;'>
                        <div class='alert alert-info' role='alert'>
                            <p>* Campo Ajuda</p>
                        </div>
                    </div>
                </div>
                <br>

                <div class='form-control'>
                    <div class='class-perguntas'>
                        <div class='pergunta'>
                            <label>Empresa - Setor</label>
                        </div>
                        <div class='icones-pergunta'>
                            <img src='../imagens/exclamacao.png' onclick='mostrarCampo("empresa_setor_obrigatorio")'/>
                            <img src='../imagens/help.png' onclick='mostrarCampo("empresa_setor_ajuda")'/>
                        </div>
                    </div>
                    <div style='display: flex; margin-top: 10px; margin-bottom: 10px;'>
                        <input type='text' class='form-control' style='width: 100%;' name='empresa_setor' id='empresa_setor' required></input>
                    </div> 
                    <div id='empresa_setor_obrigatorio' style='display: none;'>
                        <div class='alert alert-warning' role='alert'>
                            <p>* Campo Obrigatório</p>
                        </div>
                    </div>
                    <div id='empresa_setor_ajuda' style='display: none;'>
                        <div class='alert alert-info' role='alert'>
                            <p>* Campo Ajuda</p>
                        </div>
                    </div>
                </div>
                <br>

                <div class='form-control'>
                    <div class='class-perguntas'>
                        <div class='pergunta'>
                            <label>Empresa - Cargo</label>
                        </div>
                        <div class='icones-pergunta'>
                            <img src='../imagens/exclamacao.png' onclick='mostrarCampo("empresa_cargo_obrigatorio")'/>
                            <img src='../imagens/help.png' onclick='mostrarCampo("empresa_cargo_ajuda")'/>
                        </div>
                    </div>
                    <div style='display: flex; margin-top: 10px; margin-bottom: 10px;'>
                        <input type='text' class='form-control' style='width: 100%;' name='empresa_cargo' id='empresa_cargo' required></input>
                    </div> 
                    <div id='empresa_cargo_obrigatorio' style='display: none;'>
                        <div class='alert alert-warning' role='alert'>
                            <p>* Campo Obrigatório</p>
                        </div>
                    </div>
                    <div id='empresa_cargo_ajuda' style='display: none;'>
                        <div class='alert alert-info' role='alert'>
                            <p>* Campo Ajuda</p>
                        </div>
                    </div>
                </div>
                <br>

                <div class='form-control'>
                    <div class='class-perguntas'>
                        <div class='pergunta'>
                            <label>Empresa - Função</label>
                        </div>
                        <div class='icones-pergunta'>
                            <img src='../imagens/exclamacao.png' onclick='mostrarCampo("empresa_funcao_obrigatorio")'/>
                            <img src='../imagens/help.png' onclick='mostrarCampo("empresa_funcao_ajuda")'/>
                        </div>
                    </div>
                    <div style='display: flex; margin-top: 10px; margin-bottom: 10px;'>
                        <input type='text' class='form-control' style='width: 100%;' name='empresa_funcao' id='empresa_funcao' required></input>
                    </div> 
                    <div id='empresa_funcao_obrigatorio' style='display: none;'>
                        <div class='alert alert-warning' role='alert'>
                            <p>* Campo Obrigatório</p>
                        </div>
                    </div>
                    <div id='empresa_funcao_ajuda' style='display: none;'>
                        <div class='alert alert-info' role='alert'>
                            <p>* Campo Ajuda</p>
                        </div>
                    </div>
                </div>
                <br>

                <div class='form-control'>
                    <div class='class-perguntas'>
                        <div class='pergunta'>
                            <label>Empresa - Total de funcionários no posto de trabalho</label>
                        </div>
                        <div class='icones-pergunta'>
                            <img src='../imagens/exclamacao.png' onclick='mostrarCampo("empresa_numero_total_obrigatorio")'/>
                            <img src='../imagens/help.png' onclick='mostrarCampo("empresa_numero_total_ajuda")'/>
                        </div>
                    </div>
                    <div style='display: flex; margin-top: 10px; margin-bottom: 10px;'>
                        <input type='text' class='form-control' style='width: 100%;' name='empresa_numero_total' id='empresa_numero_total' required></input>
                    </div> 
                    <div id='empresa_numero_total_obrigatorio' style='display: none;'>
                        <div class='alert alert-warning' role='alert'>
                            <p>* Campo Obrigatório</p>
                        </div>
                    </div>
                    <div id='empresa_numero_total_ajuda' style='display: none;'>
                        <div class='alert alert-info' role='alert'>
                            <p>* Este campo está preparado para aceitar até 99999 funcionários, dado que a váriação de funcionários por planta pode ocorrer ou mesmo em diferentes empresas, caso sua empresa não seja atentida por está quantidade, entre em contato com o suporte técnico para uma solução ao sua necessidade</p>
                        </div>
                    </div>
                </div>
                <br>

                <div class='form-control'>
                    <div class='class-perguntas'>
                        <div class='pergunta'>
                            <label>Empresa - Documento comprovante do analista responsável (RG, CPF e CREA)</label>
                        </div>
                        <div class='icones-pergunta'>
                            <img src='../imagens/exclamacao.png' onclick='mostrarCampo("empresa_comprovante_documento_obrigatorio")'/>
                            <img src='../imagens/help.png' onclick='mostrarCampo("empresa_comprovante_documento_ajuda")'/>
                        </div>
                    </div>
                    <div style='display: flex; margin-top: 10px; margin-bottom: 10px;'>
                        <select class='form-control' id='select_document' name='select_document' required>
                            <option value='none' default>---</option>
                            <option value='cpf'>CPF</option>
                            <option value='rg'>RG</option>
                            <option value='crea'>CREA</option>
                        </select>
                    </div>                     
                    <div style='display: none; margin-top: 10px; margin-bottom: 10px;' id='campo_select_document' name='campo_select_document'>
                        <input type='text' class='form-control' style='width: 100%;' name='empresa_comprovante_documento' id='empresa_comprovante_documento' required></input>
                    </div> 
                    <div id='empresa_comprovante_documento_obrigatorio' style='display: none;'>
                        <div class='alert alert-warning' role='alert'>
                            <p>* Campo Obrigatório</p>
                        </div>
                    </div>
                    <div id='empresa_comprovante_documento_ajuda' style='display: none;'>
                        <div class='alert alert-info' role='alert'>
                            <p>* Campo preparado para o recebimento de documentos comprovantes de identidade, favor setar o tipo de documento corretamente para o mesmo poder ser preenchido de forma já estruturada</p>
                        </div>
                    </div>
                </div>
                <br>

                <div class='form-control'>
                    <div class='class-perguntas'>
                        <div class='pergunta'>
                            <label>Empresa - Posto de trabalho</label>
                        </div>
                        <div class='icones-pergunta'>
                            <img src='../imagens/exclamacao.png' onclick='mostrarCampo("empresa_posto_trabalho_obrigatorio")'/>
                            <img src='../imagens/help.png' onclick='mostrarCampo("empresa_posto_trabalho_ajuda")'/>
                        </div>
                    </div>
                    <div style='display: flex; margin-top: 10px; margin-bottom: 10px;'>
                        <input type='text' class='form-control' style='width: 100%;' name='empresa_posto_trabalho' id='empresa_posto_trabalho' required></input>
                    </div> 
                    <div id='empresa_posto_trabalho_obrigatorio' style='display: none;'>
                        <div class='alert alert-warning' role='alert'>
                            <p>* Campo Obrigatório</p>
                        </div>
                    </div>
                    <div id='empresa_posto_trabalho_ajuda' style='display: none;'>
                        <div class='alert alert-info' role='alert'>
                            <p>* Campo Ajuda</p>
                        </div>
                    </div>
                </div>
                <br>

                <div class='form-control'>
                    <div class='class-perguntas'>
                        <div class='pergunta'>
                            <label>Empresa - Descrição do posto de trabalho (Breve descrição do ambiente)</label>
                        </div>
                        <div class='icones-pergunta'>
                            <img src='../imagens/exclamacao.png' onclick='mostrarCampo("empresa_posto_trabalho_ativ_desc_obrigatorio")'/>
                            <img src='../imagens/help.png' onclick='mostrarCampo("empresa_posto_trabalho_ativ_desc_ajuda")'/>
                        </div>
                    </div>
                    <div style='display: flex; margin-top: 10px; margin-bottom: 10px;'>
                        <input type='text' class='form-control' style='width: 100%;' name='empresa_posto_trabalho_desc' id='empresa_posto_trabalho_desc' required></input>
                    </div> 
                    <div id='empresa_posto_trabalho_ativ_desc_obrigatorio' style='display: none;'>
                        <div class='alert alert-warning' role='alert'>
                            <p>* Campo Obrigatório</p>
                        </div>
                    </div>
                    <div id='empresa_posto_trabalho_ativ_desc_ajuda' style='display: none;'>
                        <div class='alert alert-info' role='alert'>
                            <p>* Campo Ajuda</p>
                        </div>
                    </div>
                </div>
                <br>

                <div class='form-control'>
                    <div class='class-perguntas'>
                        <div class='pergunta'>
                            <label>Empresa - Posto de trabalho - Atividades desenvolvidas</label>
                        </div>
                        <div class='icones-pergunta'>
                            <img src='../imagens/exclamacao.png' onclick='mostrarCampo("empresa_posto_trabalho_atividades_obrigatorio")'/>
                            <img src='../imagens/help.png' onclick='mostrarCampo("empresa_posto_trabalho_atividades_ajuda")'/>
                        </div>
                    </div>
                    <div style='display: flex; margin-top: 10px; margin-bottom: 10px;'>
                        <input type='text' class='form-control' style='width: 100%;' name='empresa_posto_trabalho_ativ_desc_1' id='empresa_posto_trabalho_ativ_desc_1' required></input>
                        <input class='btn btn-primary btn-enable-disable-field' type='button' onclick='mostrarMaisCamposMais("campoDesc2")' value='+'/>
                    </div>
                    <div style='display: none; margin-top: 10px; margin-bottom: 10px;' id='campoDesc2'>
                        <input type='text' class='form-control' style='width: 100%;' name='empresa_posto_trabalho_ativ_desc_2' id='empresa_posto_trabalho_ativ_desc_2'></input>
                        <input class='btn btn-primary btn-enable-disable-field' type='button' onclick='mostrarMaisCamposMais("campoDesc3")' value='+'/>
                        <input class='btn btn-primary btn-enable-disable-field' type='button' onclick='mostrarMaisCamposMenos("campoDesc2", "empresa_posto_trabalho_ativ_desc_2")' value='-'/>
                    </div> 
                    <div style='display: none; margin-top: 10px; margin-bottom: 10px;' id='campoDesc3'>
                        <input type='text' class='form-control' style='width: 100%;' name='empresa_posto_trabalho_ativ_desc_3' id='empresa_posto_trabalho_ativ_desc_3'></input>
                        <input class='btn btn-primary btn-enable-disable-field' type='button' onclick='mostrarMaisCamposMais("campoDesc4")' value='+'/>
                        <input class='btn btn-primary btn-enable-disable-field' type='button' onclick='mostrarMaisCamposMenos("campoDesc3", "empresa_posto_trabalho_ativ_desc_3")' value='-'/>
                    </div> 
                    <div style='display: none; margin-top: 10px; margin-bottom: 10px;' id='campoDesc4'>
                        <input type='text' class='form-control' style='width: 100%;' name='empresa_posto_trabalho_ativ_desc_4' id='empresa_posto_trabalho_ativ_desc_4'></input>
                        <input class='btn btn-primary btn-enable-disable-field' type='button' onclick='mostrarMaisCamposMais("campoDesc5")' value='+'/>
                        <input class='btn btn-primary btn-enable-disable-field' type='button' onclick='mostrarMaisCamposMenos("campoDesc4", "empresa_posto_trabalho_ativ_desc_4")' value='-'/>
                    </div> 
                    <div style='display: none; margin-top: 10px; margin-bottom: 10px;' id='campoDesc5'>
                        <input type='text' class='form-control' style='width: 100%;' name='empresa_posto_trabalho_ativ_desc_5' id='empresa_posto_trabalho_ativ_desc_5'></input>
                        <input class='btn btn-primary btn-enable-disable-field' type='button' onclick='mostrarMaisCamposMais("campoDesc6")' value='+'/>
                        <input class='btn btn-primary btn-enable-disable-field' type='button' onclick='mostrarMaisCamposMenos("campoDesc5", "empresa_posto_trabalho_ativ_desc_5")' value='-'/>
                    </div> 
                    <div style='display: none; margin-top: 10px; margin-bottom: 10px;' id='campoDesc6'>
                        <input type='text' class='form-control' style='width: 100%;' name='empresa_posto_trabalho_ativ_desc_6' id='empresa_posto_trabalho_ativ_desc_6'></input>
                        <input class='btn btn-primary btn-enable-disable-field' type='button' onclick='mostrarMaisCamposMais("campoDesc7")' value='+'/>
                        <input class='btn btn-primary btn-enable-disable-field' type='button' onclick='mostrarMaisCamposMenos("campoDesc6", "empresa_posto_trabalho_ativ_desc_6")' value='-'/>
                    </div> 
                    <div style='display: none; margin-top: 10px; margin-bottom: 10px;' id='campoDesc7'>
                        <input type='text' class='form-control' style='width: 100%;' name='empresa_posto_trabalho_ativ_desc_7' id='empresa_posto_trabalho_ativ_desc_7'></input>
                        <input class='btn btn-primary btn-enable-disable-field' type='button' onclick='mostrarMaisCamposMais("campoDesc8")' value='+'/>
                        <input class='btn btn-primary btn-enable-disable-field' type='button' onclick='mostrarMaisCamposMenos("campoDesc7", "empresa_posto_trabalho_ativ_desc_7")' value='-'/>
                    </div> 
                    <div style='display: none; margin-top: 10px; margin-bottom: 10px;' id='campoDesc8'>
                        <input type='text' class='form-control' style='width: 100%;' name='empresa_posto_trabalho_ativ_desc_8' id='empresa_posto_trabalho_ativ_desc_8'></input>
                        <input class='btn btn-primary btn-enable-disable-field' type='button' onclick='mostrarMaisCamposMais("campoDesc9")' value='+'/>
                        <input class='btn btn-primary btn-enable-disable-field' type='button' onclick='mostrarMaisCamposMenos("campoDesc8", "empresa_posto_trabalho_ativ_desc_8")' value='-'/>
                    </div> 
                    <div style='display: none; margin-top: 10px; margin-bottom: 10px;' id='campoDesc9'>
                        <input type='text' class='form-control' style='width: 100%;' name='empresa_posto_trabalho_ativ_desc_9' id='empresa_posto_trabalho_ativ_desc_9'></input>
                        <input class='btn btn-primary btn-enable-disable-field' type='button' onclick='mostrarMaisCamposMais("campoDesc10")' value='+'/>
                        <input class='btn btn-primary btn-enable-disable-field' type='button' onclick='mostrarMaisCamposMenos("campoDesc9", "empresa_posto_trabalho_ativ_desc_9")' value='-'/>
                    </div> 
                    <div style='display: none; margin-top: 10px; margin-bottom: 10px;' id='campoDesc10'>
                        <input type='text' class='form-control' style='width: 100%;' name='empresa_posto_trabalho_ativ_desc_10' id='empresa_posto_trabalho_ativ_desc_10'></input>
                        <input class='btn btn-primary btn-enable-disable-field' type='button' onclick='mostrarMaisCamposMenos("campoDesc10", "empresa_posto_trabalho_ativ_desc_10")' value='-'/>
                    </div> 
                    <div id='empresa_posto_trabalho_atividades_obrigatorio' style='display: none;'>
                        <div class='alert alert-warning' role='alert'>
                            <p>* Campo Obrigatório</p>
                        </div>
                    </div>
                    <div id='empresa_posto_trabalho_atividades_ajuda' style='display: none;'>
                        <div class='alert alert-info' role='alert'>
                            <p>* Os campos listados são referentes aos futuros formulários a serem preenchidos, dessa forma preencha em máximos 10 campos todas as funções a serem realizadas pelos funcionários</p>
                            <br>
                            <div style='color: red;'>
                                <p>Aviso:</p>
                                <br>
                                <ul style='list-style: unset;'>
                                    <li>Somente o primeiro campo é obrigatório;</li>
                                    <li>Caso o campo for preenchido e foi feito um registro, o mesmo será mostrado na tela de registro sobre este formulário;</li>
                                    <li>Caso queira deletar um determinado registro, é necessário entrar no perfil -> Selecionar o formulário determinado -> Deletar o registro preliminar;</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <br>

            </div>

        </div>

        <div id='container-msg' class='container_now container' style='display: none;'>
            <h4>Selecione um formulário para continuar a operação</h4>
        </div>

        <nav class="navbar navbar-expand-lg navbar-light bg-light footer-nav-formulario">
            <div class="container-fluid">
                <p class="navbar-brand ajuste-left-nav">Clique aqui para enviar:</p>
                <form class="d-flex">
                    <button type='submit' class='btn btn-primary'>Registro Preliminar</button>
                </form>
            </div>
        </nav>

    </form>

</div>


<script src="../js/popper.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/jquery-latest.min.js"></script>
<script src="../js/jquery.mask.min.js"></script>
<script>


//Add data now for register
const dataNow = new Date().toString();
const dayNow = new Date(dataNow);
var monthNow = new Date(dataNow);
const yearNow = new Date(dataNow);
if ((monthNow.getMonth()+1).toString().length == 1) {
    monthNow = "0" + (monthNow.getMonth()+1);
} else {
    monthNow = (monthNow.getMonth()+1);
}
document.getElementById('data_registro').value = yearNow.getFullYear() + "-" + monthNow + "-" + dayNow.getDate();


//Ajustar campo de registro legal do analista
$('#select_document').on('change', function() {
    if (this.value == "none") {
        document.getElementById('campo_select_document').style.display='none';
    } else if (this.value == "cpf") {
        document.getElementById('campo_select_document').style.display='flex';
        $(document).ready(function(){
            $('#empresa_comprovante_documento').mask('000.000.000-00', {reverse: true});
        });
    } else if (this.value == "rg") {
        document.getElementById('campo_select_document').style.display='flex';
        $(document).ready(function(){
            $('#empresa_comprovante_documento').mask('00.000.000-00', {reverse: true});
        });
    } else if (this.value == "crea") {
        document.getElementById('campo_select_document').style.display='flex';
        $(document).ready(function(){
            $('#empresa_comprovante_documento').mask('000000000-0', {reverse: true});
        });
    } else {
        document.getElementById('campo_select_document').style.display='none';
    }
});



//Mask all need fields
$(document).ready(function(){
    $('#cnpj_empresa').mask('00.000.000/0000-00', {reverse: true});
    $('#data_registro').mask('0000-00-00', {reverse: true});
});

document.getElementsByClassName("box")[0].style.height="auto";
document.getElementsByClassName('container_now')[0].style.display='block';

function trocarDisplay(display_container) {
    todos = document.getElementsByClassName('container_now');
    if (document.getElementById(display_container).style.display == 'block') {
        for(i=0;i<=todos.length-1;i++) {
            todos[i].style.display='none';
        }
        todos[todos.length-1].style.display='block';
        document.getElementsByClassName("box")[0].style.height="90%";
    } else {
        for(i=0;i<=todos.length-1;i++) {
            todos[i].style.display='none';
        }
        document.getElementById(display_container).style.display='block';
        document.getElementsByClassName("box")[0].style.height="auto";
    }
}

function mostrarCampo(entrada) {
    alvo = document.getElementById(entrada);
    if (alvo.style.display == 'block') {
        alvo.style.display='none';
    } else {
        alvo.style.display='block';
    }
}

if (!!document.getElementById("mensagem_erro_cadastro")) {
    setTimeout(() => {
        document.getElementById("mensagem_erro_cadastro").style.display="none";
    }, 5000);
}

function mostrarMaisCamposMais(entrada) {
    document.getElementById(entrada).style.display='flex';
}
function mostrarMaisCamposMenos(campo, valor) {
    document.getElementById(campo).style.display='none';
}

if (!!document.getElementById("registro-realizado")) {
    setTimeout(() => {
        document.getElementById("registro-realizado").style.display="none";
    }, 5000);
}


</script>

<?php 
//Se for para editar, ele carrega pelas chaves
if (isset($_POST) && isset($_SESSION['id_user']) && isset($_POST['id_formulario_preliminar']) && isset($_POST['cnpj_empresa']) && !empty($_SESSION['id_user']) && !empty($_POST['id_formulario_preliminar']) && !empty($_POST['cnpj_empresa'])) {
    $valores_ja_registrados = mysqli_fetch_array(mysqli_query($conexao_banco, "select * from registro_preliminar where id_usuario = '{$_SESSION['id_user']}' and id_formulario_preliminar = '{$_POST['id_formulario_preliminar']}' and cnpj_empresa = '{$_POST['cnpj_empresa']}' limit 1;"));
    $campos_nao_obrigatorios = ["empresa_posto_trabalho_ativ_desc_2","empresa_posto_trabalho_ativ_desc_3","empresa_posto_trabalho_ativ_desc_4","empresa_posto_trabalho_ativ_desc_5","empresa_posto_trabalho_ativ_desc_6","empresa_posto_trabalho_ativ_desc_7","empresa_posto_trabalho_ativ_desc_8","empresa_posto_trabalho_ativ_desc_9","empresa_posto_trabalho_ativ_desc_10"];
    foreach ($valores_ja_registrados as $chave => $valor) {



        if (!is_numeric($chave)) {
            if (in_array($chave, $campos_nao_obrigatorios)) {
                if (!empty($valor)) {
                    $chaveCampo = explode("_", $chave);
                    $chaveCampo = $chaveCampo[count($chaveCampo)-1]; //Ultimo elemento
                    ?>
                    <script>
                        document.getElementById('campoDesc<?=$chaveCampo?>').style.display='flex'
                    </script>
                    <?php
                }
            }
            ?>
            <script>
            if (!!document.getElementById("<?=$chave?>")) {
                document.getElementById("<?=$chave?>").value="<?=$valor?>";
                if ("<?=$chave?>" == "select_document") {
                    if ("<?=$valor?>" == "cpf") {
                        document.getElementById('campo_select_document').style.display='flex';
                        $(document).ready(function(){
                            $('#empresa_comprovante_documento').mask('000.000.000-00', {reverse: true});
                        });
                    } else if ("<?=$valor?>" == "rg") {
                        document.getElementById('campo_select_document').style.display='flex';
                        $(document).ready(function(){
                            $('#empresa_comprovante_documento').mask('00.000.000-00', {reverse: true});
                        });
                    } else if ("<?=$valor?>" == "crea") {
                        document.getElementById('campo_select_document').style.display='flex';
                        $(document).ready(function(){
                            $('#empresa_comprovante_documento').mask('000000000-0', {reverse: true});
                        });
                    }
                }
            }
            </script>
            <?php
        }
    }
} 


?>

</body>