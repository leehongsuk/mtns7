<?
    include "Snoopy.class.php";
    
    function get_remotefile($url, $keys)
    {      
        $snoopy = new Snoopy;
        
        $submit_url = $url;

        foreach ($keys as $key => $value) 
        {
            //echo $key . '=>' . $value . '<br>';
            $submit_vars[$key] = $value;
        }      
    
          
        if  ($snoopy->submit($submit_url,$submit_vars))
        {
            return htmlspecialchars($snoopy->results) ;
        }
        else
        {
            return "error fetching document: ".$snoopy->error ;
        }
    }
?>    