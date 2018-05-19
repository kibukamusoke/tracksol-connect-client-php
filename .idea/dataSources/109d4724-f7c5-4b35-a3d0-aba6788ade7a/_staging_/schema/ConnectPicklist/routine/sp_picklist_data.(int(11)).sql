USE ConnectPicklist;
DROP PROCEDURE sp_picklist_data;
CREATE PROCEDURE sp_picklist_data(OUT `_data` TEXT, IN `_collection_id` INT, IN _tmn_ref_id INTEGER)
  BEGIN

    SET @location_rows := 0;
    SET @doc_counter := 0;
    SET @neg_counter := 0;


    SELECT GROUP_CONCAT(rex SEPARATOR '\n') INTO _data
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
