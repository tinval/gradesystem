<?php
$sqlQuery = "SELECT * FROM " . $config['database']['tablename2'];
$resultSet = mysqli_query($conn, $sqlQuery) or die("database error:". mysqli_error($conn));
while( $student = mysqli_fetch_assoc($resultSet) ) { ?>
	<tr id="<?php echo $student ['leginr']; ?>">
	<td><?php echo $student ['leginr']; ?></td>
	<td><?php echo $student ['vorname']; ?></td>
	<td><?php echo $student ['nachname']; ?></td>
	<td><?php echo $student ['assistent']; ?></td>
	<?php for ($serie = $config['exercises2']['start']; $serie < $config['exercises2']['total']-$config['exercises2']['start']; $serie++) {
            echo "<td>" . $student ['Serie' . $serie] . "</td>";
            }?>	
	</tr>
<?php } ?>
