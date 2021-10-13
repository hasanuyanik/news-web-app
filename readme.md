# News Web App

### Proje Açıklaması

Proje 13 Ekim 2021 gün bitimine kadar bu şekilde kodlanmıştır.
Değerlendirme sonrası proje kodlanıp, tamamlanacaktır.

### Kullanım

- Öncelikle projeyi klonlayın.

```
git clone https://github.com/hasanuyanik/news-web-app.git
```

- Proje içerisindeki backend -> public klasörünün server'ınızda indeklenen dizin olarak tanımlayın.

- newsapp.sql dosyasını phpmyadmin'de içe aktarmalısınız.

- backend içerisindeki config.php'de veritabanı ayarlarınızı düzenlemelisiniz.

### Config Ayarlar

- Veritabanı bilgilerinin düzenlenebilmesi.
- Sayfalamalarda sayfa başına düşecek veri sayısı belirlenebilmesi
- Loglama tipinin seçilmesi
- Dosya yükleme işlemlerinde path değerinin düzenlenebilmesi 

### Geliştirme 

- backend klasörü içerisinde `PHP` ile backend geliştirmesini yapabilirsiniz.
- frontend klasörü içerisinde `React` ile frontend geliştirme yapabilirsiniz.
- `Front-End için;` frontend klasörü içerisindeki işlemleriniz backend -> public içerisindeki görüntüyü etkilemez.
frontend klasörü geliştirme ortamıdır.
Geliştirdiğiniz frontend klasöründeki kodu ```npm run build``` ile frontend-> build klasöründe oluşan dosyaları public içerisine kopyalayabilirsiniz.
- frontend arayüz sizde çalışmıyorsa frontend klasörü içerisindeki `package.json` dosyasındaki ``` "proxy": "http://localhost" ``` kodunda localhost yerine host değerinizi girebilirsiniz.
- frontend klasöründeki geliştirmenin bilgisayarınızda lokal çalışması için `node.js` kullanmalısınız.

#### Uygulama içi Bölünlendirmeler

- Activity
- Auth
- Category
- Comment
- Database
- Encoder
- FileManager
- Logger
- News
- Permission
- Relations
- Resource
- Role
- User

```Şifreler veritabanında hashli ve tuzlu saklanmaktadır :)```

### Kullanılan Dil, Teknoloji, Programlar

- PHP
- React
- Node.js
- PhpStorm


`teknasyon` `patika.dev` `bootcamp` `haber uygulaması` `bitirme projesi`