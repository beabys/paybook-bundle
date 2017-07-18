<?php

namespace beabys\PaybookBundle\Controller;

use beabys\PaybookBundle\Traits\Instance;
use paybook\Paybook;
use paybook\Attachment;
use paybook\Transaction;
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

    const XML_ATTACHMENT_TYPE = '56bcdfca784806d1378b4567';

    /**
     * @param $email
     * @param null $startDate
     * @param null $endDate
     * @param array $keywords
     * @param null $limit
     * @return array
     */
    public function getUsersInvoice($email, $startDate = null, $endDate = null, $keywords = [], $limit = null)
    {

        $files = [];
        Paybook::init($this->getApiKey());
        $userData = [
            'name' => $email,
        ];
        $user = User::get($userData);

        if (empty($user)) {
            return $files;
        }
        $user = $user[0];
        $session = new Session($user);
        date_default_timezone_set('America/Mexico_City');
        $options = [
            //'limit' => 3,
            'dt_transaction_from' => strtotime($startDate),
            'dt_transaction_to' => strtotime($endDate),
        ];

        if (!is_null($limit)) {
            $options['limit'] = $limit;
        }

        if (!empty($keywords)) {
            $keywords = implode(',', $keywords);
            $options['keywords'] = $keywords;
        }

        $transactions = Transaction::get($session, null, $options);
        try {
            foreach ($transactions as $transaction) {
                $transaction = isset($transaction->attachments[0]) ? $transaction->attachments[0] : [];
                if (!empty($transaction)) {
                    $xml = $this->getAttachments($session, $transaction);
                    if (!is_null($xml)) array_push($files, $xml);
                }
            }
            return $files;

        } catch (Exception $e) {
            //TODO Log Exception
            var_dump($e);
        }
    }

    /**
     * @param $session
     * @param $attachment
     * @return null|string
     */
    protected function getAttachments($session, $attachment)
    {
        if ($attachment['id_attachment_type'] != static::XML_ATTACHMENT_TYPE) return null;
        $idAttachment = substr($attachment['url'], 1, strlen($attachment['url']));
        $xml = Attachment::get($session, null, $idAttachment);
        $file = $this->getSavePath() . '/' . $attachment['file'];
        if (file_exists($file)) return null;
        $xmlFile = fopen($file, 'w');
        fwrite($xmlFile, $xml);
        fclose($xmlFile);
        return $file;
    }
}
