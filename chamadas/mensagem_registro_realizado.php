<?php
if ($_SESSION['session_registro']) {
?>
    <div class="alert alert-success" id="registro-realizado" role="alert">
        <h4><?=$_SESSION['session_registro']?></h4>
    </div>
<?php
    unset($_SESSION['session_registro']);
}