<?
    $Subject = $_POST["subject"] ;
    $Body    = $_POST["data"] ;

    require_once("class.phpmailer.php");

    $mail = new PHPMailer(true);
    try
    {
        $mail->IsSMTP();
        $mail->SMTPAuth    = true;
        $mail->Host        = "smtp.mail.nate.com";
        $mail->Port        = 465;
        $mail->SMTPSecure  = "ssl";
        $mail->Username    = "lhs0806";
        $mail->Password    = "s2619097";
        $mail->FromName    = "gmail";
        $mail->SetFrom("lhs0806@nate.com","토털 스코어");
        $mail->AddAddress("lhs0806@nate.com");
        $mail->AddCC('anmh71@empas.com', '토털 스코어');        
        $mail->Subject     = "영진위 크롤링 확인 : ".$Subject;
        $mail->Body        = $Body;
        $mail->IsHTML (true);
        $mail->Send();
        ?>
        <script>//alert("이메일을 전송하였습니다."); </script>
        <?
    }
    catch (phpmailerException $e){
        echo $e->errorMessage();
    }
    catch (Exception $e){
        echo $e->getMessage();
    }
?>
