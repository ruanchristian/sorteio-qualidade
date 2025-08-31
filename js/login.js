document.getElementById("formLoginQld").addEventListener("submit", async function(e) {
    e.preventDefault();

    const usuario = document.getElementById("user").value.trim();
    const senha = document.getElementById("passwd").value.trim();

    if (!usuario || !senha) {
        showErro("Preencha todos os campos corretamente!")
        return;
    }

    const btn = document.querySelector("#btnLogin");
    const txtBtn = document.querySelector(".btn-txt");
    const iconAt = document.querySelector(".ent-icon");
    const spin = document.querySelector(".spinner-border");
    iconAt.classList.add("d-none");
    btn.disabled = true;
    txtBtn.innerText = "";
    spin.classList.remove("d-none");

    try {
        const req = await fetch("proc_login.php", {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({ usuario,senha })
        });
        
        const text = await req.text();
        let json;
        
        try {
            json = JSON.parse(text);
        } catch {
            throw new Error("Resposta inesperada do servidor:\n" + text);
        }

        if (req.ok && json.ok) {
            window.location.href = "home.php";
        } else {
            msg = json.mensagem || "Erro desconhecido. Falar com o admin do sistema!";
            showErro(msg);
        }
    } catch (erro) {
        showErro("Erro de rede: " + erro)
    } finally {
        btn.disabled = false;
        txtBtn.innerText = "Entrar";
        spin.classList.add("d-none");
        iconAt.classList.remove("d-none");
    }
});