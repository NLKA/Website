<?php
    // Jede Seite, die im Menue aufgefuehrt ist einpflegen. Muster:
    // includeseite, Menue bzw. HTML-Titel, Menue-URL-Alias
    // Falls die Seite nicht im Menü erschienen soll (z.B. Impressum), die letzen
    // beiden Attribute weglassen
    $pages[0] = array("inp/index.html","Die Nightline","index.html");
    //$pages[1] = array("inp/mitmachen.html","Mitmachen","mitmachen.html");
    $pages[1] = array("inp/index.html","Leitbild","index.html");
    $pages[2] = array("inp/unterstuetzen.html","Unterstützen","unterstuetzen.html");
    $pages[3] = array("inp/impressum.html", "Impressum","impressum.html");
    $pages[4] = array("inp/links.html", "Anlaufstellen", "links.html");
    
    if (array_key_exists("page", $_GET)) {
        switch($_GET["page"]){
            //case "mitmachen":
                //$incId = 1;
            //break;
	    case "leitbild":
                $incId = 1;
	    break;
            case "unterstuetzen":
                $incId = 2;
            break;
            case "impressum":
                $incId = 3;
            break;
            case "links":
                $incId = 4;
            break;
            default:
                $incId = 0;
        }
    } else {
        $incId = 0;
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"	 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="de">
<head>
  <meta charset="utf-8"/>

  <title>Nightline Karlsruhe - Studentisches Zuhörtelefon</title>
  <meta name="description" content="Nightline, Karlsruhe, Pforzheim, Zuhörtelefon"/>
  <meta name="author" content="Nightline Karlsruhe e.V."/>

  <!-- mobile viewport optimisation -->
  <meta name="viewport" content="width=device-width">

  <!-- stylesheets -->
  <link rel="stylesheet" href="css/styles.css" type="text/css"/>

  <!--[if lte IE 7]>
  <link rel="stylesheet" href="yaml/core/iehacks.min.css" type="text/css"/>
  <![endif]-->
</head>
<body>
		<header>
		<div class="ym-wrapper">
		<div class="ym-wbox">
        <h1>
		<img id="logoTop" src="img/nlka_logo_web.png" alt="Nightline Karlsruhe" />
        </h1>
		</div>
		</div>
		<nav class="ym-hlist" id='navigation'>
		<div class="ym-wrapper" id="navigationContainer">
		<ul>
        <?php
            for ($k=0;$k<count($pages);$k++){
		// Second condition removes impressum from title bar, no idea what the first one does.
                if (count($pages[$k])==1 || $k == 3){
                    continue;
                }
                echo "<li".($k==$incId?" class=\"active\"":"")."><a href=\"".$pages[$k][2]."\">".$pages[$k][1]."</a></li>\n";
            }
            
        ?>
		</ul>
		</div>
		</nav>
		</header>
		<main>
			<?php
                include($pages[$incId][0]);
			?>
		</main>
		<footer>
		<div class="ym-wrapper">
		<div class="ym-wbox">
		<p>Header image <a href='https://commons.wikimedia.org/wiki/User:Leviathan1983#/media/File:Stars_01_(MK).jpg'>"starry sky near Brandenburg an der Havel (Germany), close to midnight"</a> by <a href='https://commons.wikimedia.org/wiki/User:Leviathan1983'>Mathias Krumbholz</a> 2014, modified (CC BY-SA 3.0).<br/><br/> Nightline Karlsruhe e.V.</p>
		<p><a href="impressum.html">Impressum</a></p>
		</div>
		</div>
		</footer>
</body>
</html>
