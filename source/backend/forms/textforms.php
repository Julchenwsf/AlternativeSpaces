<?php

echo '<div style="width:800px;min-height:400px">';
switch($_GET["id"]) {
    case "about":
        echo <<<EOT
            <h2>About</h2>
            <hr/>
            Some text :)
EOT;
        break;
}

echo "</div>";