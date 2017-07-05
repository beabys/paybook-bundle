<?php

namespace beabys\PaybookBundle\Controller;

use beabys\PaybookBundle\Traits\Instance;
use paybook\Paybook;
use paybook\Attachment;
use paybook\User;
use paybook\Session;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Exception;
/**
 * Class InvoicesController
 * @package beabys\PaybookBundle\Controller
 * @author Alfonso Rodriguez <beabys@gmail.com>
 */
class InvoicesController extends Controller
{

    use Instance;

    public function getUsersInvoice($idUser, $startDate = null, $endDate = null)
    {
        Paybook::init($this->getApiKey());
        //Create instance of existing user
        $user = new User(null, $idUser);
        //create session for user
        $session = new Session($user);
        $options = [
            //'id_site_organization' => $SAT_SITE_ORGANIZATION,
            'dt_transaction_from' => 1498780800, // 1ro de febrero
            'dt_transaction_to' => 1498867200, // 1ro de marzo
            'limit' => 1,
        ];
        try {
            $attachments = Attachment::get($session, null, null, null, $options);
            //$attachments = Transaction::get($session, null, $options);
            var_dump($attachments);
        } catch (Exception $e) {
            var_dump($e);
        }

    }
}
