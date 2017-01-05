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

namespace Maslosoft\Mangan\File;

use Maslosoft\Mangan\Interfaces\FileInterface;
use RuntimeException;

/**
 * Upload file helper.
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class Upload implements FileInterface
{

	/**
	 * Uploaded file name
	 * @var string
	 */
	private $fileName = '';

	/**
	 * Uploaded temporary file name
	 * @var string
	 */
	private $tempName = '';

	/**
	 * Get uploaded file data by input name
	 * @param string $inputName Input name from $_FILES array
	 * @throws RuntimeException
	 */
	public function __construct($inputName)
	{
		if (!isset($_FILES[$inputName]))
		{
			throw new RuntimeException(sprintf("Could not find uploaded file for input name `%s`", $inputName));
		}
		$upload = (object) $_FILES[$inputName];
		if ($upload->error)
		{
			throw new RuntimeException(sprintf("Upload failed for input name `%s`, error: `%s`", $inputName, $upload->error));
		}
		$this->fileName = $upload->name;
		$this->tempName = $upload->tmp_name;
	}

	public function getFileName()
	{
		return $this->fileName;
	}

	public function getTempName()
	{
		return $this->tempName;
	}

}
