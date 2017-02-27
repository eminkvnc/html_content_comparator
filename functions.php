<?php

class fonksiyonlar
{
	function veritabani_baglan($host, $username, $password, $dbname)
	{
		$link = mysql_connect($host, $username, $password);
		if (!$link) {die('Bağlanamadı: ' . mysql_error());}

		$db_selected = mysql_select_db($dbname, $link);
		if (!$db_selected) {die ('database kullanılamaz: ' . mysql_error());}

		$query=mysql_query("SET NAMES UTF8");
		$result=mysql_query($link,$query);
		return $link;
	}


	function html_al($satir)
	{
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, trim($satir));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$html=curl_exec($ch);
	#echo $html;
	$answer=curl_getinfo($ch);
	
	#$statusCodes=parse_ini_file("http_status_codes.ini");
	curl_close($ch);
	if($answer["http_code"]=='200' || $answer["http_code"]=='201' || $answer["http_code"]=='202' || $answer["http_code"]=='203')
	{
	#$html= file_get_html('http://'.trim($satir));
	echo $satir."  ".$answer["http_code"]."<br>";
	return $html;
	}
	else
	{echo "html alinamadi!<br>";}
	}
	
	
	function title_bul($html)
	{
	if (preg_match('/<title>(.*?)<\/title>/', $html, $titlematches)) 
    {	$titlewords=array(); 
        #echo $titlematches[1];
        $titlewords = explode(" ", $titlematches[1]);
    }	
		return $titlewords;
	}
	
	
	function tag_bul($html)
	{
		
		if(preg_match('<meta name="keywords" content="(.*?)">',$html,$tagmatches))
     {	$tagwords=array();
	    #echo $tagmathes[1];
		$tagmatches1 = explode(",", $tagmatches[1]);  
	 } 
	      for($n=0;$n<count($tagmatches1);$n++)
	      {
			$tagwords=explode(" ", $tagmatches1[1]); 
		  } 
		return $tagwords;
	}
	
	
	function tagd_bul($html)
	{
		
		if(preg_match('<meta name="description" content="(.*?)">',$html,$tagdmatches))
     {	$tagdwords=array();
	    #echo $tagdmathes[1];
		$tagdmatches1 = explode(",", $tagdmatches[1]);  
	 }
	      
	      for($n=0;$n<count($tagdmatches1);$n++)
	      {
			$tagdwords=explode(" ", $tagdmatches1[1]); 
		  } 
		return $tagdwords;
	}
	
	
	function domain_name_yaz($satir,$link)
	{
		$query=mysql_query("INSERT INTO `Domains`(`DOMAIN_NAME`) VALUES ('$satir')");
		if(!$query){die("domain name yazilamadi");}
		$result=mysql_query($link,$query);
	}
	
	
	function domain_name_varmi($satir,$link)
	{
		$query=mysql_query("SELECT ID FROM Domains WHERE DOMAIN_NAME = ('$satir')");
		$b=mysql_fetch_array($query);
		$domain=$b['ID'];
		if(!$domain){return true;}
		else{return false;}
	}
	
		
	function title_yaz($titlewords,$domain_id,$link)
	{
		for($j=0; $j<count($titlewords) ; $j++)
        {			#istenmeyen karakter ve kelimeleri filtrele
			$titlewords[$j]=strtolower($titlewords[$j]);
			if($titlewords[$j]!=NULL && $titlewords[$j]!='-' && $titlewords[$j]!='|' && $titlewords[$j]!='and'
									 && $titlewords[$j]!='for' && $titlewords[$j]!='&' && $titlewords[$j]!='the'
									 && $titlewords[$j]!='in' && $titlewords[$j]!='of' && $titlewords[$j]!='at'
									 && $titlewords[$j]!='is' && $titlewords[$j]!='a' && $titlewords[$j]!='by'
									 && $titlewords[$j]!='from' && $titlewords[$j]!='or' && $titlewords[$j]!='to'
									 && $titlewords[$j]!='ve' && $titlewords[$j]!='ise' /*&& $titlewords[$j]!=''*/)
			{	$query=mysql_query("INSERT INTO `Title`(`TITLE_WORD`,`DOMAIN_ID`) VALUES ('$titlewords[$j]','$domain_id')");
				if(!$query){echo "domain_id ve title yazilamadi";}
				$result=mysql_query($link,$query);
			}
	    }
	}
	
	
	function tag_yaz($tagwords,$domain_id,$link)
	{
		for($k=0; $k<count($tagwords) ; $k++)
		{			#istenmeyen karakter ve kelimeleri filtrele
			$tagwords[$k]=strtolower($tagwords[$k]);
			if($tagwords[$k]!=NULL && $titlewords[$j]!='-' && $titlewords[$j]!='|' && $titlewords[$j]!='and'
								   && $titlewords[$j]!='for' && $titlewords[$j]!='&' && $titlewords[$j]!='the'
								   && $titlewords[$j]!='in' && $titlewords[$j]!='of' && $titlewords[$j]!='at'
								   && $titlewords[$j]!='is' && $titlewords[$j]!='a' && $titlewords[$j]!='by'
								   && $titlewords[$j]!='from' && $titlewords[$j]!='or' && $titlewords[$j]!='to'
								   && $titlewords[$j]!='ve' && $titlewords[$j]!='ise' /*&& $titlewords[$j]!=''*/)
			{	$query=mysql_query("INSERT INTO `Tag`(`TAG_WORD`,`DOMAIN_ID`) VALUES ('$tagwords[$k]','$domain_id')");
				if(!$query){echo "tag yazilamadi";}
				$result=mysql_query($link,$query);
			}
		}

	}
	
	
	function tagd_yaz($tagdwords,$domain_id,$link)
	{
		for($l=0; $l<count($tagdwords) ; $l++)
		{			#istenmeyen karakter ve kelimeleri filtrele
			$tagdwords[$l]=strtolower($tagdwords[$l]);
			if($tagdwords[$l]!=NULL && $titlewords[$j]!='-' && $titlewords[$j]!='|' && $titlewords[$j]!='and'
								    && $titlewords[$j]!='for' && $titlewords[$j]!='&' && $titlewords[$j]!='the'
								    && $titlewords[$j]!='in' && $titlewords[$j]!='of' && $titlewords[$j]!='at'
								    && $titlewords[$j]!='is' && $titlewords[$j]!='a' && $titlewords[$j]!='by'
								    && $titlewords[$j]!='from' && $titlewords[$j]!='or' && $titlewords[$j]!='to'
								    && $titlewords[$j]!='ve' && $titlewords[$j]!='ise' /*&& $titlewords[$j]!=''*/)
			{	$query=mysql_query("INSERT INTO `Tag`(`TAG_WORD`,`DOMAIN_ID`) VALUES ('$tagdwords[$l]','$domain_id')");
				if(!$query){echo "tag yazilamadi";}
				$result=mysql_query($link,$query);
			}
		}	
	}
	
	
	function domain_id_al($satir)
	{
		$query=mysql_query("SELECT ID FROM Domains WHERE DOMAIN_NAME = ('$satir')");
		if(!$query){die("hata");}
		$b=mysql_fetch_array($query);
		$domain_id=$b['ID'];
		return $domain_id;
	}
		
	
	function domain_name_al($domain_id)
	{
		$query=mysql_query("SELECT DOMAIN_NAME FROM Domains WHERE ID = ('$domain_id')");
		if(!$query){die("hata");}
		$b=mysql_fetch_array($query);
		$domain_name=$b['DOMAIN_NAME'];
		return $domain_name;
	}
	
	
	function category_name_al($category_dir)
	{
		$category=explode("/",$category_dir);
		$category_name=$category[count($category)-2];
		return $category_name;
	}
	
	
	function db_category_id_al()
	{
		$query=mysql_query("SELECT ID FROM Categories");
		if(!$query){die("hata");}
		while($b=mysql_fetch_array($query))
		{
			$category_id[]=$b['ID'];
		}
		return $category_id;
	}
	
	
	function category_id_al($category_name,$link)
	{
		$query=mysql_query("SELECT ID FROM Categories WHERE Category = ('$category_name')");
		if(!$query){die("hata");}
		$b=mysql_fetch_array($query);
		$category_id=$b['ID'];
		return $category_id;
	}


	function category_id_yaz($category_id,$satir,$link)
	{	
		$query=mysql_query("UPDATE  Domains SET CATEGORY_ID=$category_id WHERE DOMAIN_NAME='$satir'");
		if(!$query){echo("category id yazilamadi");}
		$result=mysql_query($link,$query);
	}
	
	
	function title_al($category_id)
	{
		$query=mysql_query("SELECT Title.TITLE_WORD FROM Title , Domains WHERE Domains.CATEGORY_ID =('$category_id') AND Domains.ID = Title.DOMAIN_ID");
		if(!$query){die("hataa");}
		while($b=mysql_fetch_array($query))
		{
			$title_words[]=$b['TITLE_WORD'];
		}
		return $title_words;
	}
	
	
	function tag_al($category_id)
	{
		$query=mysql_query("SELECT Tag.TAG_WORD FROM Tag , Domains WHERE Domains.CATEGORY_ID =('$category_id') AND Domains.ID = Tag.DOMAIN_ID");
		if(!$query){die("hata");}
		while($b=mysql_fetch_array($query))
		{
			$tag_words[]=$b['TAG_WORD'];	
		}
		return $tag_words;
	}
	
	
	function category_bul($titlewords,$tagwords,$tagdwords,$db_category_id)
	{
		
		for($i=0;$i<count($db_category_id);$i++)
	{
		$db_title_words=$this->title_al($db_category_id[$i]);
		if(count($db_title_words))
		{
		if(count($titlewords))
		{
			echo "<br>".count($db_title_words)." title<br>";
			$titlesayac=0;
			$titlecount=array_count_values($db_title_words);
			for($j=0;$j<count($db_title_words);$j++)
			{
				for($m=0;$m<count($titlewords);$m++)
				{
					if($titlecount[$db_title_words[$j]]>4)
					{
						$temp=array_unique($db_title_words);
						if($temp[$j]==$titlewords[$m] && $temp[$j]!=NULL && $titlewords[$m]!=NULL){$titlesayac++;}
						
					}
				}
			}
			$titleoran[$i]=$titlesayac/count($titlewords);
		}
		else {$titleoran[$i]=0;}
		
		echo $titlesayac."bölü".count($titlewords);
		echo "<br>".$titleoran[$i]." titleoran<br>";
		
		}
		$db_tag_words=$this->tag_al($db_category_id[$i]);
		if(count($db_tag_words))
		{
		if(count($tagwords))
		{
			echo "<br>".count($db_tag_words)." tag<br>";
			$tagsayac=0;
			$tagcount=array_count_values($db_tag_words);
			for($k=0;$k<count($db_tag_words);$k++)
			{
				for($n=0;$n<count($tagwords);$n++)
				{
					if($tagcount[$db_tag_words[$k]]>4)
					{
						$temp=array_unique($db_tag_words);
						if($temp[$k]==$tagwords[$n] && $temp[$k]!=NULL && $tagwords[$n]!=NULL){$tagsayac++;}
						
					}
				}
				
			}
			$tagoran[$i]=$tagsayac/count($tagwords);
		}
		else {$tagoran[$i]=0;}
		
		echo $tagsayac."bölü".count($tagwords);
		echo "<br>".$tagoran[$i]." tagoran<br>";
		
		if(count($tagdwords))
		{
			$tagdsayac=0;
			for($l=0;$l<count($db_tag_words);$l++)
			{
				for($o=0;$o<count($tagdwords);$o++)
				{
					if($tagcount[$db_tag_words[$l]]>4)
					{
					$temp=array_unique($db_tag_words);
					if($temp[$l]==$tagdwords[$o] && $temp[$l]!=NULL && $tagdwords[$o]!=NULL){$tagdsayac++;}
					}
				}
				
			}
			
			$tagdoran[$i]=$tagdsayac/count($tagdwords);
		}
		else {$tagdoran[$i]=0;}
		echo $tagdsayac."bölü".count($tagdwords);
		echo "<br>".$tagoran[$i]." tagdoran<br>";
		
		}
			
		$puan[$i]=($titleoran[$i]+$tagoran[$i]+$tagdoran[$i]);
		
		
			unset($db_title_words);
			unset($db_tag_words);
	}
		$max_puan=max($puan);
		print_r($puan);
		echo "<br> max puan".$max_puan."<br>";
		for($z=0;$z<count($puan);$z++)
		{
			if($puan[$z]==$max_puan){$category_id=$db_category_id[$z];}
		}
		return $category_id;
	}
	
	
	
	
	
	
	
	
	
	
}
	
	
	


?>
