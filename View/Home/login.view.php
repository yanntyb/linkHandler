<link rel="stylesheet" href="/assets/Css/add_link.css">
<div id="main">
    <div id="form">
        <form action="/?page=form&sub=login" method="POST">
            <h3>Login</h3>
            <div>
                <input name="mail" type="text" placeholder="mail">
            </div>
            <div>
                <input name="pass" type="password" placeholder="pass">
            </div>
            <div>
                <input type="submit" value="Ajouter">
            </div>
        </form>
        <form action="?page=form&sub=register" method="POST">
            <h3>Register</h3>
            <div>
                <input name="mail" type="text" placeholder="mail">
            </div>
            <div>
                <input name="pass" type="password" placeholder="pass">
            </div>
            <div>
                <input name="re_pass" type="password" placeholder="re pass">
            </div>
            <div>
                <input type="submit">
            </div>
        </form>
    </div>
</div>