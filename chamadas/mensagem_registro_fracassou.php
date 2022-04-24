<?php
if ($_SESSION['session_erro_registro']) {
?>
    <div class="alert alert-danger" id="registro-realizado" role="alert">
        <h4><?=$_SESSION['session_erro_registro']?></h4>
    </div>
<?php
    unset($_SESSION['session_erro_registro']);
}