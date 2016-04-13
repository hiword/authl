<?php
namespace Simon\Authl\Services;
use Simon\Authl\Services\Interfaces\RegisterInterface;
use Illuminate\Support\Facades\Validator;
use Simon\Authl\Models\User;
use Simon\Authl\Models\AuthLog;
class Register extends Auth implements RegisterInterface
{
	
	protected $name = null;
	
	protected $password = null;
	
	protected $email = null;
	
	protected $mobile = null;
	
	public function __construct(User $User)
	{
		parent::__construct();
		
		$this->model = $User;
		$this->name = config('fields.name');
		$this->password = config('fields.password');
		$this->email = config('fields.email');
		$this->mobile = config('fields.mobile');
	}
	
	public function validator()
	{
		$validator = Validator::make($this->request->all(), [
			$this->name => ['required','regex:/^[\w]{3,15}$/','unique:'.config('user_table')],
			$this->password => ['required','max:16','min:6'],
			$this->email => ['required','email','unique:'.config('user_table')],
			$this->mobile => ['required','mobile','unique:'.config('user_table')],
		]);
		
		if ($validator->fails())
		{
			throw new \Exception($validator->errors()->first());
		}
	}
	
	public function checkRegisterTime(AuthLog $AuthLog)
	{
		$log = $AuthLog->where('client_ip',ip_long($this->request->ip()))->orderBy(AuthLog::CREATED_AT,'desc')->first();
		if($log && time() - $log->create_at < config('allow_register_max_time'))
		{
			throw new \Exception('time out');
		}
	}
	
	public function checkMobileCode()
	{
		
	}
	
	public function checkUserIsExists() 
	{
		$this->model = $this->model
			->where($this->name,$this->request->input($this->name))
			->orWhere($this->email,$this->request->input($this->email))
			->orWhere($this->mobile,$this->request->input($this->mobile))
			->first();
		
		if($this->model)
		{
			throw new \Exception('user is exists');
		}
	}
	
	public function auth() 
	{
		$this->validator();
		
		$this->checkUserIsExists();
	}
	
}