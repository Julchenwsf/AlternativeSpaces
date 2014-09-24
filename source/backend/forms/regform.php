<?

function getForm() {
    return <<<EOT
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/validateForm.js"></script>
    <div id="regTable">
    <div id="errors"></div>
        <form name="reg" onsubmit="return validateForm()" method="post">
            <table>
                <tr>
                    <td id="center">Register Here</td>
                </tr>
                <tr>
                    <td><label>First Name: </label><input type="text" name="fname" /></td>
                </tr>
                <tr>
                    <td><label>Last name: </label><input type="text" name="lname" /></td>
                </tr>
                <tr>
                    <td><label>Username: </label><input type="text" name="username" /></td>
                </tr>
                <tr>
                    <td><label>Password: </label><input type="password" name="password" /></td>
                </tr>
                <tr>
                    <td><label>Gender: : </label><input type="text" name="gender" /></td>
                </tr>
                <tr>
                    <td><label>Address: </label><input type="text" name="address" /></td>
                </tr>
                <tr>
                    <td><label>Profile picture: </label><input type="text" name="pic" /></td>
                </tr>
                <tr>
                    <td id="center"><input id="center2" name="submit" type="submit" value="Submit" /></td>
                </tr>
            </table>
        </form>
    </div>
EOT;
} ?>