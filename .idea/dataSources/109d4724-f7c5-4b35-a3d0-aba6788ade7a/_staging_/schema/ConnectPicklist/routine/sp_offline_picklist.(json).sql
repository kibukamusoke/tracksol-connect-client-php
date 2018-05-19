USE ConnectPicklist;
DROP PROCEDURE IF EXISTS sp_offline_picklist;
CREATE PROCEDURE sp_offline_picklist(IN `_data` JSON, OUT `_response` TINYTEXT)
  BEGIN

    DECLARE _action_id BIGINT;
    DECLARE _tmn_hw_id BIGINT;
    DECLARE _card_no VARCHAR(20);
    DECLARE _version VARCHAR(20);
    DECLARE _txndate DATETIME;
    DECLARE _p131 JSON;
    DECLARE _destination_idx BIGINT;
    DECLARE _picklist_tmn_ref_id INTEGER;
    DECLARE _ip_address VARCHAR(15);

    DECLARE _item_idx BIGINT;
    DECLARE _trans_id INTEGER;
    DECLARE _quantity DECIMAL;
    DECLARE _document_form MEDIUMTEXT;
    DECLARE _item_form MEDIUMTEXT;
    DECLARE _pick_time VARCHAR(30);
    DECLARE _source_location_idx BIGINT;
    DECLARE _item_location_array VARCHAR(1000);
    DECLARE _picklist_data VARCHAR(1500);
    DECLARE _picklist_code VARCHAR(100);
    DECLARE _source_location VARCHAR(100);
    DECLARE _destination_location VARCHAR(100);
    DECLARE _item_code VARCHAR(100);
    DECLARE _collection_id BIGINT;
    DECLARE _balance NUMERIC;

    DECLARE _log_id BIGINT;

    DECLARE _picks JSON;
    DECLARE _pick JSON;

    DECLARE _p33 VARCHAR(255);
    DECLARE _p34 VARCHAR(255);

    DECLARE _count INTEGER DEFAULT -1;

    SET @picks = _data->'$.p131';
    SET _tmn_hw_id = `_data`->>'$.tmn_hw_id';
    SET _action_id = `_data`->>'$.actionId';
    SET _destination_idx = `_data`->>'$.p34';
    SET _picklist_tmn_ref_id = `_data`->>'$.p33';
    SET _txndate = `_data`->>'$.txndate';
    SET _version = `_data`->>'$.version';
    SET _ip_address = `_data`->>'$.ip_address';

    stuff: LOOP
      SET _count = _count +1;
      IF _count < JSON_LENGTH(@picks)
      THEN
        SET @segment = CONCAT('$[', _count, ']');
        SET _pick = JSON_EXTRACT(@picks, @segment);

        SET _item_idx = _pick->>'$.item_id';
        SET _trans_id = _pick->>'$.trans_id';
        SET _quantity = _pick->>'$.Qty';
        SET _item_form = REPLACE(REPLACE(_pick->>'$.reference', '&', CHAR(10)), '=', ' : ');
        SET _item_location_array = _pick->>'$.item_location_array';
        SET _picklist_data = _pick->>'$.picklist_data';

        SET _source_location_idx = strSplit(_item_location_array, '~', 2);
        SET _pick_time = strSplit(_picklist_data, '~', 1);
        SET _card_no = strSplit(_picklist_data, '~', 3);
        SET _document_form = REPLACE(REPLACE(strSplit(_picklist_data, '~', 5), '&', CHAR(10)), '=', ' : ');

        SELECT
          B.collection_id,
          B.code,
          A.code,
          A.balance
        INTO _collection_id, _picklist_code, _item_code, _balance
        FROM pkl_picklist_item A
          INNER JOIN pkl_picklist B ON A.idx = _item_idx AND A.picklist_id = B.idx;

        SELECT name
        INTO _source_location
        FROM pkl_location
        WHERE idx = _source_location_idx;
        SELECT name
        INTO _destination_location
        FROM pkl_location
        WHERE idx = _destination_idx;

        SET _balance = _balance - _quantity;

        -- log pick
        INSERT INTO pkl_pick_log (picklist_item_idx, picklist_code, source_location_idx, source_location, trans_id, pick_time, destination_location_idx, destination_location, item_code, quantity, document_form, item_form, collection_id, balance, created_dt)
        VALUES
          (_item_idx, _picklist_code, _source_location_idx, _source_location, _trans_id, _pick_time, _destination_idx,
                      _destination_location, _item_code, _quantity, _document_form, _item_form, _collection_id,
           _balance,
           current_timestamp);

        SET _log_id = LAST_INSERT_ID();

        -- adjust picklist item balance
        SET @status = 0;
        IF _balance <= 0
        THEN
          SET @status = 1; -- completed
        END IF;

        UPDATE pkl_picklist_item
        SET balance = _balance, status = @status
        WHERE idx = _item_idx;

        ITERATE stuff;
      END IF;
      LEAVE stuff;
    END LOOP;

    SET `_response` = 'Operation Complete';


  END;
