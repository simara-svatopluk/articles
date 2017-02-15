<?php

namespace ObjectModel\Menu;

use Mockery;
use ObjectModel\LinkGenerator;
use PHPUnit\Framework\TestCase;

class MenuTest extends TestCase {

	public function testCreateEmptyMenu() {
		$menu = new Menu();
		$this->assertEquals([], $menu->show($this->createLinkGeneratorMock()));
	}

	public function testAddItemToEmptyMenu() {
		$menu = new Menu;
		$menu->addItem('About us', '64d61a8d-67dd-45f1-9d69-f71541d55fbe');

		$expected = [
			[
				'title' => 'About us',
				'link' => '/page/64d61a8d-67dd-45f1-9d69-f71541d55fbe'
			]
		];

		$linkGenerator = $this->createLinkGeneratorMock();
		$this->assertEquals($expected, $menu->show($linkGenerator));
	}

	private function createLinkGeneratorMock(): LinkGenerator {
		$returnFunction = function (string $pageId): string {
			return '/page/' . $pageId;
		};

		$linkGenerator = Mockery::mock(LinkGenerator::class);
		$linkGenerator->shouldReceive('pageLink')
			->once()
			->andReturnUsing($returnFunction);

		return $linkGenerator;
	}

}
