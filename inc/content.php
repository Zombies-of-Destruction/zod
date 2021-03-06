<?php

    if (!isset($_GET['site'])) {
	$_GET['site'] = "";
    }
    if (!isset($_GET['map'])) {
	$_GET['map'] = "";
    }

    if (!isset($_SESSION['userID'])) {
	switch ($_GET['site']) {
	    case 'register':
		include("site/register.php");
		break;
	    default:
		include("site/start.php");
	}
    } else {
	// Exp Nur wenn man eingeloggt ist:
	include("site/expcheck.php");
	include("site/questcheck.php");

	// Wenn Quest 1 nicht abgeschlossen, dann muss der Char neu erstellt werden!
	$sql_check_quest = "SELECT 1 as checkaaa FROM char_quest WHERE cquest_userID=" . $_SESSION['userID'] . " AND cquest_questID=1 AND cquest_erledigt=1";
	$query_check_quest = mysql_query($sql_check_quest);
	if (mysql_num_rows($query_check_quest) == 0) {
	    mysql_query("DELETE FROM `char` WHERE userID=" . $_SESSION['userID']);
	    mysql_query("DELETE FROM `char_skill` WHERE userID=" . $_SESSION['userID']);
	    mysql_query("DELETE FROM `inventory` WHERE userID=" . $_SESSION['userID']);
	}

	$sql_char = "SELECT * FROM `char` WHERE userID=" . $_SESSION['userID'];
	$query_char = mysql_query($sql_char);
	if (mysql_num_rows($query_char) == 0) {
	    include("site/charauswahl.php");
	} else {
	    $sql_query = "SELECT aktion, aktion_id, aktion_start, aktion_ende FROM `char` WHERE `userID` = '" . $_SESSION['userID'] . "'";
	    $result = mysql_query($sql_query);
	    $aktion = mysql_fetch_assoc($result);

	    // Seiten die ausgeführt werden können auch wenn Aktionen gemacht werden:
	    switch ($_GET['site']) {
		case "account":
		    include("site/information.php");
		    break;
		case "charakter":
		    include("site/charakter.php");
		    break;
		case "nachricht":
		    include("site/nachricht.php");
		    break;
		case "questbook":
		    include("site/questbook.php");
		    break;
		case "guild":
		    include("site/guild.php");
		    break;
		case "beitreten":
		    include("site/beitreten.php");
		    break;
		case 'ladder':
		    include("site/ladder.php");
		    break;
		case "nachricht_schreiben":
		    include("site/nachricht_schreiben.php");
		    break;
		case "nachricht_lesen":
		    include("site/nachricht_anzeige.php");
		    break;
		case "nachricht_anzeige":
		    include("site/nachricht_anzeige2.php");
		    break;
	    }

	    // Rechte Check
	    $sql_login = "SELECT * FROM `login` WHERE userID=" . $_SESSION['userID'];
	    $query_login = mysql_query($sql_login);
	    $ds_admin = @mysql_fetch_assoc($query_login);

	    if ($ds_admin['rechte'] == "4") {
		if ($_GET['site'] == "admin") {
		    switch ($_GET['db']) {
			case "quests":
			    include("admin/quests.php");
			    break;
			case "items":
			    include("admin/items.php");
			    break;
			case "itemsedit":
			    include("admin/item_liste.php");
			    break;
			case "iedit":
			    include("admin/item_edit.php");
			    break;
			case "mobs":
			    include("admin/mobs.php");
			    break;
			case "uniqs":
			    include("admin/uniqs.php");
			    break;
			case "sets":
			    include("admin/sets.php");
			    break;

			case "alex":
			    include("admin/spielwiese_alex.php");
			    break;
			case "sascha":
			    include("admin/spielwiese_sascha.php");
			    break;
		    }
		}
	    }

	    // Seiten vor denen eine Aktion geprüft wird
	    if ($aktion['aktion'] == "") {
		switch ($_GET['map']) {
		    case "weltkarte":
			include("world/worldmap.php");
			break;
		    case "trainingslager":
			include("world/trainingslager/trainingslager_map.php");
			break;
		    case "wald":
		    case "schrottplatz":
			include("world/gebiet/wald.php");
			break;
		    case "werkstatt":
			include("world/gebiet/werkstatt.php");
			break;
		    case "see":
			include("world/gebiet/see.php");
			break;
		    case "gabfall":
			include("world/gebiet/abfall_gamer.php");
			break;
		    case "abfall":
			include("world/gebiet/abfall.php");
			break;
		    case "wohngebiet":
			include("world/gebiet/wohngebiet.php");
			break;
		    case "huette":
			include("world/gebiet/huette.php");
			break;
		}
	    } else {
		if ($_GET['site'] == "") {
		    include("site/aktion.php");
		}
	    }
	}
    }
?>