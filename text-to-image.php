<?php
/*
OriginalMy.com Blockchain Quote-Of-The-Day Bot
Copyright (C) 2013  Edilson Osorio Junior

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


require 'phpmailer/PHPMailerAutoload.php';

/* Config */

# SMTP Settings
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPAuth   	= true;
$mail->SMTPSecure 	= "tls";
$mail->Port		= "587";
$mail->Host       	= "smtp.<YOUR_SERVER>.com";
$mail->Username   	= "<USERNAME>";
$mail->Password   	= "<PASSWORD>";
$mail->SMTPDebug 	= 2;
$mail->Debugoutput 	= 'html';
$mail->Subject 		= '<YOUR_SUBJECT';
$mailFrom 		= '<MAIL_FROM>';
$mailFromName 		= '<MAIL_FROM_NAME>';
$mailTo 		= '<MAIL_TO>';
$mailToName 		= '<MAIL_TO_NAME>';

# Filename
$fname = date("Ymdhis") . '.png';
$filename = 'generated/'. $fname;


/* Select and configure bg image */
$templates = scandir("templates");
srand((float)microtime()*1000000);
shuffle($templates);
$background = $templates[rand(2, count($templates) - 1)];
$im = @ImageCreateFromPNG ('templates/' . $background );

echo $background;

$imageWidth = imagesx($im);
$imageHeight = imagesy($im);

/* Select and configure font */
$fontSize = "55";
$fontAngle = "0";
$fontMsg = "fonts/PlantagenetCherokee.ttf";
$fontSig = "fonts/chops.ttf";
$textColor = "ffffff";
$fontColor = imagecolorallocate($im, 255,255,255);
$strokeColor = imagecolorallocate($im, 0, 0, 0);

/* Select and configure quote */
$fContents = file("quotes.txt");
srand((float)microtime()*1000000);
shuffle($fContents);
$text = $fContents[rand(0, count($fContents) - 1)];

while (strlen($text) > 5 && strlen($text) > 150){ // Select a 150 chars quote
	$text = $fContents[rand(0, count($fContents) - 1)];
}
echo $text;
$lines = explode(";",$text);
$msg = wordwrap($lines[0], 20, "\n");
$signature = $lines[1];
$newLines = substr_count($msg, "\n") + 1;


/* Write quote over selected bg image */

# Get dimensions of text
$box = @imageTTFBbox($fontsize,$fontangle,$fontMsg,$msg);
$boxSig = @imageTTFBbox($fontsize,$fontangle,$fontSig,$signature);

# Get width of text from dimensions
$textWidth = abs($box[2] - $box[0]);
$sigWidth = abs($boxSig[2] - $boxSig[0]);

# Get height of text from dimensions
$textHeight = abs($box[7] - $box[1]);

# Get x-coord of centered text horizontally using length of the image and length of the text
$xCord = ($imageWidth/2)-($textWidth/2)-2;

# Get y-coord of centered text vertically using height of the image and height of the text
$yCord = ($imageHeight/2)+($textHeight/2)-($newLines * $fontSize)-($newLines > 3 ? 175 : 75);

# Declare completed image with colors, font, text, and text location
imagettfwritetext($im, $fontSize, $fontAngle, $xCord, $yCord, $fontColor, $strokeColor, $fontMsg, $msg, 4);
imagettfwritetext($im, $fontSize, $fontAngle, $xCord-10, $yCord+$textHeight+20, $fontColor, $strokeColor, $fontSig, $signature, 3);

# Display completed image as PNG
imagepng($im, $filename);

# Close the image
imagedestroy($im);

/* Email sending */

# Sender
$mail->SetFrom($mailFrom, $mailFromName); //from (verified email address)
$mail->addReplyTo($mailFrom, $mailFromName);

# Recipient
$mail->AddAddress($mailTo, $mailToName);

# Message
$body = $msg . ' - ' . $signature;
$mail->MsgHTML($body);
$mail->AltBody = $body;

# Add attachment
$mail->addAttachment($filename);

# Success
# send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}


function imagettfwritetext(&$image, $size, $angle, $x, $y, &$textcolor, &$strokecolor, $fontfile, $text, $px) {
	for($c1 = ($x-abs($px)); $c1 <= ($x+abs($px)); $c1++)
		for($c2 = ($y-abs($px)); $c2 <= ($y+abs($px)); $c2++)
		$bg = imagettftext($image, $size, $angle, $c1, $c2, $strokecolor, $fontfile, $text);
		return imagettftext($image, $size, $angle, $x, $y, $textcolor, $fontfile, $text);
}

?>
