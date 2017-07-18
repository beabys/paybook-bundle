<?php

namespace beabys\PaybookBundle\Controller;

use beabys\PaybookBundle\Traits\Instance;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use paybook\Catalogues;
use paybook\Credentials;
use paybook\Paybook;
use paybook\Session;
use paybook\User;
use Exception;

/**
 * Class UserController
 * @package beabys\PaybookBundle\Controller
 * @author Alfonso Rodriguez <beabys@gmail.com>
 */
class UserController extends Controller
{

    use Instance;

    const SAT_SITE = 'CIEC';
    const SUCCESS = 'success';
    const ERROR = 'error';

    protected $satCredentials;
    protected $sites;
    protected $session;
    protected $syncRetry;

    /**
     * @param $userName
     * @param $rfc
     * @param $ciec
     * @param int $syncRetry
     * @return bool
     */
    public function setUser($userName, $rfc, $ciec, $syncRetry = 10)
    {

        $this->syncRetry = $syncRetry;
        Paybook::init($this->getApiKey());
        //Create instance of existing user or new user
        $user = $this->getUserAccount($userName);
        //create session of user
        $this->setSession($user);
        $sites = $this->getSites();
        $satSite = null;

        foreach ($sites as $site) {
            if ($site->name == static::SAT_SITE) {
                $satSite = $site;
            }
        }
        $credentials = [
            'rfc' => $rfc,
            'password' => $ciec,
        ];
        $satCredentials = new Credentials($this->getSession(), null, $satSite->id_site, $credentials);
        return $this->syncUser($satCredentials);
    }

    /**
     * @param Credentials $satCredentials
     * @return bool
     * @throws Exception
     */
    protected function syncUser(Credentials $satCredentials)
    {
        $counter = 0;
        while ($counter >= $this->syncRetry) {
            sleep(10);
            $statuses = $satCredentials->get_status($this->getSession());
            foreach ($statuses as $status) {
                $code = $status['code'];
                if ($status['code'] >= 200 && $status['code'] <= 205) {
                    return true;
                } elseif ($status['code'] >= 400 && $status['code'] <= 405) {
                    throw new Exception(
                        'There was an error with your credentials with code: '.
                        strval($code) . '.'
                    );
                }
                $counter++;
            }
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getSatCredentials()
    {
        return $this->satCredentials;
    }

    /**
     * @param $satCredentials
     * @return $this
     */
    protected function setSatCredentials($satCredentials)
    {
        $this->satCredentials = $satCredentials;

        return $this;
    }

    /**
     * @param $userName
     * @return User
     */
    protected function getUserAccount($userName)
    {
        $users = User::get();
        foreach ($users as $user) {
            if ($user->name == $userName) {
                return $user;
            }
        }
        return $this->setUserAccount($userName);
    }

    /**
     * @param $userName
     * @return User
     */
    protected function setUserAccount($userName)
    {
        return new User($userName);
    }

    /**
     * @return array
     */
    public function getSites()
    {
        return Catalogues::get_sites($this->getSession());
    }

    /**
     * @return mixed
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @param $user
     * @return $this
     */
    protected function setSession($user)
    {
        $this->session = new Session($user);

        return $this;
    }

}
