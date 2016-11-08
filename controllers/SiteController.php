<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\MainForm;
use app\models\GSearch;

class SiteController extends Controller {

	/**
	 * @inheritdoc
	 */
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only' => ['logout'],
				'rules' => [
					[
						'actions' => ['logout'],
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'logout' => ['post'],
				],
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function actions() {
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class' => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			],
		];
	}

	/**
	 * Displays main page.
	 *
	 * @return string
	 */
	public function actionIndex() {
		$model = new MainForm();

		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			foreach (glob(Yii::getAlias('@webroot') . '/img/*') as $file) {
				unlink($file);
			}

			$GSearch = new GSearch();
			$images = $GSearch->getImagesFromQuery($model->search, Yii::$app->params['CountImages']);
			// last five to start
			for ($i = 0; $i < 5; $i++) {
				$elem = array_pop($images);
				array_unshift($images, $elem);
			}

			return $this->render('results', [
						'images' => $images
			]);
		}

		return $this->render('index', [
					'model' => $model
		]);
	}

	/**
	 * Displays about page.
	 *
	 * @return string
	 */
	public function actionAbout() {
		return $this->render('about');
	}
}
