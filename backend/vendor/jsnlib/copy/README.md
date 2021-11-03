# jsnlib-copy
複製來源圖片到指定路徑，還可以縮圖處理。

## 使用方式
````php
require_once '../vendor/autoload.php';

$Urlimg = new Jsnlib\Copy\Urlimg;


//1.想要儲存的絕對路徑
$savedir            =   realpath("demo_savedir");

//2. 指定圖片網址，並COPY兩張縮小的、兩張原圖
$Urlimg
	->url("http://www.xxx.tw/upload/Ad/File_2016082620423388.JPG")
	->resize(100, 100, 1, $savedir, "s.jpg") //不指定檔名就是自動產生檔名
	->resize(100, 100, 10, $savedir, "s2.jpg")
	->org($savedir, "o.jpg")
	->copy();


//3. 檢視成果
$result = $Urlimg->result();

if ($result !== false) foreach ($result as $file)
{
	echo $file."<br>";
}
````

## url($url): object
設定來源網址

## resize(int $width, int $height, int $quantity, string $save_dir, string $newname = NULL): object
儲存縮圖
- width (int) 重新縮圖的寬度
- height (int) 重新縮圖的高度
- quantity (int) 縮圖品質 0% ~ 100%
- save_dir (string) 儲存的絕對路徑
- newname (string)(選) 儲存的檔名

## org(string $save_dir,string  $newname = NULL): object
儲存原圖
- save_dir (string) 儲存的絕對路徑
- newname (string)(選) 儲存的檔名

## copy($mode = 0775): object
運行複製
- mode 權限值

## result()
取得結果
