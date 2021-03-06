<?php
namespace Meta;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Meta\ManganMeta;
use Maslosoft\ManganTest\Models\CompositePrimaryKey;
use Maslosoft\ManganTest\Models\SimplePrimaryKey;

/**
 * Document_BaseAttributesNoAnnotationsTest
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class PrimaryKeyTest extends Unit
{

	public function testCanSetSimplePrimaryKey()
	{
		$m = new SimplePrimaryKey();
		$key = ManganMeta::create($m)->type()->primaryKey;
		$this->assertSame($key, 'primaryKey');
	}

	public function testCanSetCompositePrimaryKey()
	{
		$m = new CompositePrimaryKey();
		$key = ManganMeta::create($m)->type()->primaryKey;
		$this->assertSame($key, ['primaryOne', 'primaryTwo', 'primaryThree']);
	}
}
