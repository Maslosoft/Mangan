<?php

/**
 * This software package is licensed under AGPL or Commercial license.
 *
 * @package maslosoft/mangan
 * @licence AGPL or Commercial
 * @copyright Copyright (c) Piotr Masełkowski <pmaselkowski@gmail.com>
 * @copyright Copyright (c) Maslosoft
 * @copyright Copyright (c) Others as mentioned in code
 * @link https://maslosoft.com/mangan/
 */

namespace Maslosoft\Mangan\Traits;

/**
 * AvailableCommands
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
trait AvailableCommands
{
	abstract public function call($command, $arguments = []);
	
	/**
	 * { _isSelf : 1 } INTERNAL ONLY
	 */
	public function _isSelf()
	{
		return $this->call('_isSelf', func_get_args());
	}

	/**
	 * Runs the aggregation command. See http://dochub.mongodb.org/core/aggregation for more details.
	 */
	public function aggregate()
	{
		return $this->call('aggregate', func_get_args());
	}

	/**
	 * internal (sharding)
	 * { applyOps : [ ] , preCondition : [ { ns : ... , q : ... , res : ... } ] }
	 */
	public function applyOps()
	{
		return $this->call('applyOps', func_get_args());
	}

	/**
	 * internal
	 */
	public function authenticate()
	{
		return $this->call('authenticate', func_get_args());
	}

	/**
	 * no help defined
	 */
	public function availableQueryOptions()
	{
		return $this->call('availableQueryOptions', func_get_args());
	}

	/**
	 * get version #, etc.
	 * { buildinfo:1 }
	 */
	public function buildInfo()
	{
		return $this->call('buildInfo', func_get_args());
	}

	/**
	 * Internal command.
	 * 
	 */
	public function checkShardingIndex()
	{
		return $this->call('checkShardingIndex', func_get_args());
	}

	/**
	 * clone this database from an instance of the db on another host
	 * {clone: "host13"[, slaveOk: <bool>]}
	 */
	public function cloneDb()
	{
		return $this->call('clone', func_get_args());
	}

	/**
	 * { cloneCollection: <collection>, from: <host> [,query: <query_filter>] [,copyIndexes:<bool>] }
	 * Copies a collection from one server to another. Do not use on a single server as the destination is placed at the same db.collection (namespace) as the source.
	 * 
	 */
	public function cloneCollection()
	{
		return $this->call('cloneCollection', func_get_args());
	}

	/**
	 * { cloneCollectionAsCapped:<fromName>, toCollection:<toName>, size:<sizeInBytes> }
	 */
	public function cloneCollectionAsCapped()
	{
		return $this->call('cloneCollectionAsCapped', func_get_args());
	}

	/**
	 * Sets collection options.
	 * Example: { collMod: 'foo', usePowerOf2Sizes:true }
	 * Example: { collMod: 'foo', index: {keyPattern: {a: 1}, expireAfterSeconds: 600} Example: { collMod: 'foo', index: {name: 'bar', expireAfterSeconds: 600} }
	 * 
	 */
	public function collMod()
	{
		return $this->call('collMod', func_get_args());
	}

	/**
	 * { collStats:"blog.posts" , scale : 1 } scale divides sizes e.g. for KB use 1024
	 *     avgObjSize - in bytes
	 */
	public function collStats()
	{
		return $this->call('collStats', func_get_args());
	}

	/**
	 * compact collection
	 * warning: this operation locks the database and is slow. you can cancel with killOp()
	 * { compact : <collection_name>, [force:<bool>], [validate:<bool>],
	 *   [paddingFactor:<num>], [paddingBytes:<num>] }
	 *   force - allows to run on a replica set primary
	 *   validate - check records are noncorrupt before adding to newly compacting extents. slower but safer (defaults to true in this version)
	 * 
	 */
	public function compact()
	{
		return $this->call('compact', func_get_args());
	}

	/**
	 * stats about connections between servers in a replica set or sharded cluster.
	 */
	public function connPoolStats()
	{
		return $this->call('connPoolStats', func_get_args());
	}

	/**
	 * internal
	 */
	public function connPoolSync()
	{
		return $this->call('connPoolSync', func_get_args());
	}

	/**
	 * Returns connection-specific information such as logged-in users and their roles
	 */
	public function connectionStatus()
	{
		return $this->call('connectionStatus', func_get_args());
	}

	/**
	 * { convertToCapped:<fromCollectionName>, size:<sizeInBytes> }
	 */
	public function convertToCapped()
	{
		return $this->call('convertToCapped', func_get_args());
	}

	/**
	 * count objects in collection
	 */
	public function count()
	{
		return $this->call('count', func_get_args());
	}

	/**
	 * create a collection explicitly
	 * { create: <ns>[, capped: <bool>, size: <collSizeInBytes>, max: <nDocs>] }
	 */
	public function create()
	{
		return $this->call('create', func_get_args());
	}

	/**
	 * no help defined
	 */
	public function createIndexes()
	{
		return $this->call('createIndexes', func_get_args());
	}

	/**
	 * Adds a role to the system
	 * 
	 */
	public function createRole()
	{
		return $this->call('createRole', func_get_args());
	}

	/**
	 * Adds a user to the system
	 * 
	 */
	public function createUser()
	{
		return $this->call('createUser', func_get_args());
	}

	/**
	 * determine data size for a set of data in a certain range
	 * example: { dataSize:"blog.posts", keyPattern:{x:1}, min:{x:10}, max:{x:55} }
	 * min and max parameters are optional. They must either both be included or both omitted
	 * keyPattern is an optional parameter indicating an index pattern that would be usefulfor iterating over the min/max bounds. If keyPattern is omitted, it is inferred from the structure of min. 
	 * note: This command may take a while to run
	 */
	public function dataSize()
	{
		return $this->call('dataSize', func_get_args());
	}

	/**
	 * no help defined
	 */
	public function dbHash()
	{
		return $this->call('dbHash', func_get_args());
	}

	/**
	 * Get stats on a database. Not instantaneous. Slower for databases with large .ns files.
	 * Example: { dbStats:1, scale:1 }
	 */
	public function dbStats()
	{
		return $this->call('dbStats', func_get_args());
	}

	/**
	 * delete documents
	 */
	public function delete()
	{
		return $this->call('delete', func_get_args());
	}

	/**
	 * { distinct : 'collection name' , key : 'a.b' , query : {} }
	 */
	public function distinct()
	{
		return $this->call('distinct', func_get_args());
	}

	/**
	 * no help defined
	 */
	public function driverOIDTest()
	{
		return $this->call('driverOIDTest', func_get_args());
	}

	/**
	 * drop a collection
	 * {drop : <collectionName>}
	 */
	public function drop()
	{
		return $this->call('drop', func_get_args());
	}

	/**
	 * Drops all roles from the given database.  Before deleting the roles completely it must remove them from any users or other roles that reference them.  If any errors occur in the middle of that process it's possible to be left in a state where the roles have been removed from some user/roles but otherwise still exist.
	 * 
	 */
	public function dropAllRolesFromDatabase()
	{
		return $this->call('dropAllRolesFromDatabase', func_get_args());
	}

	/**
	 * Drops all users for a single database.
	 * 
	 */
	public function dropAllUsersFromDatabase()
	{
		return $this->call('dropAllUsersFromDatabase', func_get_args());
	}

	/**
	 * drop (delete) this database
	 */
	public function dropDatabase()
	{
		return $this->call('dropDatabase', func_get_args());
	}

	/**
	 * drop indexes for a collection
	 */
	public function dropIndexes()
	{
		return $this->call('dropIndexes', func_get_args());
	}

	/**
	 * Drops a single role.  Before deleting the role completely it must remove it from any users or roles that reference it.  If any errors occur in the middle of that process it's possible to be left in a state where the role has been removed from some user/roles but otherwise still exists.
	 * 
	 */
	public function dropRole()
	{
		return $this->call('dropRole', func_get_args());
	}

	/**
	 * Drops a single user.
	 * 
	 */
	public function dropUser()
	{
		return $this->call('dropUser', func_get_args());
	}

	/**
	 * end a set of logical sessions
	 */
	public function endSessions()
	{
		return $this->call('endSessions', func_get_args());
	}

	/**
	 * DEPRECATED
	 * Evaluate javascript at the server.
	 * http://dochub.mongodb.org/core/serversidecodeexecution
	 */
	public function evalJs()
	{
		return $this->call('eval', func_get_args());
	}

	/**
	 * explain database reads and writes
	 */
	public function explain()
	{
		return $this->call('explain', func_get_args());
	}

	/**
	 * return build level feature settings
	 */
	public function features()
	{
		return $this->call('features', func_get_args());
	}

	/**
	 *  example: { filemd5 : ObjectId(aaaaaaa) , root : "fs" }
	 */
	public function filemd5()
	{
		return $this->call('filemd5', func_get_args());
	}

	/**
	 * query for documents
	 */
	public function find()
	{
		return $this->call('find', func_get_args());
	}

	/**
	 * { findAndModify: "collection", query: {processed:false}, update: {$set: {processed:true}}, new: true}
	 * { findAndModify: "collection", query: {processed:false}, remove: true, sort: {priority:-1}}
	 * Either update or remove is required, all other fields have default values.
	 * Output is in the "value" field
	 * 
	 */
	public function findAndModify()
	{
		return $this->call('findAndModify', func_get_args());
	}

	/**
	 * for testing purposes only.  forces a user assertion exception
	 */
	public function forceerror()
	{
		return $this->call('forceerror', func_get_args());
	}

	/**
	 * http://dochub.mongodb.org/core/geo#GeospatialIndexing-geoNearCommand
	 */
	public function geoNear()
	{
		return $this->call('geoNear', func_get_args());
	}

	/**
	 * no help defined
	 */
	public function geoSearch()
	{
		return $this->call('geoSearch', func_get_args());
	}

	/**
	 * return error status of the last operation on this connection
	 * options:
	 *   { fsync:true } - fsync before returning, or wait for journal commit if running with --journal
	 *   { j:true } - wait for journal commit if running with --journal
	 *   { w:n } - await replication to n servers (including self) before returning
	 *   { w:'majority' } - await replication to majority of set
	 *   { wtimeout:m} - timeout for w in m milliseconds
	 */
	public function getLastError()
	{
		return $this->call('getLastError', func_get_args());
	}

	/**
	 * retrieve more results from an existing cursor
	 */
	public function getMore()
	{
		return $this->call('getMore', func_get_args());
	}

	/**
	 * check for errors since last reseterror commandcal
	 */
	public function getPrevError()
	{
		return $this->call('getPrevError', func_get_args());
	}

	/**
	 * internal
	 */
	public function getnonce()
	{
		return $this->call('getnonce', func_get_args());
	}

	/**
	 * Grants privileges to a role
	 * 
	 */
	public function grantPrivilegesToRole()
	{
		return $this->call('grantPrivilegesToRole', func_get_args());
	}

	/**
	 * Grants roles to another role.
	 * 
	 */
	public function grantRolesToRole()
	{
		return $this->call('grantRolesToRole', func_get_args());
	}

	/**
	 * Grants roles to a user.
	 * 
	 */
	public function grantRolesToUser()
	{
		return $this->call('grantRolesToUser', func_get_args());
	}

	/**
	 * http://dochub.mongodb.org/core/aggregation
	 */
	public function group()
	{
		return $this->call('group', func_get_args());
	}

	/**
	 * internal
	 */
	public function handshake()
	{
		return $this->call('handshake', func_get_args());
	}

	/**
	 * returns information about the daemon's host
	 */
	public function hostInfo()
	{
		return $this->call('hostInfo', func_get_args());
	}

	/**
	 * insert documents
	 */
	public function insert()
	{
		return $this->call('insert', func_get_args());
	}

	/**
	 * Check if this server is primary for a replica pair/set; also if it is --master or --slave in simple master/slave setups.
	 * { isMaster : 1 }
	 */
	public function isMaster()
	{
		return $this->call('isMaster', func_get_args());
	}

	/**
	 * kill all logical sessions, for a user, and their operations
	 */
	public function killAllSessions()
	{
		return $this->call('killAllSessions', func_get_args());
	}

	/**
	 * kill logical sessions by pattern
	 */
	public function killAllSessionsByPattern()
	{
		return $this->call('killAllSessionsByPattern', func_get_args());
	}

	/**
	 * kill a list of cursor ids
	 */
	public function killCursors()
	{
		return $this->call('killCursors', func_get_args());
	}

	/**
	 * kill a logical session and its operations
	 */
	public function killSessions()
	{
		return $this->call('killSessions', func_get_args());
	}

	/**
	 * list collections for this db
	 */
	public function listCollections()
	{
		return $this->call('listCollections', func_get_args());
	}

	/**
	 * get a list of all db commands
	 */
	public function listCommands()
	{
		return $this->call('listCommands', func_get_args());
	}

	/**
	 * list indexes for a collection
	 */
	public function listIndexes()
	{
		return $this->call('listIndexes', func_get_args());
	}

	/**
	 * de-authenticate
	 */
	public function logout()
	{
		return $this->call('logout', func_get_args());
	}

	/**
	 * Run a map/reduce operation on the server.
	 * Note this is used for aggregation, not querying, in MongoDB.
	 * http://dochub.mongodb.org/core/mapreduce
	 */
	public function mapReduce()
	{
		return $this->call('mapReduce', func_get_args());
	}

	/**
	 * internal
	 */
	public function mapReduceShardedFinish()
	{
		return $this->call('mapreduce.shardedfinish', func_get_args());
	}

	/**
	 * no help defined
	 */
	public function parallelCollectionScan()
	{
		return $this->call('parallelCollectionScan', func_get_args());
	}

	/**
	 * a way to check that the server is alive. responds immediately even if server is in a db lock.
	 */
	public function ping()
	{
		return $this->call('ping', func_get_args());
	}

	/**
	 * Drops one or all cached queries in a collection.
	 */
	public function planCacheClear()
	{
		return $this->call('planCacheClear', func_get_args());
	}

	/**
	 * Clears index filter for a single query shape or, if the query shape is omitted, all filters for the collection.
	 */
	public function planCacheClearFilters()
	{
		return $this->call('planCacheClearFilters', func_get_args());
	}

	/**
	 * Displays index filters for all query shapes in a collection.
	 */
	public function planCacheListFilters()
	{
		return $this->call('planCacheListFilters', func_get_args());
	}

	/**
	 * Displays the cached plans for a query shape.
	 */
	public function planCacheListPlans()
	{
		return $this->call('planCacheListPlans', func_get_args());
	}

	/**
	 * Displays all query shapes in a collection.
	 */
	public function planCacheListQueryShapes()
	{
		return $this->call('planCacheListQueryShapes', func_get_args());
	}

	/**
	 * Sets index filter for a query shape. Overrides existing filter.
	 */
	public function planCacheSetFilter()
	{
		return $this->call('planCacheSetFilter', func_get_args());
	}

	/**
	 * enable or disable performance profiling
	 * { profile : <n> }
	 * 0=off 1=log slow ops 2=log all
	 * -1 to get current values
	 * http://docs.mongodb.org/manual/reference/command/profile/#dbcmd.profile
	 */
	public function profile()
	{
		return $this->call('profile', func_get_args());
	}

	/**
	 * re-index a collection
	 */
	public function reIndex()
	{
		return $this->call('reIndex', func_get_args());
	}

	/**
	 * renew a set of logical sessions
	 */
	public function refreshSessions()
	{
		return $this->call('refreshSessions', func_get_args());
	}

	/**
	 * renew a set of logical sessions
	 */
	public function refreshSessionsInternal()
	{
		return $this->call('refreshSessionsInternal', func_get_args());
	}

	/**
	 * no help defined
	 */
	public function repairCursor()
	{
		return $this->call('repairCursor', func_get_args());
	}

	/**
	 * repair database.  also compacts. note: slow.
	 */
	public function repairDatabase()
	{
		return $this->call('repairDatabase', func_get_args());
	}

	/**
	 * reset error state (used with getpreverror)
	 */
	public function resetError()
	{
		return $this->call('resetError', func_get_args());
	}

	/**
	 * Revokes privileges from a role
	 * 
	 */
	public function revokePrivilegesFromRole()
	{
		return $this->call('revokePrivilegesFromRole', func_get_args());
	}

	/**
	 * Revokes roles from another role.
	 * 
	 */
	public function revokeRolesFromRole()
	{
		return $this->call('revokeRolesFromRole', func_get_args());
	}

	/**
	 * Revokes roles from a user.
	 * 
	 */
	public function revokeRolesFromUser()
	{
		return $this->call('revokeRolesFromUser', func_get_args());
	}

	/**
	 * Returns information about roles.
	 * 
	 */
	public function rolesInfo()
	{
		return $this->call('rolesInfo', func_get_args());
	}

	/**
	 * Subsequent steps in a SASL authentication conversation.
	 */
	public function saslContinue()
	{
		return $this->call('saslContinue', func_get_args());
	}

	/**
	 * First step in a SASL authentication conversation.
	 */
	public function saslStart()
	{
		return $this->call('saslStart', func_get_args());
	}

	/**
	 * returns lots of administrative server statistics
	 */
	public function serverStatus()
	{
		return $this->call('serverStatus', func_get_args());
	}

	/**
	 * stats about the shard connection pool
	 */
	public function shardConnPoolStats()
	{
		return $this->call('shardConnPoolStats', func_get_args());
	}

	/**
	 * Internal command.
	 * examples:
	 *   { splitVector : "blog.post" , keyPattern:{x:1} , min:{x:10} , max:{x:20}, maxChunkSize:200 }
	 *   maxChunkSize unit in MBs
	 *   May optionally specify 'maxSplitPoints' and 'maxChunkObjects' to avoid traversing the whole chunk
	 *   
	 *   { splitVector : "blog.post" , keyPattern:{x:1} , min:{x:10} , max:{x:20}, force: true }
	 *   'force' will produce one split point even if data is small; defaults to false
	 * NOTE: This command may take a while to run
	 */
	public function splitVector()
	{
		return $this->call('splitVector', func_get_args());
	}

	/**
	 * start a logical session
	 */
	public function startSession()
	{
		return $this->call('startSession', func_get_args());
	}

	/**
	 * touch collection
	 * Page in all pages of memory containing every extent for the given collection
	 * { touch : <collection_name>, [data : true] , [index : true] }
	 *  at least one of data or index must be true; default is both are false
	 * 
	 */
	public function touch()
	{
		return $this->call('touch', func_get_args());
	}

	/**
	 * update documents
	 */
	public function update()
	{
		return $this->call('update', func_get_args());
	}

	/**
	 * Used to update a role
	 * 
	 */
	public function updateRole()
	{
		return $this->call('updateRole', func_get_args());
	}

	/**
	 * Used to update a user, for example to change its password
	 * 
	 */
	public function updateUser()
	{
		return $this->call('updateUser', func_get_args());
	}

	/**
	 * Returns information about users.
	 * 
	 */
	public function usersInfo()
	{
		return $this->call('usersInfo', func_get_args());
	}

	/**
	 * Validate contents of a namespace by scanning its data structures for correctness.  Slow.
	 * Add full:true option to do a more thorough check
	 * Add scandata:false to skip the scan of the collection data without skipping scans of any indexes
	 */
	public function validate()
	{
		return $this->call('validate', func_get_args());
	}

	/**
	 * {whatsmyuri:1}
	 */
	public function whatsmyuri()
	{
		return $this->call('whatsmyuri', func_get_args());
	}

}
