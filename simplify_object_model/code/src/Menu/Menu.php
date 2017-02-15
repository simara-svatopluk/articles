<?php

namespace ObjectModel\Menu;

use ObjectModel\LinkGenerator;

class Menu {

	/**
	 * @var Item[]
	 */
	private $items = [];

	public function show(LinkGenerator $linkGenerator): array {
		return array_map(function (Item $item) use ($linkGenerator) {
			return $item->show($linkGenerator);
		}, $this->items);
	}

	public function addItem(string $title, string $pageId) {
		$this->items[] = new Item($title, $pageId);
	}

}
