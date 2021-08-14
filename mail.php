<?php
if($_POST)
{
    $to_email       = "tim.steele@tigercatdigital.com"; //Recipient email, Replace with own email here
    
    //check if its an ajax request, exit if not
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        
        $output = json_encode(array( //create JSON data
            'type'=>'error', 
            'text' => 'Sorry Request must be Ajax POST'
        ));
        die($output); //exit script outputting json data
    } 
    
    //Sanitize input data using PHP filter_var().
    $user_name      = filter_var($_POST["user_name"], FILTER_SANITIZE_STRING);
    $user_email     = filter_var($_POST["user_email"], FILTER_SANITIZE_EMAIL);
    $subject        = "New Contact Request from tigercatdigital.com";
    $message        = filter_var($_POST["msg"], FILTER_SANITIZE_STRING);
    
    //additional php validation
    if(strlen($user_name)<1){ // If length is less than 4 it will output JSON error.
        $output = json_encode(array('type'=>'error', 'text' => 'Please give us your name so we know who to contact.'));
        die($output);
    }
    if(!filter_var($user_email, FILTER_VALIDATE_EMAIL)){ //email validation
        $output = json_encode(array('type'=>'error', 'text' => 'That email address doesn\'t look valid. Make sure we can contact you.'));
        die($output);
    }
    if(strlen($message)<1){ //check emtpy message
        $output = json_encode(array('type'=>'error', 'text' => 'Please send us a message. It doesn\'t have to be clever, just tell us what\'s on your mind.'));
        die($output);
    }
    
    //email body
    $message_body = $message."\r\n\r\n-".$user_name."\r\nEmail : ".$user_email;
    
    //proceed with PHP email.
    $headers = 'From: '.$user_name.'' . "\r\n" .
    'Reply-To: '.$user_email.'' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
    
    $send_mail = mail($to_email, $subject, $message_body, $headers);
    
    if(!$send_mail)
    {
        //If mail couldn't be sent output error. Check your PHP email configuration (if it ever happens)
        $output = json_encode(array('type'=>'error', 'text' => 'There was a problen sending this email. We are looking into it on our end. In the mean time, you can contact tim.steele@tigercatdigital.com.'));
        die($output);
    }else{
        $output = json_encode(array('type'=>'message', 'text' => 'Thanks for reaching out '.$user_name .'. We will get back to you shortly.'));
        die($output);
    }
}
?>