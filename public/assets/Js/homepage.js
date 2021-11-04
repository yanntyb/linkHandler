function checkRole(callbackRender,callbackNotConnected){
    const xhr = new  XMLHttpRequest;
    xhr.onload = () => {
        const user = JSON.parse(xhr.responseText);
        const reqLink = new XMLHttpRequest();
        switch (user.role){
            case 1:
                reqLink.open("POST", "/index.php?page=link&sub=admin");
                break;
            case 0:
                reqLink.open("POST", "/index.php?page=link&sub=guest");
                break;
            case "notConnected":
                callbackNotConnected();
                break;

        }
        reqLink.onload = () => {
            callbackRender(JSON.parse(reqLink.responseText));

        }
        reqLink.send();
    }
    xhr.open("POST", "/index.php?page=info&sub=role");
    xhr.send();
}

function renderArticle(links){
    let main = document.getElementById('main');
    main.innerHTML = "";
    for(let child of main.childNodes){
        console.log(child);
        child.parentNode.removeChild(child);
    }
    for(let link of links){
        const linkCont = document.createElement("div");
        linkCont.className = "link-cont";
        main.appendChild(linkCont);
        linkCont.dataset.id = link.id;
        linkCont.innerHTML = `
                    <div style="background-image: url('${link.img}') " class="link-img">
                        <div class="link-action">
                            <i data-id="${link.id}" class="delete fas fa-times"></i>
                            <i data-id="${link.id}" class="edit fas fa-pen"></i>
                        </div>
                    </div>
                    <div class="link-title">
                        <a class="link-link" title="${link.title}" href="${link.href}" target="${link.target}">
                            <span class='link-name'>${link.name}</span>
                            <span class="link-user">${link.user.mail}</span>
                        </a>
                    </div>
            `;
        const remove = linkCont.getElementsByClassName("delete")[0];
        remove.addEventListener("click", () => {
            removeLink(remove.dataset.id);
        })

        const edit = linkCont.getElementsByClassName("edit")[0];
        edit.addEventListener("click", () => {
            editLink(edit.dataset.id);
        })

        linkCont.getElementsByClassName("link-title")[0].addEventListener("click",() => {
            countUsed(linkCont.dataset.id);
        })
    }
}

function login(){

}

function removeLink(id){
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "/index.php?page=action&sub=delete&id=" + id);
    xhr.onload = () => {
        checkRole(renderArticle,login);
    }
    xhr.send();
}

function editLink(id){
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "/index.php?page=info&sub=info&id=" + id);
    xhr.onload = () => {
        const link = JSON.parse(xhr.responseText);
        if(link.access === true){
            editModal(link);
        }

    }
    xhr.send();
}

function editModal(link){
    const main = document.getElementById("main");
    let modal = document.getElementById("modal-main")
    if(modal !== null){
        modal.remove();
    }
    modal = document.createElement("div");
    modal.id = "modal-main";
    main.appendChild(modal);
    modal.innerHTML =
        `
        <div id="modal-window">
            <i id="delete" class="fas fa-times"></i>
            <div id="info">
                <div id="href">
                     <span>Href:</span>
                     <div>
                        <input class="input" type="text" value="${link.href}">
                    </div>
                </div>
                <div id="name">
                    <span>Name:</span>
                    <div>
                        <input class="input"  type="text" value="${link.name}">
                    </div>
                </div>
                <div id="title">
                    <span>Title:</span>
                    <div>
                        <input class="input"  type="text" value="${link.title}">
                    </div>
                </div>
                <div id="target">
                    <span>Target:</span>
                    <div>
                        <select class="input"  name="target">
                            <option value="_self">self</option>
                            <option value="_blank" selected>blank</option>
                            <option value="_parent">parent</option>
                        <option value="_top">top</option>
                        </select>
                    </div>
                </div>
            </div>
            <div id="send">
                <input id="modal-send" type="submit" value="Modifier">
            </div>
        </div>
        `
    let remove = modal.querySelector("#delete");
    remove.addEventListener("click", () => {
        closeModal();
    })

    let send = modal.querySelector("#modal-send");
    send.addEventListener("click", () => {
        let data = {};
        let href = modal.querySelector("#href");
        if(href.querySelector(".input").value !== ""){
            data["href"] =  href.querySelector(".input").value;
        }

        let name = modal.querySelector("#name");
        if(name.querySelector(".input").value !== ""){
            data["name"] = name.querySelector(".input").value;
        }

        let title = modal.querySelector("#title");
        if(title.querySelector(".input").value !== ""){
            data["title"] =  title.querySelector(".input").value;
        }

        let target = modal.querySelector("#target");
        data["target"] = target.querySelector(".input").value;

        if(data !== []){
            data["id"] = link.id;
            console.log(data);
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "/index.php?page=link&sub=edit");
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onload = () => {
                checkRole(renderArticle,login);
            }
            xhr.send(JSON.stringify(data));
        }

    })
}

function closeModal() {
    let modal = document.getElementById("modal-main")
    if(modal !== null){
        modal.remove();
    }
}

function countUsed(linkId){
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "/index.php?page=link&sub=addUsed");
    xhr.send(JSON.stringify({"id": linkId}));
}

checkRole(renderArticle,login);