// -----------------------------
// FUNÇÃO PARA BUSCAR PRODUTO
// -----------------------------
async function buscarProduto(termo) {
    try {
        let res = await fetch("../../php/saida/buscar_produto.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ termo })
        });

        let txt = await res.text();
        let json;

        try { json = JSON.parse(txt); }
        catch(e){
            console.error("Resposta inválida:", txt);
            return null;
        }

        if (json.erro) {
            alert(json.erro);
            return null;
        }

        return json;

    } catch (err) {
        console.error("Erro ao buscar produto:", err);
        return null;
    }
}



// função só para criar um bloco vazio
function criarBlocoEmBranco() {
    const lista = document.getElementById("listaItens");
    const novo = document.createElement("div");
    novo.className = "itemSaida";
    novo.innerHTML = `
        <label>
            Buscar produto por nome ou código
            <input type="text" name="produto[]">
        </label>
        <label>
            Quantidade de unidades
            <input type="number" min="1" name="quantidade[]">
        </label>
    `;
    lista.appendChild(novo);
}

// adiciona listener apenas **uma vez**
document.getElementById("addItem").addEventListener("click", async () => {
    const lista = document.getElementById("listaItens");
    const listaFormatados = document.getElementById("listaItensFormatados");

    const item = lista.lastElementChild;

    const termo = item.querySelector("input[name='produto[]']").value.trim();
    const qtd   = parseInt(item.querySelector("input[name='quantidade[]']").value.trim());

    if (!termo) { alert("Digite o nome OU código."); return; }
    if (!qtd || qtd <= 0) { alert("Quantidade inválida."); return; }

    const prod = await buscarProduto(termo);
    if (!prod) return;

    // 👉 criar nova div formatada
    const novoItem = document.createElement("div");
    novoItem.classList.add("item-formatado");

    novoItem.innerHTML = `
        <div class="info-formatado">
            <p><strong>Nome:</strong> ${prod.nome}</p>
            <p><strong>Código:</strong> ${prod.codigo}</p>
            <p><strong>Estoque atual:</strong> ${prod.quantidade_total}</p>
            <p><strong>Remover:</strong> ${qtd}</p>

            <input type="hidden" name="id_produto[]" value="${prod.id}">
            <input type="hidden" name="quantidade[]" value="${qtd}">
        

            <button type="button" class="removerItem">X</button>
        </div>
    `;

    // 👉 botão remover
    novoItem.querySelector(".removerItem").addEventListener("click", () => {
        novoItem.remove();
    });

    // 👉 adiciona nova div na lista formatada
    listaFormatados.appendChild(novoItem);

    // 👉 limpa o bloco de entrada para o próximo item
    item.querySelector("input[name='produto[]']").value = "";
    item.querySelector("input[name='quantidade[]']").value = "";
});


// inicializa o primeiro bloco
if (listaItens.children.length === 0) {
    criarBlocoEmBranco();
}




