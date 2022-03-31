<?php
header("Cache-Control: no-cache, must-revalidate");
require_once("../banco/banco.php");
require_once("../chamadas/head_geral.php");

$_error_msg = "";
$_success_msg = "";

if (!empty($_POST)) {
    if (!empty($_POST['email']) &&
        !empty($_POST['usuario']) &&
        !empty($_POST['senha']) &&
        !empty($_POST['senhaConfirma']) &&
        !empty($_POST['chave'])
    ) {
        $User_ID = strtotime("now");
        $emailUser = $_POST['email'];
        $Username = $_POST['usuario'];
        $senha = md5($_POST['senha']);
        $senhaConfirmar = md5($_POST['senhaConfirma']);
        $chave = md5($_POST['chave']);
        //$_POST['razaoSocial']
        //$_POST['cnpj']
        if(mysqli_num_rows(mysqli_query($conexao_banco, "select User_ID from User where User_Name = '{$Username}' limit 1;")) == 0) {
            if(mysqli_num_rows(mysqli_query($conexao_banco, "select User_ID from User where User_Email = '{$emailUser}' and User_Name = '{$Username}' limit 1;")) == 0) {
                if ($senha == $senhaConfirmar) {
                    if(mysqli_query($conexao_banco, "insert into User values('{$User_ID}','{$emailUser}','{$Username}','{$senha}', '{$chave}');")) {
                        $_success_msg = "Usuário cadastrado.";
                    } else {
                        $_error_msg = "Erro no cadastro do usuário.";
                    }
                } else {
                    $_error_msg = "Senhas não se coincidem.";
                }

            } else {
                $_error_msg = "Usuário já existe, tente resetar sua senha.";
            }
        } else {
            $_error_msg = "Nome de usuário já existe, por favor, utilize outro nome de usuário.";
        }
    } else {
        $_error_msg = "Há algum problema no cadastro.";
    }
}


echo "<body>";
echo "<div class='container'>";
echo "<div class='caixa-login'>";
echo "<form action='./novoUsuario.php' method='POST'>";
?>
<h4>Cadastro de usuário</h4>
<div class="form-floating mb-3">
    <input type="email" class="form-control" id="email" name="email">
    <label for="floatingInput">Endereço de email</label>
</div>
<div class="form-floating mb-3">
    <input type="text" class="form-control" id="usuario" name="usuario">
    <label for="floatingInput">Nome de usuário</label>
</div>
<div class="form-floating mb-3">
    <input type="password" class="form-control" id="senha" name="senha">
    <label for="floatingPassword">Senha</label>
</div>
<div class="form-floating mb-3">
    <input type="password" class="form-control" id="senhaConfirma" name="senhaConfirma">
    <label for="floatingPassword">Confirmar senha</label>
</div>
<div class="form-floating mb-3">
    <input type="text" class="form-control" id="chave" name="chave" maxlength="10">
    <label for="floatingInput">Chave (10 caracteres no máximo)</label>
</div>
<!--
<div class="form-floating mb-3">
    <input type="text" class="form-control" id="cnpj" required>
    <label for="floatingInput">CNPJ</label>
</div>
<div class="form-floating mb-3">
    <input type="text" class="form-control" id="razaoSocial" required>
    <label for="floatingInput">Razão social</label>
</div>
-->
<?php
echo "<button type='submit' class='btn btn-primary form-control'>Registrar</button>";
echo "</form>";
echo "<div class='ajuste-link-login'>";
echo "<div class='ajuste-margin-login-link'>";
echo "<a href='./'>Logar</a>";
echo "</div>";
echo "<div class='ajuste-margin-login-link'>";
echo "<a href='./resetPassword.php'>Recuperar senha</a>";    
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