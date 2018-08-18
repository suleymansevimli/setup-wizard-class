# Setup Wizard Class
  Wordpress kurulumu yaparken kullanılan sistemin benzeri bir uygulama. Belirlemiş olduğunuz host üzerinde ilk önce veri tabanı bağlantınızı
kuruyorsunuz. Bağlantı sonunda import edilmesini istediğiniz veri tabanını seçiyorsunuz ve import edilen veri tabanına insert etmek istedği
niz kullanıcı adı, mail ve şifrenizi belirleyip kurulumunuzu tamamlıyorsunuz.
## Kurulum Nasıl Yapılır ?
  Örneğin index.php sayfasında bu class'ı kullanmak istediğinizi var sayıyorum;
   Class'ı kullanabilmeniz için <b>composer</b> uygulamasının bilgisayarınızda kurulu olması gerekmektedir.Aşağıdaki komutu terminal ekranında classın bulunduğu dizinde çalıştırmalısınız.
  
   <code> composer require thamaraiselvam/mysql-import </code>
   

---

```php
// Class'ımızı sayfamıza dahil ediyoruz.
  require_once 'DbClass';
  
 // Class'ımızı tanımlıyoruz
 $test = new DbClass;
 
 // diğer fonksiyonlarımızda kullanmak için veri tabanı bağlantı fonksiyonumuzu bir değişkene aktarıyoruz
 $dbInfo =  $test->DbConnect('localhost','test','root','password');

  // Bağlantı fonksiyonumuzu kullanıyoruz ve import etmek istediğimiz veri tabanını seçiyoruz 
  # örnek olan veri tabanı aynı dizin içerisinde olduğu varsayılmıştır.
  $test->DbImport($dbInfo,'example.sql');
 
  // Insert edilecek kullanıcı adı şifre ve mailimizi tanımlıyoruz 
  $test->UserInfo("username","password","mail@mail.com",$dbInfo);

  // ve yaptığımız değişiklikleri kayıt ettiriyoruz
  $test->Save($dbInfo);
```

---
