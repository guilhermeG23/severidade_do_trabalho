<?php
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
$_success_msg = "";

if (!empty($_POST)) {
    if (!empty($_POST['email']) &&
        !empty($_POST['usuario']) &&
        !empty($_POST['senha']) &&
        !empty($_POST['senhaConfirma']) &&
        !empty($_POST['chave'])
    ) {
        $emailUser = $_POST['email'];
        $Username = $_POST['usuario'];
        $senha = md5($_POST['senha']);
        $senhaConfirmar = md5($_POST['senhaConfirma']);
        $chave = md5($_POST['chave']);
        //$_POST['razaoSocial']
        //$_POST['cnpj']
        if(mysqli_num_rows(mysqli_query($conexao_banco, "select User_ID from User where User_Email = '{$emailUser}' and User_Name = '{$Username}' limit 1;")) > 0) {
            if(mysqli_num_rows(mysqli_query($conexao_banco, "select User_ID from User where User_Email = '{$emailUser}' and User_Name = '{$Username}' and User_Chave = '{$chave}' limit 1;")) > 0) {
                if ($senha == $senhaConfirmar) {
                    if(mysqli_num_rows(mysqli_query($conexao_banco, "select User_ID from User where User_Email = '{$emailUser}' and User_Name = '{$Username}' and User_Password = '{$senha}' and User_Chave = '{$chave}' limit 1;")) > 0) {
                        $_error_msg = "Por favor, altere a senha, nova e antiga senha se coincidem.";  
                    } else {
                        if(mysqli_query($conexao_banco, "update User set User_Password = '{$senha}' where  User_Email = '{$emailUser}' and User_Name = '{$Username}' and User_Chave = '{$chave}';")) {
                            $_success_msg = "Alterada a senha do usuário com sucesso.";
                        } else {
                            $_error_msg = "Erro ao alterar a senha de usuário.";
                        }
                    }
                } else {
                    $_error_msg = "Favor, verifique as senhas digitadas, as mesmas estão difentes uma da outra.";  
                }
            } else {
                $_error_msg = "Por favor, verifique suas informações, nenhum resultado foi encontrado.";
            }
        } else {
            $_error_msg = "Usuário não existe, por favor, crie o usuário na aba de cadastro.";
        }
    } else {
        $_error_msg = "Há algum problema no reset da senha do usuário.";
    }
}


echo "<div class='container'>";
echo "<div class='caixa-login'>";
echo "<form action='./resetPassword.php' method='POST'>";
?>
<h4>Alteração de senha</h4>
<div class="form-floating mb-3">
    <input type="email" class="form-control" id="email" name="email">
    <label for="floatingInput">Endereço de email</label>
</div>
<div class="form-floating mb-3">
    <input type="text" class="form-control" id="usuario" name="usuario">
    <label for="floatingInput">Nome de usuário</label>
</div>
<div class="form-floating mb-3">
    <input type="text" class="form-control" id="chave" name="chave" maxlength="10">
    <label for="floatingInput">Chave (10 caracteres no máximo)</label>
</div>
<div class="form-floating mb-3">
    <input type="password" class="form-control" id="senha" name="senha">
    <label for="floatingPassword">Nova senha</label>
</div>
<div class="form-floating mb-3">
    <input type="password" class="form-control" id="senhaConfirma" name="senhaConfirma">
    <label for="floatingPassword">Confirmar novasenha</label>
</div>
<?php
echo "<button type='submit' class='btn btn-primary form-control'>Registrar</button>";
echo "</form>";
echo "<div class='ajuste-link-login'>";
echo "<div class='ajuste-margin-login-link'>";
echo "<a href='./'>Logar</a>";
echo "</div>";
echo "<div class='ajuste-margin-login-link'>";
echo "<a href='./novoUsuario.php'>Se cadastrar</a>";    
echo "</div>";
echo "</div>";
echo "</div>";
echo "<br>";
if (!empty($_success_msg)) {
?>
<div class="alert alert-success" role="alert">
    <?=$_success_msg?>
</div>
<?php
}
if (!empty($_error_msg)) {
?>
<div class="alert alert-danger" role="alert">
    <?=$_error_msg?>
</div>
<?php
}
unset($_POST);
echo "</div>";
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light footer-nav">
    <div class="container-fluid">
        <p class="navbar-brand ajuste-center-nav">Nome do software</p>
    </div>
</nav>