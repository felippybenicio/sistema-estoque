async function excluirProduto(id, item = null) {
    if (!confirm("Deseja realmente excluir este usuário?")) return;

    try {
        let res = await fetch('../../php/funcionarios/excluir_func.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({id})
        });

        let text = await res.text();
        let json;

        try { 
            json = JSON.parse(text); 
        } catch(e){ 
            console.error("Resposta não é JSON:", text); 
            return; 
        }

        if (json.sucesso) {
            if (item) item.remove();
            location.reload();
        } else {
            alert(json.erro);
        }

    } catch(err){
        console.error("Erro ao excluir:", err);
    }
}

document.querySelectorAll('.excluir').forEach(btn => {
    btn.addEventListener('click', e => {
        e.stopPropagation();
        const item = btn.closest('.card-funcionario');
        const id = item.dataset.id;
        excluirProduto(id, item);
    });
});
