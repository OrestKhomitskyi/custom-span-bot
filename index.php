<?php

/*
* This file is part of GeeksWeb Bot (GWB).
*
* GeeksWeb Bot (GWB) is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License version 3
* as published by the Free Software Foundation.
* 
* GeeksWeb Bot (GWB) is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.  <http://www.gnu.org/licenses/>
*
* Author(s):
*
* © 2015 Kasra Madadipouya <kasra@madadipouya.com>
*
*/

require 'vendor/autoload.php';

require './getMoodSmile.php';

$client = new Zelenin\Telegram\Bot\Api('399359167:AAG77kgiiHyAjTt37Y-oi8sGI64w1X89FdU'); // Set your access token

$url = 'https://customspambot.herokuapp.com'; // URL RSS feed
$update = json_decode(file_get_contents('php://input'));
//your app
try {

    error_log(serialize($update->message));

    if ($update->message->photo) {
        $file_id = $update->message->photo->file_id;

        $fileInfo = json_decode(file_get_contents("https://api.telegram.org/bot399359167:AAG77kgiiHyAjTt37Y-oi8sGI64w1X89FdU/getFile?file_id".$file_id), TRUE);
        $filePath = $fileInfo['result']['file_path'];
        $data = file_get_contents("https://api.telegram.org/file/bot399359167:AAG77kgiiHyAjTt37Y-oi8sGI64w1X89FdU/file".$filePath);


        $fullPath = "temp/".$filePath;
        file_put_contents($fullPath, $data);

        $smile = getMoodSmile($fullPath);

        $response=$client->sendMessage([
            'chat_id' => $update->message->chat->id,
            'text'=> "Твое настроение ".$smile]
        );

    }   

    if(file_exists('file.txt')==true){
        unlink('file.txt');
        
        $response=$client->sendChatAction([
            'chat_id'=>$update->message->chat->id,
            'action'=> 'typing'
        ]);
        
        if($update->message->text=='Марта' || $update->message->text=='Marta'){
            $response=$client->sendMessage([
                'chat_id' => $update->message->chat->id,
                'text'=> "I love you {$update->message->text}"
            ]);
            $response=$client->sendSticker([
                'chat_id' => $update->message->chat->id,
                'file_id'=> 2
            ]);
        }
        else
            $response=$client->sendMessage([
            'chat_id' => $update->message->chat->id,
            'text'=> "Hello {$update->message->text}"
        ]);
    }
    else if($update->message->text == '/sayhello'){
        $response = $client->sendChatAction([
            'chat_id' => $update->message->chat->id, 'action' => 'typing']
        );
        
        $response=$client->sendMessage([
            'chat_id' => $update->message->chat->id,
            'text'=>'Tell me your name'
        ]);
        file_put_contents('file.txt','1');
    }
    else if ($update->message->text == '/smileface') {
        $response = $client->sendChatAction([
            'chat_id' => $update->message->chat->id, 'action' => 'typing']
        );
        $response=$client->sendMessage([
                'chat_id' => $update->message->chat->id,
                'text'=> "Загрузіть фото свого лиця"
        ]);
    }
    else if($update->message->text == '/help')
    {
    	$response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
    	$response = $client->sendMessage([
    		'chat_id' => $update->message->chat->id,
    		'text' => "Список команд :\n/smileface -> Отримати смайл настрою\n/goroskope -> Узнать свой гороскоп\n"
    		]);
    }
    else if($update->message->text == '/latest')
    {
    		Feed::$cacheDir 	= __DIR__ . '/cache';
			Feed::$cacheExpire 	= '5 hours';
			$rss 		= Feed::loadRss($url);
			$items 		= $rss->item;
			$lastitem 	= $items[0];
			$lastlink 	= $lastitem->link;
			$lasttitle 	= $lastitem->title;
			$message = $lasttitle . " \n ". $lastlink;
			$response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
			$response = $client->sendMessage([
					'chat_id' => $update->message->chat->id,
					'text' => $message
				]);
    }
    else
    {
    	$response = $client->sendChatAction(['chat_id' => $update->message->chat->id, 'action' => 'typing']);
    	$response = $client->sendMessage([
    		'chat_id' => $update->message->chat->id,
    		'text' => "{$_SESSION['sayhello']}Invalid command, please use /help to get list of available commands"
    		]);
    }

} catch (\Zelenin\Telegram\Bot\NotOkException $e) {

    //echo error message ot log it
    //echo $e->getMessage();
    error_log($e->getMessage());

}
