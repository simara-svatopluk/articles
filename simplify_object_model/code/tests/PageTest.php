<?php

namespace ObjectModel;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class PageTest extends TestCase {

	public function testShow() {
		$uuid = (string) Uuid::uuid4();
		$page = new Page($uuid, 'the whole content');
		self::assertSame('the whole content', $page->show());
	}

	public function testChange() {
		$uuid = (string) Uuid::uuid4();
		$page = new Page($uuid, 'the whole content');
		$page->change('the new whole content');
		self::assertSame('the new whole content', $page->show());
	}

}
