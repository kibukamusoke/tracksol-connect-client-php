USE ConnectPicklist;
DROP PROCEDURE IF EXISTS sp_add_edit_form;
CREATE PROCEDURE sp_add_edit_form(IN `_DATA` JSON, INOUT response JSON)
  stuff: BEGIN

    DECLARE _tmn_ref_id INTEGER DEFAULT 0;


    SET @idx = `_DATA`->>'$.idx';
    SET @name = `_DATA`->>'$.name';
    SET @level = `_DATA`->>'$.level';
    SET @description = `_DATA`->>'$.description';
    SET @status = `_DATA`->>'$.status';

    IF `_DATA`->>'$.status' = 9 THEN
      UPDATE pkl_custom_form
      SET status = 9
      WHERE idx = `_DATA`->>'$.idx';

      SET response = JSON_OBJECT('success', TRUE,'message', 'Item has been removed.');

      LEAVE stuff;
    END IF;

    IF COALESCE(@idx, 0) = 0
    THEN -- insert new

      SELECT tmn_ref_id
      INTO _tmn_ref_id
      FROM pkl_custom_form
      WHERE status = 0
      ORDER BY tmn_ref_id DESC
      LIMIT 1;

      SET _tmn_ref_id = COALESCE(_tmn_ref_id, 0) + 1;

      INSERT INTO pkl_custom_form (name, level, description, updatedAt, status, tmn_ref_id)
      VALUES (@name, @level, @description, current_timestamp, COALESCE(@status, 0), _tmn_ref_id);

      IF ROW_COUNT() > 0
      THEN
        SET response = JSON_OBJECT('success', TRUE, 'message', 'Form has been created', 'form_id', LAST_INSERT_ID());
      ELSE
        SET response = JSON_OBJECT('success', FALSE, 'message',
                                   'something went wrong. Please try again.'); -- this should probably be the sql err thrown.
      END IF;


    ELSE -- UDPATE

      UPDATE pkl_custom_form
      SET name      = @name,
        description = @description,
        level       = @level,
        status      = @status
      WHERE idx = @idx;

      SET response = JSON_OBJECT('success', TRUE, 'message', 'Form has been updated');

    END IF;
    
    SELECT response;


  END;
