<?

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    echo <<<EOT
    <div id="regTable">
        <div id="response"></div>
        <form id="submitTable" action="backend/forms/eventform.php" method="post">
            <table>
                <tr>
                    <td colspan="2" id="center">Create Event</td>
                </tr>

                <tr>
                    <td colspan="2" id="center"><input class="submitButton" name="regformSubmit" type="submit" value="Create Event" /></td>
                </tr>
            </table>
        </form>
    </div>
EOT;
} ?>