<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Process Data - Langkah 2</title>
</head>
<body>

<?php
require 'validate.inc';

if (validateName($_POST, 'surname'))
    echo 'Data OK!';
else
    echo 'Data invalid!';
?>

</body>
</html>
