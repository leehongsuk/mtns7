<?
// ������ mail_detail.php�� �ִ� printbody �Լ��� ���� �����ϴ�.. 
// �ٸ� �κи� ��������..

function getAttach($mailstream, $subject, $MSG_NO, $numpart, $encode, $mime, $file_name) 
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
            echo "�˼����� Encoding ���.";

        exit;
    }

    
    $filename = "/home/realtimebox/www/mailfile/".$subject.$numpart.".jpg" ;

    if (!$handle = fopen($filename, 'a')) {
          echo "Cannot open file ($filename)";
          exit;
    }

    if (fwrite($handle, $val) === FALSE) {
        echo "Cannot write to file ($filename)";
        exit;
    }
    
    fclose($handle);
    
    
    return $filename ;
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
$file_name = Decode($param->value); // ÷�������� ��� ���ϸ�
$mime = $part->subtype; // MIME Ÿ��
$encode = $part->encoding; // encoding

echo getAttach($mailstream, $Subject, $MSG_NO, $part_no, $encode, $mime, $file_name);

imap_close($mailstream);
?>

