<?php
require_once "./lib/simple_html_dom.php";
require_once "./functions.php";
require_once "./header.php";
class view {
private $parser;
private $url;

public function __construct($parser = NULL, $url = NULL) {
    $this->parser = $parser;
    $this->url = $url;
}

public function start_page() { 
    
 echo "<form action='index.php' method='post'>
       <select name='select'> 
        <option value='1'  selected>Выберите что отобразить</option>
        <option value='2'>Категории</option>
        <option value='3'>Посты</option>
        <option value='4'>Информация</option>
        </select>
        <p><input type='submit' value='Показать'></p></form> ";
}

public function errors($str) {
    echo $str;
}

public function info($bw, $dc, $alfa) {
echo "<h3>10 популярных слов среди всех постов:" .  "</h3>";
echo '<table style="border:1px solid black;text-align: center;"><tr style="border: 1px solid black;"><td style="border: 1px solid black;">Слово</td><td style="border: 1px solid black;">Кол-во</td>';
for($i=0;$i<=9;$i++) {
    echo '<tr style="border:1px  solid black;text-align: center;"><td style="border: 1px solid black;text-align: center;">'. $bw[$i][0]. '</td><td style="border: 1px solid black;text-align: center;">' . $bw[$i][1] . "</td></tr>";
}
echo '</table>';
echo "<h3>Cреднее количество букв среди всех постов: " .  round($alfa[1]/$dc[1]) ." - Гласных" . round($alfa[0]/$dc[1]) . " - Согласных" . "</h3>";
echo "<h3>Cледующий пост через(на основании всех постов): " .  $dc[3] ."Дней" . $dc[2] . "Часов" . "</h3>";
echo "<h3>Дата последнего поста(среди всех постов): " .  $dc[0] . "</h3>";
echo "<h3>Количество всего постов на сайте: " .  $dc[1] . "</h3>";
}

public function data_time($dc) {
echo "<h3>Cледующий пост через(на основании всех постов): " .  $dc[3] ."Дней" . $dc[2] . "Часов" . "</h3>";
echo "<h3>Дата последнего поста(среди всех постов): " .  $dc[0] . "</h3>";
echo "<h3>Количество всего постов на сайте: " .  $dc[1] . "</h3>";
return $dc;
}

public function best_words($bw) {
echo "<h3>10 популярных слов среди всех постов:" .  "</h3>";
echo '<table style="border:1px solid black;text-align: center;"><tr style="border: 1px solid black;"><td style="border: 1px solid black;">Слово</td><td style="border: 1px solid black;">Кол-во</td>';
for($i=0;$i<=9;$i++) {
    echo '<tr style="border:1px  solid black;text-align: center;"><td style="border: 1px solid black;text-align: center;">'. $bw[$i][0]. '</td><td style="border: 1px solid black;text-align: center;">' . $bw[$i][1] . "</td></tr>";
}
echo '</table>';
return $bw;
}

public function alfa($alfa) {
echo "<h3>Cреднее количество букв! среди всех постов: " .  round($alfa[1]/$dc[1]) ." - Гласных" . round($alfa[0]/$dc[1]) . " - Согласных" . "</h3>";
return $alfa;
}
public function col($key) {
    $cl = "https://www.gazeta.ru";
    $cl .= $this->list[$key]->href;
    $per = new subparser($cl);
    $count = count($per->list2);
    unset($per);
    return $count;
}
public function elements_cat($ar, $thiss, $coll) {
$j;$vr;$a;
//echo $this->url . "<br>";
$j=0;
foreach ($ar as $key => $value) {
   
    if($j == 0) {
        
        echo "<div class='row'>";
        
        $j++;
    }
    elseif($j % 3 == 0) {
        
        echo "</div><div class='row'>";
        
        $j++;
    }
    else { $j++; }
    
    echo "<div class='el'>";
    echo $coll[$key];
    echo "<h3 class='elh'>" . $ar[$key]->plaintext . "</h3>";
    echo '<a href="index.php?href='.$ar[$key]->href.'">Перейти в категорию</a><br>';
    echo "</div>";
    
    $vr =  $ar[$key]->plaintext;
    $a = $a + str_word_count($vr,0,  "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя");
 
   
    }
    
echo "</div>";
echo "<h3>Количество категорий на странице:" .  count($ar) . "</h3>";
echo "<h3>Cреднее количество слов в заголовках категорий:" .  round($a/$key) . "</h3>";
return $ar;
}

public function elements_posts($arpo, $arpo2, $thiss) {
$j;$vr;$a;
//echo $this->url;
$gett = $_GET['page'];
if (isset($gett)) {
    
    $i2 = $gett * 5;
    $key = $i2 - 4;
}
else {
    $i2 = 5;
    $key = $i2 - 4;
}
$j = $l2-4;

for ($key;$key<=$i2;$key++) {

    if($j == $l2-4) {
        
        echo "<div class='row'>";
        
        $j++;
    }
    elseif($j-($l2 - 4)==3) {
        
        echo "</div><div class='row'>";
        
        $j++;
    }
    else { $j++; }
    
    echo "<div class='el'>";
    echo "<h3 class='elh'>" . $arpo[$key]->plaintext . "</h3><br>";
    echo "<img  src='" . $arpo2[$key]->src . "'><br>";
    echo '<a href="index.php?href='.$arpo[$key]->href.'">Перейти на страницу</a>';
  
    echo "</div>";
    
    $vr =  $arpo[$key]->plaintext;
    $a = $a + str_word_count($vr,0,  "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя");
 
    }
    
echo "</div>";
$pagination = count($thiss)/5;
echo "<div class='pagin'>";
for($i=1;$i<=$pagination;$i++) {
    echo '<a href="index.php?page='.$i.'">'.$i.'</a><br>';
}
echo "</div>";
echo "<h3>Количество постов на странице:" .  count($thiss) . "</h3>";
echo "<h3>Cреднее количество слов в посте:" .  round($a/$key) . "</h3>";
return $arpo;
}

public function elements_page($arp, $arp2) {
$j;$vr;$a;
//echo $this->url;
foreach ($arp as $key => $value) {

   
    echo "<div>";
    echo "<h2>" . $arp2[$key]->plaintext . "</h2><br>";
    echo "<p>" . $arp[$key]->plaintext . "</p><br>";
    echo "</div>";
    $vr =  $arp[$key]->plaintext;
    $a = $a + str_word_count($vr,0,  "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя");
 
   
    }

echo "<h3>Cреднее количество слов на странице:" .  round($a/$key) . "</h3>";

return $arp;    
}

}




require_once "./footer.php";

?>