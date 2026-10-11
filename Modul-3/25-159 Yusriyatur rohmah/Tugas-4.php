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
echo implode(", ", $data) . ")<br><br>";

foreach ($height as $nama => $tinggi) {
    echo "$nama is $tinggi cm tall.<br>";
}

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

echo implode(", ", $data) . ")<br><br>";

$nama = array_keys($weight);
$berat = array_values($weight);

for ($i = 0; $i < count($weight); $i++) {
    echo "$nama[$i] is $berat[$i] kg.<br>";
}

