<?php
namespace Core\UserBundle\Request;

class Agent 
{
	private $device;

    private $userAgent;

    private $browser;

    public function getTokenDevice()
    {
        return md5($this->userAgent);
    }
	public function __construct(){
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'];
        $this->browser = get_browser($this->userAgent, true);

		$this->device = "computer";
		$apple_values = Array(
			'iPad',
			'iPod',
			'iPhone'
		);

		$phone_values = Array(
			'iPhone',
			'Android',
			'android',
			'blackberry',
			'opera mini',
			'motorola',
			'mobilephone',
			'nokia',
			'iPod'
		);

		$this->type="computer";

		foreach($phone_values as $alias){
			if (preg_match("/" . $alias . "/", $this->userAgent)) {
				$this->device="mobile";
			}
		}

		foreach($apple_values as $alias){
			if (preg_match("/" . $alias . "/", $this->userAgent)) {
				$this->device="apple";
			}
		}

		return $this->device;



	}

	public function __toString(){
		return $this->device;
	}

    public function getBrowser()
    {


        return $this->browser['browser'];
    }

    public function getVersion()
    {
        return $this->browser['version'];
    }

    public function getIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function checkCapable()
    {

        $val = true;
        //css test


        if($this->browser['browser'] != 'Chrome')
        {
            $val = false;
        }

        if($this->browser['version'] < 27)
        {
            $val = false;
        }
        /*
        if($this->getBrowser() == 'Chrome' OR $this->getBrowser() == 'Firefox' OR $this->getBrowser() == 'IE' OR $this->getBrowser() == 'Safari' OR $this->getBrowser() == 'iPad' OR $this->getBrowser() == 'Android')
        {

            if($this->getBrowser() == 'Chrome')//compruebo la version en chrome
            {
                if($this->getVersion() < 16)
                {
                    $val = false;
                }
            }

            if($this->getBrowser() == 'IE')//compruebo la version en chrome
            {
                if($this->getVersion() < 9)
                {
                    $val = false;
                }
            }

            if($this->getBrowser() == 'Firefox')//compruebo la version en chrome
            {
                if($this->getVersion() < 10)
                {
                    $val = false;
                }
            }

            if($this->getBrowser() == 'Safari')//compruebo la version en chrome
            {
                if($this->getVersion() < 5)
                {
                    $val = false;
                }
            }

        }else{
            $val = false;
        }
        */

        return $val;
    }

}