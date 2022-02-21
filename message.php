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
