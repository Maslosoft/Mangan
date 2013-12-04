<?php

/**
 * Class for storing embedded images
 *
 * @author Piotr
 */
class EMongoImage extends EMongoFile
{

	/**
	 * Get resized image
	 * @param int $width
	 * @param int $height
	 * @param bool $adaptive If true adaptive (with crop) resize will be used, if false best match scalling will be used
	 * @return MongoGridFSFile
	 */
	public function get(EMongoImageParams $params = null)
	{
		// Get original image
		if (!$params)
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
			$fileName = tempnam('/tmp/', __CLASS__);
			$result->write($fileName);


			$image = PhpThumbFactory::create($fileName);
			if ($params->adaptive)
			{
				$image->adaptiveResize($params->width, $params->height)->save($fileName);
			}
			else
			{
				$image->resize($params->width, $params->height)->save($fileName);
			}

			$this->_set($fileName, $fileName, $params->toArray());
			unlink($fileName);
			$result = $this->_get($params->toArray());
		}
//		var_dump($result);
//		exit;
		return $result;
	}

	/**
	 * Send file to browser
	 */
	public function send(EMongoImageParams $params = null)
	{
		$this->_send($this->get($params));
	}

}
