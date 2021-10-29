<link rel="stylesheet" href="/assets/Css/home.css">
<div id="action">
    <?php

    use Yanntyb\App\Model\Classes\Entity\Image;

    if(isset($_SESSION["user"])){
        $user = unserialize($_SESSION["user"])?>
        <a id="action-left"  href="/?page=home&sub=add"><i class="fas fa-plus-square"></i><span>Ajouter un lien</span></a>
        <div>
            <a href="/?page=home&sub=deco">Déconnexion</a>
            <a href="/?page=home&sub=profil">Profil</a>
        </div>
        <?php
    }
    else{?>
        <a id="action-right" href="/?page=home&sub=login"><i class="fas fa-user-circle"></i></a><?php
    }
    ?>

</div>
<div id="main">
    <?php
    if($var["links"] && count($var["links"]) > 0){
        foreach($var["links"] as $link){?>
            <div class="link-cont">
                <div style="background-image: url(<?= (new Image($link))->getImgLink() ?>); background-size: 100%" class="link-img">
                    <div class="link-action"><?php
                        if(isset($_SESSION["user"])){?>
                            <a href="/index.php?page=action&sub=delete&id=<?= $link->getId() ?>" class="fas fa-times"></a>
                            <a href="/index.php?page=action&sub=edit&id=<?= $link->getId() ?>" class="fas fa-pen"></a><?php
                        }?>
                    </div>
                </div>
                <div class="link-title">
                    <a class="link-link" title="<?= $link->getTitle() ?>" href="<?= $link->getHref() ?>" target="<?= $link->getTarget() ?>"><?php
                        if($user->getAdmin() === 1){
                            if($user->getId() === $link->getUser()->getId()){?>
                                <span class='link-name'><?= $link->getName()?></span>
                                <span class="link-user">(Vous)</span><?php
                            }
                            else{?>
                                <span class='link-name'><?= $link->getName()?></span>
                                <span class="link-user"><?= $link->getUser()->getMail() ?></span><?php
                            }
                        }
                        else{?>
                            <span class='link-name'><?= $link->getName()?></span><?php
                        }
                        ?>
                    </a>
                </div>
            </div>
        <?php
        }
    }?>
</div>