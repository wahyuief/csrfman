<?php

namespace wahyuief\csrfman;

use InvalidArgumentException;

class Csrfman
{
    protected $_csrf_hash;
    protected $_csrf_token_name = 'csrfman_token';
    protected $_csrf_session_name = 'csrfman_session';

    public function __construct()
	{
		if (config('plugin.wahyuief.csrfman.app.enable'))
		{
			foreach (array('csrf_token_name', 'csrf_session_name') as $key)
			{
				if (NULL !== ($val = config('plugin.wahyuief.csrfman.app.' . $key)))
				{
					$this->{'_'.$key} = $val;
				}
			}
			$this->csrf_set_hash();
			$this->csrf_verify();
		}
	}

    public function csrf_verify()
	{
		if (strtoupper(\request()->method()) !== 'POST') return $this->csrf_set_session();

		$valid = !empty(\request()->post($this->_csrf_token_name)) && !empty(\session($this->_csrf_session_name)) && hash_equals(\request()->post($this->_csrf_token_name), \session($this->_csrf_session_name));
		
		\request()->session()->forget($this->_csrf_token_name);
		
		if (config('plugin.wahyuief.csrfman.app.csrf_regenerate'))
		{
			\request()->session()->forget($this->_csrf_session_name);
			$this->_csrf_hash = null;
		}

		$this->csrf_set_hash();
		$this->csrf_set_session();

		if (!$valid) $this->csrf_show_error();

		return $this;
	}

    public function csrf_set_session()
	{
		\request()->session()->set($this->_csrf_session_name, $this->_csrf_hash);
		return $this;
	}

    public function csrf_show_error()
	{
		throw new InvalidArgumentException("The action you have requested is not allowed.");
	}

    public function get_csrf_hash()
	{
		return $this->_csrf_hash;
	}

    public function get_csrf_name()
	{
		return $this->_csrf_token_name;
	}

    protected function csrf_set_hash()
	{
		if (!$this->_csrf_hash)
		{
			if (\session($this->_csrf_session_name)) return $this->_csrf_hash = \session($this->_csrf_session_name);
			$this->_csrf_hash = sha1(bin2hex(random_bytes(16)));
		}

		return $this->_csrf_hash;
	}

}