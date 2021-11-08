const addButton = document.getElementById("add-link");
const statLink = document.getElementById("stat");

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
                            <i data-id="${link.id}" class="stat fas fa-chart-pie"></i>
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

        const stat = linkCont.getElementsByClassName("stat")[0];
        stat.addEventListener("click", () => {
            statModal(stat.dataset.id);
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

function statModal(id){
    let xhr = new XMLHttpRequest();
    xhr.open("POST","/index.php?page=info&sub=statSingle")
    xhr.onload = () => {
        const response = JSON.parse(xhr.responseText);
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
            <canvas id="chart"></canvas>
        </div>
        `;

        let ctx = document.getElementById("chart").getContext("2d");
        let Chart2 = new Chart(ctx, {
            type : 'bar',
            data: {
                labels : ['Red'],
                datasets: [{
                    label: "Number of clic",
                    data: [response.clic],
                    backgroundColor: [
                        "blue",
                    ],
                    borderColor: [
                        "red",
                    ],
                }]
            }
        });
        let remove = modal.querySelector("#delete");
        remove.addEventListener("click", () => {
            closeModal();
        })
    }
    xhr.send(JSON.stringify({"id": id}));
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
        `;
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

function addLink(){
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
                        <input class="input" type="text">
                    </div>
                </div>
                <div id="name">
                    <span>Name:</span>
                    <div>
                        <input class="input"  type="text">
                    </div>
                </div>
                <div id="title">
                    <span>Title:</span>
                    <div>
                        <input class="input"  type="text">
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
        `;

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

        if(Object.keys(data).length === 4){
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "/index.php?page=link&sub=add");
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onload = () => {
                checkRole(renderArticle,login);
            }
            xhr.send(JSON.stringify(data));
        }

    })

}

function globalStat(){
    let xhr = new XMLHttpRequest();
    xhr.open("POST","/index.php?page=stat&sub=render");
    xhr.onload = () => {
        const response = JSON.parse(xhr.responseText);
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
            <canvas id="chartName"></canvas>
            <canvas id="chartUser"></canvas>
        </div>
        `;

        let ctx = document.getElementById("chartName").getContext("2d");
        let data = [];
        for(let name of  Object.keys(response.by_name)){
            data.push(response.by_name[name]);
        }
        let ChartName = new Chart(ctx, {
            type : 'bar',
            data: {
                labels: Object.keys(response.by_name),
                datasets: [{
                    label: "Nombre de lien identique",
                    data: data,
                    backgroundColor: ["Red"]
                }]
            }
        });

        ctx = document.getElementById("chartUser").getContext("2d");
        data = [];
        for(let name of  Object.keys(response.by_user)){
            data.push(response.by_user[name]);
        }
        let ChartUser = new Chart(ctx, {
            type : 'bar',
            data: {
                labels: Object.keys(response.by_user),
                datasets: [{
                    label: "Nombre de lien identique",
                    data: data,
                    backgroundColor: ["Red"]
                }]
            }
        });


        let remove = modal.querySelector("#delete");
        remove.addEventListener("click", () => {
            closeModal();
        })
    }
    xhr.send();
}

checkRole(renderArticle,login);
addButton.addEventListener("click",(e) => {
    e.preventDefault();
    addLink();
});
statLink.addEventListener("click", (e) => {
    e.preventDefault();
    globalStat();
})