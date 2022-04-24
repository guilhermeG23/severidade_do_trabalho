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

if (isset($_POST) && isset($_SESSION) && isset($_FILES)) {

    /*
    echo var_dump($_SESSION);
    foreach ($_SESSION as $key => $value) {
        echo $key ." - " . $value  . "<br>";
    }
    echo "<br>";
    echo var_dump($_POST);
    foreach ($_POST as $key => $value) {
        echo $key ." - " . $value  . "<br>";
    }
    echo "<br>";
    echo var_dump($_FILES);
    echo "<br>";
    echo "<br>";
    echo "<br>";
    */


    if (isset($_POST['id_formulario_total']) && strlen($_POST['id_formulario_total']) && mysqli_num_rows(mysqli_query($conexao_banco, "select * from registro_total where id_formulario_total = '{$_POST['id_formulario_total']}' and id_formulario_preliminar = '{$_POST['id_formulario_preliminar']}' limit 1;")) > 0) {
        /*echo "select * from registro_total where id_formulario_total = '{$_POST['id_formulario_total']}' and id_formulario_preliminar = '{$_POST['id_formulario_preliminar']}' limit 1;";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "<br>";
        echo "Aqui é editar o ja existente";*/

        $valoresJaRegistrados = mysqli_fetch_array(mysqli_query($conexao_banco, "select * from registro_total where id_formulario_total = '{$_POST['id_formulario_total']}' and id_formulario_preliminar = '{$_POST['id_formulario_preliminar']}' limit 1;"));

        //Alterar a imagem
        $update_img = "";
        if (($_POST['id_image_preliminar'] == $valoresJaRegistrados['id_image_preliminar']) && (strlen($_FILES['foto_local_atividade']['name']) > 0)) {
            $uploaddir = '../imagens_empresas/';
            $uploadfile = $uploaddir . strtotime("now") . random_int(100, 999) . random_int(100, 999) . random_int(100, 999) . ".jpg";
            if (move_uploaded_file($_FILES['foto_local_atividade']['tmp_name'], $uploadfile)) {
                unlink($valoresJaRegistrados['id_image_preliminar']);
                $update_img = " id_image_preliminar = '{$uploadfile}', ";
            } else {
                $_SESSION['erro_cadastro'] = "Problemas ao registrar nova  imagem";
                header('Location: ./formularioTotal.php');
                die();
            }
        }

        if (mysqli_query($conexao_banco, "update registro_total set descricao_atividade = '{$_POST['descricao_atividade']}',$update_img gradacao_perguntas_mult_123 = '{$_POST['gradacao_perguntas_mult_123']}', gradacao_respostas_mult_1 = '{$_POST['gradacao_respostas_mult_1']}', gradacao_respostas_mult_2 = '{$_POST['gradacao_respostas_mult_2']}', gradacao_respostas_mult_3 = '{$_POST['gradacao_respostas_mult_3']}', gradacao_respostas_mult_4 = '{$_POST['gradacao_respostas_mult_4']}', gradacao_respostas_mult_5 = '{$_POST['gradacao_respostas_mult_5']}', result_gradacao_respostas_mult = '{$_POST['result_gradacao_respostas_mult']}' where id_formulario_total = '{$_POST['id_formulario_total']}' and id_formulario_preliminar = '{$_POST['id_formulario_preliminar']}' limit 1;")) {
            $_SESSION['success_cadastro'] = "Cadastro atualizado com sucesso";
            header('Location: ./postoTrabalho.php');
            die();
        } else {
            $_SESSION['erro_cadastro'] = "Problemas ao registrar atualizações no cadastro";
            header('Location: ./formularioTotal.php');
            die();
        }

    } else {
        $uploaddir = '../imagens_empresas/';
        $uploadfile = $uploaddir . strtotime("now") . random_int(100, 999) . random_int(100, 999) . random_int(100, 999) . ".jpg";
        $continuar_registro = false;
        if (move_uploaded_file($_FILES['foto_local_atividade']['tmp_name'], $uploadfile)) {
            $continuar_registro = true;
        } else {
            $_SESSION['erro_cadastro'] = "Não foi possível fazer o registro da imagem";
            header('Location: ./formularioTotal.php');
            die();
        }
        if ($continuar_registro) {
            //echo "insert into registro_total values (0,'{$_POST['registro_trabalho']}',{$_SESSION['id_formulario_preliminar']},'{$uploadfile}',{$_POST['gradacao_perguntas_mult_123']},{$_POST['gradacao_respostas_mult_1']},{$_POST['gradacao_respostas_mult_2']},{$_POST['gradacao_respostas_mult_3']},{$_POST['gradacao_respostas_mult_4']},{$_POST['gradacao_respostas_mult_5']},{$_POST['result_gradacao_respostas_mult']});";
            if (mysqli_query($conexao_banco,"insert into registro_total values (0,'{$_POST['registro_trabalho']}',{$_POST['id_formulario_preliminar']},'{$_POST['descricao_atividade']}','{$uploadfile}',{$_POST['gradacao_perguntas_mult_123']},{$_POST['gradacao_respostas_mult_1']},{$_POST['gradacao_respostas_mult_2']},{$_POST['gradacao_respostas_mult_3']},{$_POST['gradacao_respostas_mult_4']},{$_POST['gradacao_respostas_mult_5']},{$_POST['result_gradacao_respostas_mult']});")) {
                $_SESSION['success_cadastro'] = "Cadastro realizado com sucesso";
                header('Location: ./postoTrabalho.php');
                die();
            } else {
                $_SESSION['erro_cadastro'] = "Não foi possível fazer o registro do formulário";
                header('Location: ./formularioTotal.php');
                die();
            }
        }
    
    }

}
?>