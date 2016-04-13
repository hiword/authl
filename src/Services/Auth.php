<?php
namespace Simon\Authl\Services;
use Illuminate\Support\Facades\Input;
abstract class Auth
{
	
	protected $request = null;
	
	protected $model = null;
	
	public function __construct() 
	{
		$this->request = app('request');
	}
	
}