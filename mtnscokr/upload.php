<?

function get_filename_check($filepath, $filename)
{ 
  if  (!preg_match("'/$'", $filepath)) $filepath .= '/'; 
  
  if  (is_file($filepath . $filename)) 
  { 
      preg_match("'^([^.]+)(\[([0-9]+)\])(\.[^.]+)$'", $filename, $match); 

      if  (empty($match)) 
      { 
          $filename = preg_replace("`^([^.]+)(\.[^.]+)$`", "\\1[1]\\2", $filename); 
      } 
      else
      { 
          $filename = $match[1] . '[' . ($match[3] + 1) . ']' . $match[4]; 
      } 
      return get_filename_check($filepath, $filename); 
  } 
  else 
  { 
      return $filename; 
  } 
} 

?>



<?
    $file_temp = $_FILES['UploadList']['tmp_name'];
    $file_name = $_FILES['UploadList']['name'];
    $file_size = $_FILES['UploadList']['size'];

    $updir = "/usr/upload/";

    if  (!empty($file_name))
    {
        //$filename = iconv("UTF-8", "EUC-KR",$file_name);  // �ѱ�ó��
        $filename = $file_name ;  // �ѱ�ó��

        $filename = get_filename_check($updir, $filename);  // �ߺ�����ó��..

        move_uploaded_file($file_temp,$updir. $filename);
    }    



    $Now        = strtotime('now');
    $sCurrYear  = date("Y",$Now) ;    // �����..

    include "config.php";        // {[������ ���̽�]} : ȯ�漳��
                    
    $connect = dbconn() ;        // {[������ ���̽�]} : ����

    mysql_select_db($cont_db) ;  // {[������ ���̽�]} : �����

    $MaxMsgNo = 0 ;
    $sQuery = "Select Max(MsgNo) As MaxMsgNo     ".
              "  From chk_theather_sndmsg        ".
              " Where UserID  = '".$UserID."'    ".
              "   And MsgYear = '".$sCurrYear."' " ;
    $QryMaxMsgNo = mysql_query($sQuery,$connect) ;
    if  ($ArrMaxMsgNo = mysql_fetch_array($QryMaxMsgNo))
    {
        $MaxMsgNo = $ArrMaxMsgNo["MaxMsgNo"] ;
    }

    $sQuery = "Update chk_theather_sndmsg            ".
              "   Set File".$No." = '".$filename."'  ".
              " Where UserID  = '".$UserID."'        ".
              "   And MsgYear = '".$sCurrYear."'     ".
              "   And MsgNo   = '".$MaxMsgNo."'      " ;
    mysql_query($sQuery,$connect) ;

    mysql_close($connect);       // {[������ ���̽�]} : ����
?>
