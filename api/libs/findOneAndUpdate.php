<?php

function findFieldAndUpdate(&$data, $fieldChange, $valueChange)
{
    if (is_array($data) || is_object($data)) {
        foreach ($data as $key => &$value) {
            // print_r($value);
            if ($key === $fieldChange) {
                $value["value"] = $valueChange;
            } else {
                findFieldAndUpdate($value, $fieldChange, $valueChange);
            }
        }
    }
}

?>
