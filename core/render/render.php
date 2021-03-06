<?php
class Webpie_Render_Smarty extends Webpie_Render_Interface
{
	public $render = NULL;
	public function __construct($setting)
	{
		include __DIR__ . DS . 'smarty' . DS . 'Smarty.class.php';
		$this->render = new Smarty;
		foreach($setting as $key => $val)
		{
			$this->render->$key = $val;
		}
	}

	/**
	* @name display 直接显示输出模板
	*
	* @returns   
	*/
	public function display(/*$tpl, $assign = NULL*/)
	{
		if(func_num_args() < 1)
			throw new Webpie_Render_Exception('至少需要一个参数，即模板名称');

		$args = func_get_args();
		$res = $this->toAssign($args);

		return $this->render->display($res['tpl'], $res['cacheId'], $res['compileId']);
	}

	/**
	* @name fetch 返回模板内容作为字符串
	*
	* @returns   
	*/
	public function fetch(/*$tpl, $assign = NULL*/)
	{
		if(func_num_args() < 1)
			throw new Webpie_Render_Exception('至少需要一个参数，即模板名称');

		$args = func_get_args();
		$res = $this->toAssign($args);

		return $this->render->fetch($res['tpl'], $res['cacheId'], $res['compileId']);
	}

	/**
	* @name toAssign 对动态参数的处理和赋值
	*
	* @param $args
	*
	* @returns   
	*/
	protected function toAssign($args)
	{
		$res = array('tpl' => NULL, 'cacheId' => NULL, 'compileId' => NULL);
		$res['tpl'] = $args[0];
		if(!empty($args[1]))
			array_walk($args[1], function($arg, $val){$this->render->assign($arg, $val);});

		if(!empty($args[2]))
			$res['cacheId'] = $args[2];

		if(!empty($args[3]))
			$res['compileId'] = $args[3];

		return $res;
	}

	/**
	* @name get 
	*
	* @param $var
	*
	* @returns   
	*/
	public function get($var)
	{
		if(!property_exists(__CLASS__, $var))
			return $this->render->$var;
		else
			return $this->$var;
	}

	/**
	* @name set 
	*
	* @param $var
	* @param $val
	*
	* @returns   
	*/
	public function set($var, $val)
	{
		if(!property_exists(__CLASS__, $var))
			$this->render->$var = $val;
		else
			$this->$var = $val;
	}
}
