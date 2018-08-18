<?php
  // class'tan türetilmiş olan db.txt dosyası okunur
  $file = fopen('db.txt','r');
  $read = fread($file,filesize('../db.txt'));
  $close = fclose($file);

  #dizi haline getirilir
  $content = explode(".",$read);

  // veri tabanı bağlantısı kurulur
  try {
    $db= new PDO("mysql:host=$content[0];dbname=$content[1]",$content[2],$content[3]);
  } catch (PDOException $e) {
    echo $e->getMessage();
  }

 ?>
