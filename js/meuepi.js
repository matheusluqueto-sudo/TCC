const epis=document.querySelectorAll(".epi-arrastavel");
const zonas=document.querySelectorAll(".drop-zone");
const statusLista=document.getElementById("statusLista");
let usados={};

epis.forEach(epi=>{
    epi.addEventListener("dragstart",e=>{
        e.dataTransfer.setData("tipo",epi.dataset.tipo);
        e.dataTransfer.setData("nome",epi.dataset.nome);
        e.dataTransfer.setData("destino",epi.dataset.destino);
    });
});

zonas.forEach(zona=>{
    zona.addEventListener("dragover",e=>{
        e.preventDefault();
        zona.classList.add("drag-over");
    });

    zona.addEventListener("dragleave",()=>{
        zona.classList.remove("drag-over");
    });

    zona.addEventListener("drop",e=>{
        e.preventDefault();
        zona.classList.remove("drag-over");

        const tipo=e.dataTransfer.getData("tipo");
        const nome=e.dataTransfer.getData("nome");
        const destino=e.dataTransfer.getData("destino");

        if(destino!==zona.dataset.destino)return;

        usados[tipo]=nome;

        document.querySelectorAll(".epi-no-corpo").forEach(item=>{
            item.classList.remove("mostrar");
        });

        if(tipo==="oculos"){
            document.getElementById("uso-oculos").classList.add("mostrar");
        }

        if(tipo==="auricular"){
            document.getElementById("uso-auricular").classList.add("mostrar");
        }

        if(tipo==="luvas"){
            document.getElementById("uso-luva-esq").classList.add("mostrar");
            document.getElementById("uso-luva-dir").classList.add("mostrar");
        }

        atualizarStatus();
    });
});

function atualizarStatus(){
    statusLista.innerHTML="";

    Object.values(usados).forEach(nome=>{
        const item=document.createElement("div");
        item.className="status-item usando";
        item.innerHTML='<i class="fa-solid fa-circle-check"></i> '+nome+' em uso';
        statusLista.appendChild(item);
    });

    if(!Object.keys(usados).length){
        statusLista.innerHTML='<div class="status-item"><i class="fa-solid fa-circle-info"></i> Arraste um EPI para começar.</div>';
    }
}