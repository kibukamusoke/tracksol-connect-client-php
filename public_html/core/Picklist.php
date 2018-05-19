<?php
/**
 * IDE: PhpStorm.
 * Created by: Trevor
 * Date: 5/14/18
 * Time: 2:20 PM
 */

use Parse\ParseUser;


class Picklist
{

    private $classes;
    public $currentUser;
    private $__DB;
    private $cards_class;
    private $tables;
    private $connect;

    function __construct($__CLASSES, $connect)
    {
        $this->__DB = $__CLASSES['DB'];
        $this->tables = $__CLASSES['TABLES'];
        $this->connect = new Connect($__CLASSES);

    }

    public function upsertCollection($data)
    {
        $data['status'] = isset($data['status']) && !empty($data['status']) ? $data['status'] : 0;
        if (isset($data['idx'])) { // update
            $update = $this->__DB->update($this->tables['picklistCollection'], $data, 'idx = ' . (int)$data['idx']);
            if ($update) {
                return $this->connect->updatePicklistCollection($data);
                //return array('success' => true, 'message' => 'Update Successfull.');
            } else { // some shit went down. check database errors.
                return array('success' => false, 'message' => $update);
            }
        }

        // else create new collection

        $insert = $this->__DB->insert($this->tables['picklistCollection'], array('name' => $data['name'], 'description' => $data['description'], 'status' => $data['status']));
        if ($insert) {
            //return array('success' => true, 'message' => 'Insert Successfull.');
            $data['idx'] = (int)$this->__DB->lastID();
            return $this->connect->createPicklistCollection($data);
        } else { // some shit went down. check database errors.
            return array('success' => false, 'message' => $insert);
        }

    }

    public function upsertLocation($data)
    {
        $data['status'] = isset($data['status']) && !empty($data['status']) ? $data['status'] : 0;
        if (isset($data['idx'])) { // update
            $update = $this->__DB->update($this->tables['picklistLocation'], $data, 'idx = ' . (int)$data['idx']);
            if ($update) {
                return array('success' => true, 'message' => 'Update Successfull.');
            } else { // some shit went down. check database errors.
                return array('success' => false, 'message' => $update);
            }
        }

        // else create new collection

        $insert = $this->__DB->insert($this->tables['picklistLocation'], array('name' => $data['name'], 'description' => $data['description'], 'status' => $data['status']));
        if ($insert) {
            return array('success' => true, 'message' => 'Insert Successfull.');
        } else { // some shit went down. check database errors.
            return array('success' => false, 'message' => $insert);
        }

    }


    public function upsertCustomForm($data)
    {
        $data['status'] = isset($data['status']) && !empty($data['status']) ? $data['status'] : 0;

        $query = $this->__DB->query('CALL ConnectPicklist.sp_add_edit_form(\'' . json_encode($data) . '\', @output)');
        $result = $this->__DB->fetch_array($query);

        return json_decode($result[0], true);
    }


    public function upsertCustomFormField($data)
    {
        $data['status'] = isset($data['status']) && !empty($data['status']) ? $data['status'] : 0;
        if (isset($data['idx'])) { // update
            $update = $this->__DB->update($this->tables['picklistCustomFormField'], $data, 'idx = ' . (int)$data['idx']);
            if ($update) {
                return array('success' => true, 'message' => 'Update Successfull.');
            } else { // some shit went down. check database errors.
                return array('success' => false, 'message' => $update);
            }
        }
        // else create new collection
        $insert = $this->__DB->insert($this->tables['picklistCustomFormField'], array('key' => $data['key'], 'form_id' => $data['form_id'], 'type' => $data['type'], 'minlen' => $data['minlen'], 'maxlen' => $data['maxlen'], 'status' => $data['status']));
        if ($insert) {
            return array('success' => true, 'message' => 'Insert Successfull.');
        } else { // some shit went down. check database errors.
            return array('success' => false, 'message' => $insert);
        }
    }

    public function upsertPicklist($data)
    {
        $data['status'] = isset($data['status']) && !empty($data['status']) ? $data['status'] : 0;
        if (isset($data['idx'])) { // update
            $update = $this->__DB->update($this->tables['picklist'], $data, 'idx = ' . (int)$data['idx']);
            if ($update) {
                return array('success' => true, 'message' => 'Update Successfull.');
            } else { // some shit went down. check database errors.
                return array('success' => false, 'message' => $update);
            }
        }

        // else create new collection
        $insert = $this->__DB->insert($this->tables['picklist'], array('code' => $data['code'], 'form_idx' => $data['form_idx'], 'destination_location_id' => $data['destination_location_id'], 'description' => $data['description'], 'collection_id' => $data['collection_id'], 'type_id' => $data['type_id'], 'status' => $data['status']));
        if ($insert) {
            return array('success' => true, 'message' => 'Insert Successfull.');
        } else { // some shit went down. check database errors.
            return array('success' => false, 'message' => $insert);
        }
    }


    public function upsertPicklistItem($data)
    {
        $data['status'] = isset($data['status']) && !empty($data['status']) ? $data['status'] : 0;

        $query = $this->__DB->query('CALL ConnectPicklist.sp_add_edit_item(\'' . json_encode($data) . '\', @response)');
        $result = $this->__DB->fetch_array($query);

        return json_decode($result[0], true);

    }

    public function uploadCSV($file)
    {
        $handle = fopen($file, "r");
        if ($file == NULL) {
            return array('success', false, 'message', 'Please select a file to import');
        }

        $data_array = array();
        try {
            while (($filesop = fgetcsv($handle, 1000, ",")) !== false) {

                $collection_id = $filesop[0];
                $picklist_type = $filesop[1];
                $picklist_code = $filesop[2];
                $document_form_code = $filesop[3];
                $item_code = $filesop[4];
                $source_location = $filesop[5];
                $quantity = $filesop[6];
                $item_form_code = $filesop[7];

                $data = array(
                    'collection_id' => $collection_id,
                    'picklist_type' => $picklist_type,
                    'picklist_code' => $picklist_code,
                    'document_form_code' => $document_form_code,
                    'item_code' => $item_code,
                    'source_location' => $source_location,
                    'quantity' => $quantity,
                    'item_form_code' => $item_form_code
                );


                if (is_numeric($collection_id)) { // this is mostly to skip the header line

                    array_push($data_array, $data);

                }
            }


            if (sizeof($data_array) > 0) {
                // return 'CALL ConnectPicklist.sp_import(\'' . json_encode($data_array) . '\')';
                $query = $this->__DB->query('CALL ConnectPicklist.sp_import(\'' . json_encode($data_array) . '\')');
                $result = $this->__DB->fetch_array($query);

                return json_decode($result[0][0], true);
            }
            return array('success' => false, 'message' => 'no data in file.');
        } catch (Exception $e) {
            return array('success' => false, 'message' => $e);
        }


    }

}