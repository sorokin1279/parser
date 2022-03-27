<?php 
require_once "./lib/simple_html_dom.php";
require_once "./functions.php";
require_once "./view.php";

class controller {
    
private $get, $post, $session;

public function __construct() {

   $this->get = $_GET['href']; 
   $this->get2 = $_GET['page'];
   $this->post = $_POST['select'];
   $this->session = $_SESSION;

}
public function start() {
    
    
    if(isset($this->get)) {
        
    $this->gett($this->get);
        
    }
    if(isset($this->get2)) {
    $this->gett($this->get2);
    }
    elseif(isset($this->post)) {

    $this->postt($this->post);
        
    }
    else {
        
    $this->pusto();
        
    }
}
public function postt($value) {
    $url = "https://www.gazeta.ru/";
    $parser = new subparser($url);
    $parser->start_page();
    switch ($value) {
    case 1:
        $parser->errors();
        break;
    case 2:
        $parser->elements_cat();
        break;
    case 3:
        $parser->elements_post();
        break;
    case 4:
        $parser->info();
        break;
    }
    
    }
    
public function gett($href) {
    
    $url1 = "https://www.gazeta.ru/";
    $url="https://www.gazeta.ru".$href;
    $o = strripos($href, "shtml");
    
    
    if($o==false) {
       
        $pars_page = new subparser($url1);
        $pars_page->start_page();
        $pars_page->elements_post();
    }
    else {
        
        $pars_page = new subparser($url);
        $pars_page->start_page();
        $pars_page->elements_page();
    }
    
}

public function pusto() {
    $url = "https://www.gazeta.ru/";
    $parser = new subparser($url);
    $parser->start_page();
    
    }
}

$controller = new controller();
$controller->start();



?>