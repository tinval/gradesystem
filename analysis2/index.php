<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
<script src="//code.jquery.com/jquery.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script src="jquery.tabledit2.js"></script>
<title>Bonuspunkte</title>
<?php
$string = file_get_contents('config.json');
$config = json_decode($string, true);
$conn = new mysqli($config['database']['host'], $config['database']['user'], $config['database']['passwd'], $config['database']['database']) or die("Connection failed: " . mysqli_connect_error());
if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", mysqli_connect_error());
	        exit();
}
mysqli_query($conn, "SET NAMES 'utf8'");
?>

</head>
<body>
<select id=assistent name=assistent>
    <option value="" disabled selected hidden>Please choose your name</option>
    <?php 
    $file = fopen('assistents.txt', 'r');
    while (!feof($file)) {
        $line = rtrim(fgets($file));
    if(!empty($line)){echo '<option value='.$line.'>'.$line.'</option>';};
    }
?>
</select>
<style>
table {
    background-color: #fff;
}
table, .table {
    color: #181818;
}
</style>

<div id=assi style="display: none">
Mr Anderson
</div>

<div id=start style="display: none">
<?php echo $config['exercises2']['start'];?>
</div>

<div id=total style="display: none">
<?php echo $config['exercises2']['total'];?>
</div>

<div id=content class="container" style="display: none">	
	<div class="row">
		<h2>Bonuspunkte</h2>
		<input type="text" id="searchVorname" onkeyup="filterStudent()" placeholder="Filter vorname" title="Type in a name">
		<input type="text" id="searchNachname" onkeyup="filterStudent()" placeholder="Filter nachname" title="Type in a name">
                <input type="text" id="searchAssistent" placeholder="Filter by assistent" title="Type in a name">
		<table id="editableTable" class="cell-border compact hover">
                <col width="90">
			<thead>
				<tr>
					<th>leginr</th>
					<th>vorname</th>
					<th>nachname</th>
					<th>assistent</th>
					<?php for ($serie = $config['exercises2']['start']; $serie < $config['exercises2']['total']-$config['exercises2']['start']; $serie++) {
                                                echo "<th>Serie" . $serie . "</th>";
					}?>											
				</tr>
			</thead>
			<tbody>		
                        <?php include 'make_table.php' ?>
			</tbody>
		</table>	
	</div>
</div>
<script src="table.js"></script>
</body>
</html>
