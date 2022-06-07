<?php 
namespace app\models;

use Yii;
use yii\base\Model;

class TestModel extends Model
{
    public $testfield;
    public function rules()
    {
        return [
            ['testfield', 'required'],
        ];
    }

}

