document.addEventListener("DOMContentLoaded", async () => {
  const dataSorteioInput = document.getElementById("dataSorteio");
  const sorteioIdInput = document.getElementById("sorteio_id");
  const btnCadastrar = document.getElementById("btnCadastrar");

  try {
    const res = await fetch("get_sorteiohj.php");
    const json = await res.json();

    if (json.ok) {
      // sorteio ativo para cadastro
      dataSorteioInput.value = json.data;
      sorteioIdInput.value = json.id;
      btnCadastrar.disabled = false;

      document.getElementById("flagm").innerText = "Sorteio de hoje:";
      document.getElementById("infoSorteio").classList.remove("d-none");
      document.getElementById("dataAtual").textContent = json.data;

      contador(json.fim); // ativa o contador
    } else if (json.espera) {
      // ainda não começou
      dataSorteioInput.value = json.msg;
      btnCadastrar.disabled = true;

      document.getElementById("flagt").innerText = "Tempo de espera:";
      document.getElementById("infoSorteio").classList.remove("d-none");
      document.getElementById("dataAtual").textContent = "Aguardando início...";
      contador(json.inicio, true);
    } else {
      // não há sorteio / sorteio encerrado
      document.getElementById("matricula").disabled = true;
      dataSorteioInput.value = json.msg;
      btnCadastrar.disabled = true;
    }
  } catch (e) {
    dataSorteioInput.value = "Erro ao verificar sorteio.";
    btnCadastrar.disabled = true;
  }
});

// inserir participante no sorteio de hoje
document.getElementById("form-participante").addEventListener("submit", async function (e) {
    e.preventDefault();

    mostraSpin(true);
    const formData = new FormData(this);
    try {
      const res = await fetch("add_participante.php", {
        method: "POST",
        body: formData,
      });

      const text = await res.text();
      let json;

      try { json = JSON.parse(text); } 
      catch { throw new Error("Resposta inesperada do servidor:\n" + text); }

      if (!json.ok) {
        showCustomAlert("Erro inesperado!", "error", json.msg);
        return;
      }

      mostraSpin(false);
      await Swal.fire({
        title: "Cadastro concluído!",
        html: json.msg,
        icon: "success",
        confirmButtonColor: "#007bff",
      });
      location.reload();
    } catch (err) {
        console.error(err);
        Swal.fire("Erro", "Erro ao cadastrar esse colaborador.", "error");
    } finally {
        mostraSpin(false);
    }
  });

// função que vai atualizar o contador de tempo restante para iniciar/fechar o sorteio
function contador(alvo, aguarda = false) {
  const fim = new Date(alvo).getTime();
  const el = document.getElementById("contador");

  const intervalo = setInterval(() => {
    const agora = new Date().getTime();
    const distancia = fim - agora;

    if (distancia <= 0) {
      clearInterval(intervalo);
      el.innerText = aguarda
        ? "Cadastro iniciado!"
        : "Período de cadastro encerrou para o sorteio de hoje.";
      if (aguarda) {
        location.reload();
      } else {
        document.getElementById("matricula").disabled = true;
        document.getElementById("btnCadastrar").disabled = true;
      }
      return;
    }

    const horas = Math.floor(
      (distancia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)
    );
    const minutos = Math.floor((distancia % (1000 * 60 * 60)) / (1000 * 60));
    const segundos = Math.floor((distancia % (1000 * 60)) / 1000);

    el.innerText = `${horas}h ${minutos}min ${segundos}seg`;
  }, 500);
}
