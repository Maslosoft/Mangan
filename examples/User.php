<?php

class User extends EMongoRecord
{
  /**
   * A public variable should be defined for each key=>value you want in the
   * model. Just like if it were a column in a mysql database
   */
  public $username;
  public $email;
  public $personal_number;
  public $first_name;
  public $last_name;
  public $client;
  public $company;

  /**
   * this is similar to the get table method. this returns tha name of the
   * document for this class. this should be in all lowercase.
   */
  public function getCollectionName()
  {
    return 'user';
  }

  /**
   * This is defined as normal. Nothing has changed here
   *
   * @return array
   */
  public function rules() {
    return array(
      array('personal_number, first_name, last_name', 'required'),
    );
  }

  /**
   * This returns attribute labels for each public variable that will be stored
   * as key in the database. Is defined just as normal with mysql
   *
   * @return array
   */
  public function attributeLabels()
  {
    return array(
      'username' => 'UserName',
      'email' => 'EMail',
      'personal_number' => 'PN',
      'first_name' => 'First Name',
      'last_name' => 'Last Name',
      'client' => 'Client',
      'company' => 'Company',
    );
  }

  /**
   * Returns the class name just as nornal.
   *
   * @static
   * @param string $className
   * @return
   */
  public static function model($className = __CLASS__)
  {
    return parent::model($className);
  }

  /**
   * RELATIONS
   */

  /**
   * HAS ONE relation
   * @return
   */
  public function client()
  {
    return Client::model()->findByAttributes(array('client'=>$this->primaryKey));
  }

  /**
   * BELONGS TO relation
   * @return
   */
  public function company()
  {
    return Company::model()->findByPk($this->company);
  }

  /**
   * HAS MANY relation
   * assume we have an orders and client model where we want to see all orders
   * by the client this user HAS ONE of. So this will return all orders
   * belonging this the users client.
   * @return
   */
  public function orders()
  {
    return Orders::model()->findAllByAttributes(array('client_id'=>$this->primaryKey()));
  }

  /**
   * MANY MANY relation ship
   * all you have to do is create another HAS MANY relationship in the other
   * model. For example. If you had posts and tags where a post can have many
   * tags and each tag can have many posts. You would define the two following
   * methods
   *
   * This setup is also assuming you have a separate Model for handling tags
   * that lists the mongo object _id's that belongs to it. And in each post you
   * have a list of tag names that this post has.
   * @return
   */
  // This method would be in the posts model. the tags key in the document is
  // just an array of tag names.
  public function tags()
  {
    return Posts::model()->findAllByAttributes(array('tags'));
  }

  // This method would be in the tags model. each tag is a document that has a
  // relation to the objects _id.
  public function Posts()
  {
    return Tags::model()->findAllByAttributes(array('posts'));
  }

  /**
   * Embedded Documents. All you need to do is list the models that will be used
   * as embedded documents in this fashion:
   * 'name' => 'Model'
   * Where 'name' is the name of this embedded document, and how it will appear
   * as a key in the document.And 'Model' is the name of the model that holds
   * the embedded documents information. For the example below, see the
   * UserAddress.php file for an example
   *
   * @return array
   */
  public function embeddedDocuments( ) {
    // property name => embedded document class name
    return array(
      'address' => 'UserAddress',
    );
  }
}