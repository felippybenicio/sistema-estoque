// Abre o modal principal de edição
const modalEditar = document.getElementById("modalEditar");
const modalSenha = document.getElementById("modalAlterarSenha");

const fecharEdicao = document.getElementById("fechaEdicao");
const fechaEdicaoSenha = document.getElementById("fechaEdicaoSenha");
const botaoAlterarSenha = document.getElementById("alterarSenha");

// Elementos do formulário principal
const inputNome = modalEditar.querySelector("input[name='nome']");
const inputUser = modalEditar.querySelector("input[name='user']");
const radiosNivel = modalEditar.querySelectorAll("input[name='nivel']");

// Para enviar depois no update
let idEditando = null;

// Abre modal editar
document.querySelectorAll(".editar").forEach(btn => {
    btn.addEventListener('click', () => {

        const item = btn.closest(".card-funcionario");
        idEditando = item.dataset.id;

        // Preencher com os valores do card
        inputNome.value = item.querySelector("h2").textContent.trim();
        inputUser.value = item.querySelector("p strong + *")?.textContent.trim() 
            || item.querySelector("p:nth-child(2)").textContent.replace("Usuário:", "").trim();

        const nivel = item.querySelector("p:nth-child(3)").textContent.replace("Nível:", "").trim();
        radiosNivel.forEach(r => r.checked = (r.value === nivel));

        modalEditar.classList.add("ativo");
    });
});

// Fechar modal editar
fecharEdicao.addEventListener("click", () => {
    modalEditar.classList.remove("ativo");
});

fechaEdicaoSenha.addEventListener("click", () => {
    modalEditar.classList.remove("ativo");
});

// Abrir modal alterar senha
botaoAlterarSenha.addEventListener("click", () => {
    modalSenha.classList.add("ativo");
});

// Fechar modal senha
modalSenha.querySelector(".btnFechar").addEventListener("click", () => {
    modalSenha.classList.remove("ativo");
});

// Salvar edição dos dados (nome, user, nivel)
modalEditar.querySelector(".btnSalvar").addEventListener("click", async (e) => {
    e.preventDefault();

    let dados = new URLSearchParams();
    dados.append("id", idEditando);
    dados.append("nome", inputNome.value);
    dados.append("user", inputUser.value);

    radiosNivel.forEach(radio => {
        if (radio.checked) dados.append("nivel", radio.value);
    });

    let res = await fetch("../../php/funcionarios/editar_cadastro.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: dados
    });

    let txt = await res.text();
    let json;

    try { json = JSON.parse(txt); }
    catch { return console.error("Resposta inválida:", txt); }

    if (json.sucesso) {
        alert("Funcionário atualizado!");
        location.reload();
    } else {
        alert(json.erro);
    }
});

// Alterar senha
modalSenha.querySelector(".btnSalvar").addEventListener("click", async (e) => {
    e.preventDefault();

    let antiga = modalSenha.querySelector("input[name='sennhaAntiga']").value;
    let nova = modalSenha.querySelector("input[name='novaSenha']").value;
    let conf = modalSenha.querySelector("input[name='confirmacao']").value;

    let dados = new URLSearchParams({
        id: idEditando,
        antiga,
        nova,
        conf
    });

    let res = await fetch("../../php/funcionarios/alterar_senha.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: dados
    });

    let txt = await res.text();
    let json;

    try { json = JSON.parse(txt); }
    catch { return console.error("Resposta inválida:", txt); }

    if (json.sucesso) {
        alert("Senha atualizada!");
        modalSenha.classList.remove("ativo");
    } else {
        alert(json.erro);
    }
});
