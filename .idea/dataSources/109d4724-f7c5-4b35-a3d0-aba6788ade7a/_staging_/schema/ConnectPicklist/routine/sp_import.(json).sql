use ConnectPicklist;
DROP PROCEDURE sp_import;
CREATE PROCEDURE sp_import(IN `_DATA_ARRAY` JSON)
    stuff: BEGIN

    DECLARE _count INTEGER DEFAULT -1;
    DECLARE _location_id BIGINT;
    DECLARE _document_form_id BIGINT;
    DECLARE _item_form_id BIGINT;
    DECLARE _picklist_type_id BIGINT;
    DECLARE _picklist_id BIGINT;
    DECLARE _DATA JSON;
    DECLARE _JSON JSON;

    DECLARE _response JSON;


    _loop: LOOP
      SET _count = _count +1;
      IF _count < JSON_LENGTH(_DATA_ARRAY) THEN


        SET @segment = CONCAT('$[', _count, ']');
        SET _DATA = JSON_EXTRACT(_DATA_ARRAY, @segment);

        SET @collection_id = _DATA->>'$.collection_id';
        SET @picklist_type = _DATA->>'$.picklist_type';
        SET @picklist_code = _DATA->>'$.picklist_code';
        SET @document_form_code = _DATA->>'$.document_form_code';
        SET @item_code = _DATA->>'$.item_code';
        SET @source_location = _DATA->>'$.source_location';
        SET @quantity = _DATA->>'$.quantity';
        SET @item_form_code = _DATA->>'$.item_form_code';

        SET _picklist_id = 0;


        -- create type if not exists

        SELECT type_id INTO _picklist_type_id
        FROM pkl_document_type
        WHERE name = @picklist_type
        ORDER BY idx ASC
        LIMIT 1;
        IF COALESCE(_picklist_type_id, 0) = 0 THEN

          SELECT type_id INTO _picklist_type_id
            FROM pkl_document_type ORDER BY type_id DESC LIMIT 1;

          SET _picklist_type_id = COALESCE(_picklist_type_id, 0) + 1;


          INSERT INTO pkl_document_type (name, description, status, updatedAt, type_id)
          VALUES (@picklist_type, @picklist_type, 0, current_timestamp, _picklist_type_id);

        END IF;


        -- create location if not exists ::
        SELECT idx INTO _location_id
        FROM pkl_location
        WHERE name = @source_location
        ORDER BY idx ASC
        LIMIT 1;
        IF COALESCE(_location_id, 0) = 0 THEN
          INSERT INTO pkl_location (name, description, status, updatedAt, card_no)
            VALUES (@source_location, @source_location, 0, current_timestamp, '');
          SET _location_id = LAST_INSERT_ID();
        END IF;

        -- create document form if not exists ::
        SELECT idx INTO _document_form_id
        FROM pkl_custom_form
        WHERE name = @document_form_code AND level = 'document'
        ORDER BY idx ASC
        LIMIT 1;
        IF COALESCE(_document_form_id, 0) = 0 THEN
          CALL sp_add_edit_form(JSON_OBJECT('name', @document_form_code, 'description', @document_form_code, 'level', 'document', 'status', 0), _JSON );
          SET _document_form_id = _JSON->>'$.form_id';
        END IF;

        -- create item form if not exists ::
        SELECT idx INTO _item_form_id
        FROM pkl_custom_form
        WHERE name = @item_form_code AND level = 'item'
        ORDER BY idx ASC
        LIMIT 1;
        IF COALESCE(_item_form_id, 0) = 0 THEN
          CALL sp_add_edit_form(JSON_OBJECT('name', @item_form_code, 'description', @item_form_code, 'level', 'item', 'status', 0), _JSON );
          SET _item_form_id = _JSON->>'$.form_id';
        END IF;

        -- create picklist if not exists

        SELECT idx INTO _picklist_id
        FROM pkl_picklist
        WHERE code = @picklist_code
        ORDER BY idx ASC
        LIMIT 1;

        IF COALESCE(_picklist_id, 0) = 0 THEN
          INSERT INTO pkl_picklist (collection_id, code, description, form_idx,  status, updatedAt, type_id)
          VALUES (@collection_id, @picklist_code, @picklist_code, _document_form_id, 0, current_timestamp, _picklist_type_id);
          SET _picklist_id = LAST_INSERT_ID();
        END IF;

        CALL sp_add_edit_item(JSON_OBJECT('code', @item_code, 'picklist_id', _picklist_id, 'source_location_id', _location_id, 'form_idx', _item_form_id, 'quantity', @quantity, 'balance', @quantity ), @item_id);

        SET _response = JSON_MERGE_PRESERVE(_response, JSON_OBJECT('success', true, 'message', 'Item imported', 'id', @item_id));

        ITERATE _loop;
      END IF;
      LEAVE _loop;
    END LOOP;

    SELECT _response;

END;
