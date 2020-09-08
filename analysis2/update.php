<?php
header('Content-Type: application/json');
$input = filter_input_array(INPUT_POST);

$string = file_get_contents('config.json');
$config = json_decode($string, true);
$conn = new mysqli($config['database']['host'], $config['database']['user'], $config['database']['passwd'],$config['database']['database']) or die("Connection failed: " . mysqli_connect_error());

if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
mysqli_query($conn, "SET NAMES 'utf8'");

foreach ($input as $key => $value) {
  if (!in_array($key, array('leginr', 'action', 'assi'))){
    $entry = $key;
  }
}

if ($input['action'] === 'edit') {
    $stmt = $conn->prepare('UPDATE ' . $config['database']['tablename2']  . ' SET assistent=? WHERE leginr="' . $input['leginr']. '"');
    $stmt->bind_param('s', $input['assi']);
    $stmt->execute();
    $stmt = $conn->prepare('UPDATE ' . $config['database']['tablename2'] . ' SET ' . $entry . '=? WHERE leginr="' . $input['leginr']. '"');
    $stmt->bind_param('d', $input[$entry]);
    $stmt->execute();
    $msg = date('Y-m-d H:i:s') . ": " . $input['assi'] . " added " . $input[$entry] . " to student " . $input['leginr'] . " for " . $entry;
    file_put_contents('logs.txt', $msg.PHP_EOL , FILE_APPEND);
}
mysqli_close($conn);
echo json_encode($input);
?>
