<?php

namespace app\models;

use Yii;
use yii\base\Model;
use Google_Client;
use Google_Service_Customsearch;

class GSearch {

	const MAX_PAGES = 10;
	const MAX_ITEMS = 10;

	private static $client;
	private static $service;
	private static $extensions = [
		'image/gif' => "gif",
		'image/jpeg' => "jpg",
		'image/png' => "png",
		'application/x-shockwave-flash' => "swf",
		'image/psd' => "psd",
		'image/bmp' => "bmp",
		'image/tiff' => "tiff",
		'image/jp2' => "jp2",
		'image/iff' => "iff",
		'image/vnd.wap.wbmp' => "wbmp",
		'image/xbm' => "xbm",
		'image/vnd.microsoft.icon' => "ico"
	];
	public $images = [];

	public function __construct() {
		if (self::$service === null) {
			self::$client = new Google_Client();
			self::$client->setApplicationName(Yii::$app->params['GAppName']);
			self::$client->setDeveloperKey(Yii::$app->params['GApiKey']);


			self::$service = new Google_Service_Customsearch(self::$client);
		}
	}

	/**
	 * Method use cURL to get image
	 * 
	 * @param string $url
	 * @return array|false
	 */
	public function getImageFromUrl($url) {
		if ($curl = curl_init($url)) {
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$data = curl_exec($curl);
			$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			$contentType = curl_getinfo($curl, CURLINFO_CONTENT_TYPE);
			curl_close($curl);
		}
		if ($httpCode >= 200 && $httpCode < 300 && is_string($contentType) && array_key_exists($contentType, self::$extensions)) {
			$return['extension'] = self::$extensions[$contentType];
			$return['body'] = $data;
		} else {
			$return = false;
		}
		return $return;
	}

	/**
	 * Method to save image on server
	 * 
	 * @param string $path
	 * @param string $filename
	 * @param string $data
	 * @return true
	 */
	public function saveImage($path, $filename, $data) {
		$fp = fopen($path . $filename, 'w');
		fwrite($fp, $data);
		fclose($fp);
		return true;
	}

	/**
	 * Method to get and save array of images from query
	 * 
	 * @param string $query Search query to Google Customsearch
	 * @param integer $num Count of images
	 * @return array
	 */
	public function getImagesFromQuery($query, $num) {
		if (!empty($this->images)) {
			$this->$images = [];
		}
		$params = [
			'searchType' => 'image',
			'cx' => Yii::$app->params['GEngineID'],
			'num' => self::MAX_ITEMS
		];
		$count = 0;
		$images = [];
		// 10 - maximum pages
		for ($i = 0; $i < self::MAX_PAGES; $i++) {
			$result = self::$service->cse->listCSE($query, $params);
			foreach ($result->items as $item) {
				if ($count === $num) {
					break(2);
				}
				if (!$file = $this->getImageFromUrl($item->link)) {
					continue;
				}
				$filename = ++$count . '.' . $file['extension'];
				$path = Yii::getAlias('@webroot') . '/img/';
				if ($this->saveImage($path, $filename, $file['body'])) {
					$this->images[] = $filename;	
				}
			}
			if (isset($params['start'])) {
				$params['start'] += self::MAX_ITEMS;
			} else {
				$params['start'] = self::MAX_ITEMS;
			}
		}
		return $this->images;
	}

}
