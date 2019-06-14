<?php  
 
class Mylibrary
{
 
    public function __construct()
    {
        $this->ci =& get_instance();
    }
 
    function do_in_background($url)
    {
        $parts = parse_url($url);
            $errno = 0;
        $errstr = "";
 
       //Use SSL & port 443 for secure servers
       //Use otherwise for localhost and non-secure servers
       //For secure server
        $fp = fsockopen($parts['host'], isset($parts['port']) ? $parts['port'] : 80, $errno, $errstr, 30);
        //For localhost and un-secure server
       //$fp = fsockopen($parts['host'], isset($parts['port']) ? $parts['port'] : 80, $errno, $errstr, 30);
        if(!$fp)
        {
            echo "Some thing Problem";    
        }
        $out = "POST Aztecas/Cotizaciones HTTP/1.1\r\n";
        $out.= "Host: http://127.0.0.1\r\n";
        $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out.= "Content-Length: \r\n";
        //$out.= "Connection: Close\r\n\r\n";
        fwrite($fp, $out);
        fclose($fp);
  }
}
?>