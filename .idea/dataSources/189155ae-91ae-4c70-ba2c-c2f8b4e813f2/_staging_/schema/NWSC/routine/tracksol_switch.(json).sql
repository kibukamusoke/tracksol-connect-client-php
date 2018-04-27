CREATE PROCEDURE tracksol_switch(IN sqlObj JSON, OUT response VARCHAR(4000))
    switch: BEGIN

    DECLARE terminal_version VARCHAR(20);
    DECLARE tmn_hw_id VARCHAR(20);
    DECLARE action_id BIGINT;
    DECLARE p1 VARCHAR(200);
    DECLARE p2 VARCHAR(200);
    DECLARE p3 VARCHAR(200);
    DECLARE p4 VARCHAR(200);
    DECLARE p5 VARCHAR(200);
    DECLARE p6 VARCHAR(200);

    -- other variables --
    DECLARE cardNo VARCHAR(200);
    DECLARE staffName VARCHAR(200);

    DECLARE seq BIGINT;

    DECLARE tid_int BIGINT;
    DECLARE meterNo VARCHAR(20);
    DECLARE customerPhone VARCHAR(20);
    DECLARE customerName VARCHAR(200);
    DECLARE customerId BIGINT;
    DECLARE meterReading NUMERIC;
    DECLARE printString VARCHAR(4000);

    SET printString = '';

    INSERT INTO online_data_log (`data`) VALUES (sqlObj);
    SET seq = LAST_INSERT_ID();

    SET terminal_version = JSON_UNQUOTE(JSON_EXTRACT(sqlObj, '$.ver'));
    SET tmn_hw_id = JSON_UNQUOTE(JSON_EXTRACT(sqlObj, '$.tid'));
    SET action_id = JSON_UNQUOTE(JSON_EXTRACT(sqlObj, '$.act'));
    SET p1 = JSON_UNQUOTE(JSON_EXTRACT(sqlObj, '$.p1'));
    SET p2 = JSON_UNQUOTE(JSON_EXTRACT(sqlObj, '$.p2'));
    SET p3 = JSON_UNQUOTE(JSON_EXTRACT(sqlObj, '$.p3'));
    SET p4 = JSON_UNQUOTE(JSON_EXTRACT(sqlObj, '$.p4'));
    SET p5 = JSON_UNQUOTE(JSON_EXTRACT(sqlObj, '$.p5'));
    SET p6 = JSON_UNQUOTE(JSON_EXTRACT(sqlObj, '$.p6'));

     SET tid_int = CAST(CONV(tmn_hw_id,16,10) AS UNSIGNED INTEGER);

    IF (action_id = 90108)
    THEN -- meter reading

      SET cardNo = p2;
      SELECT `staff_name`
      FROM nwsc_staff
      WHERE `card_no` = cardNo AND `status` = 0
      INTO staffName;


      IF (COALESCE(staffName, '') = '')
      THEN
        SET response = formulate_outputstring
        (
            '4',
            'Invalid Staff Card',
            '',
            '',
            '',
            ''
        );

        LEAVE switch;
      END IF;

      -- p1 = meter#, customer mobile#, meter reading --
      SET meterNo = strSplit(p1, ',', 1);
      SET customerPhone = strSplit(p1, ',', 2);
      SET meterReading = strSplit(p1, ',', 3);

      SELECT
        idx,
        customer_name
      FROM nwsc_customer
      WHERE customer_number = customerPhone
      INTO customerId, customerName;

      IF (COALESCE(customerId, 0) = 0)
      THEN
        SET response = formulate_outputstring
        (
            '4',
            'Unknown Customer ',
            '',
            '',
            '',
            ''
        );

        LEAVE switch;

      END IF;

      INSERT INTO nwsc_meter_reading (seq_id, meter_number, customer_name, meter_reading, customer_id, created_dt, created_by)
      VALUES (seq, meterNo, customerName, meterReading, customerId, current_timestamp, staffName);

      SET printString =
      CONCAT(
          '{fw2}      MWSC', CHAR(10),
          '{f1}Terminal ID:', LPAD(tmn_hw_id, 20, ' '),
          'Staff Card:', LPAD(cardNo, 21, ' '),
          'Staff Name:', LPAD(staffName, 21, ' '),
          '>', CHAR(10),
          'Customer: ', customerName, CHAR(10),
          'Meter #:', meterNo, CHAR(10),
          'Reading: ', meterReading, CHAR(10),
          '================================^^^');


      SET response = formulate_outputstring
      (
          '4',
          'Meter Reading Recorded',
          CONCAT('ID: ', seq),
          '',
          '',
          printString
      );


    ELSE
      SET response = formulate_outputstring
      (
          '4',
          'Action Not Ready',
          '',
          '',
          '',
          ''
      );


    END IF;

  END;
