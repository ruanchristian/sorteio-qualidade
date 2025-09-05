$(document).ready(function () {
  $("#tabela-sorteios").DataTable({
    responsive: true,
    ordering: false,
     language: {
       url: "/sorteio-qualidade/inclusoes/pt-BR.json", // alterar esse path quando hospedado!
     },
  });
});

document.getElementById("criarSort").addEventListener("click", () => {
  // Limpa os campos
  document.getElementById("txt").innerText = "Cadastrar novo sorteio";
  document.getElementById("id_sorteio").value = "";
  document.getElementById("dia").value = "";
  document.getElementById("inicio").value = "";
  document.getElementById("fim").value = "";
});

document.addEventListener("DOMContentLoaded", () => {
  const editBtn = document.querySelectorAll(".edit-sorteio");

  editBtn.forEach((btn) => {
    btn.addEventListener("click", () => {
      let id = btn.dataset.id;
      let dia = btn.dataset.dia;
      let inicio = btn.dataset.inicio;
      let fim = btn.dataset.fim;

      document.getElementById("txt").innerText = "Editar sorteio"
      document.getElementById("id_sorteio").value = id;
      document.getElementById("dia").value = dia;
      document.getElementById("inicio").value = inicio;
      document.getElementById("fim").value = fim;

      new bootstrap.Modal(document.getElementById("modalSorteio")).show();
    });
  });
});

// cria um sorteio
document.getElementById("form-sorteio").addEventListener("submit", async (e) => {
  e.preventDefault();
  const formData = new FormData(e.target);

  mostraSpin(true);
  try {
    const res = await fetch("add_sorteio.php", { 
      method: "POST", 
      body: formData 
    });
    const json = await res.json();

    if (json.ok) {
      showOk(json.msg);
      setTimeout(() => location.reload(), 3000);
    } else {
      showCustomAlert("Erro!", "error", json.msg || "Erro desconhecido", "error");
    }
  } catch (err) {
    showCustomAlert("Erro!", "error", err.message);
  } finally {
    mostraSpin(false);
  }
});

// deletar sorteio
document.querySelectorAll(".deleta-sorteio").forEach((button) => {
  button.addEventListener("click", async () => {
    let id = button.dataset.id;
    let dia = button.dataset.dia;
    dia = dia.split("-").reverse().join("-");

    Swal.fire({
      title: `Excluir sorteio do dia "${dia}"?`,
      text: "O sorteio será excluído junto com os participantes cadastrados!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#007bff",
      confirmButtonText: "Excluir",
      cancelButtonText: "Cancelar",
      reverseButtons: true,
    }).then(async (resp) => {
      if (resp.isConfirmed) {
        try {
          mostraSpin(true);

          const res = await fetch("delete_sorteio.php", {
            method: "POST",
            body: new URLSearchParams({ id }),
          });

          const text = await res.text();
          let json;

          try { json = JSON.parse(text); } 
          catch { throw new Error("Resposta inválida: " + text); }

          if (!json.ok) {
            showCustomAlert("Erro inesperado!", "error", json.msg);
            return;
          }

          mostraSpin(false);
          await Swal.fire({
            title: "Exclusão feita!",
            text: json.msg,
            icon: "success",
            confirmButtonColor: "#007bff",
          });

          location.reload();
        } catch (err) {
          console.error(err);
          Swal.fire("Erro", "Erro ao excluir esse sorteio.", "error");
        } finally {
          mostraSpin(false);
        }
      }
    });
  });
});
