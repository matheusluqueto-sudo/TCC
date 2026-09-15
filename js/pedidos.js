document.addEventListener("DOMContentLoaded", function () {
    const filtro = document.getElementById("filtroStatus");
    const pedidos = document.querySelectorAll(".pedido-item");
    const modal = document.getElementById("modalPedido");
    const fechar = document.getElementById("fecharModal");

    if (filtro) {
        filtro.addEventListener("change", function () {
            const statusSelecionado = this.value;
            pedidos.forEach(function (pedido) {
                const status = pedido.getAttribute("data-status");
                pedido.style.display = statusSelecionado === "todos" || status === statusSelecionado ? "flex" : "none";
            });
        });
    }

    pedidos.forEach(function (pedido) {
        pedido.addEventListener("click", function () {
            document.getElementById("modalEpi").textContent = pedido.dataset.epi;
            document.getElementById("modalFuncionario").textContent = pedido.dataset.funcionario;
            document.getElementById("modalId").textContent = "#" + pedido.dataset.id;
            document.getElementById("modalData").textContent = pedido.dataset.data;
            document.getElementById("modalStatus").textContent = pedido.dataset.statusText;
            document.getElementById("modalJustificativa").textContent = pedido.dataset.justificativa || "Nenhuma justificativa informada";
            document.getElementById("modalIdInput").value = pedido.dataset.id;
            document.getElementById("modalIdInputRecusar").value = pedido.dataset.id;
            modal.classList.add("aberto");
        });
    });

    if (fechar) {
        fechar.addEventListener("click", function () {
            modal.classList.remove("aberto");
        });
    }

    if (modal) {
        modal.addEventListener("click", function (e) {
            if (e.target === modal) modal.classList.remove("aberto");
        });
    }
});