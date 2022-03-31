<?php
header("Cache-Control: no-cache, must-revalidate");
require_once("../chamadas/confirmar_sessao.php");
require_once("../banco/banco.php");
require_once("../chamadas/confirmar_sessao_banco.php");
require_once("../chamadas/head_geral.php");
require_once("../chamadas/perguntas_lista_json.php");
require_once("../chamadas/menu_nav.php");

$_success_msg = "";
$_error_msg = "";

if (!empty($_POST)) {
    if ((file_exists($_POST['inputDeleteJSON'])) && (file_exists($_POST['inputDeleteCSV']))) {
        if (mysqli_query($conexao_banco, "delete from formularios where PK_User = '{$_SESSION['id_user']}' and File_CSV = '{$_POST['inputDeleteCSV']}' and File_JSON = '{$_POST['inputDeleteJSON']}';")) {
            unlink($_POST['inputDeleteCSV']);
            unlink($_POST['inputDeleteJSON']);
            $_success_msg = "Formulário deletado com sucesso.";
        } else {
            $_error_msg = "Não foi possível deletar o arquivo.";
        }
    }
}


echo "<div class='container'>";

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

if (!empty($_success_msg)) {
    ?>
    <div class="alert alert-success" role="alert">
        <?=$_success_msg?>
    </div>
    <br>
    <?php
}

if (!empty($_error_msg)) {
    ?>
    <br>
    <div class="alert alert-danger" role="alert">
        <?=$_error_msg?>
    </div>
    <br>
    <?php
}

$arquivos = mysqli_query($conexao_banco, "select * from formularios where PK_User = '{$_SESSION['id_user']}';");
$arquivos_qtd = mysqli_num_rows($arquivos);
if ($arquivos_qtd > 0) {
    echo "<table class='table table-striped table-hover'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th scope='col'>CNPJ</th>";
    echo "<th scope='col'>Setor</th>";
    echo "<th scope='col'>Ações</th>";
    echo "</tr>";
    echo "</thead>";
    echo " <tbody>";

    while($chamada=mysqli_fetch_array($arquivos)) {
        echo "<tr>";
        echo "<th scope='row'>{$chamada['PK_CNPJ']}</th>";
        echo "<th scope='row'>{$chamada['Setor']}</th>";
        echo "<th>";
        echo "<div class='div-table-buttons'>";
        echo "<a href='{$chamada['File_CSV']}' download><img src='../imagens/csv.png' class='img' alt='editar'/></a>";
        echo "<a href='{$chamada['File_JSON']}' download><img src='../imagens/json.png' class='img' alt='editar'/></a>";
        echo "<div>";
        echo "<form action='./formulario.php' method='POST'>";
        echo "<input type='hidden' id='inputEditeCSV' name='inputEditeCSV' value='{$chamada['File_CSV']}' />";
        echo "<input type='hidden' id='inputEditeJSON' name='inputEditeJSON' value='{$chamada['File_JSON']}' />";
        echo "<button type='submit' class='btn-margin-bk'><img src='../imagens/editar.png' class='img' alt='editar'/></button>";
        echo "</form>";
        echo "</div>";
        echo "<div>";
        echo "<form action='./perfil.php' method='POST'>";
        echo "<input type='hidden' id='inputDeleteCSV' name='inputDeleteCSV' value='{$chamada['File_CSV']}' />";
        echo "<input type='hidden' id='inputDeleteJSON' name='inputDeleteJSON' value='{$chamada['File_JSON']}' />";
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
<nav class="navbar navbar-expand-lg navbar-light bg-light footer-nav">
<div class="container-fluid">
    <p class="navbar-brand ajuste-center-nav">Nome do software</p>
</div>
</nav>
<?php
echo "</body>";
require_once("../chamadas/js_geral.php");
