<?php
namespace Events;


use Maslosoft\Mangan\Events\Event;
use Maslosoft\Mangan\Events\ModelEvent;
use Maslosoft\Mangan\Interfaces\FinderInterface;
use Maslosoft\ManganTest\Models\ActiveDocument\DocumentBaseAttributes;
use Maslosoft\ManganTest\Models\BaseAttributesAnnotations;

class PreventFindTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    private $validHandled = 'no';
    private $handleHandled = 'no';

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests

	public function testEventPreventFind()
	{
		Event::on(DocumentBaseAttributes::class, FinderInterface::EventBeforeFind, [$this, 'valid']);
		Event::on(DocumentBaseAttributes::class, FinderInterface::EventBeforeFind, [$this, 'handle']);
		$this->preventingFindAction();
	}

	public function testEventPreventFindInReverseOrderOfEventsAttaching()
	{
		Event::on(DocumentBaseAttributes::class, FinderInterface::EventBeforeFind, [$this, 'handle']);
		Event::on(DocumentBaseAttributes::class, FinderInterface::EventBeforeFind, [$this, 'valid']);
		$this->preventingFindAction();
	}

    public function preventingFindAction()
    {
    	$model = new DocumentBaseAttributes;
    	$saved = $model->save();

    	$this->assertTrue($saved, 'That model was saved');

    	$data = $model->findAll();

    	codecept_debug("Handler `valid` was called: $this->validHandled");
		codecept_debug("Handler `handle` was called: $this->handleHandled");

    	$this->assertEmpty($data, 'Action findAll was prevented');
    }

    public function handle(ModelEvent $event)
	{
		$event->isValid = false;
		$event->handled = true;
		$this->handleHandled = 'yes';
	}

	public function valid(ModelEvent $event)
	{
		$event->isValid = true;
		$this->validHandled = 'yes';
	}
}