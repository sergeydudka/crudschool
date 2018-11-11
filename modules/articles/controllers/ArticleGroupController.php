<?php

namespace crudschool\modules\articles\controllers;

use crudschool\api\ApiController;
use Yii;
use crudschool\modules\articles\models\ArticleGroup;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArticleGroupController implements the CRUD actions for ArticleGroup model.
 */
class ArticleGroupController extends ApiController {
	public $modelClass = 'crudschool\modules\articles\models\ArticleGroup';
	
	/**
	 * Lists all ArticleGroup models.
	 * @return mixed
	 */
	public function actionIndex() {
		$dataProvider = new ActiveDataProvider([
			'query' => ArticleGroup::find(),
		]);
		
		$page = Yii::$app->request->get('page');
		
		if ($page) {
			Yii::$app->session->set(self::class . '_page', $page);
		} else {
			Yii::$app->session->set(self::class . '_page', 1);
		}
		
		return $this->render('index', [
			'dataProvider' => $dataProvider,
		]);
	}
	
	/**
	 * Displays a single ArticleGroup model.
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
	 * Creates a new ArticleGroup model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate() {
		$model = new ArticleGroup();
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$page = Yii::$app->session->get(self::class . '_page', 1);
			
			$params = ['index'];
			
			if ($page) {
				$params['page'] = $page;
			}
			
			return $this->redirect($params);
		}
		
		return $this->render('create', [
			'model' => $model,
		]);
	}
	
	/**
	 * Updates an existing ArticleGroup model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	public function actionUpdate($id) {
		$model = $this->findModel($id);
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			$page = Yii::$app->session->get(self::class . '_page', 1);
			
			$params = ['index'];
			
			if ($page) {
				$params['page'] = $page;
			}
			
			return $this->redirect($params);
		}
		
		return $this->render('update', [
			'model' => $model,
		]);
	}
	
	/**
	 * Deletes an existing ArticleGroup model.
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
	 * Finds the ArticleGroup model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return ArticleGroup the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id) {
		if (($model = ArticleGroup::findOne($id)) !== null) {
			return $model;
		}
		
		throw new NotFoundHttpException('The requested page does not exist.');
	}
}
