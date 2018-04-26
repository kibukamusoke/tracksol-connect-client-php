create table card
(
  idx bigint auto_increment
    primary key,
  cardNo varchar(20) not null,
  type int default '1' null,
  name varchar(100) null,
  status int default '0' not null,
  createdAt datetime default CURRENT_TIMESTAMP null,
  createdBy varchar(100) null,
  updatedAt datetime null,
  updatedBy varchar(100) null,
  constraint cards_card_id_uindex
  unique (idx)
)
;

create table product
(
  product_id bigint auto_increment
    primary key,
  list_id bigint default '0' not null,
  product_ref varchar(100) null,
  code varchar(100) not null,
  name varchar(100) null,
  min_price decimal default '0' not null,
  max_price decimal default '0' null,
  createdAt datetime null,
  createdBy varchar(100) null,
  updatedAt datetime null,
  updatedBy varchar(100) null,
  constraint product_product_id_uindex
  unique (product_id)
)
;

create table product_list
(
  list_id bigint auto_increment
    primary key,
  name varchar(100) not null,
  dos_name varchar(100) null,
  description varchar(4000) null,
  status int default '0' null,
  createdAt datetime null,
  createdBy varchar(100) null,
  updatedAt datetime null,
  updatedBy varchar(100) null,
  constraint product_list_list_id_uindex
  unique (list_id)
)
;


USE MWSC;
create table nwsc_customer
(
  idx bigint auto_increment
    primary key,
  customer_name varchar(200) not null,
  customer_number varchar(50) null,
  property_number varchar(200) null,
  meter_number varchar(50) null,
  status int default '0' not null,
  createdAt datetime default CURRENT_TIMESTAMP null,
  updatedAt datetime null,
  updatedBy varchar(100) null,
  constraint nwsc_customer_idx_uindex
  unique (idx)
)
;


create table nwsc_customer
(
  idx bigint auto_increment
    primary key,
  customer_name varchar(200) not null,
  customer_number varchar(50) null,
  property_number varchar(200) null,
  meter_number varchar(50) null,
  status int default '0' not null,
  createdAt datetime default CURRENT_TIMESTAMP null,
  updatedAt datetime null,
  updatedBy varchar(100) null,
  constraint nwsc_customer_idx_uindex
  unique (idx)
)
;

create table nwsc_meter_reading
(
  idx bigint auto_increment
    primary key,
  seq_id bigint not null,
  meter_number varchar(50) not null,
  customer_name varchar(200) null,
  meter_reading decimal null,
  customer_id bigint null,
  created_dt datetime default CURRENT_TIMESTAMP null,
  created_by varchar(100) null,
  updated_dt datetime null,
  updated_by varchar(100) null,
  constraint nwsc_meter_readings_idx_uindex
  unique (idx)
)
;

create table online_data_log
(
  idx bigint auto_increment
    primary key,
  data json null,
  response json null,
  startTime datetime default CURRENT_TIMESTAMP not null,
  endTime datetime null,
  constraint online_data_log_idx_uindex
  unique (idx)
)
;

create function formulate_outputstring (buzzer varchar(10), lcd_line1 varchar(300), lcd_line2 varchar(300), lcd_line3 varchar(300), lcd_line4 varchar(300), printer varchar(4096)) returns varchar(4000)
  BEGIN
    DECLARE B VARCHAR(10);
    DECLARE ret VARCHAR(4096);
    SET ret = '';

    IF buzzer = '3'
    THEN
      SET buzzer = 'A';
      SET B = 'B`';
    ELSE IF buzzer = '1'
    THEN
      SET B = '';
      SET buzzer = 'A';

    ELSE
      SET buzzer = 'E';
      SET B = 'B`';
    END IF;

      SET ret = CONCAT(ret, '{', B, buzzer, '`}');
      SET lcd_line1 = RTRIM(lcd_line1);
      SET lcd_line2 = RTRIM(lcd_line2);
      SET lcd_line3 = RTRIM(lcd_line3);
      SET lcd_line4 = RTRIM(lcd_line4);
      IF LENGTH(lcd_line1) > 0 OR LENGTH(lcd_line2) > 0 OR LENGTH(lcd_line3) > 0 OR LENGTH(lcd_line4) > 0
      THEN
        SET ret = CONCAT(ret, '{L`');
        IF LENGTH(lcd_line1) > 0
        THEN
          SET ret = CONCAT(ret, '[1`', lcd_line1, '`]');
        END IF;

        IF LENGTH(lcd_line2) > 0
        THEN
          SET ret = CONCAT(ret, '[2`', lcd_line2, '`]');
        END IF;
        IF LENGTH(lcd_line3) > 0
        THEN
          SET ret = CONCAT(ret, '[3`', lcd_line3, '`]');
        END IF;
        IF LENGTH(lcd_line4) > 0
        THEN
          SET ret = CONCAT(ret, '[4`', lcd_line4, '`]');
        END IF;
        SET ret = CONCAT(ret, '`}');
      END IF;

      SET printer = LTRIM(RTRIM(printer));

      IF LENGTH(printer) > 10
      THEN
        SET ret = CONCAT(ret, '{P`', '[T`', printer, '`]`}');
      END IF;

    END IF;

    RETURN ret;


  END;

create function strSplit (x varchar(65000), delim varchar(12), pos int) returns varchar(65000)
  BEGIN
    DECLARE output VARCHAR(65000);
    SET output = REPLACE(SUBSTRING(SUBSTRING_INDEX(x, delim, pos)
    , LENGTH(SUBSTRING_INDEX(x, delim, pos - 1)) + 1)
    , delim
    , '');
    IF output = '' THEN SET output = null; END IF;
    RETURN output;
  END;

create procedure tracksol_switch (IN sqlObj json, OUT response varchar(4000))
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
      SELECT `name`
      FROM Connect.card
      WHERE `cardNo` = cardNo AND `status` = 0
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

