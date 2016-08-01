<html>
    <body>
        <h1>Other View</h1>
        <p>
            Hello!
        </p>
        <p>
            You reached this other view
            because you went to the URI
            <code><?= $_SERVER['REQUEST_URI'] ?></code>
        </p>

        <ul>
            <li>
                <a href="<?= URI_PREFIX ?>">
                    Back to Index Page
                </a>
            </li>
        </ul>
    </body>
</html>
