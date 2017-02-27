
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

<?php
include('simple_html_dom.php');
include('functions.php');

$fonksiyonlar=new fonksiyonlar();

$link=$fonksiyonlar->veritabani_baglan('127.0.0.1', 'root', 'eminkvnc','test');

$domainfile="domaintest";

$domain=(fopen($domainfile,"r"));

if(!$domain){echo("Domain Dosyasi Acilamadi!");}
    $q=0;
   
while(!feof($domain))
{
	$satir=fgets($domain);

	$kontrol=$fonksiyonlar->domain_name_varmi($satir,$link);

	if($kontrol)
	{
		$html=$fonksiyonlar->html_al($satir);
		
		if($html)
		{
			
			#domainden kelimeleri al.
			$titlewords=$fonksiyonlar->title_bul($html);
		
			$tagwords=$fonksiyonlar->tag_bul($html);
		
			$tagdwords=$fonksiyonlar->tagd_bul($html);
		
			#kelimeleri db den gelen kelimelerle karşilastirarak kategori bul.
			$db_category_id=$fonksiyonlar->db_category_id_al();
			
			$category_id=$fonksiyonlar->category_bul($titlewords,$tagwords,$tagdwords,$db_category_id);
			
			#domaini db ye kategori id ile yaz.
			$fonksiyonlar->domain_name_yaz($satir,$link);
		
			$fonksiyonlar->category_id_yaz($category_id,$satir,$link);
	        
			$domain_id=$fonksiyonlar->domain_id_al($satir);
			   
			#kelimeleri db ye domain id ile yaz.
			$fonksiyonlar->title_yaz($titlewords,$domain_id,$link);
		 
			$fonksiyonlar->tag_yaz($tagwords,$domain_id,$link);
		 
			$fonksiyonlar->tagd_yaz($tagdwords,$domain_id,$link);
		}
	
	}
	   
	else {echo $satir." veritabaninda mevcut.<br>";}
	$q++;
}
	echo "<br><br>while ".$q." kez donuyor.<br><br>";
	  mysql_close($link);
      fclose($domain);
?>

