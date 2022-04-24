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
Note: 
* Falta editar os registros por setor
*/

if (isset($_POST) && isset($_SESSION)) {

    //Campos preliminares nao obrigatorios
    $ignorar_chaves = ["id_formulario_preliminar", "empresa_posto_trabalho_ativ_desc_2","empresa_posto_trabalho_ativ_desc_3","empresa_posto_trabalho_ativ_desc_4","empresa_posto_trabalho_ativ_desc_5","empresa_posto_trabalho_ativ_desc_6","empresa_posto_trabalho_ativ_desc_7","empresa_posto_trabalho_ativ_desc_8","empresa_posto_trabalho_ativ_desc_9","empresa_posto_trabalho_ativ_desc_10"];

    //Existe valores em todos os campos
    foreach ($_POST as $chave => $valor) {
        //Se for uma chave obrigatorio e está vazia, nem cadastra
        if (!in_array($chave, $ignorar_chaves)) {
            if (empty($valor)) {
                $_SESSION['erro_cadastro'] = "Nem todos os campos necessários foram preenchidos, favor registrar corretamente";
                header('Location: ./registroPreliminar.php');
                die();
            }
        }
    }

    //Limpeza de valores
    $cnpj_empresa = preg_replace('/[^a-zA-Z0-9]/', "", $_POST['cnpj_empresa']);
    $identidade = preg_replace('/[^a-zA-Z0-9]/', "", $_POST['empresa_comprovante_documento']);

    if (isset($_POST['id_formulario_preliminar']) && !empty($_POST['id_formulario_preliminar'])) {

        //Registros preliminares já existentes
        $teste = mysqli_query($conexao_banco, "select * from registro_preliminar where id_formulario_preliminar = '{$_POST['id_formulario_preliminar']}' and id_usuario = '{$_SESSION['id_user']}';");
        $chamadaPorRegistro=mysqli_fetch_array($teste);
        $todosRegistros = [];
        $todosRegistros["empresa_posto_trabalho_ativ_desc_1"] = $chamadaPorRegistro['empresa_posto_trabalho_ativ_desc_1'];
        $todosRegistros["empresa_posto_trabalho_ativ_desc_2"] = $chamadaPorRegistro['empresa_posto_trabalho_ativ_desc_2'];
        $todosRegistros["empresa_posto_trabalho_ativ_desc_3"] = $chamadaPorRegistro['empresa_posto_trabalho_ativ_desc_3'];
        $todosRegistros["empresa_posto_trabalho_ativ_desc_4"] = $chamadaPorRegistro['empresa_posto_trabalho_ativ_desc_4'];
        $todosRegistros["empresa_posto_trabalho_ativ_desc_5"] = $chamadaPorRegistro['empresa_posto_trabalho_ativ_desc_5'];
        $todosRegistros["empresa_posto_trabalho_ativ_desc_6"] = $chamadaPorRegistro['empresa_posto_trabalho_ativ_desc_6'];
        $todosRegistros["empresa_posto_trabalho_ativ_desc_7"] = $chamadaPorRegistro['empresa_posto_trabalho_ativ_desc_7'];
        $todosRegistros["empresa_posto_trabalho_ativ_desc_8"] = $chamadaPorRegistro['empresa_posto_trabalho_ativ_desc_8'];
        $todosRegistros["empresa_posto_trabalho_ativ_desc_9"] = $chamadaPorRegistro['empresa_posto_trabalho_ativ_desc_9'];
        $todosRegistros["empresa_posto_trabalho_ativ_desc_10"] = $chamadaPorRegistro['empresa_posto_trabalho_ativ_desc_10'];


        foreach($todosRegistros as $chave => $valor) {
            if (mysqli_num_rows(mysqli_query($conexao_banco, "select * from registro_total where id_formulario_preliminar = '{$_POST['id_formulario_preliminar']}' and registro_trabalho = '{$valor}';")) > 0) {
                if (empty($_POST[$chave])) {
                    //Deleta
                    $deletarImagem = mysqli_fetch_array(mysqli_query($conexao_banco, "select id_image_preliminar from registro_total where id_formulario_preliminar = '{$_POST['id_formulario_preliminar']}' and registro_trabalho = '{$valor}';"));
                    unlink($deletarImagem['id_image_preliminar']);
                    mysqli_query($conexao_banco, "delete from registro_total where id_formulario_preliminar = '{$_POST['id_formulario_preliminar']}' and registro_trabalho = '{$valor}';");
                } else {
                    //Atualiza
                    mysqli_query($conexao_banco, "update registro_total set registro_trabalho = '{$_POST[$chave]}' where id_formulario_preliminar = '{$_POST['id_formulario_preliminar']}' and registro_trabalho = '{$valor}';");
                }
            }
        }

        //Edita se já existir um igual
        if (mysqli_query($conexao_banco,"update registro_preliminar set cnpj_empresa = '{$cnpj_empresa}',razao_empresa = '{$_POST['razao_empresa']}',data_registro = '{$_POST['data_registro']}',unidade_planta_empresa = '{$_POST['unidade_planta_empresa']}',empresa_setor = '{$_POST['empresa_setor']}',empresa_cargo = '{$_POST['empresa_cargo']}',empresa_funcao = '{$_POST['empresa_funcao']}',empresa_numero_total = '{$_POST['empresa_numero_total']}',select_document = '{$_POST['select_document']}',empresa_comprovante_documento = '{$identidade}',empresa_posto_trabalho = '{$_POST['empresa_posto_trabalho']}',empresa_posto_trabalho_desc = '{$_POST['empresa_posto_trabalho_desc']}',empresa_posto_trabalho_ativ_desc_1 = '{$_POST['empresa_posto_trabalho_ativ_desc_1']}',empresa_posto_trabalho_ativ_desc_2 = '{$_POST['empresa_posto_trabalho_ativ_desc_2']}',empresa_posto_trabalho_ativ_desc_3 = '{$_POST['empresa_posto_trabalho_ativ_desc_3']}',empresa_posto_trabalho_ativ_desc_4 = '{$_POST['empresa_posto_trabalho_ativ_desc_4']}',empresa_posto_trabalho_ativ_desc_5 = '{$_POST['empresa_posto_trabalho_ativ_desc_5']}',empresa_posto_trabalho_ativ_desc_6 = '{$_POST['empresa_posto_trabalho_ativ_desc_6']}',empresa_posto_trabalho_ativ_desc_7 = '{$_POST['empresa_posto_trabalho_ativ_desc_7']}',empresa_posto_trabalho_ativ_desc_8 = '{$_POST['empresa_posto_trabalho_ativ_desc_8']}',empresa_posto_trabalho_ativ_desc_9 = '{$_POST['empresa_posto_trabalho_ativ_desc_9']}',empresa_posto_trabalho_ativ_desc_10 = '{$_POST['empresa_posto_trabalho_ativ_desc_10']}' where id_formulario_preliminar = '{$_POST['id_formulario_preliminar']}' and id_usuario = '{$_SESSION['id_user']}';")) {
            $_SESSION['success_cadastro'] = "Edição do registro #{$_POST['id_formulario_preliminar']} realizado com sucesso";
            header('Location: ./perfil.php');
            die();
        } else {
            $_SESSION['erro_cadastro'] = "Ocorreu erros ao editar o registro #{$_POST['id_formulario_preliminar']}";
            header('Location: ./registroPreliminar.php');
            die();
        }
    } else {
        //Regsitra somente se existir todos os valores necessarios e nao exista clones ja no banco
        if (mysqli_num_rows(mysqli_query($conexao_banco, "select * from registro_preliminar where id_usuario = '{$_SESSION['id_user']}' and cnpj_empresa = '{$cnpj_empresa}' limit 1;")) == 0) {
            if (mysqli_query($conexao_banco,"insert into registro_preliminar values (0,'{$_SESSION['id_user']}','{$cnpj_empresa}','{$_POST['razao_empresa']}','{$_POST['data_registro']}','{$_POST['unidade_planta_empresa']}','{$_POST['empresa_setor']}','{$_POST['empresa_cargo']}','{$_POST['empresa_funcao']}','{$_POST['empresa_numero_total']}','{$_POST['select_document']}','{$identidade}','{$_POST['empresa_posto_trabalho']}','{$_POST['empresa_posto_trabalho_desc']}','{$_POST['empresa_posto_trabalho_ativ_desc_1']}','{$_POST['empresa_posto_trabalho_ativ_desc_2']}','{$_POST['empresa_posto_trabalho_ativ_desc_3']}','{$_POST['empresa_posto_trabalho_ativ_desc_4']}','{$_POST['empresa_posto_trabalho_ativ_desc_5']}','{$_POST['empresa_posto_trabalho_ativ_desc_6']}','{$_POST['empresa_posto_trabalho_ativ_desc_7']}','{$_POST['empresa_posto_trabalho_ativ_desc_8']}','{$_POST['empresa_posto_trabalho_ativ_desc_9']}','{$_POST['empresa_posto_trabalho_ativ_desc_10']}');")) {
                $_SESSION['success_cadastro'] = "Cadastro realizado com sucesso";
                header('Location: ./perfil.php');
                die();
            } else {
                $_SESSION['erro_cadastro'] = "Não foi possível realizar o registro";
                header('Location: ./registroPreliminar.php');
                die();
            }
        } else {
            $_SESSION['erro_cadastro'] = "Registro já existe, favor verificar no seu perfil";
            header('Location: ./registroPreliminar.php');
            die();
        }
    }

} else {
    $_SESSION['erro_cadastro'] = "Não foi possível realizar o registro";
    header('Location: ./registroPreliminar.php');
    die();
}