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

namespace Maslosoft\Mangan\Model;

use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Events\ImageEvent;
use Maslosoft\Mangan\Exceptions\FileNotFoundException;
use Maslosoft\Mangan\Helpers\ImageThumb;
use MongoGridFSFile;

/**
 * Class for storing embedded images
 * @since 2.0.1
 * @author Piotr
 */
class Image extends File
{
	const EventBeforeResize = 'beforeResize';

	const EventAfterResize = 'afterResize';

	/**
	 * Image width
	 * @var int
	 */
	public $width = 0;

	/**
	 * Image height
	 * @var int
	 */
	public $height = 0;

	/**
	 * Get resized image
	 * @param ImageParams $params
	 * @return MongoGridFSFile
	 */
	public function get(ImageParams $params = null)
	{
		// Get original image or file if it is not image
		if (!$params || !$this->isImage($this->filename))
		{
			return $this->_get();
		}

		// Get resized image
		$params->isTemp = true;
		$result = $this->_get($params->toArray());

		// Resize and store if not found
		if (!$result)
		{
			$result = $this->_get();
			if (!$result)
			{
				throw new FileNotFoundException('File not found');
			}

			$originalFilename = $result->file['filename'];
			$fileName = tempnam('/tmp/', str_replace('\\', '.', __CLASS__));
			$result->write($fileName);

			$ie = new ImageEvent;
			$ie->sender = $this;
			$ie->path = $fileName;

			Event::trigger($this, self::EventBeforeResize, $ie);

			$image = new ImageThumb($fileName);
			if ($params->adaptive)
			{
				$image->adaptiveResize($params->width, $params->height)->save($fileName);
			}
			else
			{
				$image->resize($params->width, $params->height)->save($fileName);
			}

			$ie = new ImageEvent;
			$ie->sender = $this;
			$ie->path = $fileName;

			Event::trigger($this, self::EventAfterResize, $ie);

			$this->_set($fileName, $originalFilename, $params->toArray());
			unlink($fileName);
			return $this->_get($params->toArray());
		}
		return $result;
	}

	/**
	 * Return true if it is really image
	 * @return bool
	 */
	public function isImage($name)
	{
		return in_array(strtolower(pathinfo($name, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'gif', 'png']);
	}

	protected function _set($tempName, $fileName, $params = [])
	{
		if ($this->isImage($fileName))
		{
			$thumb = new ImageThumb($tempName);
			$dimensions = (object) $thumb->getCurrentDimensions();
			$this->width = $dimensions->width;
			$this->height = $dimensions->height;
		}
		parent::_set($tempName, $fileName, $params);
	}

	/**
	 * Send file to browser
	 */
	public function send(ImageParams $params = null)
	{
		$this->_send($this->get($params));
	}

	/**
	 * Stream file to browser
	 */
	public function stream(ImageParams $params = null)
	{
		$this->_stream($this->get($params));
	}

}
