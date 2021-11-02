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
    console.log(links);
    for(let link of links){
        const linkCont = document.createElement("div");
        linkCont.className = "link-cont";
        main.appendChild(linkCont);
        linkCont.innerHTML = `
                    <div style="background-image: url('${link.img}') " class="link-img">
                        <div class="link-action">
                            <i data-id="${link.id}" class="delete fas fa-times"></i>
                            <i  class="fas fa-pen"></i>
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
    xhr.send(JSON.stringify({"id": id}));
}

checkRole(renderArticle,login);