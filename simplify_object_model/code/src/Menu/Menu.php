<?php

namespace ObjectModel\Menu;

use ObjectModel\LinkGenerator;

class Menu {

	/**
	 * @var Item[]
	 */
	private $items = [];

	public function show(LinkGenerator $linkGenerator): array {
		return $this->showRecursive($this->createMap(), $linkGenerator);
	}

	public function addRootItem(string $id, string $title, string $pageId) {
		$this->items[] = new Item($id, $title, $pageId);
	}

	public function addChildItem(string $id, string $title, string $pageId, string $parentId) {
		$this->checkParentExists($parentId);
		$this->items[] = new Item($id, $title, $pageId, $parentId);
	}

	public function moveToPosition(string $id, int $position) {

	}

	public function moveUnderParent(string $id, string $parentId) {

	}

	public function moveToRoot(string $id) {

	}

	private function createMap(): array {
		$tmp = [];
		$roots = [];

		foreach ($this->items as $item) {
			$id = $item->getId();
			$parent = $item->getParent();

			if (!array_key_exists($id, $tmp)) {
				$tmp[$id] = [];
			}

			$menuItem = [
				'children' => &$tmp[$id],
				'item' => $item,
			];

			if (!array_key_exists($parent, $tmp)) {
				$tmp[$parent] = [];
			}

			if ($parent !== NULL) {
				$tmp[$parent][] = $menuItem;
			}

			if ($parent === NULL) {
				$roots[] = $menuItem;
			}
		}

		return $roots;
	}

	private function showRecursive(array $items, LinkGenerator $linkGenerator): array {
		$result = [];
		foreach ($items as $menuItem) {
			$item = $menuItem['item'];
			$id = $item->getId();
			$children = $this->showRecursive($menuItem['children'], $linkGenerator);
			$result[$id] = array_merge($item->show($linkGenerator), ['children' => $children]);
		}
		return $result;
	}

	private function checkParentExists(string $parentId) {
		if (!$this->hasItem($parentId)) {
			throw new ParentItemNotExistException();
		}
	}

	private function hasItem(string $itemId) {
		foreach($this->items as $item) {
			if ($item->getId() === $itemId) {
				return TRUE;
			}
		}

		return FALSE;
	}

}
