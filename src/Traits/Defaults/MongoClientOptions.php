<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link http://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Traits\Defaults;

use Maslosoft\Mangan\Options\AuthMechanism;
use MongoClient;
use ReflectionClass;
use ReflectionProperty;

/**
 * MongoClientOptions
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait MongoClientOptions
{

	/**
	 * Available mechanisms are:
	 * authMechanism 	Description 	Availability
	 * MONGODB-CR 	Authenticate using Challenge Response mechanism. This is the default value. 	All MongoDB versions
	 * X509 	Authenticates using X509 certificates 	MongoDB 2.6. Only available when OpenSSL is enabled
	 * PLAIN 	Authenticates using unencrypted plain username+password. Must be used over SSL connections. Generally used by MongoDB to login via 3rd party LDAP server 	MongoDB Enterprise 2.4. The Driver must be compiled against CyrusSASL2
	 * GSSAPI 	Authenticates via kerberos systems 	MongoDB Enterprise 2.4. The Driver must be compiled against CyrusSASL2
	 * @var string
	 */
	public $authMechanism = AuthMechanism::MongoDBCR;

	/**
	 * Should be set to the database name where the user is defined it.
	 * @var string
	 */
	public $authSource;

	/**
	 * If the constructor should connect before returning.
	 * Default is FALSE. When set to FALSE the driver will automatically connect to the server whenever it is necessary to do a query.
	 * Alternatively, you can run MongoClient::connect() manually.
	 * @var boolean
	 */
	public $connect = false;

	/**
	 *  How long a connection can take to be opened before timing out in milliseconds. Defaults to 60000 (60 seconds).
	 *
	 * If -1 is specified, no connection timeout will be applied and PHP will use default_socket_timeout.
	 * @var int
	 */
	public $connectTimeoutMS = 60000;

	/**
	 * The database to authenticate against can be specified here, instead of including it in the host list. This overrides a database given in the host list. 
	 * @var string
	 */
	public $db;

	/**
	 * Boolean, defaults to FALSE. If journaling is enabled, it works exactly like "j".
	 * If journaling is not enabled, the write operation blocks until it is synced to database files on disk.
	 * If TRUE, an acknowledged insert is implied and this option will override setting "w" to 0.
	 *
	 * Note: If journaling is enabled, users are strongly encouraged to use the "j" option instead of "fsync".
	 * Do not use "fsync" and "j" simultaneously, as that will result in an error.
	 *
	 * @var bool
	 */
	public $fsync = false;

	/**
	 * Boolean, defaults to FALSE. If journaling is enabled, it works exactly like "j".
	 * If journaling is not enabled, the write operation blocks until it is synced to database files on disk.
	 * If TRUE, an acknowledged insert is implied and this option will override setting "w" to 0.
	 *
	 * Note: If journaling is enabled, users are strongly encouraged to use the "j" option instead of "fsync".
	 * Do not use "fsync" and "journal" simultaneously, as that will result in an error.
	 * @var bool
	 */
	public $journal = false;

	/**
	 * Sets the » Kerberos service principal. Only applicable when authMechanism=GSSAPI. Defaults to "mongodb". 
	 * @var string
	 */
	public $gssapiServiceName;

	/**
	 * The password can be specified here, instead of including it in the host list.
	 * This is especially useful if a password has a "@" in it.
	 * This overrides a password set in the host list.
	 * @var string
	 */
	public $password;

	/**
	 * Specifies the read preference type. Read preferences provide you with control from which secondaries data can be read from.
	 *
	 * Allowed values are: MongoClient::RP_PRIMARY, MongoClient::RP_PRIMARY_PREFERRED, MongoClient::RP_SECONDARY, MongoClient::RP_SECONDARY_PREFERRED and MongoClient::RP_NEAREST.
	 *
	 * See the documentation on read preferences for more information.
	 * @var int
	 */
	public $readPreference = MongoClient::RP_PRIMARY;

	/**
	 * Specifies the read preference tags as an array of strings. Tags can be used in combination with the readPreference option to further control which secondaries data might be read from.
	 *
	 * See the documentation on read preferences for more information.
	 * @var int|string[]
	 */
	public $readPreferenceTags;

	/**
	 * The name of the replica set to connect to.
	 * If this is given, the primary will be automatically be determined.
	 * This means that the driver may end up connecting to a server that was not even listed. See the replica set example below for details.
	 * @var string
	 */
	public $replicaSet;

	/**
	 * When reading from a secondary (using ReadPreferences), do not read from secondaries known to be more then secondaryAcceptableLatencyMS away from us. Defaults to 15
	 * @var int
	 */
	public $secondaryAcceptableLatencyMS = 15;

	/**
	 * How long a socket operation (read or write) can take before timing out in milliseconds. Defaults to 30000 (30 seconds).
	 *
	 * If -1 is specified, socket operations may block indefinitely. This option may also be set on a per-operation basis using MongoCursor::timeout() for queries or the "socketTimeoutMS" option for write methods.
	 *
	 * Note: This is a client-side timeout. If a write operation times out, there is no way to know if the server actually handled the write or not, as a MongoCursorTimeoutException will be thrown in lieu of returning a write result.
	 * @var int
	 */
	public $socketTimeoutMS = 30000;

	/**
	 * A boolean to specify whether you want to enable SSL for the connections to MongoDB.
	 * Extra options such as certificates can be set with SSL context options.
	 * @var string
	 */
	public $ssl = 'false';

	/**
	 * The username can be specified here, instead of including it in the host list.
	 * This is especially useful if a username has a ":" in it.
	 * This overrides a username set in the host list.
	 * @var string
	 */
	public $username = '';

	/**
	 * The w option specifies the Write Concern for the driver, which determines how long the driver blocks when writing. The default value is 1.
	 *
	 * This option is applicable when connecting to both single servers and replica sets. A positive value controls how many nodes must acknowledge the write instruction before the driver continues. A value of 1 would require the single server or primary (in a replica set) to acknowledge the write operation. A value of 3 would cause the driver to block until the write has been applied to the primary as well as two secondary servers (in a replica set).
	 *
	 * A string value is used to control which tag sets are taken into account for write concerns. "majority" is special and ensures that the write operation has been applied to the majority (more than 50%) of the participating nodes.
	 * @var int|string
	 */
	public $w = 1;

	/**
	 * This option specifies the time limit, in milliseconds, for write concern acknowledgement.
	 * It is only applicable when "w" is greater than 1, as the timeout pertains to replication.
	 * If the write concern is not satisfied within the time limit, a MongoCursorException will be thrown.
	 * A value of 0 may be specified to block indefinitely. The default value for MongoClient is 10000 (ten seconds).
	 * @var int
	 */
	public $wTimeoutMS = 10000;

	protected function _getOptionNames()
	{
		$properties = [];
		foreach ((new ReflectionClass(MongoClientOptions::class))->getProperties(ReflectionProperty::IS_PUBLIC) as $property)
		{
			if (!$property->isStatic())
			{
				$properties[] = $property->name;
			}
		}
		return $properties;
	}

}
