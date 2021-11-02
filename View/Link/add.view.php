<link rel="stylesheet" href="/assets/Css/add_link.css">
<div id="main">
    <div id="form">
        <form action="/?page=form&sub=link" method="POST">
            <div>
                <input name="href" type="text" placeholder="http://www.site.fr">
            </div>
            <div>
                <input name="title" type="text" placeholder="title">
            </div>
            <div>
                <input name="name" type="text" placeholder="name">
            </div>
            <div>
                <select name="target" id="">
                    <option value="_self">self</option>
                    <option value="_blank">blank</option>
                    <option value="_parent">parent</option>
                    <option value="_top">top</option>
                </select>
            </div>
            <div>
                <input type="submit">
            </div>
        </form>
    </div>
</div>