<?php
session_start();
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