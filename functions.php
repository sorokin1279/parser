<?php 
require_once "./lib/simple_html_dom.php";
require_once "./view.php";


class subparser {
private $url;
private $referer;
private $ch;
private $res;
private $dom;
private $html;
private $listt1, $listt2, $list1, $list2, $list, $listh, $listp, $data;


public function __construct($url,$i=0) {
   
$this->url = $url;
$this->referer = "https://www.google.com" ;
$this->ch = curl_init();
curl_setopt($this->ch, CURLOPT_URL, $this->url);
curl_setopt($this->ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.3) 
AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36');
curl_setopt($this->ch, CURLOPT_REFERER, $this->referer);
curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
$this->res = curl_exec($this->ch);
$this->dom = new simple_html_dom();
$this->html = str_get_html($this->res);
$this->listt1 = $this->html -> find('div.b_nav a');//категории
$this->listt2 = $this->html -> find('div.b_menu-item a');//категории
$this->list = array_values(array_unique(array_merge($this->listt1, $this->listt2)));//категории
$this->list2 = $this->html -> find('div.b_ear-title a');//посты
$this->list3 = $this->html -> find('img');//изобра
$this->listh = $this->html -> find('.headline');//страница поста
$this->listp = $this->html -> find('div.b_article-text p');
$this->data = $this->html -> find('span.date');
$this->time = $this->html -> find('time');


}

public function start_page() {
    $view = new view();
    $view->start_page();
}

public function errors() {
    $str = "Вы не выбрали информацию для отображения!";
    $view = new view();
    $view->errors($str);
}

public function info() {
$bw = $this->best_words();
$dc = $this->data_time();
$alfa = $this->alfa();
$view = new view();
$view->info($bw, $dc, $alfa);
}

public function best_words() {
    $size = sizeof($this->list);
    $words = array();
    for($i=0;$i<$size;$i++) { //cписок категорий

    $cl = "https://www.gazeta.ru";
    $url = parse_url($this->list[$i]->href);
    $cl .= $url['path'];
    $p1 = new subparser($cl);
    $size2 = sizeof($p1->list2);
    
        for($j=0;$j<4;$j++) { //список постов
        
        $cl2 = "https://www.gazeta.ru";
        $url2 = parse_url($p1->list2[$j]->href);
        $cl2 .= $url2['path'];
        
        
        $p2 = new subparser($cl2);
         
      
            foreach ($p2->listp as $key => $value) { //сам пост
            
            $str .= $p2->listp[$key]->plaintext;
           

          
           }  
        }
    }
       $words = array_count_values(str_word_count($str, 1, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя"));
      $size = sizeof($words);
      arsort($words);
      $i=0;
      foreach($words as $item => $item_count) {
        if(strlen(trim($item))>5) {
        $best_words[$i][0] = $item;
        $best_words[$i][1] = $item_count;
        $i++;
            }
        }
   
   
    return $best_words;
}

public function alfa() {
    $size = sizeof($this->list);

    for($i=0;$i<$size;$i++) { //cписок категорий
       
    $cl = "https://www.gazeta.ru";
    $cl .= $this->list[$i]->href;
    $p1 = new subparser($cl);
    $size2 = sizeof($p1->list2);
    
        for($j=0;$j<4;$j++) { //список постов
        
        $cl2 = "https://www.gazeta.ru";
        $cl2 .= $p1->list2[$j]->href;
        $p2 = new subparser($cl2);
         
      
            foreach ($p2->listp as $key => $value) { //сам пост
            
            $str = $p2->listp[$key]->plaintext;

            $patt = '~(?<vowels>[аеёиоуыэюя])|(?<conson>[бвгджзйклмнпрстфхцчшщъь])~iu';
            preg_match_all($patt, $str , $a);

            $vowels = $vowels + count(array_filter($a['vowels']));
            $conson = $conson + count(array_filter($a['conson']));
            
                
            }  
        }
    }
    $alfa = array("0" => $conson, "1" => $vowels);
  
    return $alfa;
}

public function data_time() {
    $data = '01.01.1970';
    $count=0;
    $i = 0;
    $j = sizeof($this->list);
    $array = $this->list;
    $time_counth = 0;
    $time_countd = 0;
    while($i<=$j) {
    $i++;
    $cl = "https://www.gazeta.ru";
    $cl .= $array[$i]->href;
    $per = new subparser($cl);
    $j2 = sizeof($per->list2);
    $count = $count + count($per->list2);
    for($q=0;$q<=$j2;$q++) {
        if($q<$j2) {
    $start_date = new DateTime($per->time[$q]->datetime);
    $since_start = $start_date->diff(new DateTime($per->time[$q+1]->datetime));
    $time_counth = $time_counth + $since_start->h;
    $time_countd = $time_countd + $since_start->d;
    
        }
      $res = (strtotime($data)<strtotime($per->time[$q]->datetime));
        if($res==true) {
            $data = $per->time[$q]->datetime;
            }
        }
    }
    
    $time_counth = round($time_counth / $count);
    $time_countd = round($time_countd / $count);
    $dc = array("0" => $data, "1" => $count, "2" => $time_counth, "3" => $time_countd );
   
    return $dc;
}

public function col($key) {
    $cl = "https://www.gazeta.ru";
    $cl .= $this->list[$key]->href;
    $per = new subparser($cl);
    $count = count($per->list2);
    unset($per);
    return $count;
}

public function elements_cat() {
$j;$vr;$a;$coll;$help2;

$size = sizeof($this->list)-1;
for ($i = $size; $i>=0; $i--) {
    
  for ($j = 0; $j<=$i-1; $j++) {
      
      if($this->col($j)>$this->col($j+1)) {
       $help2 = $this->col($j); 
        $coll[$j] = $this->col($j+1);
        $coll[$j+1] = $help2;
        
  }
  
    if($this->col($j) > $this->col($j+1)) {
       
        $help = $this->list[$j];
        $this->list[$j] = $this->list[$j+1];
        $this->list[$j+1] = $help;
        
    }
  }
}
$ar2 = $coll;
$ar = $this->list;
$thiss = $this->list;
$view = new view();
$view->elements_cat($ar, $thiss, $ar2);
}


public function elements_post() {
$arpo = $this->list2;
$arpo2 = $this->list3;
$thiss = $this->list;
$view = new view();
$view->elements_posts($arpo, $arpo2, $thiss);
}


public function elements_page() {
$arp = $this->listp;
$arp2 = $this->listh;
$view = new view();
$view->elements_page($arp, $arp2);
}
}


