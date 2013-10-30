<?php

/**
 * This is the model class for table "AdminUser".
 *
 * The followings are the available columns in table 'AdminUser':
 * @property string $id
 * @property string $login
 * @property string $password
 * @property string $caption
 * @property string $role 
 */
class AdminUser extends CActiveRecord {

  const ROLE_ADMIN = 'administrator';
  const ROLE_MODER = 'moderator';

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AdminUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'AdminUser';
	}
	
	public function setPassword($password) {
    
    if ($password) {
      $this->password_hash = self::hashPassword($password);
    }
  }
  
  public function getRoleValues() {
  
    $values = array(
      self::ROLE_MODER=>"Модератор",
      self::ROLE_ADMIN=>"Администратор",
    );
    
    return $values;
  }
  
  public function getPassword() {
  
    return $this->password_hash;
  }
  
  public static function hashPassword($password) {
  
    $salt = '$2a$10$'.substr(str_replace('+', '.', base64_encode(pack('N4', mt_rand(), mt_rand(), mt_rand(),mt_rand()))), 0, 22).'$';
    return crypt($password, $salt);
  }
  
  public function isValidPassword($password) {
  
    return crypt($password, $this->password_hash) === $this->password_hash;
  }
  
  public function getRoleLabel() {
    
    $roles = $this->getRoleValues();
    return $roles[$this->role];
  }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('login, password, caption, role', 'required', 'message'=>"Не заполнено"),
			array('login, caption', 'length', 'max'=>100),
			array('login', 'email', 'message'=>'Некорректный адрес'),
			array('login', 'unique','message'=>'"{value}" уже зарегистрирован'),
			array('password', 'length', 'max'=>60),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, login, password, caption, role', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'login' => 'Логин',
			'password' => 'Пароль',
			'caption' => 'Имя',
			'role' => 'Роль'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('login',$this->login,true);
		$criteria->compare('caption',$this->caption,true);
		$criteria->compare('role',$this->role,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
