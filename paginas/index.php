<?php

session_start();
header("Cache-Control: no-cache, must-revalidate");
require_once("../banco/banco.php");
require_once("../chamadas/head_geral.php");

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
            header("Location: ./formulario.php");
            die();
        }
    } else {
        $_error_msg = "Usuário não existe, favor registrar o usuário se desejado";
    }
}

if (empty($_SESSION['entrar'])) {
    echo "<body>";
    echo "<div class='container'>";
    echo "<div class='caixa-login'>";
    echo "<form action='./' method='POST'>";
    echo "<input type='text'/ id='usuario' name='usuario' class='form-control ajuste-input-login' placeholder='Usuário' required>";
    echo "<input type='password' id='password' name='password' class='form-control ajuste-input-login' placeholder='Senha' required/>";
    echo "<button type='submit' class='btn btn-primary form-control'>Entrar</button>";
    echo "</form>";
    echo "<div class='ajuste-link-login'>";
    echo "<div class='ajuste-margin-login-link'>";
    echo "<a href='./resetPassword.php'>Recuperar senha</a>";
    echo "</div>";
    echo "<div class='ajuste-margin-login-link'>";
    echo "<a href='./novoUsuario.php'>Se cadastrar</a>";    
    echo "</div>";
    echo "</div>";
    echo "</div>";
    if (!empty($_error_msg)) {
        ?>
        <br>
        <div class="alert alert-danger" role="alert">
            <?=$_error_msg?>
        </div>
        <?php
    }

?>
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


