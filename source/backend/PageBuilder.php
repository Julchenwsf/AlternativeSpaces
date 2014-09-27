<?
session_start();
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

class PageBuilder {
    private $CSSImports = array('<link rel="stylesheet" type="text/css" href="styles/main.css">');
    private $JSImports = array();
    private $contentSiblings = array();
    private $content = "";
    private $title;

    function __construct($title) {
        if (isset($_SESSION['user'])) {
            $logged = "Welcome back " . $_SESSION["user"];
        } else {
            $logged = '<a class="submitButton right">Login</a><a href="register.php" class="submitButton right">Signup</a>';
        }

        $navbar = '<div id="navbar">
                       <div id="navbarContent">
                           <div id="navbarLogo"><a href="index.php"><img src="img/design/logo.png" /></a></div>
                           <div id="navbarTitle">Alternative Spaces</div>'
                           . $logged.
                       '</div>
                   </div>';

        $this->addContentSibling($navbar);
        $this->title = '<title>Alternative Spaces &raquo; '. $title .'</title>';
        $this->addJSImport("https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js");
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
        $body = '<body><div id="everything">' . join("\n", $this->contentSiblings) . '<div id="contentFlex"><div id="content">' . $this->content . '</div></div></div></body>';
        $footer = '<div id="footer">TDT4290 Customer driven project - Group 4</div>';
        return "<html>" . $head . $body . $footer . "</html>";
    }
}


?>