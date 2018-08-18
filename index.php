<?php
    require_once 'DbClass.php';

    // class tanımlanır
    $test = new DbClass;

    // oluşturulan bağlantı diğer fonksiyonlarda da kullanılabilmesi için değişkene aktarılır
    $dbInfo = $test->DbConnect('localhost','test','root','');

    // import veri tabanı bağlantımız ve import edeceğimiz sql dosyası belirtilir
    # !! sql dosyası aynı dizinde olarak baz alınmıştır
    $test->DbImport($dbInfo,'test.sql');

    // kullanıcılar bilgisi alınır ve son parametre veri tabanı bağlantısıdır.
    $test->UserInfo("username","password","mail@mail.com",$dbInfo);

    // gelen değerler kayıt ettirilir.
    $test->Save($dbInfo);
?>
