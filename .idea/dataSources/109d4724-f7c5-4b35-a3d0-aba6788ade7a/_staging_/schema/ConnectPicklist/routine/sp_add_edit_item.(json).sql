DROP PROCEDURE sp_add_edit_item;
CREATE PROCEDURE sp_add_edit_item(IN `_DATA` JSON, INOUT response JSON)
  stuff: BEGIN

    DECLARE _tmn_ref_id INTEGER;
    DECLARE _code VARCHAR(100);
    DECLARE _picklist_id BIGINT;
    DECLARE _source_location_id BIGINT;
    DECLARE _form_idx BIGINT;
    DECLARE _quantity INTEGER;
    DECLARE _idx BIGINT;
    DECLARE _balance INTEGER;


    IF `_DATA`->>'$.status' = 9 THEN
      UPDATE pkl_picklist_item
        SET status = 9
        WHERE idx = `_DATA`->>'$.idx';

      SET response = JSON_OBJECT('success', TRUE,'message', 'Item has been removed.');

      LEAVE stuff;
    END IF;

    SET _idx = JSON_UNQUOTE(JSON_EXTRACT(_DATA, '$.idx'));
    SET _code = JSON_UNQUOTE(JSON_EXTRACT(_DATA, '$.code'));
    SET _picklist_id = JSON_UNQUOTE(JSON_EXTRACT(_DATA, '$.picklist_id'));
    SET _source_location_id = JSON_UNQUOTE(JSON_EXTRACT(_DATA, '$.source_location_id'));
    SET _form_idx = JSON_UNQUOTE(JSON_EXTRACT(_DATA, '$.form_idx'));
    SET _quantity = JSON_UNQUOTE(JSON_EXTRACT(_DATA, '$.quantity'));
    SET _balance = JSON_UNQUOTE(JSON_EXTRACT(_DATA, '$.balance'));



    SELECT tmn_ref_id
    INTO _tmn_ref_id
    FROM pkl_picklist_item
    WHERE picklist_id = _picklist_id AND source_location_id = _source_location_id
    LIMIT 1;

    IF COALESCE(_tmn_ref_id, 0) = 0
    THEN

      SELECT COALESCE(tmn_ref_id, 50)
      INTO _tmn_ref_id
      FROM pkl_picklist_item
      ORDER BY tmn_ref_id DESC
      LIMIT 1;

      SET _tmn_ref_id = _tmn_ref_id +1;


    END IF;


    IF COALESCE(_idx, 0) > 0
    THEN -- edit

      UPDATE
        pkl_picklist_item
      SET code             = _code,
        form_idx           = _form_idx,
        picklist_id        = _picklist_id,
        source_location_id = _source_location_id,
        quantity           = _quantity,
        balance            = _balance
      WHERE idx = _idx;

      SET response = JSON_OBJECT('success', TRUE, 'message', 'Item has been updated');

    ELSE -- insert new

      INSERT INTO pkl_picklist_item (picklist_id, code, source_location_id, form_idx, quantity, status, updatedAt, balance, tmn_ref_id)
      VALUES
        (_picklist_id, _code, _source_location_id, _form_idx, _quantity, 0, current_timestamp, _quantity, _tmn_ref_id);

      IF ROW_COUNT() > 0
      THEN
        SET response = JSON_OBJECT('success', TRUE,'message', 'Item has been inserted');
      ELSE
        SET response = JSON_OBJECT('success', FALSE,'message',
                                   'something went wrong. Please try again.'); -- this should probably be the sql err thrown.
      END IF;

    END IF;

    SELECT response;


  END;
