<?php
/**
 * IDE: PhpStorm.
 * Created by: Trevor
 * Date: 4/27/18
 * Time: 3:21 AM
 */

use Parse\ParseUser;

class NWSC
{
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



    public function upsertCustomer($data)
    {

        $newData = array('updatedBy' => ParseUser::getCurrentUser()->get('fullname'));

        $result = false;
        if (isset($data['idx'])) { // edit
            $query = $this->__DB->select($this->tables['customer'], '*', '`idx` = ' . $this->__DB->escape_string($data['idx']) . ' AND `status` = 0', null, 1);
            $result = $this->__DB->fetch_assoc($query);
        }


        if (isset($data['customer_name']))
            $newData["customer_name"] = $data['customer_name'];
        if (isset($data['customer_number']))
            $newData["customer_number"] = $data['customer_number'];
        if (isset($data['property_number']))
            $newData["property_number"] = $data['property_number'];
        if (isset($data['meter_number']))
            $newData["meter_number"] = $data['meter_number'];
        if (isset($data['status']))
            $newData["status"] = (int)($data['status'] ? $data['status'] : 0);

        if (!$result) {

            $insert = $this->__DB->insert($this->tables['customer'], $newData);
            if ($insert) { // upsert connect
                return array('success' => true, 'message' => 'Insert Successfull.');
            } else { // some shit went down. check database errors.
                return array('success' => false, 'message' => $insert);
            }
        } else { // update
            $this->__DB->update($this->tables['customer'], $newData, 'idx = ' . $data['idx'] . ' AND `status` = 0');
            return array('success' => true, 'message' => 'Update Successfull.');
        }


    }
}