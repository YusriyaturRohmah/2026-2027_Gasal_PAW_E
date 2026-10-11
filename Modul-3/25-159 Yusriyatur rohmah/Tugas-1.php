<?php
//1.1
$fruits = array('Avocado', 'Bluebery', 'Cherry');

$fruits[] = 'Durian';
$fruits[] = 'Elderberry';
$fruits[] = 'Fig';
$fruits[] = 'Grape';
$fruits[] = 'Honeydew';


echo "fruits = (\"" . implode('", "', $fruits) . "\")<br>";
echo "Nilai dengan indeks tertinggi : " .end($fruits);


echo "<br>";
echo "<br>";


//1.2

unset($fruits[1]);
$fruits = array_values($fruits);
echo "data bluebery dihapus. <br>";
echo "fruits = (\"" . implode('", "', $fruits) . "\")<br>";
echo "Nilai dengan indeks tertinggi : " .end($fruits);
?>
