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
            display: flex;
        }
        .icones-pergunta img {
            margin: 4px;
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



        @media print {
            .navbar.navbar-expand-lg.navbar-light.bg-light.menu-ajuste-nav {
                display: none;
            }
            .navbar.navbar-expand-lg.navbar-light.bg-light.footer-nav-formulario {
                display: none;
            }
            .esconderNoPrint {
                display: none;    
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
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="<?=$display?>">Formulário</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li style='display: flex;'><button class="dropdown-item" onclick="trocarDisplay('analise_ergonomica')">Análise Ergonômica - Descrição final da atividade</button><img src='../imagens/exclamacao.png' style='margin-left: 10px; margin-right: 10px;'/></li>
                        <li><hr class="dropdown-divider"></li>
                        <li style='display: flex;'><button class="dropdown-item" onclick="trocarDisplay('frequencia_p_cada_perigo')">Frequencia p. cada perigo</button><img src='../imagens/exclamacao.png' style='margin-left: 10px; margin-right: 10px;'/></li>
                        <li><hr class="dropdown-divider"></li>
                        <li style='display: flex;'><button class="dropdown-item" onclick="trocarDisplay('severidade_carga')">Severidade - Carga de trabalho</button></li>
                        <li><hr class="dropdown-divider"></li>
                        <li style='display: flex;'><button class="dropdown-item" onclick="trocarDisplay('severidade_bio')">Severidade-Biomecanica-Postura</button></li>
                        <li><hr class="dropdown-divider"></li>
                        <li style='display: flex;'><button class="dropdown-item" onclick="trocarDisplay('severidade_cognitivo')">Severidade - Cognitivo</button></li>
                        <li><hr class="dropdown-divider"></li>
                        <li style='display: flex;'><button class="dropdown-item" onclick="trocarDisplay('severidade_ambiente')">Severidade - Ambiente</button></li>
                        <li><hr class="dropdown-divider"></li>
                        <li style='display: flex;'><button class="dropdown-item" onclick="trocarDisplay('efeitos_corretivas')">Efeitos-Corretivas</button></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <form class="d-flex">
                <a class='dropdown-item' href='./perfil.php'><img src='../imagens/user.png' class='img'/></a>
                <a class='dropdown-item' href='./postoTrabalho.php'><img src='../imagens/posto-trabalho.png' class='img'/></a>
                <a class='dropdown-item' href='#' onclick="imprimirFormulario()" id="imprimirFormulario" style="display:none;"><img src='../imagens/impressora.png' class='img'/></a>
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

<div class="modal fade" id="menuModalAvisoFaltaCampos" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Atenção</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Ainda há campos a serem preenchidos, atenção nos mesmos que possuem a imagem de uma exclamação, estes campos são obrigatórios o preenchimento</p>
            </div>
        </div>
    </div>
</div>


<?php

if (isset($_POST['registro_trabalho']) && !empty($_POST['registro_trabalho'])) {
    $_SESSION['registro_trabalho'] = $_POST['registro_trabalho'];
}
?>


<?php
//echo var_dump($_SESSION);
//echo "<br>";
//echo var_dump($_POST);
?>

<div class='container box'>

    <?php if (!empty($_SESSION['erro_cadastro'])) { ?>
        <div class="alert alert-danger" role="alert" id="mensagem_erro_cadastro">
            <p><?=$_SESSION['erro_cadastro']?>
        </div>
    <?php
    }
    unset($_SESSION['erro_cadastro']);
    ?>
    <div class="alert alert-secondary" role="alert">
        <h4>Informações adicionais do registro</h4>
        <hr>
        <p>Formulário #<?=$_SESSION['id_formulario_preliminar']?></p>
        <p>Empresa: <?=$_SESSION['razao_empresa']?></p>
        <p>CNPJ: <?=$_SESSION['cnpj_empresa']?></p>
        <p>Registro de trabalho(atividade): <?=$_SESSION['registro_trabalho']?></p>
    </div>


    <form action='realizarRegistroFormularioTotal.php' method='post' enctype='multipart/form-data'>

        <input type='hidden' name='id_formulario_total' id='id_formulario_total' value="" readonly></input>
        <input type='hidden' name='id_image_preliminar' id='id_image_preliminar' value="" readonly></input>
        <input type='hidden' name='id_formulario_preliminar' id='id_formulario_preliminar' value="<?=$_SESSION['id_formulario_preliminar']?>" readonly></input>
        <input type='hidden' name='registro_trabalho' id='registro_trabalho' value="<?=$_SESSION['registro_trabalho']?>" readonly></input>
        

        <div id='analise_ergonomica' class='container_now container'>
                        
            <div class='caixa-titulo'>
                <h4>Análise Ergonômica - Descrição final da atividade</h4>
            </div>

            <hr>

            <!--Campo para edicao de preformularios-->

            <div class='caixa-titulo'>

                <div class='form-control'>
                    <div class='class-perguntas'>
                        <div class='pergunta'>
                            <label>Descrição da atividade</label>
                        </div>
                        <div class='icones-pergunta'>
                            <img src='../imagens/exclamacao.png' onclick='mostrarCampo("descricao_atividade_obrigatorio")'/>
                            <img src='../imagens/help.png' onclick='mostrarCampo("descricao_atividade_ajuda")'/>
                        </div>
                    </div>
                    <div style='display: flex; margin-top: 10px; margin-bottom: 10px;'>
                        <textarea class='form-control' style='width: 100%;' name='descricao_atividade' id='descricao_atividade' required maxlength="200" placeholder="Descrição da atividade..."></textarea>
                    </div>
                    <div style='display: flex; margin-top: 10px; margin-bottom: 10px;'>
                        <p id="qtd_caracteres_textarea" style="margin-left: auto;">0/200</p>
                    </div>
                    <div id='descricao_atividade_obrigatorio' style='display: none;'>
                        <div class='alert alert-warning' role='alert'>
                            <p>* Campo Obrigatório</p>
                        </div>
                    </div>
                    <div id='descricao_atividade_ajuda' style='display: none;'>
                        <div class='alert alert-info' role='alert'>
                            <p>* Campo Limitado a 200 caracteres</p>
                        </div>
                    </div>
                </div>
                <br>

                <div class='form-control esconderNoPrint'>
                    <div class='class-perguntas'>
                        <div class='pergunta'>
                            <label>Foto do local onde a atividade é excercida</label>
                        </div>
                        <div class='icones-pergunta'>
                            <img src='../imagens/exclamacao.png' onclick='mostrarCampo("foto_local_atividade_obrigatorio")'/>
                            <img src='../imagens/help.png' onclick='mostrarCampo("foto_local_atividade_ajuda")'/>
                        </div>
                    </div>
                    <div style='display: flex; margin-top: 10px; margin-bottom: 10px;'>
                        <input type='file' class='form-control' style='width: 100%;' name='foto_local_atividade' id='foto_local_atividade' accept="image/png, image/gif, image/jpeg" required></input>
                    </div> 
                    <div id='foto_local_atividade_obrigatorio' style='display: none;'>
                        <div class='alert alert-warning' role='alert'>
                            <p>* Campo Obrigatório</p>
                        </div>
                    </div>
                    <div id='foto_local_atividade_ajuda' style='display: none;'>
                        <div class='alert alert-info' role='alert'>
                            <p>* É possível somente manter uma imagem por registro, caso você necessite alterar um registro já realizado, a imagem antiga será substituida pela nova imagem</p>
                        </div>
                    </div>
                </div>
                <br>

                <div class='form-control' style="display: none;" id="apresentar_imagem_ja_cadastrada">
                    <div class='class-perguntas'>
                        <div class='pergunta' style="margin:auto;">
                            <h4 style="margin-top: 20px;">Imagem já cadastrada</h4>
                            <hr>
                            <img src="" style="width:auto;height:auto;"id="imagem_ja_cadastrada_valor">
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div id='frequencia_p_cada_perigo' class='container_now container' style="display: none;">
       
                     
            <div class='caixa-titulo'>
                <h4>Gradação da Probabilidade</h4>
            </div>

            <hr>

            <!--Campo para edicao de preformularios-->

            <div class='caixa-titulo'>

                <div class='form-control'>
                    <div class='class-perguntas'>
                        <div class='pergunta'>
                            <label>Selecione a classificação de frequência mais apropriada a da atividade</label>
                        </div>
                        <div class='icones-pergunta'>
                            <img src='../imagens/exclamacao.png' onclick='mostrarCampo("gradacao_perguntas_mult_123_obrigatorio")'/>
                            <img src='../imagens/help.png' onclick='mostrarCampo("gradacao_perguntas_mult_123_ajuda")'/>
                        </div>
                    </div>
                    <div style='display: flex; margin-top: 10px; margin-bottom: 10px;'>
                        <select class='form-control' id='gradacao_perguntas_mult_123' name='gradacao_perguntas_mult_123' required>
                            <option value='' default>---</option>
                            <option value='1'>Chance do perigo ocorrer ou estar presente na execução da atividade</option>
                            <option value='2'>Nível de exposição ao perigo (para riscos ambientais):</option>
                            <option value='3'>Duração diária da atividade</option>
                        </select>
                    </div> 
                    <div style='display: flex; margin-top: 10px; margin-bottom: 10px;'>
                        <select class='form-control' id='gradacao_respostas_mult_1' name='gradacao_respostas_mult_1' style='display: none;' required>
                            <option value='0' default>---</option>
                            <option value='5'>Quase certo</option>
                            <option value='4'>Provável</option>
                            <option value='3'>Improvável</option>
                            <option value='2'>Raro</option>
                            <option value='1'>Quase improvável</option>
                        </select>

                        <select class='form-control' id='gradacao_respostas_mult_2' name='gradacao_respostas_mult_2' style='display: none;'>
                            <option value='0' default>---</option>
                            <option value='1'>Exposição em níveis muito baixos (menor que 10% do LEO)</option>
                            <option value='2'>Exposição em níveis baixos (maior que 10% a 50% do LEO)</option>
                            <option value='3'>Exposição em níveis moderados (maior que  50% a 100% do LEO)</option>
                            <option value='4'>Exposição em níveis excessivos (maior que 100% a 500% do LEO)</option>
                            <option value='5'>Exposição em níveis muito excessivos (maior que 500% do LEO)</option>
                        </select>

                        <select class='form-control' id='gradacao_respostas_mult_3' name='gradacao_respostas_mult_3' style='display: none;'>
                            <option value='0' default>---</option>
                            <option value='1'>menor que 1h</option>
                            <option value='2'>1-2h</option>
                            <option value='3'>2-4h</option>
                            <option value='4'>4-8h</option>
                            <option value='5'>maior que 8h</option>
                        </select>
                    </div> 
                    <div id='gradacao_perguntas_mult_123_obrigatorio' style='display: none;'>
                        <div class='alert alert-warning' role='alert'>
                            <p>* Campo Obrigatório</p>
                        </div>
                    </div>
                    <div id='gradacao_perguntas_mult_123_ajuda' style='display: none;'>
                        <div class='alert alert-info' role='alert'>
                            <p>* Campo Ajuda</p>
                        </div>
                    </div>
                </div>
                <br>

                <div class='form-control'>
                    <div class='class-perguntas'>
                        <div class='pergunta'>
                            <label>Controles existentes da atividade (considere as medidas de prevenção já implementadas):</label>
                        </div>
                        <div class='icones-pergunta'>
                            <img src='../imagens/exclamacao.png' onclick='mostrarCampo("gradacao_respostas_mult_4_obrigatorio")'/>
                            <img src='../imagens/help.png' onclick='mostrarCampo("gradacao_respostas_mult_4_ajuda")'/>
                        </div>
                    </div>
                    <div style='display: flex; margin-top: 10px; margin-bottom: 10px;'>
                        <select class='form-control' id='gradacao_respostas_mult_4' name='gradacao_respostas_mult_4' required>
                            <option value='' default>---</option>
                            <option value='1'>Controle excelente</option>
                            <option value='2'>Controle em conformidade legal</option>
                            <option value='3'>Controle com pequenas deficiências</option>
                            <option value='4'>Controle deficiente</option>
                            <option value='5'>Controle Inexistente</option>
                        </select>
                    </div> 
                    <div id='gradacao_respostas_mult_4_obrigatorio' style='display: none;'>
                        <div class='alert alert-warning' role='alert'>
                            <p>* Campo Obrigatório</p>
                        </div>
                    </div>
                    <div id='gradacao_respostas_mult_4_ajuda' style='display: none;'>
                        <div class='alert alert-info' role='alert'>
                            <p>* Campo Ajuda</p>
                        </div>
                    </div>
                </div>
                <br>

                <div class='form-control'>
                    <div class='class-perguntas'>
                        <div class='pergunta'>
                            <label>Em uma observação visual rápida da execução da tarefa, avalie a gradação da carga de trabalho e/ou exigência biomecânica e postural e/ou carga mental/psicossocial/exigência de atenção e/ou condição de  risco grave/iminente/insalubridade/periculosidade/morte.</label>
                        </div>
                        <div class='icones-pergunta'>
                            <img src='../imagens/exclamacao.png' onclick='mostrarCampo("gradacao_respostas_mult_5_obrigatorio")'/>
                            <img src='../imagens/help.png' onclick='mostrarCampo("gradacao_respostas_mult_5_ajuda")'/>
                        </div>
                    </div>
                    <div style='display: flex; margin-top: 10px; margin-bottom: 10px;'>
                        <select class='form-control' id='gradacao_respostas_mult_5' name='gradacao_respostas_mult_5' required>
                            <option value='' default>---</option>
                            <option value='1'>Exigência em níveis muito baixos </option>
                            <option value='2'>Exigência em níveis baixos </option>
                            <option value='3'>Exigência em níveis moderados</option>
                            <option value='4'>Exigência em níveis excessivos</option>
                            <option value='5'>Exigência em níveis muito excessivos</option>
                        </select>
                    </div> 
                    <div id='gradacao_respostas_mult_5_obrigatorio' style='display: none;'>
                        <div class='alert alert-warning' role='alert'>
                            <p>* Campo Obrigatório</p>
                        </div>
                    </div>
                    <div id='gradacao_respostas_mult_5_ajuda' style='display: none;'>
                        <div class='alert alert-info' role='alert'>
                            <p>* Campo Ajuda</p>
                        </div>
                    </div>
                </div>
                <br>


                <div class="alert alert-primary" role="alert" style="display:none;" id="div_result_gradacao_respostas_mult" name="div_result_gradacao_respostas_mult">
                    <input type="hidden" value="" name="result_gradacao_respostas_mult" id="result_gradacao_respostas_mult" required>
                    <h4>Resultado da classificação de risco</h4>
                    <hr>
                    <p id="msg_gradacao_respostas_mult"></p>
                </div>
                <br>

            </div>
    
        </div>

        <div id='severidade_carga' class='container_now container' style="display:none;">
            Necessita preencher "severidade_carga"
        </div>

        <div id='severidade_bio' class='container_now container' style="display:none;">
            Necessita preencher "severidade_bio"
        </div>

        <div id='severidade_cognitivo' class='container_now container'style="display:none;">
            Necessita preencher "severidade_cognitivo"
        </div>

        <div id='severidade_ambiente' class='container_now container' style="display:none;">
            Necessita preencher "severidade_ambiente"
        </div>

        <div id='efeitos_corretivas' class='container_now container' style="display:none;">
            Necessita preencher "efeitos_corretivas"
        </div>

        <div id='container-msg' class='container_now container' style='display: none;'>
            <h4>Selecione um formulário para continuar a operação</h4>
        </div>

        <nav class="navbar navbar-expand-lg navbar-light bg-light footer-nav-formulario">
            <div class="container-fluid">
                <p class="navbar-brand ajuste-left-nav">Clique aqui para enviar:</p>
                <form class="d-flex">
                    <button type='submit' class='btn btn-primary' id="submitFormulatorioTotal">Registro completo sobre a atividade</button>
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

$('#submitFormulatorioTotal').on('click', function(event) {
    campos_requiridos = $('input,textarea,select').filter('[required]');
    for(i=0;i<=(campos_requiridos.length-1);i++) {
        if ((campos_requiridos[i].value == "") || (campos_requiridos[i].value == "0")) {
            $('#menuModalAvisoFaltaCampos').modal('show');
            event.preventDefault();
            break;
        }
    }

});



//Mask all need fields
//$(document).ready(function(){
//    $('#cnpj_empresa').mask('00.000.000/0000-00', {reverse: true});
//});

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

//Conferir textarea no preregistro
//------------------------------------------------------------
function loopConferValuesTextAreaPreRegistro() {
    document.getElementById('qtd_caracteres_textarea').textContent = document.getElementById('descricao_atividade').value.length + "/200";
}

$('#descricao_atividade').on('change', function() {
    document.getElementById('qtd_caracteres_textarea').textContent = document.getElementById('descricao_atividade').value.length + "/200";
});

$('#descricao_atividade').on('keypress', function() {
    document.getElementById('qtd_caracteres_textarea').textContent = document.getElementById('descricao_atividade').value.length + "/200";
});

$('#descricao_atividade').on('click', function() {
    document.getElementById('qtd_caracteres_textarea').textContent = document.getElementById('descricao_atividade').value.length + "/200";
});
//------------------------------------------------------------

$('#gradacao_perguntas_mult_123').on('change', function() {
    if (this.value == "0") {
        document.getElementById('gradacao_respostas_mult_1').style.display='none';
        document.getElementById('gradacao_respostas_mult_2').style.display='none';
        document.getElementById('gradacao_respostas_mult_3').style.display='none';
        document.getElementById('gradacao_respostas_mult_1').value='0';
        document.getElementById('gradacao_respostas_mult_2').value='0';
        document.getElementById('gradacao_respostas_mult_3').value='0';
        document.getElementById('gradacao_respostas_mult_1').setAttribute("required", true);
        document.getElementById('gradacao_respostas_mult_2').removeAttribute("required");
        document.getElementById('gradacao_respostas_mult_3').removeAttribute("required");
    } else if (this.value == "1") {
        document.getElementById('gradacao_respostas_mult_1').style.display='flex';
        document.getElementById('gradacao_respostas_mult_2').style.display='none';
        document.getElementById('gradacao_respostas_mult_3').style.display='none';
        document.getElementById('gradacao_respostas_mult_2').value='0';
        document.getElementById('gradacao_respostas_mult_3').value='0';
        document.getElementById('gradacao_respostas_mult_1').setAttribute("required", true);
        document.getElementById('gradacao_respostas_mult_2').removeAttribute("required");
        document.getElementById('gradacao_respostas_mult_3').removeAttribute("required");
    } else if (this.value == "2") {
        document.getElementById('gradacao_respostas_mult_1').style.display='none';
        document.getElementById('gradacao_respostas_mult_2').style.display='flex';
        document.getElementById('gradacao_respostas_mult_3').style.display='none';
        document.getElementById('gradacao_respostas_mult_1').value='0';
        document.getElementById('gradacao_respostas_mult_3').value='0';
        document.getElementById('gradacao_respostas_mult_1').removeAttribute("required");
        document.getElementById('gradacao_respostas_mult_2').setAttribute("required", true);
        document.getElementById('gradacao_respostas_mult_3').removeAttribute("required");
    } else if (this.value == "3") {
        document.getElementById('gradacao_respostas_mult_1').style.display='none';
        document.getElementById('gradacao_respostas_mult_2').style.display='none';
        document.getElementById('gradacao_respostas_mult_3').style.display='flex';
        document.getElementById('gradacao_respostas_mult_1').value='0';
        document.getElementById('gradacao_respostas_mult_2').value='0';
        document.getElementById('gradacao_respostas_mult_1').removeAttribute("required");
        document.getElementById('gradacao_respostas_mult_2').removeAttribute("required");
        document.getElementById('gradacao_respostas_mult_3').setAttribute("required", true);
    } else {
        document.getElementById('gradacao_respostas_mult_1').style.display='none';
        document.getElementById('gradacao_respostas_mult_2').style.display='none';
        document.getElementById('gradacao_respostas_mult_3').style.display='none';
        document.getElementById('gradacao_respostas_mult_1').value='0';
        document.getElementById('gradacao_respostas_mult_2').value='0';
        document.getElementById('gradacao_respostas_mult_3').value='0';
        document.getElementById('gradacao_respostas_mult_1').setAttribute("required", true);
        document.getElementById('gradacao_respostas_mult_1').removeAttribute("required");
        document.getElementById('gradacao_respostas_mult_1').removeAttribute("required");
    }
});

//Aqui printa o div de resultado da página
function loopConferValuesGradacaoRespostas() {

    var probabilidade = 0;
    var severidade = 0;

    
    if (document.getElementById('gradacao_perguntas_mult_123').value == "0") {
        document.getElementById('div_result_gradacao_respostas_mult').style.display="none";
        return
    }

    if (document.getElementById('gradacao_perguntas_mult_123').value == "1") {
        if (document.getElementById('gradacao_respostas_mult_1').value != "none") {
            probabilidade = parseInt(document.getElementById('gradacao_respostas_mult_1').value);
        }
    }

    if (document.getElementById('gradacao_perguntas_mult_123').value == "2") {
        if (document.getElementById('gradacao_respostas_mult_2').value != "none") {
            probabilidade = parseInt(document.getElementById('gradacao_respostas_mult_2').value);
        }
    }

    if (document.getElementById('gradacao_perguntas_mult_123').value == "3") {
        if (document.getElementById('gradacao_respostas_mult_3').value != "none") {
            probabilidade = parseInt(document.getElementById('gradacao_respostas_mult_3').value);
        }
    }

    if (probabilidade == 0) {
        document.getElementById('div_result_gradacao_respostas_mult').style.display="none";
        return
    }

    if (document.getElementById('gradacao_respostas_mult_4').value != "0") {
        severidade += parseInt(document.getElementById('gradacao_respostas_mult_4').value);
    } else {
        document.getElementById('div_result_gradacao_respostas_mult').style.display="none";
        return
    }

    if (document.getElementById('gradacao_respostas_mult_5').value != "0") {
        severidade += parseInt(document.getElementById('gradacao_respostas_mult_5').value);
    } else {
        document.getElementById('div_result_gradacao_respostas_mult').style.display="none";
        return
    }

    severidade = parseInt(severidade/2);

    //console.log("probabilidade: " + probabilidade);
    //console.log("severidade: " + severidade);

    //Baixo
    if (
        ((severidade == 4) && (probabilidade == 1)) || 
        ((severidade == 3) && (probabilidade == 1)) || 
        ((severidade == 2) && (probabilidade == 2)) || 
        ((severidade == 2) && (probabilidade == 1)) || 
        ((severidade == 1) && (probabilidade == 1)) || 
        ((severidade == 1) && (probabilidade == 2)) || 
        ((severidade == 1) && (probabilidade == 3)) 
    ) {
        document.getElementById('div_result_gradacao_respostas_mult').removeAttribute("class");
        document.getElementById('div_result_gradacao_respostas_mult').setAttribute("class", "alert alert-success");
    } else if (
        ((severidade == 5) && (probabilidade == 1)) || 
        ((severidade == 4) && (probabilidade == 2)) || 
        ((severidade == 3) && (probabilidade == 2)) || 
        ((severidade == 3) && (probabilidade == 3)) || 
        ((severidade == 2) && (probabilidade == 3)) || 
        ((severidade == 1) && (probabilidade == 4)) 
    ) {
        document.getElementById('div_result_gradacao_respostas_mult').removeAttribute("class");
        document.getElementById('div_result_gradacao_respostas_mult').setAttribute("class", "alert alert-secondary");
    } else if (
        ((severidade == 5) && (probabilidade == 2)) || 
        ((severidade == 4) && (probabilidade == 3)) || 
        ((severidade == 3) && (probabilidade == 4)) || 
        ((severidade == 2) && (probabilidade == 4)) || 
        ((severidade == 2) && (probabilidade == 5)) || 
        ((severidade == 1) && (probabilidade == 5)) 
    ) {
        document.getElementById('div_result_gradacao_respostas_mult').removeAttribute("class");
        document.getElementById('div_result_gradacao_respostas_mult').setAttribute("class", "alert alert-warning");
    } else if (
        ((severidade == 5) && (probabilidade == 3)) ||
        ((severidade == 5) && (probabilidade == 4)) ||
        ((severidade == 5) && (probabilidade == 5)) ||
        ((severidade == 4) && (probabilidade == 4)) ||
        ((severidade == 4) && (probabilidade == 5)) ||
        ((severidade == 3) && (probabilidade == 3)) 
    ) {
        document.getElementById('div_result_gradacao_respostas_mult').removeAttribute("class");
        document.getElementById('div_result_gradacao_respostas_mult').setAttribute("class", "alert alert-danger");
    } else {
        document.getElementById('div_result_gradacao_respostas_mult').style.display="none";
    }

    msg_apresentar = parseInt((probabilidade + severidade)/2);

    var msg_return = "";
    if (msg_apresentar == 1) {
        document.getElementById('result_gradacao_respostas_mult').value=1;
        msg_return = "Muito baixa probabilidade de risco ergonômico (0 a 20%)";
    } else if (msg_apresentar == 2) {
        document.getElementById('result_gradacao_respostas_mult').value=2;
        msg_return = "Baixa probabilidade de risco ergonômico (>20 a 40 %)";
    } else if (msg_apresentar == 3) {
        document.getElementById('result_gradacao_respostas_mult').value=3;
        msg_return = "Moderada probabilidade de risco ergonômico (>40 a 60%)";
    } else if (msg_apresentar == 4) {
        document.getElementById('result_gradacao_respostas_mult').value=4;
        msg_return = "Alta probabilidade de risco ergonômico (>60 a 80%)";
    } else if (msg_apresentar == 5) {
        document.getElementById('result_gradacao_respostas_mult').value=5;
        msg_return = "Probabilidade em nível crítico de risco ergonômico (>80 a 100%)";
    } else {
        document.getElementById('result_gradacao_respostas_mult').value='';
        document.getElementById('div_result_gradacao_respostas_mult').style.display="none";
        return
    }

    //Apresentando o div completo
    document.getElementById('div_result_gradacao_respostas_mult').style.display="block";
    document.getElementById('msg_gradacao_respostas_mult').textContent=msg_return;

}


//Gerando um loop de 1 segundo dentro da pagina com o javascript
function loopFunctionsAll() {
    loopConferValuesTextAreaPreRegistro();
    loopConferValuesGradacaoRespostas();

    setTimeout(() => {
        loopFunctionsAll();
    }, 1000);

}

setTimeout(() => {
    loopFunctionsAll();
}, 1000);

function imprimirFormulario() {
    document.getElementById("frequencia_p_cada_perigo").style.display="block";
    document.getElementById("severidade_carga").style.display="block";
    document.getElementById("severidade_bio").style.display="block";
    document.getElementById("severidade_cognitivo").style.display="block";
    document.getElementById("severidade_ambiente").style.display="block";
    document.getElementById("efeitos_corretivas").style.display="block";
    document.getElementById("analise_ergonomica").style.display="block";
    print();
    document.getElementById("frequencia_p_cada_perigo").style.display="block";
    document.getElementById("severidade_carga").style.display="none";
    document.getElementById("severidade_bio").style.display="none";
    document.getElementById("severidade_cognitivo").style.display="none";
    document.getElementById("severidade_ambiente").style.display="none";
    document.getElementById("efeitos_corretivas").style.display="none";
    document.getElementById("analise_ergonomica").style.display="none";
}

</script>

<?php
    $chamadaProcura = "select * from registro_total where id_formulario_preliminar = '{$_SESSION['id_formulario_preliminar']}' and registro_trabalho = '{$_SESSION['registro_trabalho']}' limit 1;";
    $busca = mysqli_query($conexao_banco, $chamadaProcura);
    if (mysqli_num_rows($busca) > 0) {
        ?><script>document.getElementById("imprimirFormulario").style.display="block";</script><?php
        foreach (mysqli_fetch_array($busca) as $chave => $valor) {
            ?>
            <?php
           if ($valor != "0") {
                ?>
                <script>
                if (!!document.getElementById("<?=$chave?>")) {
                    if ("id_image_preliminar" == "<?=$chave?>") {
                        document.getElementById("<?=$chave?>").setAttribute("required", true);
                        document.getElementById("<?=$chave?>").value="<?=$valor?>";
                        document.getElementById("foto_local_atividade").removeAttribute("required");
                        document.getElementById("apresentar_imagem_ja_cadastrada").style.display="block";
                        document.getElementById("imagem_ja_cadastrada_valor").src="<?=$valor?>";
                    } else {
                        document.getElementById("<?=$chave?>").style.display="block";
                        document.getElementById("<?=$chave?>").setAttribute("required", true);
                        document.getElementById("<?=$chave?>").value="<?=$valor?>";
                    }
                }
                </script>
                <?php
            }
        };
    }
?>


</body>