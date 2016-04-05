# Blockchain Quote-of-the-day Bot

This bot does:

1. Randomly picks one bg image from templates folder
2. Randomly picks one quote from quotes.txt
3. Mix text and bg image gernerating a new PNG file under generated folder
4. Optionally sends it to an email.

## Setup: 

1) Change SMTP with your server configuration.

```
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth   	= true;
$mail->SMTPSecure 	= "tls";
$mail->Port		    = "587";
$mail->Host       	= "smtp.<YOUR_SERVER>.com";
$mail->Username   	= "<USERNAME>";
$mail->Password   	= "<PASSWORD>";
$mail->SMTPDebug 	= 2;
$mail->Debugoutput 	= 'html';
$mail->Subject 		= '<YOUR_SUBJECT>';
$mailFrom 		    = '<MAIL_FROM>';
$mailFromName 		= '<MAIL_FROM_NAME>';
$mailTo 		    = '<MAIL_TO>';
$mailToName 		= '<MAIL_TO_NAME>';
```

2) Configure cron:
- For publishing 5 to 5 days, at 10am:
```
# 0 10 */5 * * cd <path-to-text-to-image>/text-to-image/; sleep 1;  /usr/bin/php ./text-to-image.php 1>/dev/null
2>/dev/null/*
```
3) Configure an account on [IFTTT](ifttt.com) for filtering your email [$mailTo] by subject and publishing the image 
on your Facebook fanpage.
*  Better if you create a dedicated email for that.

## Tips
* We use 800x800px PNG, but it could be a 512x512px.
* [Pixabay](www.pixabay.com) can be a very good bg image repository
* Quote format is: quote;author



