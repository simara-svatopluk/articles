<?php

namespace ObjectModel\Menu;

use Mockery;
use ObjectModel\LinkGenerator;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class MenuTest extends TestCase {

	public function testCreateEmptyMenu() {
		$menu = new Menu();
		Assert::assertEquals([], $menu->show($this->createLinkGeneratorMock()));
	}

	public function testAddRootItemToEmptyMenu() {
		$menu = new Menu;
		$menu->addRootItem('A', 'About us', 'P');

		$expected = [
			'A' => [
				'title' => 'About us',
				'link' => '/page/P',
				'children' => [],
			]
		];

		$linkGenerator = $this->createLinkGeneratorMock();
		Assert::assertEquals($expected, $menu->show($linkGenerator));
	}

	public function testAddMoreRootItemToEmptyMenu() {
		$menu = new Menu;
		$menu->addRootItem('A', 'About us', 'P1');
		$menu->addRootItem('B', 'About us', 'P2');

		$expected = [
			'A' => [
				'title' => 'About us',
				'link' => '/page/P1',
				'children' => [],
			],
			'B' => [
				'title' => 'About us',
				'link' => '/page/P2',
				'children' => [],
			],
		];

		$linkGenerator = $this->createLinkGeneratorMock();
		Assert::assertEquals($expected, $menu->show($linkGenerator));
	}

	public function testAddChildItem() {
		$menu = new Menu;
		$menu->addRootItem('A', 'About us', 'P1');
		$menu->addChildItem('B', 'About us', 'P2', 'A');

		$expected = [
			'A' => [
				'title' => 'About us',
				'link' => '/page/P1',
				'children' => [
					'B' => [
						'title' => 'About us',
						'link' => '/page/P2',
						'children' => [],
					],
				]
			],
		];

		$linkGenerator = $this->createLinkGeneratorMock();
		Assert::assertEquals($expected, $menu->show($linkGenerator));
	}

	public function testAddChildItemUnderNotExistingThrowsException() {
		$menu = new Menu;
		$menu->addRootItem('A', 'About us', 'P1');

		$this->expectException(ParentItemNotExistException::class);
		$menu->addChildItem('B', 'About us', 'P2', 'X');
	}

	public function moveUnknownThrowsException() {
		
	}

	public function testMoveToNegativeThrowsException() {

	}

	public function testMoveToZeroPositionWhenOnlyOnePresent() {

	}

	public function testMoveToZeroPosition() {

	}

	public function testMoveToMiddlePosition() {

	}

	public function testMoveTooFar() {

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
