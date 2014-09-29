<?

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include_once("../db/DBUsers.php");
    $status = addUser($_POST["username"], $_POST["password"], $_POST["fname"], $_POST["lname"], $_POST["gender"]);
    $response = array("success" => empty($status), "response" => $status);
    echo json_encode($response);

} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    echo <<<EOT
    <div id="regTable">
        <div id="response"></div>
        <form id="submitTable" action="backend/forms/regform.php" method="post">
            <table>
                <tr>
                    <td colspan="2" id="center">Register</td>
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
                    <td colspan="2" id="center"><input class="submitButton" name="regformSubmit" type="submit" value="Submit" /></td>
                </tr>
            </table>
        </form>
    </div>
    <script type="text/javascript">
    $('#submitTable').submit(function (ev) {
        $.ajax({
            type: "POST",
            url: "backend/forms/regform.php",
            data: $("#submitTable").serialize(),
            dataType: "JSON",
            success: function(data) {
               if(data["success"]) {
                   $("#submitTable").html(""); //Hide the form
                   $("#response").html('<div class="success">Success!</div>'); //TODO: Write better message
               } else {
                   var out = "";
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