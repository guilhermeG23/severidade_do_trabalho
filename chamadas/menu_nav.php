<nav class="navbar navbar-expand-lg navbar-light bg-light menu-ajuste-nav">
    <div class="container-fluid">
        <a class="navbar-brand" href='./formulario.php'>Nome do software</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <?php 
            $formulario = explode(".", end(explode("/", $_SERVER["REQUEST_URI"])))[0];
            $display = "display: none";
            if ($formulario == "formulario") {
                $display = "";
            }
            ?>
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="<?=$display?>">Formulário</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <?php
                        $todos_json = count($arquivos_json)-1;
                        $contador_json_now = 0;
                        foreach ($arquivos_json as $json_now) {
                            $teste = file_get_contents($json_now);
                            $teste = json_decode($teste, true);
                            $chave_base = $teste['chave_base'];
                        ?>
                            <li><button class="dropdown-item" onclick="trocarDisplay('<?=$chave_base?>')""><?=ucwords(strtolower($teste['titulo']))?></button></li>
                            <?php if ($todos_json != $contador_json_now): ?>
                                <li><hr class="dropdown-divider"></li>
                            <?php endif; ?>
                        <?php 
                            $contador_json_now++;
                        } 
                        ?>
                    </ul>
                </li>
            </ul>
            <form class="d-flex">
                <a class='dropdown-item' href='./perfil.php'><img src='../imagens/user.png' class='img'/></a>
                <a class='dropdown-item' data-bs-toggle="modal" data-bs-target="#menuModalHelp"><img src='../imagens/help.png' class='img'/></a>
                <a class='dropdown-item' href='./logout.php'><img src='../imagens/logout.png' class='img'/></a>
            </form>
        </div>
    </div>
</nav>