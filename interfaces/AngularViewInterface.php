<?php
/**
 * Created by PhpStorm.
 * User: almon
 * Date: 01.09.2018
 * Time: 19:00
 */

namespace crudschool\interfaces;


interface AngularViewInterface {
	public function getHiddenFields($actionName);
	public function setHiddenFields($actionName, $fieldName);
	public function hasHiddenFields($actionName);
}