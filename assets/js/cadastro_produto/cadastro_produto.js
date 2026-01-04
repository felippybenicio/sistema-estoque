document.addEventListener("DOMContentLoaded", () => {

    const template = document.getElementById("produtoTemplate").innerHTML;
    const container = document.getElementById("containerProdutos");
    const btnAdd = document.getElementById("btnAddProduto");
    const form = document.getElementById("formCadastroMultiplo");

    addNovoProduto();

    btnAdd.addEventListener("click", () => {
        addNovoProduto();
    });

    function addNovoProduto() {
        const wrapper = document.createElement("div");
        wrapper.innerHTML = template;

        const bloco = wrapper.firstElementChild;

        bloco.querySelector(".btnRemoverItem").addEventListener("click", () => {
            bloco.remove();

            if (container.children.length === 0) {
                addNovoProduto();
            }
        });

        container.appendChild(bloco);
    }

    // 🚀 CAPTURA O SUBMIT E ENVIA VIA FETCH
    form.addEventListener("submit", async (e) => {
        e.preventDefault(); // impede envio tradicional

        // Pega todos os inputs como arrays automáticos
        const formData = new FormData(form);

        try {
            const response = await fetch("../../php/cadastrar_produto/salvar_produtos.php", {
                method: "POST",
                body: formData
            });

            const resultado = await response.text();

            console.log("Resposta do servidor:", resultado);

            // Se quiser redirecionar após salvar:
            window.location.href = "../../php/produtos/painel.php";

        } catch (error) {
            console.error("Erro ao enviar:", error);
            alert("Erro ao salvar os produtos!");
        }
    });

});
