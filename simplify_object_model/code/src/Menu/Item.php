<?php

namespace ObjectModel\Menu;

use ObjectModel\LinkGenerator;

class Item {

	/**
	 * @var string
	 */
	private $id;

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var string
	 */
	private $pageId;

	/**
	 * @var string|NULL
	 */
	private $parent;

	public function __construct(string $id, string $title, string $pageId, string $parent = NULL) {
		$this->id = $id;
		$this->title = $title;
		$this->pageId = $pageId;
		$this->parent = $parent;
	}

	public function show(LinkGenerator $linkGenerator): array {
		return [
			'title' => $this->title,
			'link' => $linkGenerator->pageLink($this->pageId),
		];
	}

	public function getId(): string {
		return $this->id;
	}

	/**
	 * @return NULL|string
	 */
	public function getParent() {
		return $this->parent;
	}

}
