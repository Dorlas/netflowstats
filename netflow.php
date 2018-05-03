<?php
$ip_from=$_GET["ip_from"];
$s_port=$_GET["s_port"];
$ip_to=$_GET["ip_to"];
$d_port=$_GET["d_port"];
$proto=$_GET["proto"];
$date_ins=$_GET["date_ins"];
$time_ins=$_GET["time_ins"];
$bytes=$_GET["bytes"];
$col_limit=$_GET["col_limit"];
$s_button=$_GET["s_button"];

$dbaddress="localhost";
$dblogin="nfuser";
$dbpass="987654321";
$dbname="netflow";

?>

<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<title>NetFlow v5 SQL Form</title>
</head>

<a href="http://bsdbackup.test.local/netflow.php" target="rightframe"><input type=submit value="StartAgain" /></a>
<h1></h1>

<table border=2>
<form name="form_ip_from" action="netflow.php">

<tr>
        <td>SourceIP (ip_from):</td>
        <td><input name=ip_from  value="<?php echo($ip_from);?>"></td>
        <td align="center"><input name=s_button type=submit></td>
</tr>

<tr>
        <td>SourcePort (s_port):</td>
        <td><input name=s_port value="<?php echo($s_port);?>"></td>

        <td>
                <table>
                        <tr>
                                <td>GroupField</td>
                                        <td><select name=sort_field size=1>
                                                <option value="" selected>Not Sort</option>
                                                <option value=ip_from>ip_from</option>
                                                <option value=s_port>s_port</option>
                                                <option value=ip_to>ip_to</option>
                                                <option value=d_port>d_port</option>
                                                <option value=proto>proto</option>
                                                <option value=date_ins>date_ins</option>
                                        </select>
                                        </td>
                        </tr>
                </table>
        </td>
</tr>

<tr>
        <td>DestinationIP (ip_to):</td>
        <td><input name=ip_to value="<?php echo($ip_to);?>"></td>

        <td>
                <table
                        <tr>
                                <td>Query Limit:</td><td><input name=col_limit value="<?php echo($col_limit);?>"></td>
                        </tr>
                </table>
        </td>
</tr>

<tr>
        <td>DestinationPort (d_port):</td>
        <td><input name=d_port value="<?php echo($d_port);?>"></td>

        <td align="center">

                <?php
			$mysqli = new mysqli($dbaddress, $dblogin, $dbpass, $dbname);

			$t_list = $mysqli->query("SHOW TABLES from $dbname");

                        echo("Month:");
                        echo("<select name=select_month size=1>");

			while ($tableName = mysqli_fetch_row($t_list)) {
				echo("<option value=$tableName[0]>$tableName[0]</option>");
			}
                echo("</select>");
                ?>
        </td>
</tr>

<tr>
        <td>ProtoNumber (proto):</td>
        <td><input name=proto value="<?php echo($proto);?>"></td>

        <td>
                <table>
                        <tr>
                                <td>CountField</td>
                                        <td><select name=count_field size=1>
                                                <option value="" selected>Not Count</option>
                                                <option value=show_count>Show Count</option>
                                        </select>
                                        </td>
                        </tr>
                </table>
        </td>

</tr>

<tr>
        <td>Date (date_ins):</td><td><input name=date_ins value="<?php echo($date_ins);?>"></td>

        <td>
                <table>
                        <tr>
                                <td>RowDisplay</td>
                                        <td><select name=select_field size=1>
                                                <option value="" selected>Hide Row</option>
                                                <option value=show_row>Show Row</option>
                                        </select>
                                        </td>
                        </tr>
                </table>
        </td>

</tr>

<tr>
        <td>Time (time_ins):</td><td><input name=time_ins value="<?php echo($time_ins);?>"></td>
        <td>
                <table
                        <tr>
                                <td>Bytes:</td><td><input name=bytes value="<?php echo($bytes);?>"></td>
                        </tr>
                </table>
        </td>
</tr>

</table>
</form>
</html>

<?php
        $db=mysqli_connect($dbaddress, $dblogin, $dbpass, $dbname);

        if($s_button)
                {
                        if($ip_from) { $ins_ip_from = 'and ip_from="'.$ip_from.'" '; }
                        if($s_port) { $ins_s_port = 'and s_port="'.$s_port.'" '; }
                        if($ip_to) { $ins_ip_to = 'and ip_to="'.$ip_to.'" '; }
                        if($d_port) { $ins_d_port = 'and d_port="'.$d_port.'" '; }
                        if($proto) { $ins_proto = 'and proto="'.$proto.'" '; }
                        if($bytes) { $ins_bytes = 'and bytes>"'.$bytes.'" order by date_ins '; }
                        if($date_ins) { $ins_date_ins = 'and date_ins="'.$date_ins.'" '; }
                        if($time_ins) { $ins_time = 'and time_ins="'.$time_ins.'" ';
                }

        if($_GET['sort_field'])
                {
                        $sort_select = $_GET['sort_field'].',';
                        $sort_group = ' group by '.$_GET['sort_field'].' order by 2 desc ';

                                if($col_limit)
                                        {
                                                $ins_col_limit = $col_limit; $line_limit='limit';
                                        }
                                        else {
                                                $ins_col_limit = '30'; $line_limit='limit';
                                        }
                }

echo("<h4>SQL-queue: ");
$q_http='SELECT '.$sort_select.'sum(bytes) FROM '.$_GET['select_month'].' WHERE interface="g" '.$ins_ip_from.' '.$ins_s_port.' '.$ins_ip_to.' '.$ins_d_port.' '.$ins_proto.' '.$ins_date_ins.' '.$ins_time.' '.$ins_bytes.' '.$sort_group.' '.$line_limit.' '.$ins_col_limit.';';

$bordertype1 = substr_replace($sort_select, "", -1);
$bordertype2 = "Summary Bytes";

        if($_GET['count_field'])
	        {
                $q_http='SELECT '.$sort_select.'count(host) FROM '.$_GET['select_month'].' WHERE interface="g" '.$ins_ip_from.' '.$ins_s_port.' '.$ins_ip_to.' '.$ins_d_port.' '.$ins_proto.' '.$ins_date_ins.' '.$ins_time.' '.$ins_bytes.' '.$sort_group.' '.$line_limit.' '.$ins_col_limit.';';

                $bordertype2 = "Count Rows";
        }

        if($_GET['select_field'])
        {
                if($col_limit)
                        {
                                $ins_col_limit = $col_limit; $line_limit='limit';
                        }
                        else {
                                $ins_col_limit = '30'; $line_limit='limit';
                }

                $q_http='SELECT * FROM '.$_GET['select_month'].' WHERE interface="g" '.$ins_ip_from.' '.$ins_s_port.' '.$ins_ip_to.' '.$ins_d_port.' '.$ins_proto.' '.$ins_date_ins.' '.$ins_time.' '.$ins_bytes.' '.$sort_group.' '.$line_limit.' '.$ins_col_limit.';';

        }

echo($q_http);

echo("</h4>");

if(!$_GET['select_field'])      {

$r_http=mysqli_query($db,$q_http);

echo("<table border=2>");
echo("<tr><td>$bordertype1</td><td>$bordertype2</td></tr>");
$i=0;

while ($i<$ins_col_limit+1)
        {
		$row = mysqli_fetch_row($r_http);
		$row0=$row[0];
		$row1=$row[1];
		echo("<tr><td>");
		printf($row0);
		echo("</td><td>");
		printf($row1);
		echo("</td></tr>");
                $i++;
        }

} elseif ($_GET['select_field'])        {

$r_http=mysqli_query($db,$q_http);

echo("<table border=11>");
echo("<tr><td>ip_from</td><td>s_port</td><td>ip_to</td><td>d_port</td><td>proto</td><td>packets</td><td>bytes</td><td>date_ins</td><td>time_ins</td><td>host</td><td>interface</td></tr>");
$y=0;

while ($y<$ins_col_limit+1)
        {
		$row = mysqli_fetch_row($r_http);
		$row_ip_from=$row[0];
		$row_s_port=$row[1];
		$row_ip_to=$row[2];
		$row_d_port=$row[3];
		$row_proto=$row[4];
		$row_packets=$row[5];
		$row_bytes=$row[6];
		$row_date_ins=$row[7];
		$row_time_ins=$row[8];
		$row_host=$row[9];
		$row_interface=$row[10];

		echo("<tr><td>");
		printf($row_ip_from);
		echo("</td><td>");
		printf($row_s_port);
                echo("</td><td>");
                printf($row_ip_to);
                echo("</td><td>");
                printf($row_d_port);
                echo("</td><td>");
                printf($row_proto);
                echo("</td><td>");
                printf($row_packets);
                echo("</td><td>");
                printf($row_bytes);
                echo("</td><td>");
                printf($row_date_ins);
                echo("</td><td>");
                printf($row_time_ins);
                echo("</td><td>");
                printf($row_host);
		echo("</td><td>");
		printf($row_interface);
                echo("</td><tr>");

                $y++;
        }
}
echo("</table>");

}

?>
