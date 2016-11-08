<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * TestForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class MainForm extends Model {

    public $search;

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['search'], 'required'],
            [['search'], 'filter', 'filter' => function($value) {
            return trim(htmlentities(strip_tags($value), ENT_QUOTES, 'UTF-8'));
        }],
        ];
    }
}
