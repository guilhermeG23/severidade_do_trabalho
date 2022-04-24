<script>
document.getElementsByClassName("box")[0].style.height="auto";
document.getElementsByClassName('container_now')[0].style.display='block';
function trocarDisplay(display_container) {
    todos = document.getElementsByClassName('container_now');
    if (document.getElementById(display_container).style.display == 'block') {
        for(i=0;i<=todos.length-1;i++) {
            todos[i].style.display='none';
        }
        todos[todos.length-1].style.display='block';
        document.getElementsByClassName("box")[0].style.height="90%";
    } else {
        for(i=0;i<=todos.length-1;i++) {
            todos[i].style.display='none';
        }
        document.getElementById(display_container).style.display='block';
        document.getElementsByClassName("box")[0].style.height="auto";
    }
}

function mostrarCampo(entrada) {
    alvo = document.getElementById(entrada);
    if (alvo.style.display == 'block') {
        alvo.style.display='none';
    } else {
        alvo.style.display='block';
    }
}
function mostrarMaisCamposMais(entrada) {
    document.getElementById(entrada).style.display='flex';
}
function mostrarMaisCamposMenos(entrada) {
    document.getElementById(entrada).style.display='none';
}
setTimeout(() => {
    document.getElementById("registro-realizado").style.display='none';
}, 5000);
</script>