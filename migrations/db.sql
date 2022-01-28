DROP DATABASE IF EXISTS vtc;
CREATE DATABASE IF NOT EXISTS vtc;
USE vtc;


DROP TABLE IF EXISTS wilayas;
CREATE TABLE IF NOT EXISTS wilayas(
    wilaya_id INT PRIMARY KEY,
    wilaya_name VARCHAR(20)
);
INSERT INTO wilayas VALUES (1, 'Adrar'),
                           (2, 'Chelef'),
                           (3, 'Laghouat'),
                           (4, 'Oum El Bouaghi'),
                           (5, 'Batna'),
                           (6, 'Bejaya'),
                           (7, 'Biskra'),
                           (8, 'Bechar'),
                           (9, 'Blida'),
                           (10, 'Bouira'),
                           (11, 'Tamanrasset'),
                           (12, 'Tebessa'),
                           (13, 'Tlemcen'),
                           (14, 'Tiaret'),
                           (15, 'Tizi Ouzou'),
                           (16, 'Alger'),
                           (17, 'Djelfa'),
                           (18, 'Jijel'),
                           (19, 'Setif'),
                           (20, 'Saida'),
                           (21, 'Skikda'),
                           (22, 'Sidi Bel Abbes'),
                           (23, 'Annaba'),
                           (24, 'Guelma'),
                           (25, 'Constantine'),
                           (26, 'Medea'),
                           (27, 'Mostaganem'),
                           (28, 'Msila'),
                           (29, 'Mascara'),
                           (30, 'Ouargla'),
                           (31, 'Oran'),
                           (32, 'El Bayadh')
                           ;

## Users schema
DROP TABLE IF EXISTS users;
CREATE TABLE IF NOT EXISTS users(
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    family_name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE ,
    phone_number VARCHAR(20) DEFAULT '9999999999',
    password VARCHAR(255) NOT NULL,
    address VARCHAR(100) NOT NULL,
    banned BOOLEAN DEFAULT FALSE
);


# Transporters schema
DROP TABLE IF EXISTS transporters;
CREATE TABLE IF NOT EXISTS transporters(
    transporter_id INT PRIMARY KEY,
    is_certified BOOLEAN DEFAULT FALSE,
    status VARCHAR(20),
    validated BOOLEAN DEFAULT FALSE,
    inventory DOUBLE DEFAULT 0.0,
    FOREIGN KEY transporters(transporter_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS certification_demands;
CREATE TABLE IF NOT EXISTS certification_demands(
  id INT PRIMARY KEY AUTO_INCREMENT,
  transporter_id INT,
  status VARCHAR(20) DEFAULT 'pending',
  demand_date DATETIME DEFAULT NOW(),
  FOREIGN KEY (transporter_id) REFERENCES transporters(transporter_id) ON DELETE CASCADE ON UPDATE CASCADE
);

# announcements schema
DROP TABLE IF EXISTS announcements;
CREATE TABLE IF NOT EXISTS announcements(
    announcement_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL ,
    start_point INT NOT NULL ,
    end_point INT NOT NULL ,
    type VARCHAR(20) NOT NULL ,
    weight DOUBLE NOT NULL ,
    volume DOUBLE NOT NULL ,
    way SMALLINT,
    status VARCHAR(20) NOT NULL,
    message TEXT,
    posted_at DATETIME DEFAULT NOW(),
    validated BOOLEAN DEFAULT FALSE,
    price DOUBLE DEFAULT 100000, # just for testing
    archived BOOLEAN DEFAULT FALSE,
    image_path VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (start_point) REFERENCES wilayas(wilaya_id),
    FOREIGN KEY (end_point) REFERENCES wilayas(wilaya_id)
);

# transports
DROP TABLE IF EXISTS transport;
CREATE TABLE IF NOT EXISTS transport(
    announcement_id INT NOT NULL,
    transporter_id INT NOT NULL,
    validated BOOLEAN DEFAULT FALSE,
    done BOOLEAN DEFAULT FALSE, # indicates whether the transporter has finished his work or not
    PRIMARY KEY (transporter_id, announcement_id),
    FOREIGN KEY (announcement_id) REFERENCES announcements(announcement_id),
    FOREIGN KEY (transporter_id) REFERENCES transporters(transporter_id)
);


# client demands
DROP TABLE IF EXISTS client_demands;
CREATE TABLE IF NOT EXISTS client_demands(
    announcement_id INT NOT NULL,
    transporter_id INT NOT NULL,
    PRIMARY KEY (transporter_id, announcement_id),
    FOREIGN KEY (transporter_id) REFERENCES transporters(transporter_id) ON DELETE CASCADE ON UPDATE CASCADE ,
    FOREIGN KEY (announcement_id) REFERENCES announcements(announcement_id) ON DELETE CASCADE ON UPDATE CASCADE
);


# Applications schema
DROP TABLE IF EXISTS transporter_applications;
CREATE TABLE IF NOT EXISTS transporter_applications(
    transporter_id INT NOT NULL ,
    announcement_id INT NOT NULL,
    applied_at DATETIME NOT NULL DEFAULT NOW(),
    PRIMARY KEY (transporter_id, announcement_id),
    FOREIGN KEY (transporter_id) REFERENCES transporters(transporter_id) ON DELETE CASCADE ON UPDATE CASCADE ,
    FOREIGN KEY (announcement_id) REFERENCES announcements(announcement_id) ON DELETE CASCADE ON UPDATE CASCADE
);


# notes
DROP TABLE IF EXISTS notes;
CREATE TABLE IF NOT EXISTS notes(
    user_id INT NOT NULL ,
    transporter_id INT NOT NULL,
    note SMALLINT,
    message TEXT,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (transporter_id) REFERENCES transporters(transporter_id)
);

# Client's signals
DROP TABLE IF EXISTS client_signals;
CREATE TABLE IF NOT EXISTS client_signals(
    user_id INT,
    transporter_id INT,
    message TEXT,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (transporter_id) REFERENCES transporters(transporter_id)
);

# Transporter's signals
DROP TABLE IF EXISTS transporter_signals;
CREATE TABLE IF NOT EXISTS transporter_signals(
    transporter_id INT,
    user_id INT,
    message TEXT,

    FOREIGN KEY (transporter_id) REFERENCES transporters(transporter_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

# News
DROP TABLE IF EXISTS news;
CREATE TABLE IF NOT EXISTS news(
    id INT PRIMARY KEY AUTO_INCREMENT,
    title TEXT,
    synopsis VARCHAR(20),
    content TEXT,
    paths VARCHAR(255)
);



DROP TABLE IF EXISTS covered_wilayas;
CREATE TABLE IF NOT EXISTS covered_wilayas(
  transporter_id INT NOT NULL,
  wilaya_id INT NOT NULL ,
  PRIMARY KEY (transporter_id, wilaya_id),
  FOREIGN KEY (transporter_id) REFERENCES transporters(transporter_id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (wilaya_id) REFERENCES wilayas(wilaya_id) ON DELETE CASCADE ON UPDATE CASCADE
);

DROP TABLE IF EXISTS prices;
CREATE TABLE IF NOT EXISTS prices(
  start_point INT,
  end_point INT,
  price DOUBLE NOT NULL DEFAULT 1000.0,
  PRIMARY KEY (start_point, end_point),
  FOREIGN KEY (start_point) REFERENCES wilayas(wilaya_id),
  FOREIGN KEY (end_point) REFERENCES wilayas(wilaya_id)
);


CREATE TABLE IF NOT EXISTS types_transport(
  type_id INT PRIMARY KEY AUTO_INCREMENT,
  type_name VARCHAR(20)
);
INSERT INTO types_transport (type_name) VALUES ('voiture'), ('fourgon'), ('camion'), ('avion');


## Get clients view
DROP VIEW IF EXISTS clients;
CREATE VIEW clients AS
SELECT u.user_id as client_id, u.name, u.family_name, u.phone_number ,u.email, u.password, u.address FROM users u WHERE u.user_id NOT IN (SELECT transporter_id FROM transporters) AND banned = FALSE;
# Views
DROP VIEW IF EXISTS announcements_view;
CREATE VIEW announcements_view AS
SELECT R.*, w1.wilaya_name AS start_wilaya_name, w2.wilaya_name AS end_wilaya_name FROM (SELECT a.*, name, family_name, email, password, address  FROM announcements a JOIN users u ON u.user_id = a.user_id WHERE u.banned = FALSE) AS R, wilayas w1, wilayas w2 WHERE R.start_point = w1.wilaya_id AND w2.wilaya_id = R.end_point AND archived = FALSE AND validated = TRUE;

DROP VIEW IF EXISTS transporters_view;
CREATE VIEW transporters_view AS
    SELECT t.*, u.name, u.family_name, u.phone_number, u.email, u.password, u.address, u.banned as banned
    FROM transporters t JOIN users u on t.transporter_id = u.user_id ; ## removed banned = false


DROP VIEW IF EXISTS prices_view;
CREATE VIEW prices_view AS
SELECT p.start_point, w.wilaya_name as start_wilaya_name, p.end_point, w2.wilaya_name AS end_wilaya_name, p.price
FROM prices p
         JOIN wilayas w ON p.start_point = w.wilaya_id
         JOIN wilayas w2 ON p.end_point = w2.wilaya_id;

# Get archived announcements
DROP VIEW IF EXISTS archived_announcements_view;
CREATE VIEW archived_announcements_view AS
SELECT R.*, w1.wilaya_name AS start_wilaya_name, w2.wilaya_name AS end_wilaya_name FROM (SELECT a.*, name, family_name, email, password, address  FROM announcements a JOIN users u ON u.user_id = a.user_id WHERE banned = FALSE) AS R, wilayas w1, wilayas w2 WHERE R.start_point = w1.wilaya_id AND w2.wilaya_id = R.end_point AND archived = TRUE;


# Trajectories view
DROP VIEW IF EXISTS trajectories;
CREATE VIEW trajectories AS
SELECT c1.transporter_id as transporter_id, c1.wilaya_id as start_point, c2.wilaya_id as end_point FROM covered_wilayas as c1,covered_wilayas as c2;

DROP VIEW IF EXISTS all_announcements_view;
CREATE VIEW all_announcements_view AS
SELECT R.*, w1.wilaya_name AS start_wilaya_name, w2.wilaya_name AS end_wilaya_name FROM (SELECT a.*, name, family_name, email, password, address  FROM announcements a JOIN users u ON u.user_id = a.user_id WHERE u.banned = FALSE) AS R, wilayas w1, wilayas w2 WHERE R.start_point = w1.wilaya_id AND w2.wilaya_id = R.end_point;


# Certification demand view
DROP VIEW IF EXISTS certification_demand_view;
CREATE VIEW certification_demand_view AS
SELECT tv.*, d.id as demand_id, d.status as demand_status, d.demand_date FROM certification_demands d JOIN transporters_view tv on tv.transporter_id = d.transporter_id;

# Covered wilayas view
DROP VIEW IF EXISTS covered_wilayas_view;
CREATE VIEW covered_wilayas_view AS
SELECT covered_wilayas.transporter_id, covered_wilayas.wilaya_id, w.wilaya_name FROM covered_wilayas JOIN wilayas w on covered_wilayas.wilaya_id = w.wilaya_id;

DROP VIEW IF EXISTS running_transports_view;
CREATE VIEW running_transports_view AS
SELECT * FROM transport WHERE validated = TRUE AND done = FALSE;


# DROP VIEW IF EXISTS possible_transporters_view;
# CREATE VIEW possible_transporters_view AS
# SELECT DISTINCT transporter_id, announcement_id FROM trajectories t JOIN announcements a ON a.start_point = t.start_point AND a.end_point;


# Ad types

DROP TABLE IF EXISTS announcement_types;
CREATE TABLE IF NOT EXISTS announcement_types (
    id INT PRIMARY KEY ,
    type VARCHAR(50)
);

# Contact table
DROP TABLE IF EXISTS contact;
CREATE TABLE IF NOT EXISTS announcement_types (
   name VARCHAR(50),
   mobile VARCHAR(50),
   address VARCHAR(50)
);

DROP TABLE IF EXISTS diaporama_images;
CREATE TABLE IF NOT EXISTS diaporama_images(
  id INT PRIMARY KEY AUTO_INCREMENT,
  path VARCHAR(255)
);

DROP TABLE IF EXISTS weights;
CREATE TABLE IF NOT EXISTS weights(
  weight DOUBLE,
  description VARCHAR(20),
  unit_price DOUBLE,
  PRIMARY KEY (`weight`, `unit_price`)
);
INSERT INTO weights VALUES (0.1, 'entre 0 et 100 gr', 100),
                           (1, 'entre 100 gr et 1kg', 1000),
                           (5, 'entre 1kg et 5kg', 10000),
                           (10, 'entre 5kg et 10kg', 20000);


DROP VIEW IF EXISTS client_demands_view;
CREATE VIEW client_demands_view AS
SELECT * FROM client_demands WHERE (announcement_id, transporter_id) NOT IN (
    SELECT announcement_id, transporter_id FROM transport WHERE validated = TRUE
);