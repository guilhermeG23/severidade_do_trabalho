<?php

session_start();
header("Cache-Control: no-cache, must-revalidate");
require_once("../banco/banco.php");
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

<?php
$_error_msg = "";

if (!empty($_POST['usuario']) && !empty($_POST['password'])) {
    
    $id_user = "";
    $usuario_banco = "";
    $senha_banco = "";

    $usuario_post = $_POST['usuario'];
    $usuario_password_post = md5($_POST['password']);


    $usuario = mysqli_query($conexao_banco, "select User_ID, User_Name, User_Password from User where User_Name = '{$usuario_post}' and User_Password = '{$usuario_password_post}' limit 1;");
    $usuario_qtd = mysqli_num_rows($usuario);
    if ($usuario_qtd > 0) {
        while($chamada=mysqli_fetch_array($usuario)){
            $id_user = $chamada['User_ID'];
            $usuario_banco = $chamada['User_Name'];
            $senha_banco = $chamada['User_Password'];
        }
        if (($_POST['usuario'] == $usuario_banco) && (md5($_POST['password']) == $senha_banco)) {
            $_SESSION['id_user'] = $id_user;
            $_SESSION['usuario_banco'] = $usuario_banco;
            $_SESSION['senha_banco'] = $senha_banco;
            $_SESSION['limite'] = date_timestamp_get(date_create()) + 3600;
            header("Location: ./perfil.php");
            die();
        }
    } else {
        $_error_msg = "Usuário não existe, favor registrar o usuário se desejado";
    }
}

if (empty($_SESSION['entrar'])) {
    ?>
    <div class='container'>
    <div class='caixa-login'>
    <form action='./' method='POST'>
    <input type='text' id='usuario' name='usuario' class='form-control ajuste-input-login' placeholder='Usuário' required>
    <input type='password' id='password' name='password' class='form-control ajuste-input-login' placeholder='Senha' required/>
    <button type='submit' class='btn btn-primary form-control'>Entrar</button>
    </form>
    <div class='ajuste-link-login'>
    <div class='ajuste-margin-login-link'>
    <a href='./resetPassword.php'>Recuperar senha</a>
    </div>
    <div class='ajuste-margin-login-link'>
    <a href='./novoUsuario.php'>Se cadastrar</a> 
    </div>
    </div>
    </div>
    <?php if (!empty($_error_msg)) { ?>
        <br>
        <div class="alert alert-danger" role="alert">
            <?=$_error_msg?>
        </div>
    <?php } ?>

    <nav class="navbar navbar-expand-lg navbar-light bg-light footer-nav">
        <div class="container-fluid">
            <p class="navbar-brand ajuste-center-nav">Nome do software</p>
        </div>
    </nav>
<?php
    echo "</body>";
    die();
} else {
    if ($_SESSION['limite'] > date_timestamp_get(date_create())) {
        header("Location: ./formulario.php");
    }
}

?>


