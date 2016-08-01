<!DOCTYPE html>
<html>
    <body>
        <h1>Hello from <code>php_orm_router</code>!</h1>
        <p>This is an example route and view</p>

        <div>
            <p>Here are your Get Vars in your URL:</p>
            <pre><?= print_r($getVars,1) ?></pre>
        </div>

        <ul>
            <li>
                <a href="<?= URI_PREFIX ?>/other_view">
                    Go to Other View
                </a>
            </li>
        </ul>
    </body>
</html>
