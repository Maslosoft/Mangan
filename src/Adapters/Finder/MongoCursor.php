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

namespace Maslosoft\Mangan\Adapters\Finder;

use Maslosoft\Mangan\Interfaces\Adapters\FinderCursorInterface;
use MongoCursor as MongoCursorOriginal;

/**
 * MongoCursor
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class MongoCursor extends MongoCursorOriginal implements FinderCursorInterface
{

}
