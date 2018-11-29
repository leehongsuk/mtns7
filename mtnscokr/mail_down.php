<?
// 지난번 mail_detail.php에 있는 printbody 함수와 거의 같습니다.. 
// 다른 부분만 설명하죠..

function printbody($mailstream, $subject, $MSG_NO, $numpart, $encode, $mime, $file_name) 
{
    $val = imap_fetchbody($mailstream, $MSG_NO, (string)($numpart+1), FT_UID);

    switch($encode) 
    {
        case 0: // 7bit
            break;
        case 1: // 8bit
            $val = imap_base64(imap_binary(imap_qprint(imap_8bit($val))));
            break;
        case 2: // binary
            $val = imap_base64(imap_binary($val));
            break;
        case 3: // base64
            $val = imap_base64($val);
            break;
        case 4: // quoted-print
            $val = imap_base64(imap_binary(imap_qprint($val)));
            break;
        case 5: // other
            echo "알수없는 Encoding 방식.";

        exit;
    }

    
    
    
    // 이부분이 다르죠..
    // 전엔 이부분에서 첨부파일일경우 단순 링크만 시켜 놓았고..
    // 여기서는 해당 부분을 그대로 출력합니다. 그렇게 되면 사용자 측에선
    // 다운로드가 실행되는 것이죠..

    switch($mime) 
    {
        case "PLAIN":
             Header ( "Content-Type: text/plain");
             echo str_replace("\n", "<br>", $val);
             break;
        case "HTML":
             Header ( "Content-Type: text/html");
             echo $val;
             break;
        case "OCTET-STREAM":
             Header ( "Content-Type: octet-stream");
             Header ( "Content-Disposition: attachment; filename=$file_name");
             echo $val;
             break;
        default:
             Header ( "Content-Type: octet-stream");
             Header ( "Content-Disposition: attachment; filename=$file_name");
             echo $val;
    }
}

include ("mail_lib.php");

$box = $BOX;
$part_no = $PART_NO;
if($part_no == "") $part_no = 0;
if($box == "") $box = "INBOX";

include ("mail_config.php"); 

$mailstream = imap_open($C_DOMAIN, $login, $pass);

if ($mailstream == 0) 
{
   echo "Error!";
   exit;
}

$struct = imap_fetchstructure($mailstream, $MSG_NO);
$part = $struct->parts[$part_no];
$param = $part->parameters[0];
$file_name = Decode($param->value); // 첨부파일일 경우 파일명
$mime = $part->subtype; // MIME 타입
$encode = $part->encoding; // encoding

printbody($mailstream, $Subject, $MSG_NO, $part_no, $encode, $mime, $file_name);

imap_close($mailstream);
?>

