<?php
    // Nagivation links
    $pages[0] = array("inp/index.html","Die Nightline","index.html");
    $pages[1] = array("inp/leitbild.html","Wir über uns","leitbild.html");
    $pages[2] = array("inp/unterstuetzen.html","Unterstützen","unterstuetzen.html");
    $pages[3] = array("inp/impressum.html", "Impressum","impressum.html");
    $pages[4] = array("inp/links.html", "Anlaufstellen", "links.html");
    $pages[5] = array("inp/submit.html", "Submit", "submit.html");
    $pages[6] = array("inp/on-demand.html", "On Demand", "on-demand.html");
    $pages[7] = array("inp/datenschutz.html", "Datenschutz", "datenschutz.html");
    $pages[8] = array("inp/mitmachen.html", "Mach mit!", "mitmachen.html");

    // Determine page id
    if (array_key_exists("page", $_GET)) {
        switch($_GET["page"]){
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

            case "submit":
		        $incId = 5;
                break;

            case "on-demand":
                $incId = 6;
                break;

            case "datenschutz":
                $incId = 7;
                break;

            case "mitmachen":
                $incId = 8;
                break;

            default:
                $incId = 0;
                break;
        }
    } else {
        $incId = 0;
    }
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="de">
<head>
	<meta charset="utf-8"/>

	<title>Nightline Karlsruhe - Studentisches Zuhörtelefon</title>
	<meta name="description" content="Nightline, Karlsruhe, Pforzheim, Zuhörtelefon, Hochschulgruppe"/>
  	<meta name="author" content="Nightline Karlsruhe e.V."/>

  	<meta name="viewport" content="width=device-width">

  	<link rel="stylesheet" href="css/styles.css" type="text/css"/>
</head>

<body>
    <?php 
        require_once('onDemandEmbed.php');
        buildOnDemandTopbar(); 
    ?>
	
	<header>
		<div class="ym-wrapper">
			<div class="ym-wbox">
                    <h1>
					<img id="logoTop" src="img/nlka_logo_moon.png" alt="Nightline Karlsruhe" />
        			</h1>
			</div>
		</div>
		
        <!-- Pages Menu -->
		<nav class="ym-hlist" id='navigation'>
			<div class="ym-wrapper" id="navigationContainer">
                <ul>
       				<?php
                        for ($k=0; $k<count($pages); $k++){
                            // Second condition removes impressum from title bar, third removes submit, last removes on-demand
                			if (count($pages[$k]) == 1 || $k == 3 || $k == 5 || $k == 6 || $k == 7) {
                                continue;
                			}
                            echo "<li".($k==$incId?" class=\"active\"":"")."><a href=\"".$pages[$k][2]."\">".$pages[$k][1]."</a></li>\n";
                        }
        			?>
				</ul>
			</div>
		</nav>
        <!-- End Pages Menu -->
	</header>
	
    <!-- Include Main Page -->
	<main>
        <?php
            include($pages[$incId][0]);
		?>
	</main>
	<!-- End Main Page -->

	<footer>
		<div class="ym-wrapper">
			<div class="ym-wbox">
				<p>Header image <a href='https://commons.wikimedia.org/wiki/User:Leviathan1983#/media/File:Stars_01_(MK).jpg'>"starry sky near Brandenburg an der Havel (Germany), close to midnight"</a> by <a href='https://commons.wikimedia.org/wiki/User:Leviathan1983'>Mathias Krumbholz</a> 2014, modified (CC BY-SA 3.0).<br/><br/> Nightline Karlsruhe e.V.</p>
                <p><a href="impressum.html">Impressum</a></p>
                <p><a href="datenschutz.html">Datenschutzerklärung</a></p>
			</div>
		</div>
	</footer>

	<!-- Piwik -->
	<script type="text/javascript">
  		var _paq = _paq || [];
  		// tracker methods like "setCustomDimension" should be called before "trackPageView"
  		_paq.push(["disableCookies"]);
  		_paq.push(['trackPageView']);
  		_paq.push(['enableLinkTracking']);
  		(function() {
   			var u="//nightline-karlsruhe.de/piwik/";
    			_paq.push(['setTrackerUrl', u+'piwik.php']);
    			_paq.push(['setSiteId', '2']);
    			var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    			g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  		})();
	</script>
	<!-- End Piwik Code -->
</body>
</html>
