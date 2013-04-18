<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Simple email class
 *
 * @package    OC
 * @category   Core
 * @author     Chema <chema@garridodiaz.com>, Slobodan <slobodan.josifovic@gmail.com>
 * @copyright  (c) 2009-2012 Open Classifieds Team
 * @license    GPL v3
 */



class Email {

    /**
     * sends an email using our configs
     * @param  [type] $to        [description]
     * @param  [type] $subject   [description]
     * @param  [type] $body      [description]
     * @param  [type] $reply     [description]
     * @param  [type] $replyName [description]
     * @param  [type] $file      [description]
     * @return boolean
     */
    public static function send($to,$subject,$body,$reply,$replyName,$file = NULL)
    {
        require Kohana::find_file('vendor', 'php-mailer/phpmailer','php');

        //get the template from the html email boilerplate
        $body = View::factory('email',array('title'=>$subject,'content'=>$body))->render();

        $mail= new PHPMailer();

        if(core::config('email.smtp_active') == TRUE)
        { 
            //SMTP HOST config
            if (core::config('email.smtp_host')!="")
            {
                $mail->IsSMTP();
                $mail->Host       = core::config('email.smtp_host');              // sets custom SMTP server
            }

            //SMTP PORT config
            if (core::config('email.smtp_port')!="")
            {
                $mail->Port       = core::config('email.smtp_port');              // set a custom SMTP port
            }

            //SMTP AUTH config

            if (core::config('email.smtp_auth') == TRUE)
            {
                $mail->SMTPAuth   = TRUE;                                                  // enable SMTP authentication
                $mail->Username   = core::config('email.smtp_user');              // SMTP username
                $mail->Password   = core::config('email.smtp_pass');              // SMTP password
               

                if (core::config('email.smtp_ssl') == TRUE)
                {
                    $mail->SMTPSecure = "ssl";                  // sets the prefix to the server
                }
                    
            }

            $mail->From       = core::config('email.notify_email');
            $mail->FromName   = "no-reply ".core::config('general.site_name');
            $mail->Subject    = $subject;
            $mail->MsgHTML($body);

            if($file !== NULL) $mail->AddAttachment($file['tmp_name'],$file['name']);

            $mail->AddReplyTo($reply,$replyName);//they answer here
            $mail->AddAddress($to,$to);
            $mail->IsHTML(TRUE); // send as HTML

            if(!$mail->Send()) 
            {//to see if we return a message or a value bolean
                Alert::set(Alert::ALERT,"Mailer Error: " . $mail->ErrorInfo);
                return FALSE;
            } 
            else 
                return TRUE;
           
            
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


            return mail($to,$subject,$body,$headers);
        }
        // Sent at 9:39 AM on Friday
 
    }


} //en email