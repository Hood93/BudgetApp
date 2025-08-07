<?php
include "../db.php";

$querycount = "SELECT * FROM transazionipending";

$result = mysqli_query($conn, $querycount);

if (!$result) {
  echo "An error occurred.\n";
  exit;
}
$count = 0;
while ($row = mysqli_fetch_assoc($result)) {
  extract($row);
$count = $count + 1;

}

echo $count;
?>