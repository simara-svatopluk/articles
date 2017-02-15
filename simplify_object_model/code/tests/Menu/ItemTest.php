<?php

namespace ObjectModel\Menu;

use Mockery;
use ObjectModel\LinkGenerator;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase {

	public function testShow() {
		$pageId = '113352ab-65ee-4f58-b775-187e4e105771';
		$item = new Item('About us', $pageId);

		$linkGenerator = Mockery::mock(LinkGenerator::class);
		$linkGenerator->shouldReceive('pageLink')
			->once()
			->with('113352ab-65ee-4f58-b775-187e4e105771')
			->andReturn('/page/about-us');

		$expected = [
			'title' => 'About us',
			'link' => '/page/about-us',
		];

		$this->assertEquals($expected, $item->show($linkGenerator));
	}

}
