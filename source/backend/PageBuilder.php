<?
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

include_once($_SERVER['DOCUMENT_ROOT'] . "/backend/functions/log.php");


class PageBuilder {
    private $CSSImports = array();
    private $JSImports = array();
    private $contentSiblings = array();
    private $content = "";
    private $title;

    function __construct($title) {
        $this->title = '<title>MySplot &raquo; '. $title .'</title><meta charset="UTF-8">';
        $this->addJSImport("https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js");
        $this->addJSImport("https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places");
        $this->addJSImport("/js/jquery.datetimepicker.js");
        $this->addJSImport("/js/jquery.tokeninput.js");
        $this->addJSImport("/js/markerclusterer.js");
        $this->addJSImport("/js/overlay.js");

        $this->addCSSImport("/styles/main.css");
        $this->addCSSImport("/styles/jquery.datetimepicker.css");
        $this->addCSSImport("/styles/token-input.css");

        if (isLoggedIn()) {
            $logged = '<a href="/backend/functions/log.php?out" class="submitButton right">Sign out</a><button type="button" id="eventButton" class="submitButton right">New Event</button>';
            $navbarButtonsJS = '<script type="text/javascript">$(document).ready(function(){
            $("#eventButton").click(function(e){$.get("/backend/forms/eventform.php", function(data){modal.open({content: data});});});
            });</script>';
        } else {
            $logged = '<button type="button" id="loginButton" class="submitButton right">Sign in</button><button type="button" id="signupButton" class="submitButton right">Sign up</button>';

            $navbarButtonsJS = '<script type="text/javascript">$(document).ready(function(){
            $("#signupButton").click(function(e){$.get("/backend/forms/regform.php", function(data){modal.open({content: data});});});
            $("#loginButton").click(function(e){$.get("/backend/forms/logform.php", function(data){modal.open({content: data});});});
            });</script>';
        }


        $navbar = '<div id="navbar">
                       <div id="navbarContent">
                           <div id="navbarLogo"><a href="/."><img src="/img/design/logo.png" /></a></div>
                           <div id="navbarTitle">MySplot</div>'
                           . $logged.
                       '</div>
                   </div>';
        $this->addContentSibling($navbar);
        array_push($this->JSImports, $navbarButtonsJS);
    }

    public function addCSSImport($URL) {
        array_push($this->CSSImports, '<link rel="stylesheet" href="' . $URL . '" type="text/css" />');
    }

    public function addJSImport($URL) {
        array_push($this->JSImports, '<script type="text/javascript" src="' . $URL . '"></script>');
    }

    public function appendContent($content) {
        $this->content .= $content;
    }

    public function addContentSibling($content) {
        array_push($this->contentSiblings, $content);
    }

    public function toHTML() {
        $head = "<head>" . $this->title . join("\n\t", $this->CSSImports) . join("\n\t", $this->JSImports) . "</head>";
        $body = '<body><div id="everything">' . join("\n", $this->contentSiblings) . '<div id="contentFlex"><div id="content">' . $this->content . '</div></div></div>';
        $footer = '<div id="footer">TDT4290 Customer driven project - Group 4</div></body>';
        return "<html>" . $head . $body . $footer . "</html>";
    }
}
?>