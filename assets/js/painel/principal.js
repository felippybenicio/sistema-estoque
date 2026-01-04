const pesquisar = document.getElementById('pesquisar');
const lista = document.querySelectorAll('.item-produto');
const modal = document.getElementById('modalProduto');


pesquisar.addEventListener('input', () => {
    const valor = pesquisar.value.toLowerCase();
    lista.forEach(produto => {
        const nome = produto.querySelector('.nome').textContent.toLowerCase();
        const codigo = produto.querySelector('.codigo').textContent.toLowerCase();
        produto.style.display = nome.includes(valor) || codigo.includes(valor) ? 'block' : 'none';
    });
});





