<!DOCTYPE html>
<html>
<head>
  <title>Store form data in .txt file</title>
</head>
<body>
  <form method="post">
    <label for="fname">Your Name</label>
        <input type="text" id="fname" name="name" placeholder="Your name..">
        <label for="fname">Email</label>
        <input type="text" id="fname" name="email" placeholder="Email..">
        <label for="subject">Message</label>
        <textarea id="subject" name="subject" placeholder="Write something.." style="height:170px"></textarea>
        <input type="submit" value="Submit">
  </form>
</body>
</html>
<?php
              
if(isset($_POST['firstname']))
{
$data=$_POST['firstname'];
$fp = fopen('message.txt', 'a');
fwrite($fp, $data);
  $data=$_POST['email'];
fwrite($fp, $data);
    $data=$_POST['subject'];
fwrite($fp, $data);

fclose($fp);
}
?>
