
<h1><?php echo text_ausgabe("itemdb_cat", 12, $bg['sprache']); ?></h1>
<hr /><br>


<form method="post" enctype="multipart/form-data">
    <table border="0">
        <tr><td><br /></td></tr>

        <tr>
            <td><?php echo text_ausgabe("itemdb_text", 1, $bg['sprache']); ?>:<br></p></td>
	        <td><input placeholder="Name" maxlength="20" size="25" name="name" type="text" /></p></td>
	        <td><?php echo text_ausgabe("itemdb_info", 1, $bg['sprache']); ?><br></td>
	    </tr>

	    <tr><td><br /></td></tr>

        <tr>
            <td><?php echo text_ausgabe("itemdb_text", 2, $bg['sprache']); ?>:<br></p></td>
	        <td><input placeholder="ItemID" maxlength="20" size="25" name="itemID" type="text" /></p></td>
	        <td><?php echo text_ausgabe("itemdb_info", 2, $bg['sprache']); ?><br></td>
        </tr>

        <tr><td><br /></td></tr>

        <tr>
            <td><p><?php echo text_ausgabe("itemdb_text", 3, $bg['sprache']); ?>:<br></p></td>
	        <td><input placeholder="Monster min lvl (drop)" maxlength="20" size="25" name="min_lvl" type="text" /></p></td>
	        <td><?php echo text_ausgabe("itemdb_info", 3, $bg['sprache']); ?><br></td>
	    </tr>
        <tr>
	        <td><p><?php echo text_ausgabe("itemdb_text", 4, $bg['sprache']); ?>:<br></p></td>
	        <td><input placeholder="Monster max lvl (drop)" maxlength="20" size="25" name="max_lvl" type="text" /></p></td>
	        <td><?php echo text_ausgabe("itemdb_info", 4, $bg['sprache']); ?><br></td>
	    </tr>

        <tr><td><br /></td></tr>

        <tr>
	        <td><?php echo text_ausgabe("itemdb_text", 6, $bg['sprache']); ?>:<br></p></td>
	        <td><input placeholder="Stack (wie oft stabelbar)" maxlength="20" size="25" name="itemID" type="text" /></p></td>
	        <td><?php echo text_ausgabe("itemdb_info", 6, $bg['sprache']); ?><br></td>
        </tr>

	    <tr><td><br /></td></tr>

        <tr>
            <td colspan="2"><p><?php echo text_ausgabe("itemdb_text", 18, $bg['sprache']); ?>:</p><input maxlength="20" type="file" name="datei"></td>
            <td><?php echo text_ausgabe("itemdb_info", 18, $bg['sprache']); ?><br></td>
        </tr>
    <td><br /></td></tr>
	<tr>
            <td align="center"><input type="reset" name="Reset" value="Reset"/></td>
            <td align="center"><input type="submit" name="add" value="Adden"/></td>
        </tr>
    </table>
    <input value="0" name="art" type="hidden" />
    <input value="" name="text" type="hidden" />
    <input value="0" name="mindmg" type="hidden" />
    <input value="0" name="maxdmg" type="hidden" />
    <input value="0" name="def" type="hidden" />
    <input value="0" name="mdef" type="hidden" />
    </form>