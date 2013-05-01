<?php
	function text_ausgabe($title, $id, $sprache) {
		$title=strtolower($title);
		global $mysql;
		mysql_connect($mysql['host'], $mysql['user'], $mysql['pw']) or die ("Es konnte keine Verbindung zum Datenbankserver aufgebaut werden!");
    	mysql_select_db($mysql['db']) or die ("Die Datenbank konnte nicht geöffnet werden!");
		
		$sql="SELECT ".$sprache." FROM texte WHERE kurz='".$title."' AND id=".$id;
		$query=mysql_query($sql);
		$text=@mysql_result($query,0,0);
		if (mysql_num_rows($query)==0) {
			$sql_akt="INSERT INTO texte SET ".$sprache."='".$title."', kurz='".$title."', id=".$id;
			mysql_query($sql_akt);
			$text=$title;
		}
		$text=nl2br(utf8_encode($text));
		return $text;
	}

    function item_bilder($item, $art="show", $menge=0) {
        $title='title="'.item_hover($item).'"';
        if (!file_exists("picture/items/".$item.".png")) {
            $item=0;
        }
        if (file_exists("picture/items/".$item.".png")) {
            $oBild=imagecreatefrompng("picture/items/".$item.".png");
            $oBreite = imageSX($oBild);
            if ($art=="inv") {
                $faktor=25/$oBreite;
                $title.=' class="item"';
            } elseif ($art=="show") {
                $faktor=25/$oBreite;
                $title.=' class="item"';
            } elseif ($art=="equip") {
                $faktor=75/$oBreite;
                $title.=' class="equip"';
            }
            $nBreite=$oBreite*$faktor;
            $bild = imagecreatetruecolor($nBreite,$nBreite);
            imageCopyResampled($bild, $oBild, 0, 0, 0, 0, $nBreite, $nBreite, $oBreite, $oBreite);
            $schwarz = imagecolorallocate($bild, 0, 0, 0);
            if (($menge>0) && ($menge<10)) {
                imagestring($bild, 1, 19, $nBreite-9, $menge, $schwarz);
            }
            elseif (($menge>9) && ($menge<100)) {
                imagestring($bild, 1, 14, $nBreite-9, $menge, $schwarz);
            }
            elseif ($menge>99) {
                imagestring($bild, 1, 8, $nBreite-9, $menge, $schwarz);
            }
            imagejpeg($bild,"picture/items/".'temp.jpg');
            $content = file_get_contents("picture/items/".'temp.jpg');
            $content=base64_encode($content);
            unlink("picture/items/".'temp.jpg');
            $posting='<img src="data:image/jpg;base64,'.$content.'" alt="base64 Test" '. $title.'>';
            return $posting;
        }
    }

    function item_hover($item) {
        //text_ausgabe($title, $id, $sprache)
        global $mysql, $bg;
        mysql_connect($mysql['host'], $mysql['user'], $mysql['pw']) or die ("Es konnte keine Verbindung zum Datenbankserver aufgebaut werden!");
        mysql_select_db($mysql['db']) or die ("Die Datenbank konnte nicht geöffnet werden!");
        $sql_item="SELECT * FROM item_db WHERE itemID=".$item." LIMIT 0,1";
        $query_item=mysql_query($sql_item);
        if (mysql_num_rows($query_item)>0) {
            $item_row=mysql_fetch_assoc($query_item);
            $return="<strong>".text_ausgabe("item", $item_row['itemID'], $bg['sprache'])."</strong><br>";
            $return.='<i>'.text_ausgabe("item_art", $item_row['art'], $bg['sprache']).'</i><br>';
            switch ($item_row['art']) {
                case 0:
                    $return.=text_ausgabe("stack", 0, $bg['sprache']).':&nbsp;';
                    $return.=$item_row['stack'].'<br>';
                    break;
                case 1:
                case 2:
                    $return.=text_ausgabe("schaden", 0, $bg['sprache']).':&nbsp;';
                    $return.=$item_row['mindmg'].'&nbsp;-&nbsp;'.$item_row['maxdmg'].'<br>';
                    $return.=text_ausgabe("trefferchance", 0, $bg['sprache']).':&nbsp;';
                    $return.=$item_row['hit'].'<br>';
                    $return.=text_ausgabe("kritchance", 0, $bg['sprache']).':&nbsp;';
                    $return.=$item_row['crit'].'<br>';
                    break;
                case 3:
                    $return.=text_ausgabe("stack", 0, $bg['sprache']).':&nbsp;';
                    $return.=$item_row['stack'].'<br>';
                    $return.=text_ausgabe("refill_art", 0, $bg['sprache']).':&nbsp;';
                    $return.="<span class='".text_ausgabe("char_status", $item_row['refillart'], "deutsch")."_text'>".$item_row['refill'].'&nbsp;';
                    $return.=text_ausgabe("char_status", $item_row['refillart'], $bg['sprache'])."</span><br>";


                    break;
                case 4:
                    $return.=text_ausgabe("platz", 0, $bg['sprache']).':&nbsp;';
                    $return.=$item_row['platz'].'<br>';
                    break;
            }
            $return.=text_ausgabe("item_text", $item_row['itemID'], $bg['sprache']).'<br>';
            return $return;
        } else {
            $return="<strong>".text_ausgabe("item", $item, $bg['sprache'])."</strong><br>";
            $return.=text_ausgabe("item_text", $item, $bg['sprache']).'<br>';
            return $return;
        }
    }
	
	function inventar_platz($rucksack) {
		$max_plaetze=0;
		global $mysql;
		mysql_connect($mysql['host'], $mysql['user'], $mysql['pw']) or die ("Es konnte keine Verbindung zum Datenbankserver aufgebaut werden!");
		mysql_select_db($mysql['db']) or die ("Die Datenbank konnte nicht geöffnet werden!");
		$sql_rucksack="SELECT platz FROM `item_db` WHERE itemID=".$rucksack;
		$query_rucksack=mysql_query($sql_rucksack);
		$max_plaetze=mysql_result($query_rucksack,0,0);
		return $max_plaetze;
	}
	
	function player_inventar_platz($user) {
		global $mysql;
		mysql_connect($mysql['host'], $mysql['user'], $mysql['pw']) or die ("Es konnte keine Verbindung zum Datenbankserver aufgebaut werden!");
		mysql_select_db($mysql['db']) or die ("Die Datenbank konnte nicht geöffnet werden!");
		$sql_euipment="SELECT rucksack FROM `char` WHERE userID=".$user;
		$query_euipment=mysql_query($sql_euipment);
		$rucksack_id=mysql_result($query_euipment,0,0);
		$plaetze_max=inventar_platz($rucksack_id);
		return $plaetze_max; 
	}

    function player_inventar_frei($user) {
        $max=player_inventar_platz($user);
        $plaetze_belegt=inventar_belegt($user);
        $frei=$max-$plaetze_belegt;
        return $frei;
    }
	
	function inventar_belegt($user) {
		$stack=0;
		global $mysql;
		mysql_connect($mysql['host'], $mysql['user'], $mysql['pw']) or die ("Es konnte keine Verbindung zum Datenbankserver aufgebaut werden!");
		mysql_select_db($mysql['db']) or die ("Die Datenbank konnte nicht geöffnet werden!");
		$sql_rucksack="SELECT invID FROM `inventory` WHERE userID=".$user;
		$query_rucksack=mysql_query($sql_rucksack);
		$stack=mysql_num_rows($query_rucksack);
		return $stack;
	}
	
	function item_stacknum($item) {
		$stack=0;
		global $mysql;
		mysql_connect($mysql['host'], $mysql['user'], $mysql['pw']) or die ("Es konnte keine Verbindung zum Datenbankserver aufgebaut werden!");
		mysql_select_db($mysql['db']) or die ("Die Datenbank konnte nicht geöffnet werden!");
		$sql_rucksack="SELECT stack FROM `item_db` WHERE itemID=".$item;
		$query_rucksack=mysql_query($sql_rucksack);
		$stack=mysql_result($query_rucksack,0,0);
		return $stack;
	}
	
	function zeit_anzeigen($sekunden) {
		$merken=$sekunden;
		$stunden=(int)($sekunden/60/60);
		$sekunden=$sekunden-($stunden*60*60);
		$minuten=(int)($sekunden/60);
		$sekunden=$sekunden-($minuten*60);
		$sekunden=str_pad($sekunden, 2, '0', STR_PAD_LEFT);
		$minuten=str_pad($minuten, 2, '0', STR_PAD_LEFT);
		$stunden=str_pad($stunden, 2, '0', STR_PAD_LEFT);
		//return $stunden.":".$minuten.":".$sekunden." (".$merken.")";
		return $stunden.":".$minuten.":".$sekunden;
	}

    function player_abbau_faktor($user, $art) {

        return 1;
    }
	
	function inventory_add($user, $item, $menge) {
		global $mysql;
		mysql_connect($mysql['host'], $mysql['user'], $mysql['pw']) or die ("Es konnte keine Verbindung zum Datenbankserver aufgebaut werden!");
		mysql_select_db($mysql['db']) or die ("Die Datenbank konnte nicht geöffnet werden!");
		
		$plaetze_max=player_inventar_platz($user);
		$plaetze_belegt=inventar_belegt($user);
		$platz_frei=$plaetze_max-$plaetze_belegt;
		
		$item_max=item_stacknum($item);
		$temp_stack=0;
		$sql_inventory="SELECT invID, menge FROM `inventory` WHERE userID=".$user." AND itemID=".$item." AND menge<".$item_max;
		$query_inventory=mysql_query($sql_inventory);
		$stack_id=@mysql_result($query_inventory,0,0);
		$temp_stack=@mysql_result($query_inventory,0,1);
		$stack_frei=$item_max-$temp_stack;
		$restmenge=$menge-$stack_frei;
		
		$reststack=ceil($restmenge/$item_max);
		if ($platz_frei>$reststack) {
			// ture
			if (($stack_id>0) && ($stack_frei>0)) {
                if ($menge+$temp_stack>$item_max) {
                    $tmenge=$item_max;
                    $help=$item_max-$temp_stack;
                    $ueber=$menge-$help;
                } else {
                    $tmenge=$menge+$temp_stack;
                    $ueber=0;
                }
				$sql_update_stack="UPDATE `inventory` SET menge=".$tmenge." WHERE userID=".$user." AND itemID=".$item." AND menge<".$item_max;
				mysql_query($sql_update_stack);
				$menge=$ueber;
			}
			while ($menge>0) {
				if ($menge>$item_max) {
					$tmenge=$item_max;
				} else {
					$tmenge=$menge;
				}
				$sql_update_stack="INSERT INTO `inventory` SET menge=".$tmenge.", userID=".$user.", itemID=".$item;
				mysql_query($sql_update_stack);
				$menge=$menge-$tmenge;
			}
			return true;
		}	else {
			return false;
		}
	}

    function inventory_remove($user, $item, $menge) {
        global $mysql;
        mysql_connect($mysql['host'], $mysql['user'], $mysql['pw']) or die ("Es konnte keine Verbindung zum Datenbankserver aufgebaut werden!");
        mysql_select_db($mysql['db']) or die ("Die Datenbank konnte nicht geöffnet werden!");
        $sql_inventory="SELECT sum(menge) FROM `inventory` WHERE userID=".$user." AND itemID=".$item;
        $query_inventory=mysql_query($sql_inventory);
        $gesamtmenge=mysql_result($query_inventory,0,0);
        if ($gesamtmenge>=$menge) {
            while ($menge>0) {
                $sql_inventory="SELECT invID, menge FROM `inventory` WHERE userID=".$user." AND itemID=".$item." ORDER BY menge";
                $query_inventory=mysql_query($sql_inventory);
                $inv=mysql_result($query_inventory,0,0);
                $inv_menge=mysql_result($query_inventory,0,1);
                if ($menge>$inv_menge) {
                    $t_menge=$inv_menge;
                } else {
                    $t_menge=$menge;
                }
                $sql_update="UPDATE inventory SET menge=menge-".$t_menge." WHERE invID=".$inv;
                mysql_query($sql_update);
                $sql_delete="DELETE FROM inventory WHERE menge=0";
                mysql_query($sql_delete);
                $menge=$menge-$t_menge;
            }
            return true;
        } else {
            return false;
        }

    }
?>