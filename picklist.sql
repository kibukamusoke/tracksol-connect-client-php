create table pkl_collection_delete_this
(
  idx bigint auto_increment
    primary key,
  name varchar(200) not null,
  dos_name varchar(12) null,
  description varchar(1000) null,
  fileId bigint null,
  status int default '0' not null,
  addedBy varchar(100) null,
  updatedAt datetime default CURRENT_TIMESTAMP not null,
  constraint pkl_collection_idx_uindex
  unique (idx)
)
;

create table pkl_custom_form
(
  idx bigint auto_increment
    primary key,
  name varchar(100) not null,
  level varchar(10) null,
  description varchar(1000) null,
  addedBy varchar(100) null,
  updatedAt datetime default CURRENT_TIMESTAMP null,
  status int default '0' not null,
  tmn_ref_id bigint default '0' not null,
  constraint pkl_custom_form_idx_uindex
  unique (idx)
)
;

create table pkl_custom_form_field
(
  idx bigint auto_increment
    primary key,
  form_id bigint not null,
  `key` varchar(100) null,
  type varchar(100) default 'string' not null,
  minlen int default '0' null,
  maxlen int default '255' null,
  status int default '0' not null,
  updatedAt datetime default CURRENT_TIMESTAMP null,
  constraint pkl_custom_form_fields_idx_uindex
  unique (idx)
)
;

create table pkl_document_type
(
  idx bigint auto_increment
    primary key,
  name varchar(100) null,
  description varchar(1000) null,
  status int default '0' not null,
  addedBy varchar(100) null,
  updatedAt datetime default CURRENT_TIMESTAMP null,
  type_id int not null,
  code varchar(100) null,
  constraint pkl_document_type_idx_uindex
  unique (idx)
)
;

create table pkl_location
(
  idx bigint auto_increment
    primary key,
  name varchar(100) null,
  description varchar(200) null,
  status int default '0' not null,
  addedBy varchar(100) null,
  updatedAt datetime default CURRENT_TIMESTAMP null,
  card_no varchar(20) null,
  constraint pkl_location_idx_uindex
  unique (idx)
)
;

create table pkl_pick_log
(
  idx bigint auto_increment
    primary key,
  picklist_item_idx bigint default '0' not null,
  picklist_code varchar(100) null,
  source_location_idx bigint not null,
  source_location varchar(100) null,
  trans_id int null,
  pick_time varchar(30) null,
  destination_location_idx bigint null,
  destination_location varchar(100) null,
  item_code varchar(100) null,
  quantity decimal default '0' null,
  document_form varchar(1500) null,
  item_form varchar(1500) null,
  created_dt datetime null,
  collection_id int null,
  balance decimal default '0' null,
  constraint pkl_pick_log_idx_uindex
  unique (idx)
)
;

create table pkl_picklist
(
  idx bigint auto_increment
    primary key,
  code varchar(100) null,
  form_idx bigint null,
  destination_location_id bigint null,
  status int default '0' not null,
  updatedAt datetime default CURRENT_TIMESTAMP null,
  description varchar(100) null,
  type_id int default '1' not null,
  collection_id int null,
  constraint pkl_picklist_idx_uindex
  unique (idx)
)
;

create table pkl_picklist_item
(
  idx bigint auto_increment
    primary key,
  picklist_id bigint not null,
  code varchar(100) null,
  source_location_id bigint null,
  form_idx bigint null,
  quantity int null,
  status int default '0' null,
  updatedAt datetime default CURRENT_TIMESTAMP null,
  balance int default '0' not null,
  tmn_ref_id int null,
  constraint pkl_piclist_item_idx_uindex
  unique (idx)
)
;

create function URLENCODE (str varchar(4096)) returns varchar(4096)
  BEGIN
    -- the individual character we are converting in our loop
    -- NOTE: must be VARCHAR even though it won't vary in length
    -- CHAR(1), when used with SUBSTRING, made spaces '' instead of ' '
    DECLARE sub VARCHAR(1) CHARSET utf8;
    -- the ordinal value of the character (i.e. ñ becomes 50097)
    DECLARE val BIGINT DEFAULT 0;
    -- the substring index we use in our loop (one-based)
    DECLARE ind INT DEFAULT 1;
    -- the integer value of the individual octet of a character being encoded
    -- (which is potentially multi-byte and must be encoded one byte at a time)
    DECLARE oct INT DEFAULT 0;
    -- the encoded return string that we build up during execution
    DECLARE ret VARCHAR(4096) DEFAULT '';
    -- our loop index for looping through each octet while encoding
    DECLARE octind INT DEFAULT 0;

    IF ISNULL(str) THEN
      RETURN NULL;
    ELSE
      SET ret = '';
      -- loop through the input string one character at a time - regardless
      -- of how many bytes a character consists of
      WHILE ind <= CHAR_LENGTH(str) DO
        SET sub = MID(str, ind, 1);
        SET val = ORD(sub);
        -- these values are ones that should not be converted
        -- see http://tools.ietf.org/html/rfc3986
        IF NOT (val BETWEEN 48 AND 57 OR     -- 48-57  = 0-9
                val BETWEEN 65 AND 90 OR     -- 65-90  = A-Z
                val BETWEEN 97 AND 122 OR    -- 97-122 = a-z
                -- 45 = hyphen, 46 = period, 95 = underscore, 126 = tilde
                val IN (45, 46, 95, 126)) THEN
          -- This is not an &quot;unreserved&quot; char and must be encoded:
          -- loop through each octet of the potentially multi-octet character
          -- and convert each into its hexadecimal value
          -- we start with the high octect because that is the order that ORD
          -- returns them in - they need to be encoded with the most significant
          -- byte first
          SET octind = OCTET_LENGTH(sub);
          WHILE octind > 0 DO
            -- get the actual value of this octet by shifting it to the right
            -- so that it is at the lowest byte position - in other words, make
            -- the octet/byte we are working on the entire number (or in even
            -- other words, oct will no be between zero and 255 inclusive)
            SET oct = (val >> (8 * (octind - 1)));
            -- we append this to our return string with a percent sign, and then
            -- a left-zero-padded (to two characters) string of the hexadecimal
            -- value of this octet)
            SET ret = CONCAT(ret, '%', LPAD(HEX(oct), 2, 0));
            -- now we need to reset val to essentially zero out the octet that we
            -- just encoded so that our number decreases and we are only left with
            -- the lower octets as part of our integer
            SET val = (val & (POWER(256, (octind - 1)) - 1));
            SET octind = (octind - 1);
          END WHILE;
        ELSE
          -- this character was not one that needed to be encoded and can simply be
          -- added to our return string as-is
          SET ret = CONCAT(ret, sub);
        END IF;
        SET ind = (ind + 1);
      END WHILE;
    END IF;
    RETURN ret;
  END;

create procedure sp_add_edit_form (IN `_DATA` json, INOUT response json)
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

create procedure sp_add_edit_item (IN `_DATA` json, INOUT response json)
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

create procedure sp_clear_old_data (OUT `_commands` text)
  BEGIN

    SELECT CONCAT(GROUP_CONCAT(rex SEPARATOR '
'), CHAR(10)) INTO _commands
    FROM (
           SELECT
             DISTINCT CONCAT('{X`D|', tmn_ref_id, '|.PL*|9`}') AS rex
           FROM pkl_picklist_item
         ) X;

  END;

create procedure sp_import (IN `_DATA_ARRAY` json)
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

create procedure sp_offline_picklist (IN `_data` json, OUT `_response` tinytext)
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

create procedure sp_offline_switch (IN `_data` json, OUT `_response` tinytext)
  BEGIN

    DECLARE _action_id BIGINT;
    DECLARE _tmn_hw_id BIGINT;
    DECLARE _card_no VARCHAR(20);
    DECLARE _version VARCHAR(20);
    DECLARE _txndate DATETIME;
    DECLARE _p131 JSON;


    DECLARE _p33 VARCHAR(255);
    DECLARE _p34 VARCHAR(255);

    DECLARE _is_picklist BOOLEAN;

    IF _is_picklist = TRUE THEN


      CALL sp_offline_picklist(_data, @response);
      SET _response = @response;

    ELSE

      SET _response = 'Action not ready';

    END IF;



  END;

create procedure sp_picklist_data (OUT `_data` text, IN `_tmn_ref_id` int, IN `_collection_id` int)
  BEGIN

    SET SESSION group_concat_max_len = 1000000;
    SET @location_rows := 0;
    SET @doc_counter := 0;
    SET @neg_counter := 0;


    SELECT GROUP_CONCAT(rex SEPARATOR '
') INTO _data
    FROM (
           SELECT
             CONCAT(node, '|', ROW_NUMBER() OVER(ORDER BY ( SELECT 1)), '|', tmn_ref_id, '|', is_parent, '|', type_short,
                    '|',
                    CASE WHEN tmn_ref_id < 0 THEN '' ELSE tmn_ref_id END, '|', description, '|', description, '|', type_long, '|') AS rex
           FROM
             (
               SELECT
                 type_id                          AS node,
                 name                             AS description,
                 @neg_counter := @neg_counter - 1 AS tmn_ref_id,
                 1                                AS is_parent,
                 code                             AS type_short,
                 name                             AS type_long
               FROM pkl_document_type
               WHERE status = 0

               UNION ALL

               SELECT
                 CONCAT(type_id, '.', cnt)        AS node,
                 code                             AS description,
                 @neg_counter := @neg_counter - 1 AS tmn_ref_id,
                 1                                AS is_parent,
                 type_code                        AS type_short,
                 name                             AS type_long
               FROM
                 (
                   SELECT
                     ROW_NUMBER() OVER (PARTITION BY B.type_id) AS cnt,
                     A.type_id,
                     B.code,
                     A.code AS    type_code,
                     A.name
                   FROM
                     pkl_document_type A
                     INNER JOIN pkl_picklist B ON B.collection_id = `_collection_id` AND A.status = 0 AND B.status = 0 AND A.type_id = B.type_id

                   ORDER BY A.type_id ASC
                 ) AS AA

               UNION ALL


               SELECT
                 CONCAT(type_id, '.', doc_cnt, '.', loc_cnt) AS node,
                 location_name                               AS description,
                 tmn_ref_id                                  AS tmn_ref_id,
                 0                                           AS is_parent,
                 type_code                                   AS type_short,
                 name                                        AS type_long
               FROM
                 (
                   SELECT
                     ROW_NUMBER()  OVER (PARTITION BY ( SELECT 1)) AS loc_cnt,
                     DENSE_RANK()  OVER(ORDER BY B.idx ASC) AS doc_cnt,
                     A.type_id,
                     D.name     AS location_name,
                     C.tmn_ref_id,
                     'LOC'      AS type_code,
                     'Location' AS name
                   FROM
                     pkl_document_type A
                     INNER JOIN pkl_picklist B
                       ON A.status = 0 AND B.status = 0 AND collection_id = _collection_id AND A.type_id = B.type_id
                     INNER JOIN pkl_picklist_item C ON C.status = 0 AND C.picklist_id = B.idx AND C.tmn_ref_id > 0
                     INNER JOIN pkl_location D ON D.status = 0 AND C.source_location_id = D.idx

                   ORDER BY A.type_id ASC, C.picklist_id ASC
                 ) AS AB

             ) AS X
         ) AS Z;

    SET _data = CONCAT(
        'T=6', CHAR(10),
        'I=', HEX(_tmn_ref_id), CHAR(10),
        'R=1', CHAR(10),
        'L=PICKLIST.DOC', CHAR(10),
        'M=d67c', CHAR(10),
        'D=', CHAR(10), _data, CHAR(10)
    );


    SET _data = CONCAT('B=', LENGTH(_data), CHAR(10), _data);

    -- build individual picklist files by location
    CALL sp_picklist_data_mini_files(`_collection_id`, @small_files);
    CALL sp_picklist_supporting_files(@supporting_files);

    SET _data = CONCAT(_data, @small_files, @supporting_files);


  END;

create procedure sp_picklist_data_mini_files (IN collection_id int, OUT `_file_data` text)
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
          SELECT GROUP_CONCAT(rex SEPARATOR '
')
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

          SELECT GROUP_CONCAT(rex SEPARATOR '
')
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

create procedure sp_picklist_supporting_files (OUT `_file_data` text)
  BEGIN

    SET @noloc = CONCAT(-- no location file
        'T=6', CHAR(10),
        'I=', HEX(1), CHAR(10),
        'R=1', CHAR(10),
        'L=NOLOC.TXT', CHAR(10),
        'M=d67c', CHAR(10),
        'D=', CHAR(10),
        '-1|1|-1|0|LOC|-1||N/A|', CHAR(10)
    );

    SET _file_data = CONCAT('B=', LENGTH(@noloc), CHAR(10), @noloc);

    SET @temp1 = CONCAT('T=6', CHAR(10),
                        'I=', HEX(1), CHAR(10),
                        'R=1', CHAR(10),
                        'L=DESTLOC.TXT', CHAR(10),
                        'M=d67c', CHAR(10),
                        'D=', CHAR(10),
                        '-1|1|-1|0|LOC|-1||N/A|', CHAR(10)
    );

    SELECT GROUP_CONCAT(rex SEPARATOR '
')
    INTO @temp2
    FROM (
           SELECT CONCAT(
                      ROW_NUMBER() OVER(PARTITION BY( SELECT 1) ORDER BY NAME ASC), '|',
                      ROW_NUMBER() OVER(PARTITION BY( SELECT 1) ORDER BY NAME ASC) + 1, '|',
                      idx, '|0|LOC|',
                      idx, '|',
                      REPLACE(
                          REPLACE(
                              REPLACE(name, '%', '%25'), '&', '%26'), '|', '%7C'), '|',
                      REPLACE(
                          REPLACE(
                              REPLACE(name, '%', '%25'), '&', '%26'), '|', '%7C'), '|') AS rex
           FROM pkl_location
           WHERE status = 0
           ORDER BY name ASC
         ) X;

    SET @temp1 = CONCAT(@temp1, COALESCE(@temp2, ''), CHAR(10));

    SET @temp1 = CONCAT('B=', LENGTH(@temp1), CHAR(10), @temp1);

    SET _file_data = CONCAT(_file_data, @temp1);

    -- build custom fields file - document level

    SELECT GROUP_CONCAT(dx SEPARATOR '')
    INTO @remarks
    FROM (
           SELECT CONCAT('B=', LENGTH(rex), CHAR(10), rex) AS dx
           FROM (
                  SELECT CONCAT(
                             'T=6', CHAR(10),
                             'I=', HEX(tmn_ref_id), CHAR(10),
                             'R=1', CHAR(10),
                             'L=REM.TXT', CHAR(10),
                             'M=d67c', CHAR(10),
                             'D=', CHAR(10), GROUP_CONCAT(rx SEPARATOR '
'),
                             CHAR(10)) AS rex

                  FROM

                    (SELECT
                       A.tmn_ref_id,
                       CONCAT(ROW_NUMBER() OVER(ORDER BY ( SELECT 1)), '|',
                              'I,', ROW_NUMBER() OVER(ORDER BY ( SELECT 1)), ',',
                              URLENCODE(B.`key`), ',,', B.type, ',',
                              COALESCE(B.minlen, 0), ',',
                              COALESCE(B.maxlen, 255)) AS rx

                     FROM pkl_custom_form A
                       INNER JOIN pkl_custom_form_field B
                         ON A.idx = B.form_id AND A.status = 0 AND B.status = 0 AND
                            A.level = 'document'

                     ORDER BY A.idx ASC) X
                  GROUP BY tmn_ref_id
                ) Y
         ) Z;

    SET _file_data = CONCAT(_file_data, COALESCE(@remarks, ''));

    -- other location files --

    -- LNODE file

    SELECT GROUP_CONCAT(rex SEPARATOR '
')
    INTO @lnode
    FROM (
           SELECT '-1|-1|-1||N/A|' AS rex
           UNION ALL
           SELECT CONCAT(
                      ROW_NUMBER() OVER(ORDER BY ( SELECT 1)), '|',
                      idx, '|',
                      idx, '||',
                      REPLACE(
                          REPLACE(
                              REPLACE(name, '%', '%25'), '&', '%26'), '|', '%7C'), '|') AS rex
           FROM
             pkl_location
           WHERE status = 0
         ) X;

    IF (COALESCE(@lnode, '')) <> ''
    THEN
      SET @lnode = CONCAT(@lnode, CHAR(10));
    ELSE
      SET @lnode = '';
    END IF;

    SET @lnode = CONCAT(
        'T=6', CHAR(10),
        'I=1', CHAR(10),
        'R=1', CHAR(10),
        'L=LNODE.TXT', CHAR(10),
        'M=d67c', CHAR(10),
        'D=', CHAR(10), @lnode);

    SET @lnode = CONCAT('B=', LENGTH(@lnode), CHAR(10), @lnode);

    SET `_file_data` = CONCAT(`_file_data`, @lnode);

    -- location store cards --


    SET @loc = '';
    SELECT GROUP_CONCAT(
        card_no, '|',
        REPLACE(
            REPLACE(
                REPLACE(name, '%', '%25'), '&', '%26'), '|', '%7C'), '|',
        idx, '|' SEPARATOR '
')
    INTO @loc
    FROM
      pkl_location
    WHERE status = 0 AND LENGTH(card_no) > 5;

    IF (COALESCE(@loc, '')) <> ''
    THEN
      SET @loc = CONCAT(@loc, CHAR(10));
    ELSE
      SET @loc = '';
    END IF;


    SET @loc = CONCAT(
        'T=6', CHAR(10),
        'I=1', CHAR(10),
        'R=1', CHAR(10),
        'L=LOC.TXT', CHAR(10),
        'M=d67c', CHAR(10),
        'D=', CHAR(10), @loc);

    SET @loc = CONCAT('B=', LENGTH(@loc), CHAR(10), @loc);

    SET `_file_data` = CONCAT(`_file_data`, @loc);

    -- Store List File ---

    -- STRLOC.LST

    SELECT GROUP_CONCAT(rex SEPARATOR '
')
    INTO @strloc
    FROM (
           SELECT '-1|N/A|' AS rex
           UNION ALL
           SELECT CONCAT(
                      idx, '|',
                      REPLACE(
                          REPLACE(
                              REPLACE(name, '%', '%25'), '&', '%26'), '|', '%7C'), '|') AS rex
           FROM
             pkl_location
           WHERE status = 0
         ) X;

    IF (COALESCE(@strloc, '')) <> ''
    THEN
      SET @strloc = CONCAT(@strloc, CHAR(10));
    ELSE
      SET @strloc = '';
    END IF;

    SET @strloc = CONCAT(
        'T=6', CHAR(10),
        'I=1', CHAR(10),
        'R=1', CHAR(10),
        'L=STRLOC.LST', CHAR(10),
        'M=d67c', CHAR(10),
        'SCAN=1', CHAR(10),
        'O_EN=Please select one ', CHAR(10),
        'SEARCH_COL=2;6,1,LOCMAP.TXT;1,3', CHAR(10),
        'D=', CHAR(10), @strloc);

    SET @strloc = CONCAT('B=', LENGTH(@strloc), CHAR(10), @strloc);

    SET `_file_data` = CONCAT(`_file_data`, @strloc);

    -- PRODLOC.TXT

    SET @prodloc = CONCAT(
        'T=6', CHAR(10),
        'I=1', CHAR(10),
        'R=1', CHAR(10),
        'L=PRODLOC.TXT', CHAR(10),
        'M=d67c', CHAR(10),
        'D=', CHAR(10));

    SET @prodloc = CONCAT('B=', LENGTH(@prodloc), CHAR(10), @prodloc);

    SET `_file_data` = CONCAT(`_file_data`, @prodloc);


    -- LOCMAP.TXT

    SET @locmap = CONCAT(
        'T=6', CHAR(10),
        'I=1', CHAR(10),
        'R=1', CHAR(10),
        'L=LOCMAP.TXT', CHAR(10),
        'M=d67c', CHAR(10),
        'D=', CHAR(10));

    SET @locmap = CONCAT('B=', LENGTH(@locmap), CHAR(10), @locmap);

    SET `_file_data` = CONCAT(`_file_data`, @locmap);

  END;

create function strSplit (x text, delim varchar(12), pos int) returns text
  BEGIN
    DECLARE output TEXT;
    SET output = REPLACE(SUBSTRING(SUBSTRING_INDEX(x, delim, pos)
    , LENGTH(SUBSTRING_INDEX(x, delim, pos - 1)) + 1)
    , delim
    , '');
    IF output = '' THEN SET output = null; END IF;
    RETURN output;
  END;

