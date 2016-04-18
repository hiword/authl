<?php
namespace Simon\Authl\Providers;
use Illuminate\Support\ServiceProvider;
class AuthProvider extends ServiceProvider
{
	
	/**
	 *
	 * @var string
	 * @author simon
	 */
	protected $namespaceName = 'authl';
	
	protected $packagePath = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;
	
	public function boot()
	{
		//加载路由
		/* $this->setupRoutes($this->app->router);
			
		//加载视图
		$this->loadViewsFrom($this->packagePath.'views',$this->namespaceName);
			
		//加载语言包
		$this->loadTranslationsFrom($this->packagePath.'lang', $this->namespaceName);
			
		//移动目录
		$this->publishes([
		//     			$this->packagePath.'config' => config_path(),
		//     			$this->packagePath.'views' => base_path('resources/views/vendor/'.$this->namespaceName),
		//     			$this->packagePath.'database'=>database_path(),
		]); */
	}
	
	/* 
	 * (non-PHPdoc)
	 * @see \Illuminate\Support\ServiceProvider::register()
	 * @author simon
	 */
	public function register()
	{
		// TODO Auto-generated method stub
		$this->mergeConfigFrom(
			$this->packagePath."config/config.php", $this->namespaceName
		);
		
		$this->app->bind(
			'Simon\Authl\Services\Interfaces\AbsRegister',
			'Simon\Authl\Services\Register'
		);
		
		$this->app->bind(
			'Simon\Authl\Services\Interfaces\AbsLogin',
			'Simon\Authl\Services\Login'
		);
	}

	
	
	
}