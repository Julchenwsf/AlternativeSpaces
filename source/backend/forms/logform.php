<?

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once("../db/DBUsers.php");
    //TODO: Run log in procedure

} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    //TODO: Make login form
    echo <<<EOT
    <div id="regTable">
        <div id="response"></div>
        <form id="submitTable" action="backend/forms/logform.php" method="post">
            <table>
                <tr>
                    <td>Login</td>
                </tr>
            </table>
        </form>
    </div>
EOT;
} ?>