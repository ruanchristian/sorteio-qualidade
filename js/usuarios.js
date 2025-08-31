$(document).ready(function () {
  // monta datatable dos usuários
  $("#usuariosTable").DataTable({
    responsive: true,
     language: {
       url: "/sorteio-qualidade/inclusoes/pt-BR.json", // alterar o path quando hospedado!
     },
    ordering: false,
  });
});

// monta modal de edição com os dados do usuário
document.addEventListener("DOMContentLoaded", () => {
  const editBtn = document.querySelectorAll(".edit-user");

  editBtn.forEach((btn) => {
    btn.addEventListener("click", () => {
      let user = btn.dataset.user;
      let tipo = btn.dataset.type;
      let id = btn.dataset.id;

      document.getElementById("name-user").value = user;
      document.getElementById("type-user").value = tipo;
      document.getElementById("user-id").value = id;
      document.getElementById("type-hidden").value = tipo;

      // atualiza select
      document.getElementById("type-user").addEventListener("change", () => {
        document.getElementById("type-hidden").value =
          document.getElementById("type-user").value;
      });

      if (id == meuID) {
        document.getElementById("type-user").disabled = true;
      } else {
        document.getElementById("type-user").disabled = false;
      }

      new bootstrap.Modal(document.getElementById("modalEdit")).show();
    });
  });
});

// add usuário
const form = document.querySelector("#form-cadastro");
form.addEventListener("submit", async (e) => {
  e.preventDefault();

  const data = new FormData(form);

  mostraSpin(true);
  try {
    const res = await fetch("add_user.php", {
      method: "POST",
      body: data,
    });

    const json = await res.json();

    if (!res.ok) {
      showCustomAlert("Erro!", "error", json.erro);
      return;
    }
    showOk(json.sucesso);

    setTimeout(() => {
      location.reload();
    }, 3500);
  } catch (err) {
    showError(err.message);
  } finally {
    mostraSpin(false);
  }
});

// editar usuário
document.getElementById("form-update").addEventListener("submit", async function (e) {
    e.preventDefault();

    mostraSpin(true);

    const formData = new FormData(this);

    try {
      const res = await fetch("edit_user.php", {
        method: "POST",
        body: formData,
      });

      const text = await res.text();
      let json;

      try {
        json = JSON.parse(text);
      } catch {
        throw new Error("Resposta inesperada do servidor:\n" + text);
      }

      if (json.erro) {
        showCustomAlert("Erro inesperado!", "error", json.erro);
        return;
      }

      showOk(json.sucesso);
      bootstrap.Modal.getInstance(document.getElementById("modalEdit")).hide();
      setTimeout(() => location.reload(), 3000);
    } catch (err) {
      showCustomAlert("Erro!", "error", err.message);
    } finally {
      mostraSpin(false);
    }
  });

//deletar usuário
document.querySelectorAll(".delete-user").forEach((button) => {
  button.addEventListener("click", async () => {
    const id = button.dataset.id;
    const nome = button.dataset.nome;

    Swal.fire({
      title: `Tem certeza que deseja excluir o usuário "${nome}"?`,
      text: "Este usuário não terá mais acesso ao sistema!",
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

          const res = await fetch("delete_user.php", {
            method: "POST",
            body: new URLSearchParams({ id }),
          });

          const text = await res.text();
          let json;

          try {
            json = JSON.parse(text);
          } catch {
            throw new Error("Resposta inválida: " + text);
          }

          if (json.erro) {
            showCustomAlert("Erro inesperado!", "error", json.erro);
            return;
          }

          mostraSpin(false);
          await Swal.fire({
            title: "Exclusão feita!",
            text: "Esse usuário foi excluído com sucesso.",
            icon: "success",
            confirmButtonColor: "#007bff",
          });

          location.reload();
        } catch (err) {
          console.error(err);
          Swal.fire("Erro", "Erro ao excluir esse user.", "error");
        } finally {
            mostraSpin(false);
        }
      }
    });
  });
});
