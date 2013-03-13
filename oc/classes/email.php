<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Simple email class
 *
 * @package    OC
 * @category   Core
 * @author     Chema <chema@garridodiaz.com>
 * @copyright  (c) 2009-2012 Open Classifieds Team
 * @license    GPL v3
 */



class Email {


    // /**
    //  * Simple function to send an email
    //  *
    //  * @param string $to
    //  * @param string $from
    //  * @param string $subject
    //  * @param string $body
    //  * @param string $extra_header
    //  * @return boolean
    //  */
    // public static function send($to,$from,$subject,$body,$headers=NULL,$view = 'email')
    // {
    //     //d(func_get_args());
    //     if ($headers==NULL)
    //     {
    //         $headers = 'MIME-Version: 1.0' . PHP_EOL;
    //         $headers.= 'Content-type: text/html; charset=utf8'. PHP_EOL;
    //         $headers.= 'From: '.$from.PHP_EOL;
    //         $headers.= 'Reply-To: '.$from.PHP_EOL;
    //         $headers.= 'Return-Path: '.$from.PHP_EOL;
    //         $headers.= 'X-Mailer: PHP/' . phpversion().PHP_EOL;
    //     }


    //     //get the template from the html email boilerplate
    //     //$body = View::factory($view,array('title'=>$subject,'content'=>$body))->render();

    //     //echo($body);
    //     //die();

    //     return mail($to,$subject,$body,$headers);

    // }

    public static function sendEmailFile($to,$subject,$body,$reply,$replyName,$file = NULL)
    {//send email using smtp from gmail


        require Kohana::find_file('vendor', 'php-mailer/phpmailer','php');

        $mail= new PHPMailer();

        if(core::config('email-settings.smtp_active') !== FALSE)
        { 
            //SMTP HOST config
            if (core::config('email-settings.smtp_host')!="")
            {
                $mail->IsSMTP();
                    $mail->Host       = core::config('email-settings.smtp_host');              // sets custom SMTP server
            }

            //SMTP PORT config
            if (core::config('email-settings.smtp_port')!="")
            {
                    $mail->Port       = core::config('email-settings.smtp_port');              // set a custom SMTP port
            }

            //SMTP AUTH config

            if (core::config('email-settings.smtp_auth') == 'TRUE')
            {
                    $mail->SMTPAuth   = true;                                                  // enable SMTP authentication
                    $mail->Username   = core::config('email-settings.smtp_user');              // SMTP username
                    $mail->Password   = core::config('email-settings.smtp_pass');              // SMTP password
                    $mail->SMTPSecure = "ssl";                  // sets the prefix to the server
     
            }
           // d(core::config('email-settings.smtp_active').' '.core::config('email-settings.smtp_auth').' '.core::config('email-settings.smtp_host').' '.core::config('email-settings.smtp_port').' '.core::config('email-settings.smtp_user').' '.core::config('email-settings.smtp_pass'));
            $mail->From       = core::config('email-settings.notify_email');
            $mail->FromName   = "no-reply ".core::config('general.site_name');
            $mail->Subject    = $subject;
            $mail->MsgHTML($body);

            if($file !== NULL) $mail->AddAttachment($file['tmp_name'],$file['name']);

            $mail->AddReplyTo($reply,$replyName);//they answer here
            $mail->AddAddress($to,$to);
            $mail->IsHTML(true); // send as HTML

            if(!$mail->Send()) 
            {//to see if we return a message or a value bolean
                echo "Mailer Error: " . $mail->ErrorInfo;
            } else return false;
            // echo "Message sent! $to";
            
        }    
        else
        {
            // d(func_get_args());
            if ($headers==NULL)
            {
                $headers = 'MIME-Version: 1.0' . PHP_EOL;
                $headers.= 'Content-type: text/html; charset=utf8'. PHP_EOL;
                $headers.= 'From: '.$reply.PHP_EOL;
                $headers.= 'Reply-To: '.$reply.PHP_EOL;
                $headers.= 'Return-Path: '.$reply.PHP_EOL;
                $headers.= 'X-Mailer: PHP/' . phpversion().PHP_EOL;
            }

            //get the template from the html email boilerplate
            $body = View::factory($view,array('title'=>$subject,'content'=>$body))->render();

            return mail($to,$subject,$body,$headers);
        }
        // Sent at 9:39 AM on Friday
 
    }


} //en email