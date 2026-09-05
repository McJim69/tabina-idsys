DROP DATABASE IF EXISTS idsystem_lgu;
CREATE DATABASE idsystem_lgu;
USE idsystem_lgu;

CREATE TABLE cert_indigency (
  idn int NOT NULL,
  name_fam varchar(100),
  name_1st varchar(100),
  name_mid varchar(100),
  birth_month varchar(100),
  birth_day varchar(100),
  birth_year varchar(100),
  status varchar(100),
  sex varchar(100),
  address varchar(100),
  barangay varchar(100),
  city_mun varchar(100),
  province varchar(100),
  is_month varchar(100),
  is_day varchar(100),
  is_year varchar(100),
  ispicset int DEFAULT '0'
);

CREATE TABLE chat_room (
  id int NOT NULL DEFAULT '0',
  room varchar(100)
);

INSERT INTO chat_room (id, room) VALUES
(1, 'Chat Room 01'),
(2, 'Chat Room 02'),
(3, 'Chat Room 03'),
(4, 'Chat Room 04'),
(5, 'Chat Room 05'),
(6, 'Chat Room 06'),
(7, 'Chat Room 07'),
(8, 'Chat Room 08'),
(9, 'Chat Room 09'),
(10, 'Chat Room 10'),
(11, 'Chat Room 11'),
(12, 'Chat Room 12'),
(13, 'Chat Room 13'),
(14, 'Chat Room 14'),
(15, 'Chat Room 15'),
(16, 'Chat Room 16'),
(17, 'Chat Room 17'),
(18, 'Chat Room 18'),
(19, 'Chat Room 19'),
(20, 'Chat Room 20'),
(21, 'Chat Room 21'),
(22, 'Chat Room 22'),
(23, 'Chat Room 23'),
(24, 'Chat Room 24'),
(25, 'Chat Room 25');

CREATE TABLE clearances (
  idn int NOT NULL,
  name_fam varchar(100),
  name_1st varchar(100),
  name_mid varchar(100),
  sex varchar(100),
  civil_status varchar(100),
  address varchar(100),
  barangay varchar(100),
  city_mun varchar(100),
  province varchar(100),
  is_month varchar(100),
  is_day varchar(100),
  is_year varchar(100),
  isorno varchar(100),
  oramount varchar(100),
  ispicset int DEFAULT '0'
);

CREATE TABLE countdown (
  id int NOT NULL,
  name varchar(200),
  year varchar(22),
  month varchar(22),
  day varchar(22),
  hour varchar(22),
  min varchar(22),
  sec varchar(22)
);

INSERT INTO countdown (id, name, year, month, day, hour, min, sec) VALUES
(1, 'Barangay Malim Fiesta', '2020', '09', '13', '00', '00', '00');

CREATE TABLE districts (
  sitio varchar(50),
  bario varchar(50),
  municipal varchar(50)
);

INSERT INTO districts (sitio, bario, municipal) VALUES
('Amor', 'Abong-Abong', 'Tabina'),
('Bagong-Silang', 'Abong-Abong', 'Tabina'),
('Bual', 'Abong-Abong', 'Tabina'),
('Hagimit', 'Abong-Abong', 'Tabina'),
('Madasigon', 'Abong-Abong', 'Tabina'),
('Masanagon', 'Abong-Abong', 'Tabina'),
('Paradise', 'Abong-Abong', 'Tabina'),
('Quarry', 'Abong-Abong', 'Tabina'),
('Tinutongan', 'Abong-Abong', 'Tabina'),
('Balite', 'BAGANIAN', 'Tabina'),
('Bangus', 'BAGANIAN', 'Tabina'),
('Buga', 'BAGANIAN', 'Tabina'),
('Giwanon', 'BAGANIAN', 'Tabina'),
('Kuhol', 'BAGANIAN', 'Tabina'),
('Mantis', 'BAGANIAN', 'Tabina'),
('Overflow', 'BAGANIAN', 'Tabina'),
('San Lorenzo', 'BAGANIAN', 'Tabina'),
('Stardust', 'BAGANIAN', 'Tabina'),
('Upper Baganian', 'BAGANIAN', 'Tabina'),
('San Antonio', 'BAGANIAN', 'Tabina'),
('Tubo-Tubo', 'BAGANIAN', 'Tabina'),
('Tulingan', 'BAGANIAN', 'Tabina'),
('Alagase', 'Baya-Baya', 'Tabina'),
('Badbad', 'Baya-Baya', 'Tabina'),
('Banaba', 'Baya-Baya', 'Tabina'),
('Nato', 'Baya-Baya', 'Tabina'),
('Palmera', 'Baya-Baya', 'Tabina'),
('Tabigue', 'Baya-Baya', 'Tabina'),
('Mercury', 'Capisan', 'Tabina'),
('Saturn', 'Capisan', 'Tabina'),
('Sidlak', 'Capisan', 'Tabina'),
('Acasia', 'CONCEPCION', 'Tabina'),
('Alto', 'CONCEPCION', 'Tabina'),
('Bajo', 'CONCEPCION', 'Tabina'),
('Kahayag', 'CONCEPCION', 'Tabina'),
('San Juan', 'CONCEPCION', 'Tabina'),
('Sulip', 'CONCEPCION', 'Tabina'),
('Tubod', 'CONCEPCION', 'Tabina'),
('Calubian', 'Culabay', 'Tabina'),
('Dapdap', 'Culabay', 'Tabina'),
('Lumboy', 'Culabay', 'Tabina'),
('Manggostan', 'Culabay', 'Tabina'),
('Maranding', 'Culabay', 'Tabina'),
('Pagoda', 'Culabay', 'Tabina'),
('Tandayan', 'Culabay', 'Tabina'),
('BAGACAY', 'DOÑA JOSEFINA', 'Tabina'),
('BITUON', 'DOÑA JOSEFINA', 'Tabina'),
('DALAMAN', 'DOÑA JOSEFINA', 'Tabina'),
('HINDANG', 'DOÑA JOSEFINA', 'Tabina'),
('Cabuso', 'LUMBIA', 'Tabina'),
('Kanaway', 'LUMBIA', 'Tabina'),
('Timog', 'LUMBIA', 'Tabina'),
('Bomba', 'MABUHAY', 'Tabina'),
('Corbada', 'MABUHAY', 'Tabina'),
('Hanagdong', 'MABUHAY', 'Tabina'),
('Malimpuno', 'MABUHAY', 'Tabina'),
('Mangga', 'MABUHAY', 'Tabina'),
('Mol-aw', 'MABUHAY', 'Tabina'),
('Sandayong', 'MABUHAY', 'Tabina'),
('Tabok', 'MABUHAY', 'Tabina'),
('Waling-Waling', 'MABUHAY', 'Tabina'),
('Arbor', 'MALIM', 'Tabina'),
('Kamansi', 'MALIM', 'Tabina'),
('Jakarta', 'MALIM', 'Tabina'),
('Pagatpat', 'MALIM', 'Tabina'),
('Pangalaran', 'MALIM', 'Tabina'),
('Patag', 'MALIM', 'Tabina'),
('Sta. Lucia', 'MALIM', 'Tabina'),
('Tambulian', 'MALIM', 'Tabina'),
('Tambunan', 'MALIM', 'Tabina'),
('Tuboran', 'MALIM', 'Tabina'),
('Bonbon', 'Manicaan', 'Tabina'),
('Lawis', 'Manicaan', 'Tabina'),
('Nabunturan', 'Manicaan', 'Tabina'),
('San Jose', 'Manicaan', 'Tabina'),
('Sigay', 'Manicaan', 'Tabina'),
('Tagaytay', 'Manicaan', 'Tabina'),
('Bahay', 'NEW OROQUIETA', 'Tabina'),
('Dao', 'NEW OROQUIETA', 'Tabina'),
('Gibo', 'NEW OROQUIETA', 'Tabina'),
('Luyaw', 'NEW OROQUIETA', 'Tabina'),
('Waling-Waling', 'NEW OROQUIETA', 'Tabina'),
('Bayanihan', 'POBLACION', 'Tabina'),
('Budlong', 'POBLACION', 'Tabina'),
('Burawin', 'POBLACION', 'Tabina'),
('Daag', 'POBLACION', 'Tabina'),
('Fishing Village', 'POBLACION', 'Tabina'),
('Hibino', 'POBLACION', 'Tabina'),
('Hilltop', 'POBLACION', 'Tabina'),
('Kawashi', 'POBLACION', 'Tabina'),
('Paran', 'POBLACION', 'Tabina'),
('Saniblangis', 'POBLACION', 'Tabina'),
('Sarilikha', 'POBLACION', 'Tabina'),
('Tanducan', 'POBLACION', 'Tabina'),
('Triangle', 'POBLACION', 'Tabina'),
('Uptown', 'POBLACION', 'Tabina'),
('Mars', 'San Francisco', 'Tabina'),
('Uranus', 'San Francisco', 'Tabina'),
('Venus', 'San Francisco', 'Tabina'),
('Amihan', 'Tultolan', 'Tabina'),
('Anahaw', 'Tultolan', 'Tabina'),
('Bugo', 'Tultolan', 'Tabina'),
('Habagat', 'Tultolan', 'Tabina'),
('Lawis', 'Tultolan', 'Tabina'),
('Habagat', 'Tultolan', 'Tabina');

CREATE TABLE emoticons (
  id int NOT NULL,
  code varchar(100) DEFAULT '',
  title varchar(12) DEFAULT '',
  location varchar(100) DEFAULT ''
);

INSERT INTO emoticons (id, code, title, location) VALUES
(1, 'Aa@', 'Smile', '<img src=\"emoticons/smile.png\"/>'),
(2, 'Bb#', 'Kiss', '<img src=\"emoticons/kissing.png\"/>'),
(3, 'Cc$', 'Angry', '<img src=\"emoticons/angry.png\"/>'),
(4, 'Dd', 'Blink', '<img src=\"emoticons/blink.png\"/>'),
(5, 'Ee*', 'Blush', '<img src=\"emoticons/blush.png\"/>'),
(6, 'Ff(', 'Cheer', '<img src=\"emoticons/cheerful.png\"/>'),
(7, 'Gg)', 'Cool', '<img src=\"emoticons/cool.png\"/>'),
(8, 'Hh+', 'Dizzy', '<img src=\"emoticons/dizzy.png\"/>'),
(9, 'Ii-', 'Ermm', '<img src=\"emoticons/ermm.png\"/>'),
(10, 'Jj:', 'Laugh', '<img src=\"emoticons/laughing.png\"/>'),
(11, 'Kk;', 'Love', '<img src=\"emoticons/love.png\"/>'),
(12, 'Ll?', 'Sad', '<img src=\"emoticons/sad.png\"/>'),
(13, 'Mm1', 'Shocked', '<img src=\"emoticons/shocked.png\"/>'),
(14, 'Nn2', 'Sick', '<img src=\"emoticons/sick.png\"/>'),
(15, 'Oo3', 'Sideways', '<img src=\"emoticons/sideways.png\"/>'),
(16, 'Pp4', 'Silly', '<img src=\"emoticons/silly.png\"/>'),
(17, 'Qq5', 'Tongue', '<img src=\"emoticons/tongue.png\"/>'),
(18, 'Rr6', 'Thumbs', '<img src=\"emoticons/thumbs.gif\"/>'),
(19, 'Ss7', 'Unsure', '<img src=\"emoticons/unsure.png\"/>'),
(20, 'Tt8', 'Woohoo', '<img src=\"emoticons/w00t.png\"/>'),
(21, 'Uu9', 'Huh', '<img src=\"emoticons/wassat.png\"/>'),
(22, 'Vv0', 'Whistle', '<img src=\"emoticons/whistling.png\"/>'),
(23, 'Ww=', 'Wink', '<img src=\"emoticons/wink.png\"/>'),
(24, 'Xx.', 'Pinch', '<img src=\"emoticons/pinch.png\"/>'),
(25, 'Yy?', 'Question', '<img src=\"emoticons/question.gif\"/>'),
(26, 'Zz!', 'Exclam', '<img src=\"emoticons/exclam.gif\"/>');

CREATE TABLE employees (
  idn int NOT NULL,
  name_fam varchar(100),
  name_1st varchar(100),
  name_mid varchar(100),
  agency varchar(100),
  department varchar(100),
  app_month varchar(100),
  app_day varchar(100),
  app_year varchar(100),
  position varchar(100),
  address varchar(100),
  barangay varchar(100),
  city_mun varchar(100),
  province varchar(100),
  contact varchar(100),
  emailadd varchar(100),
  sex varchar(100),
  birth_month varchar(100),
  birth_day varchar(100),
  birth_year varchar(100),
  pagibig varchar(100),
  philhealth varchar(100),
  gsis varchar(100),
  tin varchar (50),
  contactperson varchar(100),
  relationship varchar(100),
  emergencyno varchar(50),
  ispicset int DEFAULT '0'
);

CREATE TABLE hh_members (
  hmid int NOT NULL,
  hm_belong varchar(50),
  address varchar(50),
  barangay varchar(50),
  city_mun varchar(50),
  hm_name varchar(50),
  hm_sex varchar(50),
  hm_birth varchar(50),
  hm_education varchar(50),
  hm_enrolled varchar(50),
  hm_main_income varchar(50),
  hm_second_income varchar(50),
  hm_estimated_income varchar(50),
  hm_social varchar(50),
  hm_remarks varchar(50),
  ispicset int DEFAULT NULL
);

CREATE TABLE households (
  hhid int NOT NULL,
  address varchar(50),
  barangay varchar(50),
  city_mun varchar(50),
  province varchar(50),
  hh_name varchar(50),
  hh_occupation varchar(50),
  hh_sex varchar(50),
  hh_birth varchar(50),
  hh_religion varchar(50),
  hh_ethnicity varchar(50),
  hh_contact varchar(50),
  remarks varchar(50),
  hh_members varchar(50),
  toilet_have varchar(50),
  water_access varchar(50),
  mem_death varchar(50),
  death_cause_if_yes varchar(50),
  mem_hospitalize varchar(50),
  illness_if_yes varchar(50),
  mem_crime_victim varchar(50),
  what_crime_if_yes varchar(50),
  settlement varchar(50),
  house_type varchar(50),
  access_hospital varchar(50),
  access_hospital_distance varchar(50),
  access_school varchar(50),
  access_school_distance varchar(50),
  access_church varchar(50),
  access_church_distance varchar(50),
  access_recreation varchar(50),
  access_recreation_distance varchar(50),
  swm_reuse varchar(50),
  swm_reduce varchar(50),
  swm_recycling varchar(50),
  swm_composting varchar(50),
  swm_waste_to_mrf varchar(50),
  swm_remarks varchar(50),
  enumerator varchar(50),
  verifier varchar(50),
  date_verified varchar(50),
  ispicset int DEFAULT NULL
);

CREATE TABLE indigents (
  idn int NOT NULL,
  fullname varchar(50),
  barangay varchar(50),
  city_mun varchar(50),
  period varchar(50),
  amount varchar(50),
  signature varchar(50),
  date_paid varchar(50),
  remarks varchar(50),
  ispicset int DEFAULT NULL
);

CREATE TABLE kinder (
  idn int NOT NULL,
  name_1st varchar(100),
  name_mid varchar(100),
  name_fam varchar(100),
  address varchar(100),
  barangay varchar(100),
  city_mun varchar(100),
  province varchar(100),
  birth_month varchar(100),
  birth_day varchar(100),
  birth_year varchar(100),
  sex varchar(100),
  parent varchar(100),
  contact varchar(100),
  ispicset int DEFAULT '0'
);

CREATE TABLE message_board (
  mbid int NOT NULL AUTO_INCREMENT,
  msgb_from varchar(100),
  msgb_email varchar(100),
  msgb_phone varchar(100),
  msgb_adress varchar(100),
  msgb_attnto varchar(100),
  msgb_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  msgb_content varchar(100),
  ispicset int DEFAULT '0',
  PRIMARY KEY (`mbid`)
);

CREATE TABLE messages (
  idn int NOT NULL,
  msg_from varchar(100),
  msg_office varchar(100),
  msg_to varchar(100),
  msg_attn varchar(100),
  msg_month varchar(100),
  msg_day varchar(100),
  msg_year varchar(100),
  msg_content varchar(100),
  confirm_month varchar(100),
  confirm_day varchar(100),
  confirm_year varchar(100),
  contact_person varchar(100),
  contact_number varchar(100),
  contact_email varchar(100),
  contact_postal varchar(100),
  ispicset int DEFAULT '0'
);

INSERT INTO messages (idn, msg_from, msg_office, msg_to, msg_attn, msg_month, msg_day, msg_year, msg_content, confirm_month, confirm_day, confirm_year, contact_person, contact_number, contact_email, contact_postal, ispicset) VALUES
(1, 'Provincial Governor', 'PGO', 'Municipal Mayor', 'MITC', '4', '4', '2018', 'MITC Provincial Governor Municipal Mayor', '2', '4', '2017', 'JC Maata', '09776848642', 'weeqw@qweqwe.com', 'Tabina, ZDS', 0);

CREATE TABLE msgout (
  idn int NOT NULL,
  msg_from varchar(100),
  msg_office varchar(100),
  msg_to varchar(100),
  msg_attn varchar(100),
  msg_month varchar(100),
  msg_day varchar(100),
  msg_year varchar(100),
  msg_content varchar(100),
  confirm_month varchar(100),
  confirm_day varchar(100),
  confirm_year varchar(100),
  contact_person varchar(100),
  contact_number varchar(100),
  contact_email varchar(100),
  contact_postal varchar(100),
  ispicset int DEFAULT '0'
);

INSERT INTO msgout (idn, msg_from, msg_office, msg_to, msg_attn, msg_month, msg_day, msg_year, msg_content, confirm_month, confirm_day, confirm_year, contact_person, contact_number, contact_email, contact_postal, ispicset) VALUES
(1, 'Provincial Governor', 'PGO', 'Municipal Mayor', 'MITC', '2', '3', '2018', 'Provincial Governor PGO Municipal Mayor MITC', '3', '4', '2018', 'JC Maata', '09776848642', 'weeqw@qweqwe.com', 'Tabina, ZDS', 0);

CREATE TABLE offices (
  id int NOT NULL,
  ofcode varchar(100),
  ofname varchar(100)
);

INSERT INTO offices (id, ofcode, ofname) VALUES
(1, 'BLGU', 'Barangay Local Government Unit'),
(2, 'HRMD', 'Human Resource Management Division'),
(3, 'ICUO', 'Internal Control Unit'),
(4, 'LCEO', 'Local Chief Executive Office'),
(5, 'MACD', 'Mun. Accounting Division'),
(6, 'MADO', 'Municipal Administrator Office'),
(7, 'MAFD', 'Mun. Agri-Fishery Division'),
(8, 'MASD', 'Mun. Assessment Services Division'),
(9, 'MBDO', 'Mun. Budget Division'),
(10, 'MCRD', 'Mun. Civil Registry Division'),
(11, 'MDRM', 'Risk Reduction Management Office'),
(12, 'MEDO', 'Mun. Engineering Division'),
(13, 'MERO', 'Environment & Natural Resources Office'),
(14, 'MESD', 'Mun. Executive Services Division'),
(15, 'MEXD', 'Mun. Extension Services Division'),
(16, 'MHSD', 'Mun. Health Services Division'),
(17, 'MPDO', 'Mun. Planning Division'),
(18, 'MPEE', 'Mun. Public Economic Enterprise'),
(19, 'MSSD', 'Mun. Social Services Division'),
(20, 'MITC', 'Mun. Info. Tech. & Comm. System'),
(21, 'MTDO', 'Mun. Treasury Division Office'),
(22, 'MTRO', 'Municipal Tourism Office'),
(23, 'PESO', 'Public Employment Services Office'),
(24, 'SBMO', 'Sangguniang Bayan Office'),
(25, 'SPSO', 'Sangguniang Panlalawigan'),
(26, 'SSFO', 'Social Services Facilitator Office'),
(27, 'DPHW', 'DPWH-Guipos'),
(28, 'LBOP', 'Land Bank of the Philippines'),
(29, 'DZDS', 'DEPED, Region IX, ZDS Division'),
(30, 'CONS', 'LGU-Tabina On-Going-Projects');

CREATE TABLE permit_business (
  idn int NOT NULL,
  is_mode varchar(100),
  is_month varchar(100),
  is_day varchar(100),
  is_year varchar(100),
  tradename varchar(100),
  address varchar(100),
  barangay varchar(100),
  city_mun varchar(100),
  province varchar(100),
  name_1st varchar(100),
  name_mid varchar(100),
  name_fam varchar(100),
  activity varchar(100),
  isorno varchar(100),
  isormonth varchar(100),
  isorday varchar(100),
  isoryear varchar(100),
  oramount varchar(100),
  ispicset int DEFAULT '0'
);

CREATE TABLE permit_operate (
  idn int NOT NULL,
  is_mode varchar(100),
  is_month varchar(100),
  is_day varchar(100),
  is_year varchar(100),
  tradename varchar(100),
  address varchar(100),
  barangay varchar(100),
  city_mun varchar(100),
  province varchar(100),
  name_1st varchar(100),
  name_mid varchar(100),
  name_fam varchar(100),
  activity varchar(100),
  isorno varchar(100),
  isormonth varchar(100),
  isorday varchar(100),
  isoryear varchar(100),
  oramount varchar(100),
  ispicset int DEFAULT '0'
);

DROP TABLE IF EXISTS positions;
CREATE TABLE positions (
  id int NOT NULL,
  pscode varchar(100),
  psname varchar(100)
);

INSERT INTO positions (id, pscode, psname) VALUES
(0, 'AASS', 'Administrative Assistant'),
(0, 'AAS1', 'Administrative Assistant I'),
(0, 'AAS2', 'Administrative Assistant II'),
(0, 'AAS3', 'Administrative Assistant III'),
(0, 'AAS4', 'Administrative Assistant IV'),
(0, 'AAS5', 'Administrative Assistant V'),
(0, 'AAS6', 'Administrative Assistant VI'),
(0, 'ABCP', 'ABC Federated President'),
(0, 'ACC1', 'Accountant I'),
(0, 'ADA2', 'Administrative Aide II'),
(0, 'ADA3', 'Administrative Aide III'),
(0, 'ADA4', 'Administrative Aide IV'),
(0, 'ADA5', 'Administrative Aide V'),
(0, 'ADA6', 'Administrative Aide VI'),
(0, 'ADO1', 'Administrative Officer I'),
(0, 'ADO2', 'Administrative Officer II'),
(0, 'ADO3', 'Administrative Officer III'),
(0, 'ADO4', 'Administrative Officer IV'),
(0, 'ADO5', 'Administrative Officer V'),
(0, 'AGC2', 'Agriculturist II'),
(0, 'AGC3', 'Agriculturist III'),
(0, 'AKP0', 'Animal Keeper'),
(0, 'ASC1', 'Assessment Clerk I'),
(0, 'BCAP', 'Barangay Captain'),
(0, 'BKAG', 'Barangay Kagawad'),
(0, 'BRKP', 'Barangay Record Keeper'),
(0, 'BSEC', 'Barangay Secretary'),
(0, 'BSKC', 'Barangay SK Chairman'),
(0, 'BTRS', 'Barangay Treasurer'),
(0, 'BTAN', 'Barangay Tanod'),
(0, 'BBD2', 'Book Binder II'),
(0, 'CAMF', 'Const. & Maintenance Foreman'),
(0, 'CAO3', 'Community Affairs Officer III'),
(0, 'CAO4', 'Community Affairs Officer IV'),
(0, 'CAS2', 'Confidential Assistant II'),
(0, 'COM1', 'Computer Operator I'),
(0, 'COM2', 'Computer Operator II'),
(0, 'COM3', 'Computer Operator III'),
(0, 'CSCL', 'Casual/Clerk'),
(0, 'DPM1', 'Deputy Mayor'),
(0, 'DPM2', 'Senior Deputy Mayor'),
(0, 'DPM3', 'Most Senior Deputy Mayor'),
(0, 'DRV2', 'Driver II'),
(0, 'DCW1', 'Daycare Worker I'),
(0, 'EXA2', 'Executive Assistant II'),
(0, 'EXA4', 'Executive Assistant V'),
(0, 'ENG1', 'Engineer I'),
(0, 'ENG2', 'Engineer II'),
(0, 'ENG3', 'Engineer III'),
(0, 'ENG4', 'Engineer IV'),
(0, 'FFMN', 'Farm Foreman'),
(0, 'HEO1', 'Heavy Equipment Operator 1'),
(0, 'IPRP', 'Indigenous People Representative'),
(0, 'COST', 'Chief of Staff'),
(0, 'JOBS', 'Job Order'),
(0, 'LAO2', 'Local Assessment Operation Officer II'),
(0, 'LAO4', 'Local Assessment Operation Officer IV'),
(0, 'LCEO', 'Local Chief Executive/Municipal Mayor'),
(0, 'LRM1', 'LDRRMO I (Admin & Training)'),
(0, 'LRM1', 'LDRRMO I (Research & Planning)'),
(0, 'LRM1', 'LDRRMO I (Operation & Warning)'),
(0, 'LRM2', 'LDRRMO II (MDRRMO)'),
(0, 'LEXA', 'Local Executive Assistant'),
(0, 'LIC2', 'License Inspector II'),
(0, 'LRC2', 'Local Revenue Collection Officer II'),
(0, 'LLSO', 'Local Legislative Staff Officer'),
(0, 'LTO4', 'Local Treasury Operation Officer IV'),
(0, 'LTO2', 'Local Treasury Operation Officer II'),
(0, 'MACC', 'Municipal Accountant'),
(0, 'MCLM', 'Mun. Consultant on Legislative Matters'),
(0, 'MADM', 'Municipal Administrator'),
(0, 'MAGR', 'Municipal Agriculturist'),
(0, 'MASS', 'Municipal Assessor'),
(0, 'MBDO', 'Municipal Budget Officer'),
(0, 'MCRO', 'Municipal Civil Registrar'),
(0, 'MENG', 'Municipal Engineer'),
(0, 'MGDH', 'Mun. Govt. Department Head I'),
(0, 'MHO1', 'Municipal Health Officer'),
(0, 'MPDC', 'Mun. Planning and Devt. Coordinator'),
(0, 'MSEC', 'Municipal Secretary'),
(0, 'SWDO', 'Mun. Social Welfare & Dev. Officer I'),
(0, 'MTRS', 'Municipal Treasurer'),
(0, 'MVCM', 'Municipal Vice Mayor'),
(0, 'NAT1', 'Nursing Attendant I'),
(0, 'PDAS', 'Project Development Assistant'),
(0, 'PDVR', 'Personal Driver'),
(0, 'PLO1', 'Planning Officer I'),
(0, 'PLO2', 'Planning Officer II'),
(0, 'PLO4', 'Planning Officer IV'),
(0, 'PSC2', 'Private Secretary II'),
(0, 'RCC1', 'Reveue Collection Clerk I'),
(0, 'RCC2', 'Reveue Collection Clerk II'),
(0, 'RCC3', 'Reveue Collection Clerk III'),
(0, 'RNS5', 'Rural Nurse V'),
(0, 'RNS4', 'Rural Nurse IV'),
(0, 'RNS3', 'Rural Nurse III'),
(0, 'RNS2', 'Rural Nurse II'),
(0, 'RNS1', 'Rural Nurse I'),
(0, 'RMW5', 'Rural Midwife V'),
(0, 'RMW4', 'Rural Midwife IV'),
(0, 'RMW1', 'Rural Midwife I'),
(0, 'SAA1', 'Senior Administrative Assistant I'),
(0, 'SAA2', 'Senior Administrative Assistant II'),
(0, 'SPAE', 'Special Alter Ego'),
(0, 'SPAG', 'Supervising Agriculturist'),
(0, 'SAO4', 'Supervising Admin. Officer IV'),
(0, 'SBMO', 'Sangguniang Bayan Member'),
(0, 'SKFP', 'SK Federated President'),
(0, 'SPJC', 'Supervising Project Coordinator'),
(0, 'SWO3', 'Social Welfare Officer III'),
(0, 'SWO2', 'Social Welfare Officer II'),
(0, 'SWO1', 'Social Welfare Officer I'),
(0, 'TORO', 'Municipal Tourism Officer'),
(0, 'TOR1', 'Tourist Receiptionist I'),
(0, 'LTA0', 'Legislative Technical Aide'),
(0, '4PML', '4Ps Municipal Link'),
(0, 'BSKT', 'Barangay SK Treasurer'),
(0, 'BHW1', 'Barangay Health Worker I'),
(0, 'CFA1', 'Community Facilitator Assistant I'),
(0, 'DPRM', 'DPWH Road Maintenance'),
(0, 'ICTO', 'Info & Comm Technology Officer'),
(0, 'ICTA', 'Info & Comm Technology Assistant'),
(0, 'CABT', 'Costumer Associate-Bank Teller'),
(0, 'MPLG', 'Municipal Para-Legal Group'),
(0, 'MACM', 'Mun. Advisory Council (MAC) Member'),
(0, 'PSTR', 'Public School Teacher'),
(0, 'BSKK', 'Barangay SK Kagawad'),
(0, 'WORK', 'Construction Worker'),
(0, 'PENG', 'Project Engineer'),
(0, 'PWDS', 'PWD Beneficiary'),
(0, 'PEEO', 'MPEEO Manager');

CREATE TABLE pwd (
  idn int NOT NULL,
  name_1st varchar(100),
  name_mid varchar(100),
  name_fam varchar(100),
  sex varchar(100),
  address varchar(100),
  barangay varchar(100),
  city_mun varchar(100),
  province varchar(100),
  civilstatus varchar(100),
  birth_month varchar(100),
  birth_day varchar(100),
  birth_year varchar(100),
  birth_place varchar(100),
  emailadd varchar(100),
  mobileno varchar(100),
  association varchar(100),
  position varchar(100),
  education varchar(100),
  occupation varchar(100),
  assoc_id_no varchar(100),
  assoc_reg_month varchar(100),
  assoc_reg_day varchar(100),
  assoc_reg_year varchar(100),
  contactperson varchar(100),
  relationship varchar(100),
  emergencyno varchar(100),
  interviewer varchar(100),
  inter_month varchar(100),
  inter_day varchar(100),
  inter_year varchar(100),
  ispicset int DEFAULT '0'
);

INSERT INTO pwd (idn, name_1st, name_mid, name_fam, sex, address, barangay, city_mun, province, civilstatus, birth_month, birth_day, birth_year, birth_place, emailadd, mobileno, association, position, education, occupation, assoc_id_no, assoc_reg_month, assoc_reg_day, assoc_reg_year, contactperson, relationship, emergencyno, interviewer, inter_month, inter_day, inter_year, ispicset) VALUES
(1, 'Antonio', 'D', 'Maata', 'Female', 'Arbor', 'MALIM', 'Tabina', 'ZDS', 'Separated', '4', '6', '1927', 'Siquijor', '', '', 'Malim Senior Citizen Association', 'President', 'High School', 'Farmer', '03478', '4', '10', '2017', 'Phenina C. Maata', 'Mother', '09773678265', 'Sarah Jane Cabante', '3', '17', '2017', 1);

CREATE TABLE reg_fishing (
  idn int NOT NULL,
  regtype varchar(100),
  is_month varchar(100),
  is_day varchar(100),
  is_year varchar(100),
  name_1st varchar(100),
  name_mid varchar(100),
  name_fam varchar(100),
  address varchar(100),
  barangay varchar(100),
  city_mun varchar(100),
  province varchar(100),
  homeport varchar(100),
  tradename varchar(100),
  builder varchar(100),
  build_place varchar(100),
  build_year varchar(100),
  build_hull varchar(100),
  former_owner varchar(100),
  former_vname varchar(100),
  fvtype varchar(100),
  fvcolor varchar(100),
  service_type varchar(100),
  description varchar(100),
  lenght varchar(100),
  breadth varchar(100),
  depth varchar(100),
  grosston varchar(100),
  netton varchar(100),
  enginemake varchar(100),
  enginesn varchar(100),
  enginehp varchar(100),
  engcylinder int NOT NULL,
  engineno varchar(100),
  crewno varchar(100),
  coastgno varchar(100),
  gearused varchar(100),
  isorno int NOT NULL,
  isormonth varchar(100),
  isorday varchar(100),
  isoryear varchar(100),
  oramount int NOT NULL,
  ispicset int DEFAULT '0'
);

CREATE TABLE sap_ben (
  idn int NOT NULL,
  name_fam varchar(100),
  name_1st varchar(100),
  name_mid varchar(100),
  name_ext varchar(100),
  sap_form varchar(100),
  barangay varchar(100),
  city_mun varchar(100),
  period varchar(100),
  amount varchar(100),
  signature varchar(100),
  date_paid varchar(100),
  remarks varchar(100),
  ispicset int DEFAULT NULL
);

CREATE TABLE sc_fam_composition (
  idn int NOT NULL,
  sc_control_no varchar(100),
  sc_fam_name varchar(100),
  sc_1st_name varchar(100),
  sc_mid_name varchar(100),
  relationship varchar(100),
  age varchar(50),
  fc_civilstatus varchar(100),
  fc_occupation varchar(100)
);

DROP TABLE IF EXISTS senior;
CREATE TABLE senior (
  idn int;
  name_1st varchar(100),
  name_mid varchar(100),
  name_fam varchar(100),
  sex varchar(100),
  address varchar(100),
  barangay varchar(100),
  city_mun varchar(100),
  province varchar(100),
  civilstatus varchar(100),
  birth_date varchar(100),
  birth_place varchar(100),
  emailadd varchar(100),
  mobileno varchar(100),
  association varchar(100),
  position varchar(100),
  education varchar(100),
  occupation varchar(100),
  assoc_id_no varchar(100),
  assoc_reg_date varchar(100),
  contactperson varchar(100),
  relationship varchar(100),
  emergencyno varchar(100),
  interviewer varchar(100),
  inter_date varchar(100),
  ispicset int DEFAULT '0'
);

ALTER TABLE senior
  MODIFY idn int NOT NULL AUTO_INCREMENT;

ALTER TABLE senior
  ADD PRIMARY KEY (idn);


CREATE TABLE session (
  user varchar(100) DEFAULT '',
  time varchar(100) DEFAULT '',
  date varchar(100) DEFAULT '',
  sid varchar(100) DEFAULT '0',
  ipc varchar(100) DEFAULT '0',
  guest int DEFAULT '1',
  gid int NOT NULL
);

CREATE TABLE solo_parent (
  idn int NOT NULL,
  name_1st varchar(100),
  name_mid varchar(100),
  name_fam varchar(100),
  sex varchar(100),
  address varchar(100),
  barangay varchar(100),
  city_mun varchar(100),
  province varchar(100),
  civilstatus varchar(100),
  birth_month varchar(100),
  birth_day varchar(100),
  birth_year varchar(100),
  birth_place varchar(100),
  emailadd varchar(100),
  mobileno varchar(100),
  association varchar(100),
  position varchar(100),
  education varchar(100),
  occupation varchar(100),
  assoc_id_no varchar(100),
  assoc_reg_month varchar(100),
  assoc_reg_day varchar(100),
  assoc_reg_year varchar(100),
  contactperson varchar(100),
  relationship varchar(100),
  emergencyno varchar(100),
  interviewer varchar(100),
  inter_month varchar(100),
  inter_day varchar(100),
  inter_year varchar(100),
  ispicset int DEFAULT '0'
);

INSERT INTO solo_parent (idn, name_1st, name_mid, name_fam, sex, address, barangay, city_mun, province, civilstatus, birth_month, birth_day, birth_year, birth_place, emailadd, mobileno, association, position, education, occupation, assoc_id_no, assoc_reg_month, assoc_reg_day, assoc_reg_year, contactperson, relationship, emergencyno, interviewer, inter_month, inter_day, inter_year, ispicset) VALUES
(1, 'Antonio', 'D', 'Maata', 'Female', 'Arbor', 'MALIM', 'Tabina', 'ZDS', 'Separated', '4', '7', '1926', 'Siquijor', '', '', 'Malim Senior Citizen Association', 'Secretary', 'High School', 'Farmer', '03478', '5', '13', '2017', 'Phenina C. Maata', 'Brother', '09773678265', 'Sarah Jane Cabante', '3', '17', '2019', 1);

DROP TABLE IF EXISTS users;
CREATE TABLE users (
  uno INT AUTO_INCREMENT PRIMARY KEY,
  fullname VARCHAR(50) NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  password CHAR(60) NOT NULL, -- for password_hash()
  access VARCHAR(50) NOT NULL,
  birth DATE DEFAULT NULL,
  email VARCHAR(100) DEFAULT NULL,
  phone VARCHAR(20) DEFAULT NULL,
  purok VARCHAR(50) DEFAULT NULL,
  barangay VARCHAR(50) DEFAULT NULL,
  city_mun VARCHAR(50) DEFAULT NULL,
  imgUrl TINYINT(1) DEFAULT 0
);

INSERT INTO users (fullname, username, password, access, birth, email, phone, purok, barangay, city_mun, imgUrl) VALUES
('McJim Maata', 'McJim', 'McJim654123', 'Administrator', NULL, NULL, NULL, NULL, NULL, NULL, 0),
('Rolly Joy Fernandez', 'rjxmyth', 'R3str1ct3d', 'Administrator', NULL, NULL, NULL, NULL, NULL, NULL, 0),
('Jelly Arcadio Impal', 'Jelly', 'Arcadio', 'Executive', NULL, NULL, NULL, NULL, NULL, NULL, 0),
('Romulo V Lumo', 'Romulus', 'Romulus', 'Senior', NULL, NULL, NULL, NULL, NULL, NULL, 0),
('Sarah Jane Cabante', 'Social', 'Welfare', 'Welfare', NULL, NULL, NULL, NULL, NULL, NULL, 0);

DROP TABLE IF EXISTS validity;

CREATE TABLE validity (
  validity date DEFAULT NULL
);

INSERT INTO validity (validity) VALUES
('2026-06-20');

CREATE TABLE visitors (
  idn int NOT NULL,
  name_fam varchar(100),
  name_1st varchar(100),
  name_mid varchar(100),
  sex varchar(100),
  position varchar(100),
  station varchar(100),
  office varchar(100),
  address varchar(100),
  contact varchar(100),
  emailadd varchar(100),
  visit_month varchar(100),
  visit_day_from varchar(100),
  visit_day_to varchar(100),
  visit_year varchar(100),
  visit_purpose varchar(100),
  ispicset int DEFAULT '0'
);


ALTER TABLE cert_indigency
  ADD PRIMARY KEY (idn);

ALTER TABLE chat_room
  ADD PRIMARY KEY (id);

ALTER TABLE clearances
  ADD PRIMARY KEY (idn);

ALTER TABLE countdown
  ADD PRIMARY KEY (id);

ALTER TABLE emoticons
  ADD PRIMARY KEY (id);

ALTER TABLE employees
  ADD PRIMARY KEY (idn);

ALTER TABLE hh_members
  ADD PRIMARY KEY (hmid);

ALTER TABLE households
  ADD PRIMARY KEY (hhid);

ALTER TABLE indigents
  ADD PRIMARY KEY (idn);

ALTER TABLE kinder
  ADD PRIMARY KEY (idn);

ALTER TABLE messages
  ADD PRIMARY KEY (idn);

ALTER TABLE msgout
  ADD PRIMARY KEY (idn);

ALTER TABLE offices
  ADD PRIMARY KEY (id);

ALTER TABLE permit_business
  ADD PRIMARY KEY (idn),
  ADD UNIQUE KEY idn (idn);

ALTER TABLE permit_operate
  ADD PRIMARY KEY (idn);

ALTER TABLE positions
  ADD PRIMARY KEY (id);

ALTER TABLE pwd
  ADD PRIMARY KEY (idn);

ALTER TABLE reg_fishing
  ADD PRIMARY KEY (idn);

ALTER TABLE sap_ben
  ADD PRIMARY KEY (idn);

ALTER TABLE sc_fam_composition
  ADD PRIMARY KEY (idn);

ALTER TABLE senior
  ADD PRIMARY KEY (idn);

ALTER TABLE session
  ADD PRIMARY KEY (gid),
  ADD UNIQUE KEY sid (sid),
  ADD KEY whosonline (guest,user);

ALTER TABLE solo_parent
  ADD PRIMARY KEY (idn);

ALTER TABLE users
ADD PRIMARY KEY (uno),
  ADD UNIQUE KEY username (username);

ALTER TABLE visitors
  ADD PRIMARY KEY (idn);

ALTER TABLE cert_indigency
  MODIFY idn int NOT NULL AUTO_INCREMENT;

ALTER TABLE clearances
  MODIFY idn int NOT NULL AUTO_INCREMENT;

ALTER TABLE countdown
  MODIFY id int NOT NULL AUTO_INCREMENT;

ALTER TABLE emoticons
  MODIFY id int NOT NULL AUTO_INCREMENT;

ALTER TABLE employees
  MODIFY idn int NOT NULL AUTO_INCREMENT;

ALTER TABLE hh_members
  MODIFY hmid int NOT NULL AUTO_INCREMENT;

ALTER TABLE households
  MODIFY hhid int NOT NULL AUTO_INCREMENT;

ALTER TABLE indigents
  MODIFY idn int NOT NULL AUTO_INCREMENT;

ALTER TABLE kinder
  MODIFY idn int NOT NULL AUTO_INCREMENT;

ALTER TABLE messages
  MODIFY idn int NOT NULL AUTO_INCREMENT;

ALTER TABLE msgout
  MODIFY idn int NOT NULL AUTO_INCREMENT;

ALTER TABLE offices
  MODIFY id int NOT NULL AUTO_INCREMENT;

ALTER TABLE permit_business
  MODIFY idn int NOT NULL AUTO_INCREMENT;

ALTER TABLE permit_operate
  MODIFY idn int NOT NULL AUTO_INCREMENT;

ALTER TABLE positions
  MODIFY id int NOT NULL AUTO_INCREMENT;

ALTER TABLE pwd
  MODIFY idn int NOT NULL AUTO_INCREMENT;

ALTER TABLE reg_fishing
  MODIFY idn int NOT NULL AUTO_INCREMENT;

ALTER TABLE sap_ben
  MODIFY idn int NOT NULL AUTO_INCREMENT;

ALTER TABLE sc_fam_composition
  MODIFY idn int NOT NULL AUTO_INCREMENT;

ALTER TABLE senior
  MODIFY idn int NOT NULL AUTO_INCREMENT;

ALTER TABLE session
  MODIFY gid int NOT NULL AUTO_INCREMENT;

ALTER TABLE solo_parent
  MODIFY idn int NOT NULL AUTO_INCREMENT;

ALTER TABLE users
  MODIFY uno int NOT NULL AUTO_INCREMENT;

ALTER TABLE visitors
  MODIFY idn int NOT NULL AUTO_INCREMENT;
COMMIT;