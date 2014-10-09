<?

if ($_SERVER['REQUEST_METHOD'] == 'POST') {


} else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    echo <<<EOT
    <div id="regTable">
        <div id="response"></div>
        <form id="submitTable" action="backend/forms/eventform.php" method="post">
            <table>
                <tr>
                    <td colspan="2" id="center">Event Creation</td>
                </tr>
                    <td><input type="text" Event name="Ename" placeholder="Event name" /></td>
                    <td><input type="text" Place="Loc" placeholder="Location" /></td>

                <tr>

                   <td>
                    Month<select name=month >

                    <option value='01'>January</option>
                    <option value='02'>February</option>
                    <option value='03'>March</option>
                    <option value='04'>April</option>
                    <option value='05'>May</option>
                    <option value='06'>June</option>
                    <option value='07'>July</option>
                    <option value='08'>August</option>
                    <option value='09'>September</option>
                    <option value='10'>October</option>
                    <option value='11'>November</option>
                    <option value='12'>December</option>
                    </select>

                    </td><td>
                    Date<select name=dt >
                    <option value='01'>01</option>


                    <option value='02'>02</option>
                    <option value='03'>03</option>
                    <option value='04'>04</option>
                    <option value='05'>05</option>
                    <option value='06'>06</option>
                    <option value='07'>07</option>
                    <option value='08'>08</option>
                    <option value='09'>09</option>
                    <option value='10'>10</option>
                    <option value='11'>11</option>
                    <option value='12'>12</option>
                    <option value='13'>13</option>
                    <option value='14'>14</option>
                    <option value='15'>15</option>
                    <option value='16'>16</option>
                    <option value='17'>17</option>
                    <option value='18'>18</option>
                    <option value='19'>19</option>
                    <option value='20'>20</option>
                    <option value='21'>21</option>
                    <option value='22'>22</option>
                    <option value='23'>23</option>
                    <option value='24'>24</option>
                    <option value='25'>25</option>
                    <option value='26'>26</option>
                    <option value='27'>27</option>
                    <option value='28'>28</option>
                    <option value='29'>29</option>
                    <option value='30'>30</option>
                    <option value='31'>31</option>
                    </select>

                    <tr>
                    <td >
                    Year(yyyy)<input type=text name=year size=4 value=2005>
                    <td><input type="int" no of people="no" placeholder="No of people invited" /></td>

                    </table>


                                   </select>
                                        </td>
                                    </tr>
                                    Hour<select name=hr >
                                                        <option value='01'>01</option>


                                                        <option value='02'>02</option>
                                                        <option value='03'>03</option>
                                                        <option value='04'>04</option>
                                                        <option value='05'>05</option>
                                                        <option value='06'>06</option>
                                                        <option value='07'>07</option>
                                                        <option value='08'>08</option>
                                                        <option value='09'>09</option>
                                                        <option value='10'>10</option>
                                                        <option value='11'>11</option>
                                                        <option value='12'>12</option>
                                                        <option value='13'>13</option>
                                                        <option value='14'>14</option>
                                                        <option value='15'>15</option>
                                                        <option value='16'>16</option>
                                                        <option value='17'>17</option>
                                                        <option value='18'>18</option>
                                                        <option value='19'>19</option>
                                                        <option value='20'>20</option>
                                                        <option value='21'>21</option>
                                                        <option value='22'>22</option>
                                                        <option value='23'>23</option>
                                                        <option value='24'>24</option>
                                                         </select>
                                                         </td>
                                                         </tr>
                               Min(mm)<input type=text name=min size=2 value=00>

                    <td colspan="2" id="center"><input class="submitButton" name="eventformSubmit" type="submit" value="Create Event" /></td>
                </tr>
            </table>
        </form>
    </div>
    <script type="text/javascript">
                $('#submitTable').submit(function (ev) {
                    $.ajax({
                        type: "POST",
                        url: "backend/forms/eventform.php",
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