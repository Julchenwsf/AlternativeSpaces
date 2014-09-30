<?
include_once("backend/PageBuilder.php");



$eventHeader = <<<EOT
                   <div id="eventPage">
                        <div id="eventDetails">
                            <div id="eventImage">
                            <a href="index.php"><img src="img/design/football.png" /></a>
                            </div>
                            <div id="eventDescription">
                                <div id="eventDescriptionHeader">
                                    <a id="titleText">Description</a>
                                </div>
                                <fieldset>
                                    <legend>When</legend>
                                        15. October at 13:00
                                </fieldset>
                                <fieldset>
                                    <legend>Where</legend>
                                        Tøyenparken
                                </fieldset>
                                <fieldset>
                                    <legend>What</legend>
                                        Play football
                                </fieldset>
                            </div>

                        </div>
                        <div id="eventMap">
                            <div id="eventImage">
                                <img src="http://maps.googleapis.com/maps/api/staticmap?center=40.718217,-73.998284&zoom=13&size=175x175&maptype=roadmap
                                                          &markers=color:red%7Clabel:C%7C40.718217,-73.998284" />
                            </div>
                            <div id="eventDescription">
                                <div id="eventDescriptionHeader">
                                    <a id="titleText">Join the event</a>
                                </div>
                            <fieldset>
                                <legend>Event status</legend>
                                    10/20
                            </fieldset>
                            <button type="button" id="attendButton" class="submitButton">Attend</button>
                            </div>
                        </div>

                       <div id="eventComments">
                            <div id="addComment">
                                <h2>Come play football</h2>
                                <hr/>
                                Here is some description. Here is some description. Here is some description. Here is some description. Here is some description. Here is some description.
                            </div>
                        </div>

                        <div id="eventComments">
                            <div id="addComment">
                                <a id="commentTitle">New Comment</a>
                                <br/><hr/>
                            </div>
                        </div>
                   </div>
EOT;


$pb = new PageBuilder("Event");
$pb->addCSSImport("styles/event.css");
$pb->appendContent($eventHeader);
echo $pb->toHTML();
?>