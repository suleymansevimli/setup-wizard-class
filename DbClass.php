<?php
/**
 * wordpress stilinde veri tabanı bağlantısını kurmak ve hali hazırda bulunan veri tabanını import etmek için
 * hazırlanmıştır
 * Hazırlayan : Süleyman Sevimli
 * Github : https://github.com/suleymansevimli
 */

  // import için gerekli olan dosyalarımızı çağırıyoruz
    require('vendor/autoload.php');
    use Thamaraiselvam\MysqlImport\Import;
  // use libs\Import;

class DbClass
{

  // private olarak değişkenlerimizi tanımlıyoruz.
  private $host;
  private $dbname;
  private $dbuser;
  private $dbpass;
  private $dbConn;
  function __construct()
  {
    // end adında bir çerez oluşturup hangi sayfayı göstereceğimize ona göre karar veriyoruz
    # içi boş bir değerse veya yoksa index.php dosyamızı gösteriyoruz
    if (empty($_COOKIE['end'])) {
      require_once 'index.php';
    }

  }

  // Veri tabanı bağlantısını aşağıdaki fonksiyon ile gerçekleştireceğiz.
  public function DbConnect( $host, $dbname, $dbuser, $dbpass){

    // Gelen verilerimizi filtreliyoruz.
    $host = htmlspecialchars(str_replace(" ","",$host));
    $dbname = htmlspecialchars(str_replace(" ","",$dbname));
    $dbuser = htmlspecialchars(str_replace(" ","",$dbuser));
    $dbpass = htmlspecialchars(str_replace(" ","",$dbpass));

    // gelen verileri test etmek için aşağıdaki kod satırını çalıştırabilirsiniz
    # echo $host." ".$dbname." ".$dbuser." ".$dbpass;

    // veri tabanı bağlantımızı pdo ile kuruyoruz.
     try {
       $db = new PDO("mysql:host=$host;dbname=$dbname",$dbuser,$dbpass,$dbConn=null);
     } catch (PDOException $e) {

       // echo komutunu koymak isterseniz koyabilirsiniz
       # ikinci bir kontrol yaptığım için bu alanda kullanmıyorum.
        $e->getMessage();
     }

     // veri tabanı bağlantısı başarıyla gerçekleşmiş ise verilerimizi bir diziye aktarıyoruz
     if (isset($db) && $db == true) {

       // veri tabanı bağlantısı kurduğumuz değişkenleri diziye aktarıyoruz.
       # aynı değerler ile DbImport() içerisinde tekar işlem yapacağız
      return $dbConn = [$host,$dbname,$dbuser,$dbpass];
       // gelen değerleri aşağıdan kontrol edebilirsiniz
      // print_r($dbConn);

     }else{

       // eğer bir hata meydana gelmiş ise belirtelim
       echo "<b>".$e->getFile()."</b>"." dosyasında <b>".$e->getLine()."</b> satırında <b>".$e->getMessage()."</b> hatası ile karışlaşıldı.";

     }
  }

  // tanımlanan sql dosyamızı bağlanmış olduğumuz veritabanına import ettiriyoruz
  public function DbImport($dbConn,$sql,$importOK=null){

    // DbConnect() fonksiyonundan gelen dizimizi alabilmiş miyiz kontrol edelim
    // print_r($dbConn);

    // $dbConn içerisindeki verileri kullanarak veri tabanımızı import ettirelim
    # $dbConn değişkenimiz bir dizimi değil mi onu kontrol edelim
    if (is_array($dbConn)) {

      # dizi içerisindeki verileri değişkenlerimize atayalım
      $filename = $sql;
      $username = $dbConn[2];
      $password = $dbConn[3];
      $database = $dbConn[1];
      $host = $dbConn[0];

      # import class'ımızdan yararlanıyoruz.
      $import = new Import($filename, $username, $password, $database, $host);
      $this->importOK = "ok";
      return $this->importOK;

      }else{

        // import işlemi gerçekleşmemiş ise hata mesajımızı ekrana bastıralım
        echo "kullanıcı adı, şifre, şifre ve veri tabanı belirtilmemiş tanımlanmamış";
      }

  }

  // kullanıcı bilgileri veri tabanı kurulduktan sonra eklensin istiyorsak aşağıdaki fonksiyonu kullanabiliriz
  public function UserInfo($username,$password,$mail,$dbConn,$db=null){

      // echo $this->importOK;

      //veri tabanı bağlantımızı tekrar kuruyoruz.
      $db = new PDO("mysql:host=$dbConn[0];dbname=$dbConn[1]",$dbConn[2],$dbConn[3]);

      if (isset($this->importOK) && $this->importOK == "ok" ) {
          $sorgu = $db->prepare("INSERT INTO kullanicilar SET mail = ?, username = ?, password  = ?");
          $insert = $sorgu->execute([
            $mail, $username, $password
          ]);
          if (isset($insert)) {
            return $db;
          }
      }
  }
  // gelen verilerimiz ile beraber veri tabanı bağlantımızı kalıcı hale getiriyoruz.
  public function Save($dbConn){
    // ilk olarak bir txt dosyası oluşturup veri tabanı bağlantımızda kullandığımız verileri yazdırıyoruz
    $db = new PDO("mysql:host=$dbConn[0];dbname=$dbConn[1]",$dbConn[2],$dbConn[3]);

    // txt dosyasına yazdıracak olduğumuz değerleri belirliyoruz.
    $content = $dbConn[0].".".$dbConn[1].".".$dbConn[2].".".$dbConn[3];
    if (isset($db) && $db == true) {

      $file = touch('db.txt');
      $file_o = fopen('db.txt','w+');
      $file_w = fwrite($file_o,$content);
      $file_c = fclose($file_o);
      // çerez oluşturuyoruz
      setcookie("end","ok",time()+3600);
      # oluşturulan dosya içeriği config.php dosyasına kaydettirilir ve bağlantı kalıcı olarak sağlanmış olur.
      # yönlendirme yaparak test edelim
      //header("Location:config.php");

    }else{
      echo "db not found";
    }
  }
  function __destruct(){

    if (isset($_COOKIE['end']) && $_COOKIE['end'] == "ok") {

      header("Location:end.php");

    }

  }
}


 ?>
