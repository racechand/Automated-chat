<?php 
/****
File name : Chat.php
Description : Simple automated chat.
Author : Chandra Mohan
Date : 15th October 2014
****/
error_reporting(0);
require_once "class.MySQL.php";
$csvReply = array();
$row = 1;
if (($handle = fopen("reply.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle)) !== FALSE) {
        $num = count($data);
        $row++;
       // for ($c=0; $c < $num; $c++) {
       if($row!=1){
            $csvReply[message][] = $data[0] ;
            $csvReply[reply][] = $data[1];
        }
    }
    fclose($handle);
    
}

$dbconnect = new MySQL();
$dbconnect->connectMySql("com.inc");
$ip=$_SERVER['REMOTE_ADDR'];
$d=date("Y-m-d H:i:s",time());
$d10=date("Y-m-d H:i:s",time()-864000);
$op = $_REQUEST['op'];
$name = "You: ";
$replierName ="Computer: ";
$DefaultMessage ="";
$replierMessage ="";
for($i=0;$i<count($csvReply[message]);$i++){
if(stristr($csvReply[message][$i],trim($_REQUEST['text']) )):	
	$replierMessage = $csvReply[reply][$i];
endif;
if($csvReply[message][$i] == "Default" ):	
	$DefaultMessage = $csvReply[reply][$i];
endif;
}
if($replierMessage == ""):
   $replierMessage = $DefaultMessage;
   $replierMessage = mysql_real_escape_string($replierMessage);
else:
     $replierMessage = mysql_real_escape_string($replierMessage);
endif;
$replierDate =date("Y-m-d H:i:s",time()+2);
$message = mysql_real_escape_string($_REQUEST['text']);
if ($op=="insert"){
 if ($message!=""){
	  $dbconnect->query("insert into chat (date,name,text,ip,reply,reply_name) values 
	                    ('$d','$name','$message','$ip','$replierMessage','$replierName')");
	  
 }
}

$dbconnect->query("delete from chat where date < '$d10' ");
$result = $dbconnect->query("select date,name,text,reply,reply_name from chat order by date asc limit 10");
$data .= "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"1\">";
for ($i = 0; $i < mysql_num_rows($result); $i++)  {
	$row_array = mysql_fetch_row($result);
  $data .= "<tr style=\"color:black;font:bold 14px Tahoma;\">";
  $data .= "<td valign=\"top\" align=\"left\">$row_array[1]</td>";
  $data .= "<td valign=\"top\" style=\"width:200px;text-wrap:hard-wrap;\" align=\"left\">".wordwrap($row_array[2], 40, '<br>',true)."</td><tr>";
  $data .= "<tr style=\"color:black;font:bold 14px Tahoma;\">";
  $data .= "<td valign=\"top\" align=\"left\">$row_array[4]</td>";
  $data .= "<td valign=\"top\" style=\"width:200px;text-wrap:hard-wrap;\" align=\"left\">".wordwrap($row_array[3], 40, '<br>',true)."</td><tr>";
}
$data .="</table>";
print $data;


?>
