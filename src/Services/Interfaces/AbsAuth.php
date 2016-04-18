<?php
namespace Simon\Authl\Services\Interfaces;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Simon\Authl\Models\AuthLog;
use Simon\Authl\Models\User;
abstract class AbsAuth
{
	
	protected $request = null;
	
	protected $model = null;
	
	protected $log = null;
	
	public function __construct(Request $Request,AuthLog $AuthLog,User $User) 
	{
		$this->request = $Request;
		$this->model = $User;
		$this->log = $AuthLog;
	}
	
	abstract protected function checkCode();
	
	abstract protected function validator();
	
	abstract protected function checkPostTime();
	
	abstract protected function log($status);
}