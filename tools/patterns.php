<?php
$patterns=scandir("patterns/");
$output="";
foreach ($patterns as $pattern) {
  if(!is_dir($pattern)){
    $tmp=explode(".",$pattern);
    $name=$tmp[0];
    $output.='
.pattern-'.$name.'{
  background-image:url("../patterns/'.$pattern.'");
}
    ';
  }
}
file_put_contents("patterns.css",$output);

 ?>
