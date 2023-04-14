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

use Maslosoft\Mangan\Traits\Access\GetSet;

/**
 * Image parameter holder
 * @since 2.0.2
 * @author Piotr
 */
class ImageParams
{

	use GetSet;

	/**
	 * Width of image
	 * @var int
	 */
	public int $width = 0;

	/**
	 * Height of image
	 * @var int
	 */
	public int $height = 0;

	/**
	 * If set to `true` adaptive (with crop) resize will be used,
	 * if value is `false` best match scaling will be used
	 * @var bool
	 */
	public bool $adaptive = false;

	/**
	 * This is to indicate that image is temporary,
	 * this should set to true for thumbs or other ephemeral images that could be
	 * rebuilt from source image
	 * @var bool
	 */
	public bool $isTemp = false;

	public function toArray(): array
	{
		if ($this->width > 0 || $this->height > 0)
		{
			// Get resized
			return [
				'width' => $this->getWidth(),
				'height' => $this->getHeight(),
				'adaptive' => $this->getAdaptive(),
				'isTemp' => $this->getIsTemp()
			];
		}

		// Get original image
		return [
			'isTemp' => false
		];
	}

	public function getWidth(): int
	{
		return $this->width;
	}

	public function getHeight(): int
	{
		return $this->height;
	}

	public function getAdaptive(): bool
	{
		return $this->adaptive;
	}

	public function getIsTemp(): bool
	{
		return $this->isTemp;
	}

	public function setWidth(int $width): void
	{
		$this->width = $width;
	}

	public function setHeight(int $height): void
	{
		$this->height = $height;
	}

	public function setAdaptive(bool $adaptive): void
	{
		$this->adaptive = $adaptive;
	}

	public function setIsTemp(bool $isTemp): void
	{
		$this->isTemp = $isTemp;
	}

}
