document.getElementById("formLoginQld").addEventListener("submit", async function(e) {
    e.preventDefault();

    const usuario = document.getElementById("user").value.trim();
    const senha = document.getElementById("passwd").value.trim();

    if (!usuario || !senha) {
        showErro("Preencha todos os campos corretamente!")
        return;
    }

    try {
        const req = await fetch("https://testes.epquixeramobim.com.br/proc_login.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            credentials: "include",
            body: JSON.stringify({ usuario, senha })
        });
        const resultado = await req.json();

        if (req.ok && resultado.ok) {
            window.location.href = "home.html";
        } else {
            msg = resultado.mensagem || "Erro desconhecido. Falar com o admin do sistema!";
            showErro(msg);
        }
    } catch (erro) {
        showErro("Erro: " + erro)
    }
});

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