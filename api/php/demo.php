<?php

$arr = array(
  "routers" => array(
    "react-router" => array(
      'value' => false,
      "resources" => array()
    ),
    "reach-router" => array(
      "value" => false,
      "resources" => array()
    )
  ),
  "ssr" => array(
    "nextjs" => array(
      'value' => false,
      "resources" => array()
    )
  )
);

function findFieldAndUpdate( &$data, $field_change, $value_change) {
  if(is_array($data) || is_object($data)){
    foreach ( $data as $key => &$value ) {
      if ($key === $field_change) {
        $value["value"] = $value_change;
      } else {
        findFieldAndUpdate($value, $field_change, $value_change);
      } 
    }
  }
}

findFieldAndUpdate($arr, "nextjs", true);
echo json_encode($arr);


