<?php

// FUNGSI LANGKAH #1
function validateName($field_list, $field_name)
{

    if (!isset($field_list[$field_name]))
        return false;

      $pattern = "/^[a-zA-Z'-]+$/"; 

    if (!preg_match($pattern, $field_list[$field_name]))
        return false;

    return true;
}


// FUNGSI LANGKAH #4 (Validasi dengan Array Error)

function validateNamearrayerror(&$errors, $field_list, $field_name)
{
     $pattern = "/^[a-zA-Z'-]+$/"; 


    if (!isset($field_list[$field_name]) || empty($field_list[$field_name]))
        $errors[$field_name] = 'required';
  
    else if (!preg_match($pattern, $field_list[$field_name]))
        $errors[$field_name] = 'invalid';
}
?>