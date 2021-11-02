<link rel="stylesheet" href="/assets/Css/home.css">
<link rel="stylesheet" href="/assets/Css/modal_edit.css">
<div id="action">
    <?php
    if(isset($_SESSION["user"])){
        $user = unserialize($_SESSION["user"])?>
        <a id="action-left"  href="/?page=home&sub=add"><i class="fas fa-plus-square"></i><span>Ajouter un lien</span></a>
        <div>
            <a href="/?page=home&sub=deco">Déconnexion</a>
            <a href="/?page=home&sub=profil">Profil</a>
            <a href="/?page=stat&sub=render">Statistique</a>
        </div>
        <?php
    }
    else{?>
        <a id="action-right" href="/?page=home&sub=login"><i class="fas fa-user-circle"></i></a><?php
    }
    ?>
</div>
<div id="main"></div>
<script src="/assets/Js/homepage.js" type="module"></script>
