let produtoId = null;

const overlayInfo = document.createElement('div');
overlayInfo.classList.add('overlayInfo');
document.body.appendChild(overlayInfo);

const modalProduto = document.getElementById("modalProduto");
const btnEditar = document.getElementById('editar');
const btnSalvar = document.getElementById('salvarEdicao');
const campos = [
    'modalNome',
    'modalCategoria',
    'modalQtdPorLote',
    'modalQtdDeLote',
    'modalPrecoPagoLote',
    'modalValorRevendaUnidade',
    'modalDescricao'
];

document.querySelectorAll(".item-produto").forEach(item => {
    item.addEventListener("click", async () => {
        produtoId = item.dataset.id;
        if(!produtoId) return;

        try {
            let resposta = await fetch("../../php/produtos/getProdutos.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "id=" + produtoId
            });

            let produto = await resposta.json();

            if(produto.erro){
                alert(produto.erro);
                return;
            }

            document.getElementById("modalNome").innerHTML = `<strong>${produto.nome}</strong>`;
            document.getElementById("modalCodigo").innerHTML = `Código: <strong>${produto.codigo}</strong>`;
            document.getElementById("modalCategoria").innerHTML = `Categoria: <strong>${produto.categoria}</strong>`;
            document.getElementById("modalQtdPorLote").innerHTML = `Qtd. por lote: <strong>${produto.qtd_por_lote}</strong>`;
            document.getElementById("modalQtdDeLote").innerHTML = `Qtde de lotes: <strong>${produto.qtd_de_lote}</strong>`;
            document.getElementById("modalPrecoPagoLote").innerHTML = `Preço pago por lote: <strong>R$ ${produto.preco_pago_lote}</strong>`;
            document.getElementById("modalValorRevendaUnidade").innerHTML = `Revenda por unidade: <strong>R$ ${produto.valor_revenda_unidade}</strong>`;
            document.getElementById("modalDescricao").innerHTML = `Descrição: <strong>${produto.descricao}</strong>`;
            document.getElementById("modalQtdTotalUnidades").innerHTML = `Total de unidades: <strong>${produto.qtd_total_unidades}</strong>`;
            document.getElementById("modalLucroPorLote").innerHTML = `Lucro por lote: <strong>R$ ${produto.lucro_por_lote}</strong>`;
            document.getElementById("modalLucroPorUnidade").innerHTML = `Lucro por unidade: <strong>R$ ${produto.lucro_por_unidade}</strong>`;

            modalProduto.classList.add("ativo");
            overlayInfo.classList.add('ativo');
            modalProduto.style.pointerEvents = "auto";

        } catch(err) {
            console.error("Erro ao buscar produto:", err);
        }
    });
});

function criarTextareaComContador(valor = "", max = 250) {
    // container
    const wrap = document.createElement("div");
    wrap.classList.add("textarea-wrap");

    // textarea
    const textarea = document.createElement("textarea");
    textarea.value = valor;
    textarea.maxLength = max;
    textarea.classList.add("campo-textarea");

    // contador
    const counter = document.createElement("div");
    counter.classList.add("char-counter");
    counter.textContent = `${valor.length}/${max}`;

    // atualiza contador
    function update() {
        const len = textarea.value.length;
        counter.textContent = `${len}/${max}`;
    }

    textarea.addEventListener("input", update);
    textarea.addEventListener("paste", () => setTimeout(update, 0));

    // adiciona no wrap
    wrap.appendChild(textarea);
    wrap.appendChild(counter);

    return wrap;
}


btnEditar.addEventListener('click', () => {
    campos.forEach(id => {
        const p = document.getElementById(id);

        if(id === 'modalNome') {
            const valor = p.textContent;
            const input = document.createElement('input');
            input.type = 'text';
            input.value = valor;
            input.id = id + '_input';
            p.innerHTML = '';
            p.appendChild(input);
        } else {
            const strong = p.querySelector('strong');
            const valor = strong.textContent;

            let input;
            if(id === 'modalDescricao'){
                input = document.createElement('textarea');
                input.value = valor;
                input.maxLength = 250
                
            } else if (id.includes('Qtd') || id.includes('Preco') || id.includes('Valor')){
                input = document.createElement('input');
                input.type = 'number';
                input.value = valor.replace('R$ ', '');
            } else {
                input = document.createElement('input');
                input.type = 'text';
                input.value = valor;
            }

            input.id = id + '_input';
            input.classList.add('input-modal');
            strong.replaceWith(input);
        }
    });

    btnEditar.style.display = 'none';
    btnSalvar.style.display = 'block';
});

btnSalvar.addEventListener('click', async () => {
    const dados = {
        id: produtoId,
        nome: document.getElementById('modalNome_input').value,
        categoria: document.getElementById('modalCategoria_input').value,
        qtd_por_lote: document.getElementById('modalQtdPorLote_input').value,
        qtd_de_lote: document.getElementById('modalQtdDeLote_input').value,
        preco_pago_lote: document.getElementById('modalPrecoPagoLote_input').value,
        valor_revenda_unidade: document.getElementById('modalValorRevendaUnidade_input').value,
        descricao: document.getElementById('modalDescricao_input').value
    };

    try {
        let resposta = await fetch('../../php/produtos/editar.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams(dados)
        });

        let res = await resposta.json();

        if(res.sucesso){
            alert(res.msg);
            modalProduto.classList.remove('ativo');
            overlayInfo.classList.remove('ativo');
            modalProduto.style.pointerEvents = "none";
            btnEditar.style.display = 'block';
            btnSalvar.style.display = 'none';
            location.reload();
            
        } else {
            alert(res.erro);
        }

    } catch(err){
        console.error("Erro ao salvar edição:", err);
    }
});

document.getElementById("fecharModal").addEventListener("click", () => {
    modalProduto.classList.remove("ativo");
    overlayInfo.classList.remove('ativo');
    modalProduto.style.pointerEvents = "none";
    btnEditar.style.display = 'block';
    btnSalvar.style.display = 'none';
});


document.addEventListener('DOMContentLoaded', () => {
    const modalProduto = document.getElementById('modalProduto');
    const overlayInfo = document.createElement('div');
    overlayInfo.classList.add('overlayInfo');
    document.body.appendChild(overlayInfo);

    let produtoAtual = null;

    // Abrir modal ao clicar no item
    document.querySelectorAll('.item-produto').forEach(item => {
        item.addEventListener('click', async () => {
            const id = item.dataset.id;
            if (!id) return;

            try {
                const res = await fetch("/sistema-estoque/php/produtos/getProdutos.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: new URLSearchParams({id})
                });
                const produto = await res.json();
                if(produto.erro) { alert(produto.erro); return; }

                produtoAtual = produto;

                document.getElementById("modalNome").innerHTML = `<strong>${produto.nome}</strong>`;
                document.getElementById("modalCodigo").innerHTML = `Código: <strong>${produto.codigo}</strong>`;
                document.getElementById("modalCategoria").innerHTML = `Categoria: <strong>${produto.categoria}</strong>`;
                document.getElementById("modalQtdPorLote").innerHTML = `Qtd. por lote: <strong>${produto.qtd_por_lote}</strong>`;
                document.getElementById("modalQtdDeLote").innerHTML = `Qtde de lotes inteiros: <strong>${produto.qtd_de_lote}</strong>`;
                document.getElementById("modalPrecoPagoLote").innerHTML = `Preço pago por lote: <strong>R$ ${produto.preco_pago_lote}</strong>`;
                document.getElementById("modalValorRevendaUnidade").innerHTML = `Revenda por unidade: <strong>R$ ${produto.valor_revenda_unidade}</strong>`;
                document.getElementById("modalDescricao").innerHTML = `Descrição:<br><strong>${produto.descricao}</strong>`;
                document.getElementById("modalQtdTotalUnidades").innerHTML = `Total de unidades: <strong>${produto.qtd_total_unidades}</strong>`;
                document.getElementById("modalLucroPorLote").innerHTML = `Lucro por lote:  <strong>R$ ${produto.lucro_por_lote}</strong>`;
                document.getElementById("modalLucroPorUnidade").innerHTML = `Lucro por unidade: <strong>R$ ${produto.lucro_por_unidade}</strong>`;

                modalProduto.classList.add('ativo');
                overlayInfo.classList.add('ativo');
                modalProduto.style.pointerEvents = "auto";

            } catch(err) {
                console.error("Erro ao buscar produto:", err);
            }
        });
    });

    // Fechar modal
    document.getElementById('fecharModal').addEventListener('click', () => {
        modalProduto.classList.remove('ativo');
        overlayInfo.classList.remove('ativo');
        modalProduto.style.pointerEvents = "none";
    });

    // EDITAR
    const btnEditar = document.getElementById('editar');
    const btnSalvar = document.getElementById('salvarEdicao');
    const campos = ['modalNome','modalCategoria','modalQtdPorLote','modalQtdDeLote','modalPrecoPagoLote','modalValorRevendaUnidade','modalDescricao'];

    btnEditar.addEventListener('click', () => {

    campos.forEach(id => {
        const p = document.getElementById(id);

        // pega o <strong> dentro do parágrafo
        const strong = p.querySelector("strong");
        if (!strong) return;

        const valor = strong.textContent.trim();

        // cria input ou textarea
        const input = (id === 'modalDescricao')
            ? document.createElement('textarea')
            : document.createElement('input');

        // tipo correto
        input.type = (id.includes('Qtd') || id.includes('Preco') || id.includes('Valor'))
            ? 'number'
            : 'text';

        input.value = valor.replace('R$ ', '');
        input.classList.add('input-modal');
        input.id = id + "_input";

        // substitui APENAS o <strong>
        strong.replaceWith(input);
    });

    btnEditar.style.display = 'none';
    btnSalvar.style.display = 'block';
});


    btnSalvar.addEventListener('click', async () => {
        if (!produtoAtual) return;

        const dados = { id: produtoAtual.id };

        campos.forEach(id => {
            const input = document.getElementById(id + "_input");
            dados[id] = input ? input.value : "";
        });

        try {
            const res = await fetch("/sistema-estoque/php/produtos/editar.php", {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams(dados)
            });

            const data = await res.json();

            if (data.sucesso) {
                location.reload(); // opcional — só para recarregar dados atualizados
            } else {
                alert(data.erro);
            }

        } catch (err) {
            console.error("Erro ao salvar edição:", err);
        }
    });




    async function excluirProduto(id, item = null) {
    if (!confirm("Deseja realmente excluir este produto?")) return;

    try {
        let res = await fetch('../../php/produtos/excluir_produtos.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({id})
        });

        let text = await res.text();
        let json;
        try { json = JSON.parse(text); } 
        catch(e){ console.error("Resposta não é JSON:", text); return; }

        if(json.sucesso){
            if(item) item.remove(); // remove da lista
            const modal = document.getElementById('modalProduto');
            location.reload();
            if(modal.classList.contains('ativo') && produtoId == id){
                modal.classList.remove('ativo');
                overlayInfo.classList.remove('ativo');
            }
        } else {
            alert(json.erro);
        }
    } catch(err){
        console.error("Erro ao excluir:", err);
    }
}

// Botão dentro da lista
document.querySelectorAll('#deletarProduto2').forEach(btn => {
    btn.addEventListener('click', e => {
        e.stopPropagation();
        const item = btn.closest('.item-produto');
        const id = item.dataset.id;
        excluirProduto(id, item);
    });
});

// Botão dentro do modal
document.getElementById('deletarProduto1').addEventListener('click', () => {
    if (!produtoId) return;
    const item = document.querySelector(`.item-produto[data-id="${produtoId}"]`);
    excluirProduto(produtoId, item);
});


    
});



