<?php
/**
 * IDE: PhpStorm.
 * Created by: Trevor
 * Date: 4/26/18
 * Time: 2:31 PM
 */

use Parse\ParseObject;
use Parse\ParseQuery;
use Parse\ParseACL;
use Parse\ParsePush;
use Parse\ParseUser;
use Parse\ParseInstallation;
use Parse\ParseException;
use Parse\ParseAnalytics;
use Parse\ParseFile;
use Parse\ParseCloud;
use Parse\ParseClient;
use Parse\ParsePushStatus;
use Parse\ParseServerInfo;
use Parse\ParseLogs;
use Parse\ParseAudience;

class Connect
{

    private $classes;
    public $currentUser;
    private $__DB;
    private $cards_class;
    private $tables;

    function __construct($__CLASSES)
    {
        $this->__DB = $__CLASSES['DB'];
        $this->cards_class = 'TerminalCard';
        $this->tables = $__CLASSES['TABLES'];

    }

    function signUp()
    {

    }

    function adminLogin($post)
    {
        try {
            $user = ParseUser::logIn($post['username'], $post['password']);
            $this->setCurrentUser();
            $_SESSION['admin'] = $this->currentUser;
            return $this->currentUser;
        } catch (ParseException $error) {
            // The login failed. Check error to see why.
            return $error;
        }
    }

    function setCurrentUser()
    {
        $this->currentUser = ParseUser::getCurrentUser();
    }

    function getProductLists()
    {
        $query = new ParseQuery('TerminalList');
    }

    function adminLogOut()
    {
        ParseUser::logOut();
        unset($_SESSION['admin']);
        header('Location: index');
    }

    function deactivateCard($data) // no need for this. use the upsert ::
    {
        if ($data['cardNo']) { // deactivate this card

            $query = $this->__DB->select($this->tables['card'], '*', 'cardNo = ' . $this->__DB->escape_string($data['cardNo']) . ' AND status = 0', null, 1);
            $result = $this->__DB->fetch_assoc($query);


            if (!$result) {
                // attempt delete from connect anyway ...
                $this->upsertConnectCard(array('cardNo' => $data['cardNo'], 'status' => 9));
                return array('success' => false, 'message' => 'Card does not exist. If it was available on terminal, it has been removed. Please reload menu to update.');
            } else {
                return $this->upsertCard(array('cardNo' => $data['cardNo'], 'status' => 9));
            }

        }
    }

    function upsertCard($data)
    {


        $query = $this->__DB->select($this->tables['card'], '*', '`cardNo` = \'' . $this->__DB->escape_string($data['cardNo']) . '\' AND `status` = 0', null, 1);
        $result = $this->__DB->fetch_assoc($query);

        $newData = array('updatedBy' => $this->currentUser->get('fullname'));

        if (isset($data['cardNo']))
            $newData["cardNo"] = $data['cardNo'];
        if (isset($data['name']))
            $newData["name"] = $data['name'];
        if (isset($data['type']))
            $newData["type"] = $data['type'];
        if (isset($data['status']))
            $newData["status"] = (int)($data['status'] ? $data['status'] : 0);


        // check the connect system and upsert if necessary
        $connectQuery = new ParseQuery($this->cards_class);
        $connectQuery->equalTo("cardNo", $data['cardNo']);
        $connectQuery->limit(1);
        $connectResult = $connectQuery->first();

        if (!$result) {

            $insert = $this->__DB->insert($this->tables['card'], $newData);
            if ($insert) { // upsert connect
                return $this->upsertConnectCard($newData, $connectResult);
            } else { // some shit went down. check database errors.
                return array('success' => false, 'message' => $insert);
            }
        } else { // update
            $this->__DB->update($this->tables['card'], $newData, 'cardNo = \'' . $data['cardNo'] . '\' AND `status` = 0');
            return $this->upsertConnectCard($newData, $connectResult);
        }

    }

    public function upsertConnectCard($data, $cardObject = false)
    {
        if (!$cardObject) { // new card
            $cardObject = new ParseObject($this->cards_class);
        }

        if (isset($data['cardNo']))
            $cardObject->set("cardNo", $data['cardNo']);
        if (isset($data['name']))
            $cardObject->set('name', $data['name']);
        if (isset($data['type']))
            $cardObject->set('type', (int)$data['type']);
        $cardObject->set('status', (int)(isset($data['status']) ? $data['status'] : 0));
        $cardObject->set('addedBy', $this->currentUser);

        try {
            $cardObject->save();
            return array('success' => true, 'message' => 'Card Stored Successfully.');
        } catch (ParseException $ex) {
            return $this->handleParseError($ex);
        }
    }

    public function handleParseError(ParseException $ex)
    {
        $code = $ex->getCode();
        switch ($code) {
            case 209: // INVALID_SESSION_TOKEN
                $this->adminLogOut();
                return false; // we will never get here anyway.
                break;
            default:
                return array('success' => false, 'message' => $ex->getMessage());
                break;
        }
    }

}