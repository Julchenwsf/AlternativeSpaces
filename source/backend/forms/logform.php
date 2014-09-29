<?

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once("../db/DBUsers.php");
    include_once("../functions/log.php");

    $status = checkLogin($_POST["username"], $_POST["password"]);
    $response = array("success" => !empty($status), "response" => (!empty($status)) ? array() : array("Incorrect username/password"));
    login($status["user_id"], $status["username"], $status["first_name"], $status["last_name"]);
    echo json_encode($response);

} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    echo <<<EOT
    <div id="regTable">
        <div id="response"></div>
        <form id="submitTable" action="backend/forms/logform.php" method="post">
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
    $('#submitTable').submit(function (ev) {
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