<?php

	class Links extends Controller {

		function __construct()
		{
			parent::Controller();
		}

		function index()
		{
			$this -> load -> view ( 'links' );
		}
	}

?>