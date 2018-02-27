<?php
namespace Transformator;

use Codeception\Test\Unit;
use Maslosoft\Mangan\Transformers\RawArray;
use Maslosoft\ManganTest\Models\BaseAttributesNoAnnotations;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Transformers_ToArrayBaseAttributesNoAnnotationsTest
 *
 * @author Piotr Maselkowski <pmaselkowski at gmail.com>
 */
class ToArray_BaseAttributesNoAnnotationsTest extends Unit
{

	public function testToArray()
	{
		$model = new BaseAttributesNoAnnotations();

		$array = RawArray::fromModel($model);

		$this->assertSame($model->int, $array['int']);
		$this->assertSame($model->string, $array['string']);
		$this->assertSame($model->bool, $array['bool']);
		$this->assertSame($model->float, $array['float']);
		$this->assertSame($model->array, $array['array']);
		$this->assertSame($model->null, $array['null']);
	}

}
