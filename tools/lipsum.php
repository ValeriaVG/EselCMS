<?php
$url="http://loripsum.net/api/10/medium/headers/decorate/bold/italic/link/bq/code/";

for($i=0;$i<75000;$i++){
  echo $i."\n";
$text=file_get_contents($url);
  file_put_contents(dirname(__DIR__)."/pages/lipsum/".$i.".html",'{% extends "docs.twig" %}
  {% block textcontent %}'.$text.'{% endblock %}');
  //sleep(1);
}

 ?>
