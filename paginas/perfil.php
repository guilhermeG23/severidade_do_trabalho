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
<div class='container'>

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
    
    <?php if (!empty($_SESSION['success_cadastro'])) { ?>
        <div class="alert alert-success" role="alert" id="mensagem_success_cadastro">
            <p><?=$_SESSION['success_cadastro']?>
        </div>
    <?php
    }
    unset($_SESSION['success_cadastro']);
    ?>

<?php

//echo var_dump($_SESSION) . "<br>";


$registros_do_usuario = mysqli_query($conexao_banco, "select * from registro_preliminar where id_usuario = '{$_SESSION['id_user']}';");
$qtd_registros_do_usuario = mysqli_num_rows($registros_do_usuario);
if ($qtd_registros_do_usuario > 0) {
    
    echo "<div class='caixa-titulo'><h4>Análises Ergonômicas Preliminares já cadastradas:</h4></div>";
    echo "<hr>";
    echo "<br>";
    echo "<table class='table table-striped table-hover'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th scope='col'>#</th>";
    echo "<th scope='col'>CNPJ</th>";
    echo "<th scope='col'>Razão social</th>";
    echo "<th scope='col'>Ações</th>";
    echo "</tr>";
    echo "</thead>";
    echo " <tbody>";

    while($chamada=mysqli_fetch_array($registros_do_usuario)) {
        echo "<tr>";
        echo "<th scope='row'>{$chamada['id_formulario_preliminar']}</th>";
        echo "<th scope='row'>{$chamada['cnpj_empresa']}</th>";
        echo "<th scope='row'>{$chamada['razao_empresa']}</th>";
        echo "<th>";
        echo "<div class='div-table-buttons'>";
        //echo "<a href='{$chamada['File_CSV']}' download><img src='../imagens/csv.png' class='img' alt='editar'/></a>";
        //echo "<a href='{$chamada['File_JSON']}' download><img src='../imagens/json.png' class='img' alt='editar'/></a>";
        echo "<div>";
        echo "<form action='./registroPreliminar.php' method='POST'>";
        echo "<input type='hidden' id='id_formulario_preliminar' name='id_formulario_preliminar' value='{$chamada['id_formulario_preliminar']}' />";
        echo "<input type='hidden' id='cnpj_empresa' name='cnpj_empresa' value='{$chamada['cnpj_empresa']}' />";
        echo "<input type='hidden' id='razao_empresa' name='razao_empresa' value='{$chamada['razao_empresa']}' />";
        echo "<button type='submit' class='btn-margin-bk'><img src='../imagens/editar.png' class='img' alt='editar'/></button>";
        echo "</form>";
        echo "</div>";

        echo "<div>";
        echo "<form action='./postoTrabalho.php' method='POST'>";
        echo "<input type='hidden' id='id_formulario_preliminar' name='id_formulario_preliminar' value='{$chamada['id_formulario_preliminar']}' />";
        echo "<input type='hidden' id='cnpj_empresa' name='cnpj_empresa' value='{$chamada['cnpj_empresa']}' />";
        echo "<input type='hidden' id='razao_empresa' name='razao_empresa' value='{$chamada['razao_empresa']}' />";
        echo "<button type='submit' class='btn-margin-bk'><img src='../imagens/formulario.png' class='img' alt='formularios'/></button>";
        echo "</form>";
        echo "</div>";

        echo "<div>";
        echo "<form action='./perfil.php' method='POST'>";
        echo "<input type='hidden' id='id_formulario_preliminar' name='id_formulario_preliminar' value='{$chamada['id_formulario_preliminar']}' />";
        echo "<input type='hidden' id='cnpj_empresa' name='cnpj_empresa' value='{$chamada['cnpj_empresa']}' />";
        echo "<input type='hidden' id='razao_empresa' name='razao_empresa' value='{$chamada['razao_empresa']}' />";
        echo "<button type='submit' class='btn-margin-bk'><img src='../imagens/delete.png' alt='deletar'/></button>";
        echo "</form>";
        echo "</div>";
        echo "</div>";
        echo "</th>";
        echo "</tr>";
    }
} else {
    echo "<h4>Não há registros de formulário neste usuário</h4>";
}

echo "</tbody>";
echo "</table>";
echo "</div>";
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light footer-nav-formulario">
    <div class="container-fluid">
        <p class="navbar-brand ajuste-left-nav">Clique aqui para realizar:</p>
        <form class="d-flex" action="./registroPreliminar.php">
            <button type='submit' class='btn btn-primary'>Registro preliminar</button>
        </form>
    </div>
</nav>
</body>
<script src="../js/popper.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script>
if (!!document.getElementById("mensagem_success_cadastro")) {
    setTimeout(() => {
        document.getElementById("mensagem_success_cadastro").style.display="none";
    }, 5000);
}
</script>
