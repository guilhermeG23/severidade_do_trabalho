<?php
require_once("../chamadas/confirmar_sessao.php");
require_once("../banco/banco.php");
require_once("../chamadas/confirmar_sessao_banco.php");
require_once("../chamadas/perguntas_lista_json.php");

$retorno_json = [];
$retorno_csv = "";
$cnpj_now = $_POST["analisePreliminar1_1"];
$setor_now = $_POST["analisePreliminar1_5"];

foreach ($arquivos_json as $json_now) {

    $teste = file_get_contents($json_now);
    $teste = json_decode($teste, true);

    $chave_base = $teste['chave_base'];
    foreach ($teste['todas_questoes'] as $questoes) {
        foreach ($questoes['questoes'] as $info_questao) {
            $chave_confirmada = $chave_base . $info_questao['id'];
            if (!empty($info_questao['Qtd_campos'])) {
                $chave_confirmada = $chave_base . $info_questao['id'] . "1";
            }
            if (array_key_exists($chave_confirmada, $_POST)) {
                $chave_confirmada = $chave_base . $info_questao['id'];
                if (!empty($info_questao['obrigatorio'])) {
                    if (!empty($info_questao['Qtd_campos'])) {
                        $primeiro_obrigatorio=true;
                        for($contador=1;$contador<=$info_questao['Qtd_campos'];$contador++) {
                            $chave_temporaria = $chave_confirmada . $contador;
                            if ($primeiro_obrigatorio) {
                                $primeiro_obrigatorio=false;
                                if (!empty($_POST[$chave_temporaria])) {
                                    $retorno_json[$chave_temporaria] = "{$_POST[$chave_temporaria]}";
                                    $retorno_csv = $retorno_csv . ";" . $_POST[$chave_temporaria];
                                } else {
                                    $_SESSION['session_erro_registro'] = "Campo obrigatorio não preenchido";
                                    header("Location: ./formulario.php");
                                    die();
                                }
                            } else {
                                $retorno_json[$chave_temporaria] = "{$_POST[$chave_temporaria]}";
                                $retorno_csv = $retorno_csv . ";" . $_POST[$chave_temporaria];
                            }
                        }
                    } else {
                        if (!empty($_POST[$chave_confirmada])) {
                            $retorno_json[$chave_confirmada] = "{$_POST[$chave_confirmada]}";
                            $retorno_csv = $retorno_csv . ";" . $_POST[$chave_confirmada];
                        } else {
                            $_SESSION['session_erro_registro'] = "Campo obrigatorio não preenchido";
                            header("Location: ./formulario.php");
                            die();
                        }
                    }
                } else {
                    $retorno_json[$chave_confirmada] = "{$_POST[$chave_confirmada]}";
                    $retorno_csv = $retorno_csv . ";" . $_POST[$chave_confirmada];
                }
            }
        }
    }
}

$sessao_tempo = date_timestamp_get(date_create());
$arquivo_saida = "../arquivos/{$sessao_usuario}{$sessao_tempo}";

$sessao_usuario = $_SESSION['id_user'];

if (!empty($_SESSION['inputEditeCSV']) && !empty($_SESSION['inputEditeJSON'])) {
    if (mysqli_query($conexao_banco, "delete from formularios where PK_User = '{$_SESSION['id_user']}' and File_CSV = '{$_SESSION['inputEditeCSV']}' and File_JSON = '{$_SESSION['inputEditeJSON']}';")) {
        unlink($_SESSION['inputEditeCSV']);
        unlink($_SESSION['inputEditeJSON']);
        unset($_SESSION['inputEditeCSV']);
        unset($_SESSION['inputEditeJSON']);
        $_SESSION['session_registro'] = "Registro atualizado com sucesso";

    }
}
   
if (mysqli_query($conexao_banco, "insert into formularios values(0, '{$sessao_usuario}', '{$cnpj_now}', '{$setor_now}', NOW(), '{$arquivo_saida}.csv', '{$arquivo_saida}.json');")) {
    file_put_contents("{$arquivo_saida}.json", json_encode($retorno_json));
    $json_formado = file_get_contents("{$arquivo_saida}.json");
    file_put_contents("{$arquivo_saida}.csv", $retorno_csv);
    if (empty($_SESSION['session_registro'])) {
        $_SESSION['session_registro'] = "Registro realizado com sucesso";
    }
    header("Location: ./formulario.php");
    die();
} else {
    $_SESSION['session_registro'] = "Problemas de registro";
    header("Location: ./formulario.php");
    die();
}


?>