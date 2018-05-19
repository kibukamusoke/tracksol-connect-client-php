USE ConnectPicklist;
DROP PROCEDURE sp_picklist_supporting_files;
CREATE PROCEDURE sp_picklist_supporting_files(OUT `_file_data` TEXT)
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
                             'D=', CHAR(10), GROUP_CONCAT(rx SEPARATOR '\n'),
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

    SELECT GROUP_CONCAT(rex SEPARATOR '\n')
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
        idx, '|' SEPARATOR '\n')
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

    SELECT GROUP_CONCAT(rex SEPARATOR '\n')
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
