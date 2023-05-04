<?php

namespace Maslosoft\Mangan\File\Wrappers;

use Maslosoft\Mangan\File\Wrappers\Traits\Data;
use Maslosoft\Mangan\Interfaces\File\WrapperInterface;
use MongoDB\BSON\ObjectId;
use MongoDB\GridFS\Bucket;

class BucketWrapper extends Base implements WrapperInterface
{
	use Data;
	
	private ObjectId $id;

	public function __construct(private readonly Bucket $bucket, private readonly array $data)
	{
		$this->id = $this->data['_id'];
	}

	public function getBytes(): string
	{
		$stream = $this->bucket->openDownloadStream($this->id);
		return stream_get_contents($stream);
	}

	public function getLength(): int
	{
		return $this->data['length'];
	}

	public function getStream()
	{
		return $this->bucket->openDownloadStream($this->id);
	}


}