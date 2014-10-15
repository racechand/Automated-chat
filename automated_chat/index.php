<?php 
/****
File name : index.php
Description : Initialize chat screen.
Author : Chandra Mohan
Date : 04th October 2014
****/
?>
<html>
<head>
</head>
<body>
<?php	
require_once "class.Chat.php"; 
$chat = new Chat();
$chat->createChat("");
?>
</body>
</html>
