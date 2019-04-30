<?php
$hubVerifyToken = [TOKEN];
$accessToken = [accessToken];

// check token at setup

if ($_REQUEST['hub_verify_token'] === $hubVerifyToken) {
	echo $_REQUEST['hub_challenge'];
	exit;
}

// handle bot's anwser

$input = json_decode(file_get_contents('php://input') , true);
$senderId = $input['entry'][0]['messaging'][0]['sender']['id'];
$messageing = $input['entry'][0]['messaging'][0];



if (isset($messageing['postback']))
$messageText = $messageing['postback']['payload'];
elseif (isset($messageing['message']['quick_reply']))
$messageText = $messageing['message']['quick_reply']['payload'];

else
$messageText = $messageing['message']['text'];
$user_tag=file_get_contents("http://badsendhellobot.onlinewebshop.net/bot/get_tag.php?id=$senderId");
$user_lang=file_get_contents("http://badsendhellobot.onlinewebshop.net/bot/get_lang.php?id=$senderId");
// ===========================Commands handling==================================
$get_tag = explode(">>", $messageText);
if($get_tag[0].'1'=='1'&&!isset($messageing['message']['attachments'])){
$new_tag=strtolower(str_replace(" ","%20",$get_tag[1]));
$messageText="/command_EDIT_TAG_command/";
}




switch ($messageText) {

case "/command_Get_Started_command/":
		sendOptionMessage($senderId,$accessToken,"💡 Welcome in Hell Bot, choose your language","English","/command_GET_ENGLISH_command/","عربى","/command_GET_ARABIC_command/");
		die();
	break;
		
		
case "/command_GET_ENGLISH_command/":
		file_get_contents("http://badsendhellobot.onlinewebshop.net/bot/get_started_sign.php?id=$senderId&lang=0");
		sendNewMessage($senderId, $accessToken,"💡 In Hello Bot you can chat with random people around the world and the conversation will be gone as soon as you finish it, use the side menu to end chat or start a new one");
		die();
	break;
		
		
case "/command_GET_ARABIC_command/":
		file_get_contents("http://badsendhellobot.onlinewebshop.net/bot/get_started_sign.php?id=$senderId&lang=1");
		sendNewMessage($senderId, $accessToken,"💡 فى Hello Bot  يمكنك الدردشة مع اشخاص لاتعرفهم يتم اختيارهم بشكل عشوائى من جميع انحاء العالم وستنتهى الدردشة تماما بمجرد انهائك لها , اسخدم القائمة الجانبية لانهاء الدردشة او بداية واحدة جديدة");
		die();
	break;	
		
		
case "/command_continue_CHAT_command/":
		die();
	break;

case "/command_NEW_CHAT_command/":
    	$sign_result = file_get_contents("http://badsendhellobot.onlinewebshop.net/bot/sign_test.php?id=$senderId&tag=x");
		$user_tag=file_get_contents("http://badsendhellobot.onlinewebshop.net/bot/get_tag.php?id=$senderId");
	if ($sign_result == 'waiting_success'){
		if($user_lang=='0')
    sendEndMessage($senderId, $accessToken,"💡 You were successfully registered, Just sit back and wait someone to chat with him");
		else
		sendEndMessage($senderId, $accessToken,"💡 لقد تم تسجيلك بنجاح ، ما عليك سوى الجلوس والانتظار لشخص ما للدردشة معه");
			
	}
	elseif ($sign_result == 'chatting_success') {
		$recId = file_get_contents("http://badsendhellobot.onlinewebshop.net/bot/get_twin.php?id=$senderId");
		if($user_lang=='0'){
		sendTextMessage($senderId,"💡 You have been connected with a possible new friend, you are able to quit this chat using the side menu", $accessToken);
		sendOneWordMessage($senderId, $accessToken,"Say Hello","Hello!","Hello!");
		}
		else{
			sendTextMessage($senderId,"💡 أنت الان متصل مع صديق جديد محتمل ، يمكنك إنهاء هذه الدردشة فى اى وقت باستخدام القائمة الجانبية", $accessToken);
			sendOneWordMessage($senderId, $accessToken,"قل مرحبا","Hello!","Hello!");
		}
		$rec_lang=file_get_contents("http://badsendhellobot.onlinewebshop.net/bot/get_lang.php?id=$recId");	
		if($rec_lang=='0'){
		sendTextMessage($recId,"💡 You have been connected with a possible new friend, you are able to quit this chat using the side menu", $accessToken);
		sendOneWordMessage($recId, $accessToken,"Say Hello","Hello!","Hello!");
		}
		else{
			sendTextMessage($recId,"💡 أنت الان متصل مع صديق جديد محتمل ، يمكنك إنهاء هذه الدردشة فى اى وقت باستخدام القائمة الجانبية", $accessToken);
			sendOneWordMessage($recId, $accessToken,"قل مرحبا","Hello!","Hello!");
		}
	}
	elseif ($sign_result == 'waiting'){
		if($user_lang=='0')
		sendEndMessage($senderId, $accessToken,"💡 Just sit back and wait someone to chat with him");
		else
			sendEndMessage($senderId, $accessToken,"💡 فقط عليك الجلوس وانتظار شخص ما للدردشة معه");
	}
	elseif ($sign_result == 'chatting'){
		if($user_lang=='0')
		sendOptionMessage($senderId, $accessToken,"💡 You are already in chat, just quit and start a new one","Quit this chat","/command_END_CHAT_command/","Continue chat","/command_continue_CHAT_command/");
		else
			sendOptionMessage($senderId, $accessToken,"💡 أنت بالفعل فى دردشة الان , قم بانهاء الدردشة و ابدأ واحدة جديدة","انهاء الدردشة","/command_END_CHAT_command/","متابعة الدردشة","/command_continue_CHAT_command/");
	}
	else{
		if($user_lang=='0')
		sendNewMessage($senderId, $accessToken,"💡 An error occurred while starting new chat, please try again");	
		else
			sendNewMessage($senderId, $accessToken,"💡 حدث خطأ أثناء بدء دردشة جديدة، يرجى إعادة المحاولة");	
	}
		die();
  break;
  
case "/command_HOW_TO_EDIT_TAG_command/":
		file_get_contents("http://badsendhellobot.onlinewebshop.net/bot/set_is_edit_tag.php?id=$senderId&isEditTag=1");
		if($user_lang=='0')
  sendOneWordMessage($senderId,$accessToken,'💡 Enter your interest or use this General button to enter chats without interest',"General",">>general");
else
  sendOneWordMessage($senderId,$accessToken,'💡 قم بادخال اهتمامك أو استخدام هذا الزر لدخول دردشات بدون اهتمام محدد',"General",">>general");
		die();
break;
  
case "/command_EDIT_TAG_command/":
		if (strlen($new_tag)>=1){
	file_get_contents("http://badsendhellobot.onlinewebshop.net/bot/set_is_edit_tag.php?id=$senderId&isEditTag=0");
  $edit_tag_result = file_get_contents("http://badsendhellobot.onlinewebshop.net/bot/edit_tag.php?id=$senderId&tag=".str_replace("'","''",$new_tag));
  if($edit_tag_result >= 0){
		if($user_lang=='0')
			sendOneWordMessage($senderId,$accessToken,"💡 Your interest changed to $new_tag","Edit interest again","/command_HOW_TO_EDIT_TAG_command/");
		else
			sendOneWordMessage($senderId,$accessToken,"💡 تم تغير الاهتمام الى $new_tag","اعادة تغيرالاهتمام","/command_HOW_TO_EDIT_TAG_command/");
	}
  else{
		if($user_lang=='0')
  		sendNewMessage($senderId, $accessToken,"💡 Your are not registed, registe by starting new chat");
		else
			sendNewMessage($senderId, $accessToken,"💡 انت عير مسجل ,يمكنك التسجيل عن طريق بداية دردشة جديدة من القائمة الجانبية");
			
	}
		}
		else{
			if($user_lang=='0')
				sendTextMessage($senderId,"💡 interest cannot be empty", $accessToken);
			else
				sendTextMessage($senderId,"💡 الأهتمام لا يمكن ان يكون فارغا", $accessToken);
		}
		die();
  break;
	
case "/command_HOW_TO_EDIT_LANGUAGE_command/":
			if($user_lang=='0')
   		sendOptionMessage($senderId,$accessToken,"💡 choose your language","English","/command_MENU_ENGLISH_command/","عربى","/command_MENU_ARABIC_command/");
		else
			sendOptionMessage($senderId,$accessToken,"💡 أختار اللغة المفضلة","English","/command_MENU_ENGLISH_command/","عربى","/command_MENU_ARABIC_command/");
		die();
break;
		
case "/command_MENU_ENGLISH_command/":
		file_get_contents("http://badsendhellobot.onlinewebshop.net/bot/get_started_sign.php?id=$senderId&lang=0");	
		sendTextMessage($senderId,"💡 Bot language changed to english", $accessToken);
		die();
break;
		
case "/command_MENU_ARABIC_command/":
		file_get_contents("http://badsendhellobot.onlinewebshop.net/bot/get_started_sign.php?id=$senderId&lang=1");
		sendTextMessage($senderId,"💡 تم تغير اللغة الى العربية", $accessToken);
		die();
break;

	
case "/command_END_CHAT_command/":
    $recId = file_get_contents("http://badsendhellobot.onlinewebshop.net/bot/get_twin.php?id=$senderId");
	$leave_result = file_get_contents("http://badsendhellobot.onlinewebshop.net/bot/end_chat_new.php?id=$senderId");
	if ($leave_result > 0){
		if($user_lang=='0'){
			sendNewMessage($senderId, $accessToken,"💡 Chat has been closed, let's start new one");
		}
		else{
			sendNewMessage($senderId, $accessToken,"💡 تم إغلاق الدردشة، دعنا نبدأ واحدة جديدة");
		}
		$rec_lang=file_get_contents("http://badsendhellobot.onlinewebshop.net/bot/get_lang.php?id=$recId");	
		if($rec_lang=='0'){
			sendNewMessage($recId, $accessToken,"💡 Chat has been closed from the other side, but no problem, let's start new one");
		}
		else{
			sendNewMessage($recId, $accessToken,"💡 تم إغلاق الدردشة من الجانب الآخر، ولكن لا توجد مشكلة، دعنا نبدأ واحدة جديدة");
		}
		
		
}
	else {
		if($user_lang=='0')
			sendNewMessage($senderId, $accessToken,"💡 You aren't in chat, let's start new one");
		else
			sendNewMessage($senderId, $accessToken,"💡 أنت لست في دردشة، دعنا نبدأ واحدة جديدة");
			
	}
		die();
	break;
	
	
default:
	
		$isEditTag=file_get_contents("http://badsendhellobot.onlinewebshop.net/bot/is_edit_tag.php?id=$senderId");
		if($isEditTag){
			 $edit_tag_result = file_get_contents("http://badsendhellobot.onlinewebshop.net/bot/edit_tag.php?id=$senderId&tag=".str_replace("'","''",$messageText));
  if($edit_tag_result >= 0){
		if($user_lang=='0')
		sendOneWordMessage($senderId,$accessToken,"💡 Your interest changed to $messageText","Edit interest again","/command_HOW_TO_EDIT_TAG_command/");
		else
		sendOneWordMessage($senderId,$accessToken,"💡 تم تغير الاهتمام الى $messageText","اعادة تغيرالاهتمام","/command_HOW_TO_EDIT_TAG_command/");
	}
  else{
		if($user_lang=='0')
  		sendNewMessage($senderId, $accessToken,"💡 Your are not registed, registe by starting new chat");
		else
			sendNewMessage($senderId, $accessToken,"💡 انت عير مسجل ,يمكنك التسجيل عن طريق بداية دردشة جديدة من القائمة الجانبية");
			
	}
			file_get_contents("http://badsendhellobot.onlinewebshop.net/bot/set_is_edit_tag.php?id=$senderId&isEditTag=0");
			die();
		}
			
		
		
		$user_ststus=file_get_contents("http://badsendhellobot.onlinewebshop.net/bot/get_status.php?id=$senderId");
	
	if ($user_ststus== 2){
	  $recId = file_get_contents("http://badsendhellobot.onlinewebshop.net/bot/get_twin.php?id=$senderId");
	    if (isset($messageing['message']['attachments'])){
	      $type=$messageing['message']['attachments'][0]['type'];
	      $url=$messageing['message']['attachments'][0]['payload']['url'];

	      sendAttachmentMessage($recId,$accessToken,$type,$url);
        die();
      }
	    sendTextMessage($recId, $messageText, $accessToken);
	}
	elseif ($user_ststus== 1){
		if($user_lang=='0')
	  	sendEndMessage($senderId, $accessToken,"💡 Just sit back and wait, we are seeking for someone has the same interest");
		else
			sendEndMessage($senderId, $accessToken,"💡 فقط عليك الجلوس والانتظار، ونحن نبحث عن شخص لديه نفس الأهتمام");
	}
	else
	{
		if($user_lang=='0')
			sendNewMessage($senderId, $accessToken,"💡 You aren't in chat, let's start new one");
		else
			sendNewMessage($senderId, $accessToken,"💡 أنت لست في دردشة، دعنا نبدأ واحدة جديدة");
			
	}
}

// ===========================End Commands handling==============================



// ===========================Final sending======================================

function sendTextMessage($recId, $messageText, $accessToken)
{
	$response = ['recipient' => ['id' => $recId], 'message' => ['text' => $messageText]];
	$ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token=' . $accessToken);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
	curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
	curl_exec($ch);
	curl_close($ch);
}

// ==============================================================================

function sendRawMessage($senderId,$accessToken,$text,$title,$payload)
{
  //https://image.flaticon.com/icons/svg/291/291201.svg
	if($payload=='/command_END_CHAT_command/')
	$logo='148766';
	else
	$logo='148764';
	$raw = '{
  "recipient":{
    "id":"' . $senderId . '"
  },
  "message":{
    "text":"' . $text . '",
    "quick_replies":[
      {
        "content_type":"text",
        "title":"'.$title.'",
        "payload":"' . $payload . '",
        "image_url":"https://image.flaticon.com/icons/png/128/148/'.$logo.'.png"
      }
    ]
  }
}';
	$ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token=' . $accessToken);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $raw);
	curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
	curl_exec($ch);
	curl_close($ch);
}
//======================================================================================


function sendEndMessage($senderId,$accessToken,$text)
{
  //https://image.flaticon.com/icons/svg/291/291201.svg

	$raw = '{
  "recipient":{
    "id":"' . $senderId . '"
  },
  "message":{
    "text":"' . $text . '",
    "quick_replies":[
      {
        "content_type":"text",
        "title":"Quit this chat",
        "payload":"/command_END_CHAT_command/",
        "image_url":"https://image.flaticon.com/icons/png/128/148/148766.png"
      }
    ]
  }
}';
	$ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token=' . $accessToken);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $raw);
	curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
	curl_exec($ch);
	curl_close($ch);
}
//==================================================================
function sendNewMessage($senderId,$accessToken,$text)
{
  //https://image.flaticon.com/icons/svg/291/291201.svg

	$raw = '{
  "recipient":{
    "id":"' . $senderId . '"
  },
  "message":{
    "text":"' . $text . '",
    "quick_replies":[
      {
        "content_type":"text",
        "title":"New chat",
        "payload":"/command_NEW_CHAT_command/",
        "image_url":"https://image.flaticon.com/icons/png/128/148/148764.png"
      }
    ]
  }
}';
	$ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token=' . $accessToken);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $raw);
	curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
	curl_exec($ch);
	curl_close($ch);
}
//================================================================================================================
function sendOptionMessage($senderId,$accessToken,$text,$titleOne,$payloadOne,$titleTwo,$payloadTwo)
{
	if($payloadOne=="/command_GET_ENGLISH_command/"||$payloadOne=="/command_MENU_ENGLISH_command/"){
  	$image_url_one="https://image.flaticon.com/icons/png/128/294/294059.png";
		$image_url_two="https://image.flaticon.com/icons/png/128/197/197569.png";
	}
	elseif($payloadOne=="/command_END_CHAT_command/"){
  	$image_url_one="https://image.flaticon.com/icons/png/128/148/148766.png";
		$image_url_two="https://image.flaticon.com/icons/png/128/148/148764.png";
	}

	$raw = '{
  "recipient":{
    "id":"' . $senderId . '"
  },
  "message":{
    "text":"' . $text . '",
    "quick_replies":[
      {
        "content_type":"text",
        "title":"'.$titleOne.'",
        "payload":"'.$payloadOne.'",
        "image_url":"'.$image_url_one.'"
      },
			{
        "content_type":"text",
        "title":"'.$titleTwo.'",
        "payload":"'.$payloadTwo.'",
        "image_url":"'.$image_url_two.'"
      }
    ]
  }
}';
	$ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token=' . $accessToken);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $raw);
	curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
	curl_exec($ch);
	curl_close($ch);
}
//====================================================================================================
function sendOptionsMessage($senderId,$accessToken,$text)
{
  //https://image.flaticon.com/icons/svg/291/291201.svg

	$raw = '{
  "recipient":{
    "id":"' . $senderId . '"
  },
  "message":{
    "text":"' . $text . '",
    "quick_replies":[
      {
        "content_type":"text",
        "title":"General",
        "payload":"/command_GENERAL_CHAT_command/",
        "image_url":"https://image.flaticon.com/icons/png/128/148/148766.png"
      },
			{
        "content_type":"text",
        "title":"Tags",
        "payload":"/command_TAGS_CHAT_command/",
        "image_url":"https://image.flaticon.com/icons/png/128/148/148764.png"
      }
    ]
  }
}';
	$ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token=' . $accessToken);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $raw);
	curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
	curl_exec($ch);
	curl_close($ch);
}
//====================================================================================================
function sendOneWordMessage($senderId,$accessToken,$text,$title,$payload)
{
$image_url="https://image.flaticon.com/icons/png/512/187/187156.png";
  if($title=="General")
		$image_url="https://image.flaticon.com/icons/png/128/134/134914.png";
	
	elseif($title=="Edit interest again"||$title=="اعادة تغيرالاهتمام")
$image_url="https://image.flaticon.com/icons/png/128/137/137531.png";
		

	$raw = '{
  "recipient":{
    "id":"' . $senderId . '"
  },
  "message":{
    "text":"' . $text . '",
    "quick_replies":[
      {
        "content_type":"text",
        "title":"'.$title.'",
        "payload":"'.$payload.'",
        "image_url":"'.$image_url.'"
      }
    ]
  }
}';
	$ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token=' . $accessToken);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $raw);
	curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
	curl_exec($ch);
	curl_close($ch);
}
//======================================================================================================
function sendAttachmentMessage($senderId,$accessToken,$type,$url)
{

	$raw = '{
  "recipient":{
    "id":"'.$senderId.'"
  },
  "message":{
    "attachment":{
      "type":"'.$type.'",
      "payload":{
        "url":"'.$url.'"
      }
    }
  }
}';
	$ch = curl_init('https://graph.facebook.com/v2.6/me/messages?access_token=' . $accessToken);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $raw);
	curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
	curl_exec($ch);
	curl_close($ch);
}


// ===========================End Final sending==================================

