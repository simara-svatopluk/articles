<?php

namespace ObjectModel\Menu;

use ObjectModel\LinkGenerator;

class Item {

	/**
	 * @var string
	 */
	private $title;

	/**
	 * @var string
	 */
	private $pageId;

	public function __construct(string $title, string $pageId) {
		$this->title = $title;
		$this->pageId = $pageId;
	}

	public function show(LinkGenerator $linkGenerator): array {
		return [
			'title' => $this->title,
			'link' => $linkGenerator->pageLink($this->pageId),
		];
	}



}