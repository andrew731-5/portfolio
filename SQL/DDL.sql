CREATE DATABASE 10065;

CREATE TABLE Employee(
No VARCHAR(20) NOT NULL PRIMARY KEY UNIQUE,
Name VARCHAR(20),
MailAddress VARCHAR(255),
Password VARCHAR(255)
);

CREATE TABLE Item(
ID integer NOT NULL PRIMARY KEY UNIQUE,
ItemName character varying(20)
);

-- 商品単価テーブル
CREATE TABLE UnitPrice(
ID integer NOT NULL PRIMARY KEY UNIQUE,
UnitPrice integer
);

-- タイトルテーブル
CREATE TABLE Title(
TitleNo integer NOT NULL PRIMARY KEY UNIQUE auto_increment,
Title VARCHAR(100),
RegisteredPerson VARCHAR(100)
);

ALTER TABLE Item ADD
TitleNo integer NOT NULL;

ALTER TABLE UnitPrice ADD
TitleNo integer NOT NULL;

ALTER TABLE UnitPrice ADD
UnitPrice integer NOT NULL;

ALter TABLE UnitPrice ADD
consumptionTax integer NOT NULL;

ALter TABLE UnitPrice ADD
total integer NOT NULL;

ALter TABLE Title ADD
totalTax integer;

ALter TABLE Title ADD
total integer;
