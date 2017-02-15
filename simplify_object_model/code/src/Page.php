<?php

namespace ObjectModel;

class Page {

	/**
	 * @var string
	 */
	private $id;

	/**
	 * @var string
	 */
	private $content;

	public function __construct(string $id, string $content) {
		$this->id = $id;
		$this->content = $content;
	}

	public function show(): string {
		return $this->content;
	}

	public function change(string $content) {
		$this->content = $content;
	}

}
