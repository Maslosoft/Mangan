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

/**
 * Description of EMongoImageParams
 * @since 2.0.2
 * @property int $width Width of image
 * @property int $height Height of image
 * @property bool $adaptive If true adaptive (with crop) resize will be used, if false best match scalling will be used
 * @property bool $isTemp This is to indicate that image is temporary, will be automatically set to true
 * @author Piotr
 */
class ImageParams
{

	use \Maslosoft\Mangan\Traits\Access\GetSet;

	/**
	 *
	 * @var int
	 */
	private $_width = 0;

	/**
	 *
	 * @var int
	 */
	private $_height = 0;

	/**
	 * If true adaptive (with crop) resize will be used, if false best match scalling will be used
	 * @var bool
	 */
	private $_adaptive = false;

	/**
	 * This is to indicate that image is temporary, this should set to true for thumbs etc.
	 * @var bool
	 */
	private $_isTemp = false;

	public function toArray()
	{
		if ($this->_width || $this->_height)
		{
			// Get resized
			return [
				'width' => $this->getWidth(),
				'height' => $this->getHeight(),
				'adaptive' => $this->getAdaptive(),
				'isTemp' => $this->getIsTemp()
			];
		}
		else
		{
			// Get original image
			return [
				'isTemp' => false
			];
		}
	}

	public function getWidth()
	{
		return (int) $this->_width;
	}

	public function getHeight()
	{
		return (int) $this->_height;
	}

	public function getAdaptive()
	{
		return (bool) $this->_adaptive;
	}

	public function getIsTemp()
	{
		return $this->_isTemp;
	}

	public function setWidth($width)
	{
		$this->_width = $width;
		return $this;
	}

	public function setHeight($height)
	{
		$this->_height = $height;
		return $this;
	}

	public function setAdaptive($adaptive)
	{
		$this->_adaptive = $adaptive;
		return $this;
	}

	public function setIsTemp($isTemp)
	{
		$this->_isTemp = $isTemp;
		return $this;
	}

}
