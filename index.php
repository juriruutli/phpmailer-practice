<?php
//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';
$success = filter_input(INPUT_GET, 'result', FILTER_SANITIZE_STRING);
if (!empty($success) && $success == 'success') {
echo 'Saadetud';
}
$firstName = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
$lastName = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$telephone = filter_input(INPUT_POST, 'telephone', FILTER_SANITIZE_STRING);
$comments = filter_input(INPUT_POST, 'comments', FILTER_SANITIZE_STRING);
$submit = filter_input(INPUT_POST, 'submit', FILTER_SANITIZE_STRING); 


	if (array_key_exists('userfile', $_FILES)) {
	    // First handle the upload
	    // Don't trust provided filename - same goes for MIME types
	    // See http://php.net/manual/en/features.file-upload.php#114004 for more thorough upload validation
	    $uploadfile = $_FILES['userfile']['name'];
	        //print_r($uploadfile);
	    move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile);
	}

	if (isset($submit) && $submit == 'Kinnita') {
	    $errors = []; 

	    if (empty($firstName)) { 
	       $errors['fname'] = 'Eesnimi on puudu!'; 
	    }
	    if (empty($lastName)) { 
	       $errors['lname'] = 'Perekonnanimi on puudu!'; 
	    }
	    if (empty($telephone)) { 
	       $errors['telephone'] = 'Telefoninumber on puudu!'; 
	    }
	    if (empty($email)) { 
	       $errors['email'] = 'E-mail on puud!'; 
	    }
	    if (empty($comments)) { 
	       $errors['comments'] = 'Kommentaari lahter on tühi.'; 
	    } 

	    //Create a new PHPMailer instance
	    $mail = new PHPMailer;
	    //Tell PHPMailer to use SMTP
	    $mail->isSMTP();
	    //Enable SMTP debugging
	    // 0 = off (for production use)
	    // 1 = client messages
	    // 2 = client and server messages
	    //$mail->SMTPDebug = 2;             // uncomment to debug
	    //Set the hostname of the mail server
	    $mail->Host = 'smtp.gmail.com';
	    // use
	    // $mail->Host = gethostbyname('smtp.gmail.com');
	    // if your network does not support SMTP over IPv6
	    //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
	    $mail->Port = 587;
	    //Set the encryption system to use - ssl (deprecated) or tls
	    $mail->SMTPSecure = 'tls';
	    //Whether to use SMTP authentication
	    $mail->SMTPAuth = true;
	    //Username to use for SMTP authentication - use full email address for gmail
	    $mail->Username = "jryytli@gmail.com";
	    //Password to use for SMTP authentication
	    $mail->Password = "*******";
	    $mail->isHTML(TRUE);


	    //Set who the message is to be sent from
	    $mail->setFrom('jryytli@gmail.com', $firstName);
	    //Set an alternative reply-to address
	    $mail->addReplyTo($email, $firstName . " " . $lastName);
	    //Set who the message is to be sent to
	    $mail->addAddress('jryytli@gmail.com', 'Tagasiside kasutajalt: ' . $email);
	   
	    //Set the subject line
	    $mail->Subject = 'Tagasiside' ;
	    //Read an HTML message body from an external file, convert referenced images to embedded,


	    $mail->Body = '
	    <html>
	        <head>
	            <style>
	                body {
	                    background-image: url("https://mdbootstrap.com/img/Photos/Others/background.jpg");
	                    background-repeat: repeat-y no-repeat; background-color: #00BFFF; margin: 0; padding: 0;
	                }
	                h3 {font-size: .12em; color: #202020; margin-bottom: 1em; margin-left: .45em;}
	                p {color: #202020; font-size: .75em; margin-left: .5em; line-height: 1.3em; margin-bottom:1em;}
	                ul li {list-style:none; font-size: 1.0em;}
	                em { color: #202020;}
	            </style>
	        </head>

	        <body>
	            <table width="100%" border="0" cellspacing="0" cellpadding="20" background="https://mdbootstrap.com/img/Photos/Others/background.jpg">
	                <tr><td>
	                    <p>Kasutaja andmed:</p>
	                        <ul>
	                            <li><b>Eesnimi: </b>'.$firstName.'</li>
	                            <li><b>Perekonnanimi: </b>'.$lastName.'</li>
	                            <li><b>Telefon: </b>'.$telephone.'</li>
	                            <li><b>E-mail: </b>'.$email.'</li>
	                       
	                        </ul>
	                    <p>Kasutaja tagasiside:</p>
	                    <p><em>'.$comments.'</em></p>
	                   <br>
	                </td></tr>
	            </table>
	        </body>
	        </html>';

	    //Replace the plain text body with one created manually
	    $mail->AltBody = $comments;
	    // Attach the uploaded file
	    if (!empty($uploadfile)) {

	        $mail->addAttachment($uploadfile, 'Manus');
	    }
	    //send the message, check for errors
	    if (!$mail->send()) {
	        echo "Kirja saatmine ei õnnestunud: " . $mail->ErrorInfo;
	    } elseif (!empty($errors)) {
	        foreach ($errors as $error) {
	           echo "Kirja saatmine ei õnnestunud: " . $error . $mail->ErrorInfo;
	        }
	    } else { 
	        echo "Tagasiside saadetud!";
	    }

	    //message for sender 

	    // Remove previous recipients
	    $mail->ClearAllRecipients();
	    // alternative in this case (only addresses, no cc, bcc): 
	    $mail->ClearAddresses();
	    $mail->clearAttachments();
	    $mail->Body     = 'Sinu tagasiside on saadetud.';


	    // Add the admin address
	    $mail->AddAddress($email);
	    if ($mail->send()) {
	    	echo '<meta http-equiv="refresh" content="0;url=https://phpmailer.dev/?result=success" />';
		}
	}

?>
<html>
<head>
<meta charset="UTF-8">
<title>Tagasiside vorm</title>
    <style>
        body {
            background-image: url("https://mdbootstrap.com/img/Photos/Others/background.jpg");
            background-repeat: repeat-y no-repeat; background-color: #00BFFF; margin: 0; padding: 0;
        }
        td {font-size: 1.0em; color: #202020; margin-bottom: 1em; margin-left: .45em;}
        p {color: #202020; font-size: .75em; margin-left: .5em; line-height: 1.3em; margin-bottom:1em;}
        ul li {list-style:none; font-size: 1.0em;}
        em { color: #202020;}
    </style>
</head>
<body>
    <div class="conteiner" style="padding: 20px";>
        <form name="contactform" method="post" enctype="multipart/form-data">
            <table width="450px">
                <tr>
                    <td valign="top">
                        <label for="first_name">Eesnimi *</label>
                    </td>
                    <td valign="top">
                        <input  type="text" name="fname" maxlength="50" size="30">
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <label for="last_name">Perekonnanimi *</label>
                    </td>
                    <td valign="top">
                        <input  type="text" name="lname" maxlength="50" size="30">
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <label for="email">E-mail *</label>
                    </td>
                    <td valign="top">
                        <input  type="text" name="email" maxlength="80" size="30">
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <label for="telephone">Telefoni number *</label>
                    </td>
                    <td valign="top">
                        <input  type="text" name="telephone" maxlength="30" size="30">
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <label for="comments">Tagasiside *</label>
                    </td>
                    <td valign="top">
                        <textarea  name="comments" maxlength="1000" cols="25" rows="6"></textarea>
                    </td>
                </tr>
                <tr>
                    <input type="hidden" name="MAX_FILE_SIZE" value="100000"> Vali fail: <input name="userfile" type="file">
                </tr>
                <tr>
                    <td colspan="2" style="text-align:center">
                        <input type="submit" value="Kinnita" name="submit">   
                    </td>
                </tr>
            </table>
        </form>
    </div>
</body>


