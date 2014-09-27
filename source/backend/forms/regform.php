<?

function getForm() {
    return <<<EOT
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/validateForm.js"></script>
    <div id="regTable">
    <div id="errors"></div>
        <form name="reg" action="backend/db/DBUsers.php" onsubmit="return validateForm()" method="post">
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
                    <td colspan="2" id="center"><input class="submitButton" name="submit" type="submit" value="Submit" /></td>
                </tr>
            </table>
        </form>
    </div>
EOT;
} ?>