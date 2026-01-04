// ------------------------------
// ELEMENTOS
// ------------------------------
const formSaida = document.getElementById("formSaida");
const listaItens = document.getElementById("listaItens");
const btnAddItem = document.getElementById("addItem");

// Inicializa com pelo menos um item
if (listaItens.children.length === 0) {
    criarBlocoEmBranco();
}

// ------------------------------
// FUNÇÃO PARA PROCESSAR SAÍDA
// ------------------------------
async function processarSaida(dados) {
    try {
        const res = await fetch("../../php/saida/saida_produto.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: dados,
            credentials: "same-origin"
        });

        const text = await res.text();
        let json;
        try { 
            json = JSON.parse(text);
        } catch (e) { 
            console.error("Resposta inválida do PHP:", text);
            alert("Erro inesperado ao processar a saída.");
            return;
        }

        if (json.sucesso) {
            alert("Saída lançada com sucesso!");
            listaItens.innerHTML = "";
            criarBlocoEmBranco();
        } else {
            alert("Erro: " + json.erro);
        }

    } catch (err) {
        console.error("Erro ao processar saída:", err);
        alert("Erro ao processar saída.");
    }
}


// ------------------------------
// SUBMIT DO FORMULÁRIO
// ------------------------------
formSaida.addEventListener("submit", e => {
    e.preventDefault();

    const blocos = listaItens.querySelectorAll(".item-formatado");
    if (blocos.length === 0) {
        alert("Nenhum item válido para lançar saída. Formate os itens antes de enviar.");
        return;
    }

    const dados = new URLSearchParams();

    blocos.forEach(item => {
        const id = item.querySelector("input[name='id_produto[]']")?.value;
        const qtd = item.querySelector("input[name='quantidade[]']")?.value;

        if (!id || !qtd || qtd <= 0) return;

        dados.append("id_produto[]", id);
        dados.append("quantidade[]", qtd);
    });

    processarSaida(dados);
});






