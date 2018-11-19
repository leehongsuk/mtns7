<?
include ("mail_lib.php");

$cmd = $CMD;
$box = $BOX;
$part_no = $PART_NO;
if($box == "") $box = "INBOX";

include ("mail_config.php"); 

$mailstream = imap_open($C_DOMAIN, $login, $pass);

if  ($mailstream == 0) 
{
    echo "Error!";
    exit;
}

switch($cmd) 

       case "del":
                  for ($i=0;$i<count($NO);$i++) 
                  {
                      $result = imap_delete($mailstream, $NO[$i]);
                      // 해당 번호의 메일에 삭제 표시를 합니다. 즉 위 함수는 실제
                      // 삭제시키는 함수가 아니라는 거죠..

                      if  (!$result) 
                      {
                          echo "삭제실패";
                          imap_close($mailstream);
                          exit;
                      }
                      imap_expunge($mailstream);
                      // 위에서 삭제 표시를 한 메일을 삭제하는 명령을 수행합니다.
                      // 이 함수가 호출 되지 않고 루틴이 끝나면 삭제되지 않습니다.
                  }
                  break;
}

imap_close($mailstream);

RedirectTarget("mail_list.php?BOX=".$box, "");
?>
