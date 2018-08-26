<?php

namespace crudschool\modules\languages\controllers;

use Yii;
use crudschool\modules\languages\models\Language;
use yii\data\ActiveDataProvider;
use crudschool\api\ApiController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * LanguageController implements the CRUD actions for Language model.
 */
class LanguageController extends ApiController {
	public $modelClass = 'crudschool\modules\languages\models\Language';
	/**
	 * {@inheritdoc}
	 */
	public function behaviors() {
		return [
			'verbs' => [
				'class' => VerbFilter::class,
				'actions' => [
					'delete' => ['POST'],
				],
			],
		];
	}
	
	/**
	 * Lists all Language models.
	 * @return mixed
	 */
	public function actionIndex() {
		$dataProvider = new ActiveDataProvider([
			'query' => Language::find(),
		]);
		
		return $this->render('index', [
			'dataProvider' => $dataProvider,
		]);
	}
	
	/**
	 * Displays a single Language model.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionView($id) {
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}
	
	/**
	 * Creates a new Language model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new Language();
		$model->flag = UploadedFile::getInstances($model, 'flag');
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			if ($model->upload()) {
				// file is uploaded successfully
				return $this->redirect(['index']);
			}
		}
		
		return $this->render('create', [
			'model' => $model,
		]);
	}
	
	/**
	 * Updates an existing Language model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate($id) {
		$model = $this->findModel($id);
		
		if ($model->load(Yii::$app->request->post())) {
			
			$flag = UploadedFile::getInstance($model, 'flag');
			
			if ($flag) {
				$model->flag = is_array($flag) ? reset($flag) : $flag;
			}
			
			if ($model->upload()) {
				// file is uploaded successfully
				$model->save();
				return $this->redirect(['index']);
			}
		}
		
		return $this->render('update', [
			'model' => $model,
		]);
	}
	
	/**
	 * Deletes an existing Language model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionDelete($id) {
		$this->findModel($id)->delete();
		
		return $this->redirect(['index']);
	}
	
	/**
	 * Finds the Language model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return Language the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = Language::findOne($id)) !== null) {
			return $model;
		}
		
		throw new NotFoundHttpException('The requested page does not exist.');
	}
}
