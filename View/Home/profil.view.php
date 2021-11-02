<link rel="stylesheet" href="/assets/Css/add_link.css">
<div id="main">
    <div id="form">
        <form action="/?page=form&sub=edit_profil" method="POST">
            <div>
                <h2>Pass</h2>
                <input name="oldPass" type="password" placeholder="old pass">
                <input name="newPass" type="password" placeholder="new pass">
            </div>
            <div>
                <h2>API</h2>
                <div>
                    <h3>Embeb API key</h3>
                    <input disabled type="text" value="<?= $var["user"]->getApikey() ?>">
                    <input name="apiKey" placeholder="Embeb API key">
                </div>
                <div>
                    <h3>API secret</h3>
                    <input type="text" disabled value="<?= $var["user"]->getApisecret() ?>">
                    <input type="text" name="apiSecret" placeholder="Api secret">
                </div>

            </div>
            <div>
                <input type="submit" value="Ajouter">
            </div>
        </form>
    </div>
</div>