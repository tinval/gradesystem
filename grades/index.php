<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<style>
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid ;
    text-align: left;
    padding: 8px;
    }
</style>
<title>Bonus</title>
<?php
$string = file_get_contents('config.json');
$config = json_decode($string, true);
$conn = new mysqli($config['database']['host'], $config['database']['user'], $config['database']['passwd'], $config['database']['database']) or die("Connection failed: " . mysqli_connect_error());
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
mysqli_query($conn, "SET NAMES 'utf8'");

function get_result( $Statement ) {
	$RESULT = array();
	$Statement->store_result();
	for ( $i = 0; $i < $Statement->num_rows; $i++ ) {
		$Metadata = $Statement->result_metadata();
		$PARAMS = array();
		while ( $Field = $Metadata->fetch_field() ) {
			$PARAMS[] = &$RESULT[ $i ][ $Field->name ]; }
                call_user_func_array( array( $Statement, 'bind_result' ), $PARAMS );
                $Statement->fetch();}
	return $RESULT; }
?>
</head>
<body>
<div id=content class="container">	
	<div class="row">
	<h2>Bonus von <?php echo $_GET['name']. ', ' . $_GET['leginr'] ?></h2>
		<table>
			<thead>
				<tr>
					<th></th>
					<th>assistent</th>
					<?php for ($serie = $config['exercises1']['start']; $serie < $config['exercises1']['total']-$config['exercises1']['start']; $serie++) {
                                                echo "<th>Serie" . $serie . "</th>";
                                                }?>
                                        <th>Summe</th>
                                        <th>Bonus</th>
                                 </tr>
			</thead>
                        <tbody>
                        <?php $stmt = $conn->prepare("SELECT * FROM " . $config['database']['tablename1'] . " WHERE kurz = ? && leginr= ?");
                        $stmt->bind_param('ss', $_GET['name'], $_GET['leginr']);
                        $stmt->execute();
			$result = get_result($stmt);
			while ($student = array_shift($result)) { ?>
                                <tr id="<?php echo $student ['leginr'];?>">
                                <td><?php echo $config['course'] . " I" ?></td>
				<td><?php echo $student ['assistent']; ?>
				<?php
				$sum1 = 0;
				for ($serie = $config['exercises1']['start']; $serie < $config['exercises1']['total']+$config['exercises1']['start']; $serie++) {
                                        echo "<td>" . $student ['Serie' . $serie] . "</td>";
                                        $sum1 += $student['Serie' . $serie];
				}
				echo "<td>" . $sum1 . "</td>";
                                $bonus1 = round(min(max(($sum1 - $config['bonus1']['low'])/($config['bonus1']['high']-$config['bonus1']['low']),0),1) * 0.25, 2);
                                echo "<td bgcolor='#FFA500'>" . $bonus1 . "</td>";
                                $comment = $student['comment'];
				if(!empty($comment)){echo "<td>". $comment . "</td>";};
		       		}?>
                        </tr>
			<?php $stmt = $conn->prepare("SELECT * FROM " . $config['database']['tablename2'] . " WHERE kurz = ? && leginr= ?");
                        $stmt->bind_param('ss', $_GET['name'], $_GET['leginr']);
                        $stmt->execute();
			$result = get_result($stmt);
			while ($student = array_shift($result)) { ?>
                                <tr id="<?php echo $student ['leginr'];?>">
                                <td><?php echo $config['course'] . " II" ?></td>
				<td><?php echo $student ['assistent']; ?></td>
				<?php
				$sum2 = 0;
				for ($serie = $config['exercises2']['start']; $serie < $config['exercises2']['total']+$config['exercises2']['start']; $serie++) {
                                        echo "<td>" . $student ['Serie' . $serie] . "</td>";
                                        $sum2 += $student['Serie' . $serie];
				}
				echo "<td>" . $sum2 . "</td>";
                                $bonus2 = min(max(($sum2 - $config['bonus2']['low'])/($config['bonus2']['high']-$config['bonus2']['low']),0),1) * 0.25;
                                echo "<td bgcolor='#FFA500'>" . $bonus2 . "</td>";
                                $comment = $student['comment'];
				if(!empty($comment)){echo "<td>". $comment . "</td>";};
		       		}?>
                        </tr>
		</tbody>
               </table>
        </div>
	</br>
<?php if($config['semester2view']){
        echo 'Dein Bonus:  <b><div style="color:red; font-size:20px;background-color:#66CC66">' . round(($bonus1+$bonus2)/2, 2) . '</div></b>'; }?>
</body>
</html>
