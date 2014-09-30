<?
include_once("backend/PageBuilder.php");

$eventHeader = <<<EOT
                   <div id="eventPage">
                        <div id="eventHeader">
                            <b>Event</b>
                        </div>
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


$pb = new PageBuilder("Event");
$pb->appendContent($eventHeader);
echo $pb->toHTML();
?>