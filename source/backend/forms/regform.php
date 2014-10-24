<?

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once("../db/DBUsers.php");
    include_once("../functions/log.php");
    $status = addUser($_POST["username"], $_POST["password"], $_POST["fname"], $_POST["lname"], $_POST["gender"]);
    $response = array("success" => empty($status), "response" => $status);
    $status = loginPlainText($_POST["username"], $_POST["password"]);
    echo json_encode($response);

} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    echo <<<EOT
    <div class="submitTable">
        <div id="response"></div>
        <form id="registerForm" action="backend/forms/regform.php" method="post">
            <table>
                <tr>
                    <th colspan="2">Sign up</th>
                </tr>
                <tr>
                    <td><input type="text" name="fname" placeholder="First name" /></td>
                    <td><input type="text" name="lname" placeholder="Last name" /></td>
                </tr>
                <tr>
                    <td><input type="text" name="username" placeholder="Username" /></td>
                    <td><input type="password" name="password" placeholder="Password" /></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <select name="gender">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" id="center"><button class="submitButton" name="regformSubmit" type="submit">Sign up</button></td>
                </tr>
            </table>
        </form>
    </div>
    <script type="text/javascript">
    $('#registerForm').submit(function (ev) {
        $.ajax({
            type: "POST",
            url: "backend/forms/regform.php",
            data: $("#registerForm").serialize(),
            dataType: "JSON",
            success: function(data) {
               if(data["success"]) {
                   location.reload(true);
               } else {
                   var out = "";
                   for(var error in data["response"]) {
                       out += "<li>" + data["response"][error] + "</li>";
                   }
                   $("#response").html('<ul class="error">' + out + "</ul>");
               }
            }
        });
        ev.preventDefault();
    });
    </script>
EOT;
} ?>