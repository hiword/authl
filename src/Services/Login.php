<?php
namespace Simon\Authl\Services;
use Simon\Authl\Services\Interfaces\AbsLogin;
use Simon\Authl\Services\Interfaces\AuthInterface;
use Simon\Authl\Models\AuthLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class Login extends AbsLogin implements AuthInterface
{
	/* 
	 * (non-PHPdoc)
	 * @see \Simon\Authl\Services\Interfaces\AbsAuth::log()
	 * @author simon
	 */
	protected function log($status)
	{
		// TODO Auto-generated method stub
		//log
		$this->log->create([
			'name'=>$this->request->input('name'),
			'type'=>AuthLog::TYPE_LOGIN,
			'status'=>$status,
			'ip'=>ip_long($this->request->ip()),
		]);
	}

	/* 
	 * (non-PHPdoc)
	 * @see \Simon\Authl\Services\Interfaces\AbsLogin::checkPassword()
	 * @author simon
	 */
	protected function checkPassword()
	{
		// TODO Auto-generated method stub
		if (!Hash::check($this->request->input('password'), $this->model->password))
		{
			//log
			$this->log(AuthLog::STATUS_ERROR);
			
		    throw new \Exception('password is error');
		}
	}

	/* 
	 * (non-PHPdoc)
	 * @see \Simon\Authl\Services\Interfaces\AbsLogin::getUser()
	 * @author simon
	 */
	protected function getUser()
	{
		// TODO Auto-generated method stub
		$this->model = $this->model->where('name',$this->request->input('name'))->get();
		if (empty($this->model))
		{
			//log
			$this->log(AuthLog::STATUS_ERROR);
			
			throw new \Exception('user is not exists');
		}
	}

	/* 
	 * (non-PHPdoc)
	 * @see \Simon\Authl\Services\Interfaces\AbsAuth::checkCode()
	 * @author simon
	 */
	protected function checkCode()
	{
		// TODO Auto-generated method stub
		
	}

	/* 
	 * (non-PHPdoc)
	 * @see \Simon\Authl\Services\Interfaces\AbsAuth::checkPostTime()
	 * @author simon
	 */
	protected function checkPostTime()
	{
		// TODO Auto-generated method stub
		$counts = $this->log
				->whereBetween('created_at',[strtolower(Carbon::today()),strtolower(Carbon::tomorrow())])
				->clientIp($this->request->ip())
				->type(AuthLog::TYPE_LOGIN)
				->orderBy(AuthLog::CREATED_AT,'desc')
				->status(AuthLog::STATUS_ERROR)
				->count();
		
		if($counts > config('allow_login_error_count'))
		{
			throw new \Exception('login error num max');
		}
	}

	/* 
	 * (non-PHPdoc)
	 * @see \Simon\Authl\Services\Interfaces\AbsAuth::validator()
	 * @author simon
	 */
	protected function validator()
	{
		// TODO Auto-generated method stub
		Validator::make($this->request->all(),[
			'name'=>['required','regex:/^[\w]{3,15}$/'],
			'password'=>['required','min:3','max:20']
		]);
		
		if ($validator->fails())
		{
			throw new \Exception($validator->errors()->first());
		}
	}

	/* 
	 * (non-PHPdoc)
	 * @see \Simon\Authl\Services\Interfaces\AuthInterface::auth()
	 * @author simon
	 */
	public function auth(array $data = [])
	{
		// TODO Auto-generated method stub
		$this->checkCode();
		
		$this->validator();
		
		$this->checkPostTime();
		
		$this->getUser();
		
		$this->checkPassword();
		
		$this->log(AuthLog::STATUS_SUCCESS);
		
		return $this->model;
	}

	
	
	
}