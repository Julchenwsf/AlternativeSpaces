<?

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once("../functions/log.php");

    $status = loginPlainText($_POST["username"], $_POST["password"]);
    $response = array("success" => !empty($status), "response" => (!empty($status)) ? array() : array("Incorrect username/password"));
    echo json_encode($response);

} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    echo <<<EOT
    <div id="regTable" class="submitTable">
        <div id="response"></div>
        <form id="loginForm" action="backend/forms/logform.php" method="post">
            <table>
                <tr>
                    <td colspan="2" id="center">Login</td>
                </tr>
                <tr>
                    <td><input type="text" name="username" placeholder="Username" /></td>
                    <td><input type="password" name="password" placeholder="Password" /></td>
                </tr>
                <tr>
                    <td colspan="2" id="center"><input class="submitButton" name="logformSubmit" type="submit" value="Login" /></td>
                </tr>
            </table>
        </form>
    </div>
    <script type="text/javascript">
    $('#loginForm').submit(function (ev) {
        $.ajax({
            type: "POST",
            url: "backend/forms/logform.php",
            data: $("#submitTable").serialize(),
            dataType: "JSON",
            success: function(data) {
               if(data["success"]) {
                   location.reload(true);
               } else {
                   var out = "";
                   console.log(data["response"]);
                   for(var error in data["response"]) {
                       out += "<li>" + data["response"][error] + "</li>";
                   }
                   $("#response").html('<div class="error"><ul>' + out + "</ul></div>");
               }
            }
        });
        ev.preventDefault();
    });
    </script>
EOT;
} ?>