// função que mostra a msg de erro em forma de toast no canto superior direito da tela
function showErro(msg) {
    const TOAST = Swal.mixin({
        toast: true,
        position: 'top-end',
        timerProgressBar: true,
        showConfirmButton: false,
        timer: 3000
    });

    TOAST.fire({ icon: 'error', text: msg });
}

function showOk(msg) {
    const TOAST = Swal.mixin({
        toast: true,
        position: 'top-end',
        timerProgressBar: true,
        showConfirmButton: false,
        timer: 3000
    });

    TOAST.fire({icon: 'success', text: msg});
}

function showCustomAlert(title, type, msg) {
    Swal.fire({
        titleText: title,
        text: msg,
        icon: type,
        confirmButtonText: "OK",
        confirmButtonColor: '#3085d6'
    });
}

function mostraSpin(flag) {
    let doc1 = document.getElementById('loading');
    let doc2 = document.getElementById('loading-content');

    if(flag) {
        // mostrar spinner de load
        doc1.classList.add('loading');
        doc2.classList.add('loading-content');
    } else {
        doc1.classList.remove('loading');
        doc2.classList.remove('loading-content');
    }
}