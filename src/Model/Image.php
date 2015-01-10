<?php

/**
 * @author Piotr Maselkowski, Maslosoft
 * @copyright 2013 Maslosoft http://maslosoft.com
 * @license New BSD license
 * @version 2.0.1
 * @category ext
 * @package maslosoft/yii-mangan
 */

namespace Maslosoft\Mangan\Model;

use CFileHelper;
use Exception;
use MongoGridFSFile;
use PHPThumb\GD;

/**
 * Class for storing embedded images
 * @since 2.0.1
 * @author Piotr
 */
class Image extends File
{

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
				throw new Exception('File not found');
			}

			$originalFilename = $result->file['filename'];
			$fileName = tempnam('/tmp/', __CLASS__);
			$result->write($fileName);


			$image = new GD($fileName);
			if ($params->adaptive)
			{
				$image->adaptiveResize($params->width, $params->height)->save($fileName);
			}
			else
			{
				$image->resize($params->width, $params->height)->save($fileName);
			}

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
		return in_array(strtolower(CFileHelper::getExtension($name)), ['jpg', 'jpeg', 'gif', 'png']);
	}

	protected function _set($tempName, $fileName, $params = [])
	{
		if ($this->isImage($fileName))
		{
			$thumb = new GD($tempName);
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
