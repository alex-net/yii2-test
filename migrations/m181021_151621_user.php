<?php

use yii\db\Migration;

/**
 * Class m181021_151621_user
 */
class m181021_151621_user extends Migration
{
   


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->createTable('user',[
            'id' => $this->primaryKey(),
            'username' => $this->string(60)->notNull(),
            'password' => $this->string(60)->defaultValue(''),
            'authKey' => $this->string(15)->notNull(),
            'fromulogin'=>$this->boolean()->defaultValue(0),
            // поля от ulogin
        ]);
        // добавить индекс ... 
        $this->createIndex('un','user','username',true);
        // добавляем админа . 
        $u=new \app\models\User;
        $u->username='admin';
        $u->password='test';
        $u->save();
    }

    public function down()
    {
        echo "m181021_151621_user cannot be reverted.\n";

        return false;
    }

}
