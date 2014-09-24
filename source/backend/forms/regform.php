<?

function getForm() {
    return <<<EOT
    <script type="text/javascript" src="js/validateForm.js"></script>
    <form name="reg" action="backend/db/DBUsers.php" onsubmit="return validateForm()" method="post">
        <div id="regTable">
            <table >
                <tr>
                    <td colspan="2">Register Here</td>
                </tr>
                <tr>
                    <td width="95"><div align="right">First Name:</div></td>
                    <td width="171"><input type="text" name="fname" /></td>
                </tr>
                <tr>
                    <td><div align="right">Last Name:</div></td>
                    <td><input type="text" name="lname" /></td>
                </tr>
                <tr>
                    <td><div align="right">Gender:</div></td>
                    <td><input type="text" name="mname" /></td>
                </tr>
                <tr>
                    <td><div align="right">Address:</div></td>
                    <td><input type="text" name="address" /></td>
                </tr>
                <tr>
                    <td><div align="right">Contact No.:</div></td>
                    <td><input type="text" name="contact" /></td>
                </tr>
                <tr>
                    <td><div align="right">Picture:</div></td>
                    <td><input type="text" name="pic" /></td>
                </tr>
                <tr>
                    <td><div align="right">Username:</div></td>
                    <td><input type="text" name="username" /></td>
                </tr>
                <tr>
                    <td><div align="right">Password:</div></td>
                    <td><input type="text" name="password" /></td>
                </tr>
                <tr>
                    <td><div align="right"></div></td>
                    <td><input name="submit" type="submit" value="Submit" /></td>
                </tr>
            </table>
        </div>
    </form>

EOT;
} ?>