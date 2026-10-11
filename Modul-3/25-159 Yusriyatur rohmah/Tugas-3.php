<?php
$height = array(
    "Andy" => "176",
    "Barry" => "165",
    "Charlie" => "170"
);

$height["David"] = "180";
$height["Ethan"] = "172";
$height["Frank"] = "168";
$height["George"] = "175";
$height["Harry"] = "182";

echo "height = (";
$data = array();
foreach ($height as $nama => $tinggi) {
    $data[] = '"' . $nama . '"=>"' . $tinggi . '"';
}

echo implode(", ", $data) . ")<br>";

echo "Nilai dengan indeks terakhir: " . end($height) . "<br><br>";

unset($height["Barry"]);

echo "height = (";
$data = array();

foreach ($height as $nama => $tinggi) {
    $data[] = '"' . $nama . '"=>"' . $tinggi . '"';
}

echo implode(", ", $data) . ")<br>";

echo "Nilai dengan indeks terakhir setelah dihapus: " . end($height);


echo "<br>";
echo "<br>";


$weight = array(
    "Andy" => "70",
    "Barry" => "65",
    "Charlie" => "75"
);

echo 'weight = (';
$data = array();

foreach ($weight as $nama => $berat) {
    $data[] = '"' . $nama . '"=>"' . $berat . '"';
}

echo implode(", ", $data) . ")<br>";

$nilai = array_values($weight);
echo "Data kedua: " . $nilai[1];
