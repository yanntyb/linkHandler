<link rel="stylesheet" href="/assets/Css/add_link.css">
<div id="main">
    <div id="form">
        <form action="/?page=form&sub=link_edit" method="POST">
            <div>
                <input name="href" type="text" placeholder="link" value="<?= $var["link"]->getHref() ?>">
            </div>
            <div>
                <input name="title" type="text" placeholder="title" value="<?= $var["link"]->getTitle() ?>">
            </div>
            <div>
                <input name="name" type="text" placeholder="name" value="<?= $var["link"]->getName() ?>">
            </div>
            <div>
                <select name="target" id="">
                    <option value="_self" <?php if($var["link"]->getTarget() === "_self"){echo "selected";} ?>>self</option>
                    <option value="_blank" <?php if($var["link"]->getTarget() === "_blank"){echo "selected";} ?>>blank</option>
                    <option value="_parent" <?php if($var["link"]->getTarget() === "_parent"){echo "selected";} ?>>parent</option>
                    <option value="_top" <?php if($var["link"]->getTarget() === "_top"){echo "selected";} ?>>top</option>
                </select>
            </div>
            <div>
                <input type="text" name="id" class="hidden" value="<?= $var["link"]->getId() ?>">
                <input type="submit" value="Modifier">
            </div>
        </form>
    </div>
</div>