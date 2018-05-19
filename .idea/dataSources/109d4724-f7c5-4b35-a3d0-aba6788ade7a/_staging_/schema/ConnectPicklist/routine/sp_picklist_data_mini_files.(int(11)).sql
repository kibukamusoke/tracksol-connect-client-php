USE ConnectPicklist;
DROP PROCEDURE sp_picklist_data_mini_files;
CREATE PROCEDURE sp_picklist_data_mini_files(IN collection_id INT, OUT _file_data TEXT)
  BEGIN

    DECLARE v_finished INTEGER DEFAULT 0;
    DECLARE _idx BIGINT;
    DECLARE _picklist_id BIGINT;
    DECLARE _code VARCHAR(100);
    DECLARE _source_location_id BIGINT;
    DECLARE _form_idx BIGINT;
    DECLARE _quantity BIGINT;
    DECLARE _balance BIGINT;
    DECLARE _tmn_ref_id INTEGER;

    DECLARE _item_fields TEXT;
    DECLARE _last_idx BIGINT;
    DECLARE _temp_list TEXT;
    DECLARE _count INTEGER;
    DECLARE _picklist TEXT;
    DECLARE _current_tmn_ref_id INTEGER;

    DECLARE _last_source_location_id BIGINT;


    -- declare cursor for picklist ordered by tmn ref id
    DECLARE picklists_cursor CURSOR FOR
      SELECT DISTINCT
        A.idx,
        A.picklist_id,
        A.code,
        A.source_location_id,
        A.form_idx,
        A.quantity,
        A.balance,
        A.tmn_ref_id
      FROM pkl_picklist_item A
        INNER JOIN pkl_picklist B ON B.status = 0 AND A.status = 0 AND B.collection_id = collection_id
                                     AND A.tmn_ref_id > 0

      UNION ALL -- place holder to force one more iteration.
      SELECT
        9223372036854775807, -- this null record should always come last. max unsigned bigint value
        NULL,
        NULL,
        NULL,
        NULL,
        NULL,
        NULL,
        NULL
      ORDER BY idx ASC, tmn_ref_id ASC;

    -- declare NOT FOUND handler
    DECLARE CONTINUE HANDLER
    FOR NOT FOUND SET v_finished = 1;

    SET _file_data = '';

    OPEN picklists_cursor;

    get_picklists: LOOP

      FETCH picklists_cursor
      INTO _idx, _picklist_id, _code, _source_location_id, _form_idx, _quantity, _balance, _tmn_ref_id;


      IF COALESCE(_current_tmn_ref_id, 0) <> COALESCE(_tmn_ref_id, -1)
      THEN

        IF LENGTH(COALESCE(_picklist, '')) <> 0
        THEN


          SELECT C.tmn_ref_id
          INTO @form_tmn_ref_id
          FROM pkl_picklist A
            INNER JOIN pkl_picklist_item B ON B.tmn_ref_id = _current_tmn_ref_id AND B.picklist_id = A.idx
            INNER JOIN pkl_custom_form C ON A.form_idx = C.idx AND C.status = 0
          LIMIT 1;

          SET @form_ref = '';


          IF COALESCE(@form_tmn_ref_id, 0) > 0
          THEN
            SET @form_ref = CONCAT('REM_FILE=6,', @form_tmn_ref_id, ',REM.TXT');
          END IF;

          SET _temp_list = CONCAT(
              'T=9', CHAR(10),
              'I=', HEX(_current_tmn_ref_id), CHAR(10),
              'R=1', CHAR(10),
              'L=.PL', CHAR(10),
              'M=d67c', CHAR(10),
              'LOCCFG=6,1,LOC.TXT;6,1,PRODLOC.TXT;6,1,LNODE.TXT;;6,1,STRLOC.LST;6,1,LOCMAP.TXT', CHAR(10),
              'DESTFILE=6,1,DESTLOC.TXT;6,1,LOC.TXT;6,1,LOCMAP.TXT', CHAR(10),
              @form_ref, CHAR(10),
              'PICKIDX=', _current_tmn_ref_id, CHAR(10),
              'D=', CHAR(10), _picklist);

          -- inject the N/A location as source in picklist step
          SELECT GROUP_CONCAT(rex SEPARATOR '\n')
          INTO @dx
          FROM
            (
              SELECT CONCAT(A.idx, '.8.', ROW_NUMBER() OVER(PARTITION BY A.idx), '|', _last_source_location_id) AS rex
              FROM pkl_picklist_item A
              WHERE tmn_ref_id = _last_idx AND status = 0
              ORDER BY A.idx ASC
            ) X;

          SET _temp_list = CONCAT(_temp_list, COALESCE(@dx, ''), CHAR(10));

          SET @dx = '';

          SELECT GROUP_CONCAT(rex SEPARATOR '\n')
          INTO @dx
          FROM
            (
              SELECT CONCAT(A.idx, '.9.', ROW_NUMBER() OVER(PARTITION BY A.idx), '|I,', ROW_NUMBER()
                            OVER(PARTITION BY A.idx), ',', URLENCODE(COALESCE(C.key, 'No Key Provided')), ',,',
                            COALESCE(C.type, 'S'), ',', COALESCE(C.minlen, 0), ',', COALESCE(C.maxlen, 255)) AS rex
              FROM pkl_picklist_item A
                INNER JOIN pkl_custom_form B
                  ON A.status = 0 AND B.status = 0 AND B.idx = A.form_idx AND A.tmn_ref_id = _last_idx
                INNER JOIN pkl_custom_form_field C ON C.status = 0 AND C.form_id = B.idx
              ORDER BY A.idx, C.idx ASC
            ) X;


          SET _item_fields = COALESCE(@dx, '');
          -- inject the item fields into picklist file
          SET _temp_list = CONCAT(_temp_list, COALESCE(_item_fields, ''), CHAR(10));

          SET _temp_list = CONCAT('B=', LENGTH(_temp_list), CHAR(10), _temp_list);

          SET _file_data = CONCAT(_file_data, _temp_list);

        END IF;


        SET _picklist = '';
        SET _count = 0;
        SET _current_tmn_ref_id = _tmn_ref_id;
      END IF;

      IF v_finished = 1
      THEN
        LEAVE get_picklists;
      END IF;


      SET _count = _count +1;
      SET _last_idx = _tmn_ref_id;
      SET _last_source_location_id = _source_location_id;

      -- build pick list
      SET _picklist = CONCAT(COALESCE(_picklist, ''), _count, '|', _idx, '|0|', _balance, '|', COALESCE(_code, ''), '|', COALESCE(_code, ''), '|',
                             _idx, '|||', COALESCE(_code, ''), '|', CONCAT(_idx, '.8'), '|', CONCAT(_idx, '.9'), '||||',
                             CHAR(10));


    END LOOP get_picklists;

    CLOSE picklists_cursor;

  END;
