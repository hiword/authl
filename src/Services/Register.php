<?php
namespace Simon\Authl\Services;
use Simon\Authl\Services\Interfaces\AbsAuth;
use Simon\Authl\Services\Interfaces\AuthInterface;
use Simon\Authl\Services\Interfaces\AbsRegister;
use Illuminate\Support\Facades\Validator;
use Simon\Authl\Models\AuthLog;
use Illuminate\Support\Facades\DB;
class Register extends AbsRegister implements AuthInterface
{
	/* 
	 * (non-PHPdoc)
	 * @see \Simon\Authl\Services\Interfaces\AbsRegister::saveUser()
	 * @author simon
	 */
	protected function saveUser(array $data = [])
	{
		// TODO Auto-generated method stub
		
		$old = [
			'name'=>$this->request->input('name'),
			'password'=>bcrypt($this->request->input('password')),
		];
		
		$data = array_merge($old,$data);

		$this->model = $this->model->create($data);
		
		return $this->model;
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
		
		$log = $this->log->where('created_at','<',time()+config('authl.allow_register_max_time'))->clientIp($this->request->ip())->orderBy(AuthLog::CREATED_AT,'desc')->first();
		
		if ($log)
		{
			throw new \Exception('time out');
		}
		
// 		$log = $this->log->clientIp($this->request->ip())->orderBy(AuthLog::CREATED_AT,'desc')->first();
// 		if($log && time() - $log->created_at < config('authl.allow_register_max_time'))
// 		{
// 			throw new \Exception('time out');
// 		}
	}

	/* 
	 * (non-PHPdoc)
	 * @see \Simon\Authl\Services\Interfaces\AbsAuth::validator()
	 * @author simon
	 */
	protected function validator()
	{
		// TODO Auto-generated method stub
		$validator = Validator::make($this->request->all(), [
			'name' => ['required','regex:/^[\w]{3,15}$/','unique:'.config('authl.user_table')],
			'password' => ['required','max:16','min:6'],
// 			'email' => ['required','email','unique:'.config('user_table')],
// 			'mobile' => ['required','mobile','unique:'.config('user_table')],
		]);
		
		if ($validator->fails())
		{
			throw new \Exception($validator->errors()->first());
		}
	}

	/* 
	 * (non-PHPdoc)
	 * @see \Simon\Authl\Services\Interfaces\AbsAuth::log()
	 * @author simon
	 */
	protected function log($status)
	{
		// TODO Auto-generated method stub
		$this->log->create([
			'name'=>$this->request->input('name'),
			'status'=>$status,
			'type'=>AuthLog::TYPE_REGISTER,
			'client_ip'=>ip_long($this->request->ip()),
		]);
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
		
		$this->saveUser($data);
		
		$this->log(AuthLog::STATUS_SUCCESS);
		
		return $this->model;
	}

	
	
	
}