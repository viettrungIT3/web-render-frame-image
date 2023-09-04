<?php
class Website extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index(
	) {
		return $this
			->set_full_layout(TRUE)
			->set_page_title(META_DEFAULT_PAGE_TITLE)
			->set_main_template("welcome")
			->render();
	}
}