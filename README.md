#Yii Mangan
######Advanced MongoDB document mapper for Yii

This extension originally started as a fork of [YiiMongoDbSuite](canni.github.com/YiiMongoDbSuite "YiiMongoDbSuite"),
written by [canni](https://github.com/canni "canni") and further improved by several developers ([mintao](https://github.com/mintao "mintao"), et al).
YiiMongoDbSuite originally started as a fork of [MongoRecord](www.yiiframework.com/extension/mongorecord "MongoRecord")
extension written by [tyohan](http://www.yiiframework.com/user/31/ "tyohan"),
to fix some major bugs, and add full featured suite for [MongoDB](http://www.mongodb.org "MongoDB") developers.

The current version is 3.0.0-beta.1

## The Key Feature List:

### Features Covered From Standard Yii Implementations

- Support of using Class::model()->find / findAll / count / countByAttributes and other Yii ActiveRecord syntax.
- Named scopes, along with default scope and parameterized scopes, just like in AR.
- Ready to go out-of-box *EFFICIENT* DataProvider, witch use native php db driver sort, limit and offset features for returning results!
- Model classes and embedded documents inherit from CModel, so you can use every class witch can handle of CModel (ie: Gii form generator)
- Relations support *idea/concept/example*.
- **Support for generating CRUD for EMongoDocument models, with Gii!**.
- **Support for generating mongo document models from existing SQL tables!**.
- Use MongoDB for LogRoute and HttpSession.
- Easy to use criteria object, you don't have to create complex MongoDB query arrays.
- **Fixtures manager, that can replace the Yii default one, and work with Mongo model.**

### MongoDB Related Feature List

- Support of schema-less documents with Yii standard rules and validation features
- Embedded document and arrays of embedded documents support
- Ability to set FSync and/or Safe flag of DB write operations on different scopes, globally, on model level, and on single model object
- **Ability to use efficient MongoDB Cursors instead of raw arrays of results, returned by the findAll* methods**
- MongoDB GridFS feature support, thanks to work of Jose Martinez and Philippe Gaultier
- Support for using any other than _id field as a Primary Key, for a collection
- Automated efficient index definition for collections, per model
- Support "Soft" documents, documents that do not have fixed list of attributes
- **Ability to do *Extreme Efficent* document partial updates, that make use of MongoDB `$set` operator/feature**

### Yii Addendum Related Feature List

- Easy model definition
- Clean view of any model field options/properties
- Lightweight and extensible model metadata

## Limitations
- The main limitations are only those present in MongoDB itself, like the 16mb data transfer limit.
- In it's current incarnation, This extension does NOT work with the "$or" criteria operator. When we get it working we will remove this line and add an example.

## Requirements

- Yii 1.1.14+ is recommended.
- MongoDB 2.4.0+ is recommended. Untested with older versions.
- Mongo PHP Driver 1.4.5+
- PHP 5.5+
- composer

## Setup

Use composer to install extension:

	composer require maslosoft/mangan <version>

In your protected/config/main.php config file. Add `mongoDB` array
for your database in the components section, and add the following to the file:

	'mongodb' => [
		'connectionString' => 'mongodb://user:password@mongo-db-host.example.com',
		'dbName' => 'db_name',
		'class' => 'Maslosoft\Mangan\MongoDB'
	],

- ConnectionString: 'localhost' should be changed to the ip or hostname of your host being connected to. For example
  if connecting to a server it might be `'connectionString' => 'mongodb://username@xxx.xx.xx.xx'` where xx.xx.xx.xx is
  the ip (or hostname) of your webserver or host.
- dbName: The database name, where your collections will be be stored in.
- fsyncFlag If set to true, this makes mongodb make sure all writes to the database are safely stored to disk (true by default).
- safeFlag If set to true, mongodb will wait to retrieve status of all write operations, and check if everything went OK (true by default).
- useCursors If set to true, extension will return EMongoCursor instead of raw pre-populated arrays, from findAll* methods (defaults to false for backwards compatibility).

That's all you have to do for setup. You can use it very much like the active record.
For example:

	<?php
    $client = new Client();
    $client->first_name='something';
    $client->save();
    $clients = Client::model()->findAll();

## Basic Usage

Just define following model:

~~~php
    class User extends EMongoDocument
    {
      /**
       * @Label('User login')
       * @RequiredValidator
       */
      public $login;
      
      /**
       * @Label('Full name')
       * @LengthValidator(max => 255)
       */
      public $name;
      
      /**
       * @Label('Password')
       * @RequiredValidator
       * @LengthValidator(min => 6, max => 20)
       */
      public $pass;
    }
~~~

And that's it! Now start using this User model class like standard Yii AR model.

## Embedded Documents

*NOTE: For performance reasons embedded documents should extend from EMongoEmbeddedDocument instead of EMongoDocument.*

EMongoEmbeddedDocument is almost identical as EMongoDocument, in fact EMongoDocument extends from EMongoEmbeddedDocument
and adds to it the DB connection and related functions.

So if you have a User.php model, and an UserAddress.php model which is the embedded document.
Lest assume we have following embedded document:

	<?php
    class UserAddress extends EMongoEmbeddedDocument
    {
      /**
       * @Label('City')
       * @LengthValidator(max => 255)
       */
      public $city;
      
      /**
       * @Label('Street')
       * @LengthValidator(max => 255)
       */
      public $street;
      
      /**
       * @Label('Home number')
       * @LengthValidator(max => 255)
       */
      public $house;
      
      /**
       * @Label('Apartment number')
       * @LengthValidator(max => 10)
       */
      public $apartment;
      
      /**
       * @Label('Postal code')
       * @LengthValidator(max => 6)
       */
      public $zip;
    }

Now we can add this document to our User model from previous section:

	<?php
    class User extends EMongoDocument {
      ...

      /**
       * @Embedded('UserAddress')
       */
      public $address = null;

      ...
    }

And using it is as easy as π!

	<?php
    $client = new Client;
    $client->address->city='New York';
    $client->save();

This will automatically call validation for the model and all its embedded documents.
You can even nest embedded documents in embedded documents and array of embbedded document, also mix any embedded document types!
*IMPORTANT*: This mechanism uses recurrency, and **will not handle circular nesting**, so use this feature with care.

## Arrays

You easily can store arrays in DB!

**Simple arrays**

- Just define a property for an array, and store an array in it.

**Arrays of embedded documents**

- Just need to add @EmbeddedArray annotation:


	<?php
    /**
     * @EmbeddedArray('UserAddress')
     */
    public $addresses = array();
    

So for the user, if you want them to be able to save multiple addresses, you can do this:

	<?php
    $c = new Client;
    $c->addresses[0] = new ClientAddress;
    $c->addresses[0]->city='NY';
    $c->save(); // will handle validation of array too


Then you can loop addresses:

	<?php
    $c = Client::model()->find();
    foreach($c->addresses as $addr)
    {
        echo $addr->city;
    }
