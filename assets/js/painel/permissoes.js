document.addEventListener("DOMContentLoaded", () => {

    // Leitor – remove tudo que edita
    if (NIVEL_USUARIO === "leitor") {

        // Esconde botões de criar, editar e deletar
        document.getElementById("btnAbrirCadastro").remove();
        document.querySelectorAll("#deletarProduto2").forEach(b => b.remove());

        // Dentro do modal
        const btnEdicoes = document.getElementById("btnEdcoes");
        if (btnEdicoes) btnEdicoes.style.display = "none";
    }

    // Operador – pode mexer produtos e entradas, mas NÃO usuários e saídas
    if (NIVEL_USUARIO === "operador") {

        // Esconder Saídas e Usuários
        const botoes = document.querySelectorAll("nav ul li button");

        botoes.forEach(btn => {

            if (btn.innerText === "Usuários" || btn.innerText === "Saídas") {
                btn.remove();
            }
        });
    }

    // Admin – não precisa fazer nada
});
