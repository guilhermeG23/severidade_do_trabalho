<?php
header("Cache-Control: no-cache, must-revalidate");
require_once("../chamadas/confirmar_sessao.php");
require_once("../banco/banco.php");
require_once("../chamadas/confirmar_sessao_banco.php");
require_once("../chamadas/head_geral.php");
require_once("../chamadas/perguntas_lista_json.php");
require_once("../chamadas/menu_nav.php");
?>

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

<?php

echo "<div class='container box'>";

require_once("../chamadas/mensagem_registro_realizado.php");
require_once("../chamadas/mensagem_registro_fracassou.php");

echo "<form action='registroFormulario.php' method='post' enctype='multipart/form-data'>";

foreach ($arquivos_json as $json_now) {

    $teste = file_get_contents($json_now);
    $teste = json_decode($teste, true);

    echo "<div id={$teste['chave_base']} class='container_now container' style='display: none;'>";
    
    echo "<div class='caixa-titulo'>";
    $identificador=true;
    foreach ($teste["subtitulo"] as $subtitulo) {
        if ($identificador) {
            echo "<h4>{$subtitulo}</h4>";
            $identificador=false;
        } else {
            echo "<p>{$subtitulo}</p>";
        }
    }
    echo "<hr>";
    echo "</div>";

    foreach ($teste['todas_questoes'] as $partes_niveis) {
        echo "<div class='caixa-titulo'>";
        $identificador=true;
        foreach ($partes_niveis['desc'] as $desc_now) {
            if ($identificador) {
                echo "<h4>" . $desc_now . "</h4>";
                $identificador=false;
            } else {
                echo "<p>" . $desc_now . "</p>";
            }
        }
        echo "<hr>";
        echo "</div>";
        foreach ($partes_niveis['questoes'] as $partes) {
            echo "<div class='form-control'>";
            echo "<div class='class-perguntas'>";
            echo "<div class='pergunta'>";
            echo "<label for='{$teste['chave_base']}{$partes['id']}'>";
            $identificador=true;
            foreach ($partes['questao'] as $textos) {
                if ($identificador) {
                    echo "<p>" . explode("_", $partes['id'])[1] . ". " . $textos . "</p>";
                    $identificador=false;
                } else {
                    echo "<p>{$textos}</p>";
                }
            }
            echo "</label>";
            echo "</div>";
            echo "<div class='icones-pergunta'>";
            if (!empty($partes['obrigatorio'])) {
                echo "<img src='../imagens/exclamacao.png' onclick='mostrarCampo(" . "\"" .  $teste['chave_base'] . $partes['id'] . "_obrigatorio" . "\"" . ")'/>";
            }
            if (!empty($partes['ajuda'])) {
                echo "<img src='../imagens/help.png' onclick='mostrarCampo(" . "\"" .  $teste['chave_base'] . $partes['id'] . "_ajuda" . "\"" . ")'/>";
            }
            echo "</div>";
            echo "</div>";

            echo "<div>";


            if ($partes['tipo_campo'] != "") {


                if (!empty($partes['Qtd_campos'])) {
                    $primeira_pergunta=true;
                    for($contador=1;$contador<=$partes['Qtd_campos'];$contador++) {
                        $proxima_caixa=$contador+1;
                        if ($primeira_pergunta) {
                            echo "<div style='display: flex; margin-top: 10px; margin-bottom: 10px;'>";
                            echo "<input type='{$partes['tipo_campo']}' class='form-control' style='width: 100%;' name='{$teste['chave_base']}{$partes['id']}{$contador}' id='{$teste['chave_base']}{$partes['id']}{$contador}' {$partes['obrigatorio']}></input>";
                            echo "<input class='btn btn-primary' type='button' style='margin-left: 10px;' onclick='mostrarMaisCamposMais(" . "\"" . $teste['chave_base'] . $partes['id'] . $proxima_caixa . "_caixa" .  "\"" . ")' value='+'/>";
                            echo "</div>";    
                            $primeira_pergunta=false;
                        } else {
                            if ($contador == $partes['Qtd_campos']) {
                                echo "<div style='display: flex; margin-top: 10px; margin-bottom: 10px; display: none;' id='{$teste['chave_base']}{$partes['id']}{$contador}_caixa' name='{$teste['chave_base']}{$partes['id']}{$contador}_caixa'>";
                                echo "<input type='{$partes['tipo_campo']}' class='form-control' style='width: 100%;' name='{$teste['chave_base']}{$partes['id']}{$contador}' id='{$teste['chave_base']}{$partes['id']}{$contador}'></input>";
                                echo "<input class='btn btn-primary' type='button' style='margin-left: 10px;' onclick='mostrarMaisCamposMenos(" . "\"" . $teste['chave_base'] . $partes['id'] . $contador . "_caixa" .  "\"" . ")' value='-'/>";
                                echo "</div>";
                            } else {
                                echo "<div style='display: flex; margin-top: 10px; margin-bottom: 10px; display: none;' id='{$teste['chave_base']}{$partes['id']}{$contador}_caixa' name='{$teste['chave_base']}{$partes['id']}{$contador}_caixa'>";
                                echo "<input type='{$partes['tipo_campo']}' class='form-control' style='width: 100%;' name='{$teste['chave_base']}{$partes['id']}{$contador}' id='{$teste['chave_base']}{$partes['id']}{$contador}'></input>";
                                echo "<input class='btn btn-primary' type='button' style='margin-left: 10px;' onclick='mostrarMaisCamposMais(" . "\"" . $teste['chave_base'] . $partes['id'] . $proxima_caixa . "_caixa" .  "\"" . ")' value='+'/>";
                                echo "<input class='btn btn-primary' type='button' style='margin-left: 10px;' onclick='mostrarMaisCamposMenos(" . "\"" . $teste['chave_base'] . $partes['id'] . $contador . "_caixa" .  "\"" . ")' value='-'/>";
                                echo "</div>";
                            }
                        }
                    }
                } else {
                    echo "<input type='{$partes['tipo_campo']}' class='form-control' name='{$teste['chave_base']}{$partes['id']}' id='{$teste['chave_base']}{$partes['id']}' {$partes['obrigatorio']}></input>";
                }
            } else {
                echo "<select class='form-control' name='{$teste['chave_base']}{$partes['id']}' id='{$teste['chave_base']}{$partes['id']}' {$partes['obrigatorio']}>";
                echo "<option value='' default>---</option>";
                foreach ($partes['respostas'] as $perguntas) {
                    echo "<option value='{$perguntas[0]}'>{$perguntas[1]}</option>";
                }
                echo "</select>";
            }
            echo "</div>";

            if (!empty($partes['obrigatorio'])) {
                echo "<div id='{$teste['chave_base']}{$partes['id']}_obrigatorio' style='display: none;'>";
                echo "<br>";
                echo "<div class='alert alert-warning' role='alert'>";
                echo "<p>* Campo Obrigatório</p>";
                echo "</div>";
                echo "</div>";
            }
            if (!empty($partes['ajuda'])) {
                echo "<div id='{$teste['chave_base']}{$partes['id']}_ajuda' style='display: none;'>";
                echo "<br>";
                echo "<div class='alert alert-info' role='alert'>";
                foreach ($partes['ajuda'] as $textos) {
                    echo "<p>" . $textos . "</p>";
                }
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
            echo "<br>";
        }
    }
    echo "</div>";
}

echo "<div id='container-msg' class='container_now container' style='display: none;'>";
echo "<h4>Selecione um formulário para continuar a operação</h4>";
echo "</div>";

require_once("../chamadas/footer_formulario.php");

echo "</form>";
echo "</div>";

require_once("../chamadas/js_formulario.php");
require_once("../chamadas/js_geral.php");

if (!empty($_POST['inputEditeJSON'])) {
    $_SESSION['inputEditeCSV'] = $_POST['inputEditeCSV'];
    $_SESSION['inputEditeJSON'] = $_POST['inputEditeJSON'];
    $fileJsonRespostas = file_get_contents($_POST['inputEditeJSON']);
    $fileJsonRespostas = json_decode($fileJsonRespostas, true);
    $chaves_valores = array_keys($fileJsonRespostas);
    echo "<script>";
    foreach ($chaves_valores as $chave_now) {
        ?>document.getElementById("<?=$chave_now?>").value="<?=$fileJsonRespostas[$chave_now]?>";<?php
    }
    echo "</script>";
}

?>
