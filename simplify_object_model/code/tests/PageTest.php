<?php

namespace ObjectModel;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class PageTest extends TestCase {

	public function testShow() {
		$uuid = '2a85a666-4bc3-4680-9ca2-616c377cce80';
		$page = new Page($uuid, 'the whole content');
		Assert::assertSame('the whole content', $page->show());
	}

	public function testChange() {
		$uuid = '2a85a666-4bc3-4680-9ca2-616c377cce80';
		$page = new Page($uuid, 'the whole content');
		$page->change('the new whole content');
		Assert::assertSame('the new whole content', $page->show());
	}

}
