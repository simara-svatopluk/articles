<?php

namespace ObjectModel\Menu;

use PHPUnit\Framework\TestCase;

class MenuTest extends TestCase {

	public function testCreateEmptyMenu() {
		$menu = new Menu();
		$this->assertEquals([], $menu->show());
	}

}
