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

