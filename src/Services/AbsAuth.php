<?php
namespace Simon\Authl\Services;
use Illuminate\Support\Facades\Input;
abstract class AbsAuth
{
	
	protected $request = null;
	
	protected $model = null;
	
	protected $name = null;
	
	protected $password = null;
	
	protected $email = null;
	
	protected $mobile = null;
	
	public function __construct() 
	{
		$this->request = app('request');
		
		$this->name = config('fields.name');
		$this->password = config('fields.password');
		$this->email = config('fields.email');
		$this->mobile = config('fields.mobile');
	}
	
}