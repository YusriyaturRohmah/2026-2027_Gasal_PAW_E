<?php

// 1. array_push()
$array1 = array("A");

echo "Array awal: (\"A\")<br>";

array_push($array1, "B");

echo "Hasil array_push: " . implode(" ", $array1) . "<br><br>";


// 2. array_merge()
$array2 = array("A", "B");
$array3 = array("C");

echo "Array awal: (\"A\", \"B\") digabung dengan (\"C\")<br>";

$hasil_merge = array_merge($array2, $array3);

echo "Hasil array_merge: " . implode(" ", $hasil_merge) . "<br><br>";


// 3. array_values()
$array4 = array("x" => 1, "y" => 2);

echo 'Array awal: ("x" => 1, "y" => 2)<br>';

$hasil_values = array_values($array4);

echo "Hasil array_values: " . implode(" ", $hasil_values) ;


// 4. array_search()
$array5 = array("A", "B", "C");

echo 'Mencari "B" pada array: ("A", "B", "C")<br>';

$hasil_search = array_search("B", $array5);

echo "Hasil array_search: " . $hasil_search . "<br><br>";


// 5. array_filter()
$array6 = array(0, 1, false, 2, "", 3, "array");

echo 'Array awal: (0, 1, false, 2, "", 3, "array")<br>';

$hasil_filter = array_filter($array6);

echo "Hasil array_filter: ";
echo implode(" ", $hasil_filter) . "<br><br>";


// 6. sort() dan rsort()
$array7 = array(3, 1, 2);

echo "Array awal: (3, 1, 2)<br>";

$hasil_sort = $array7;
sort($hasil_sort);
echo "Hasil sort: " . implode(" ", $hasil_sort) . "<br>";

$hasil_rsort = $array7;
rsort($hasil_rsort);

echo "Hasil rsort: " . implode(" ", $hasil_rsort) . "<br><br>";


// 7. Sorting array asosiatif
$array8 = array(
    "Peter" => 35,
    "Ben" => 37,
    "Joe" => 43
);

echo 'Array awal: ("Peter"=>35, "Ben"=>37, "Joe"=>43)<br>';

$hasil_asort = $array8;
asort($hasil_asort);

echo "Hasil asort: ";
foreach ($hasil_asort as $nama => $umur) {
    echo "$nama=> $umur, ";
}
echo "<br>";

$hasil_ksort = $array8;
ksort($hasil_ksort);

echo "Hasil ksort: ";
foreach ($hasil_ksort as $nama => $umur) {
    echo "$nama=> $umur, ";
}
echo "<br>";

$hasil_arsort = $array8;
arsort($hasil_arsort);

echo "Hasil arsort: ";
foreach ($hasil_arsort as $nama => $umur) {
    echo "$nama=> $umur, ";
}
echo "<br>";

$hasil_krsort = $array8;
krsort($hasil_krsort);

echo "Hasil krsort: ";
foreach ($hasil_krsort as $nama => $umur) {
    echo "$nama=> $umur, ";
}
