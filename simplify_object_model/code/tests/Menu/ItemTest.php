<?php

namespace ObjectModel\Menu;

use Mockery;
use ObjectModel\LinkGenerator;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase {

	public function testShow() {
		$item = new Item('A', 'About us', 'P');

		$linkGenerator = Mockery::mock(LinkGenerator::class);
		$linkGenerator->shouldReceive('pageLink')
			->once()
			->with('P')
			->andReturn('/page/about-us');

		$expected = [
			'title' => 'About us',
			'link' => '/page/about-us',
		];

		Assert::assertEquals($expected, $item->show($linkGenerator));
	}

}
