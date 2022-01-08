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
                           (5, 'Batna');

## Users schema
DROP TABLE IF EXISTS users;
CREATE TABLE IF NOT EXISTS users(
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    family_name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE ,
    phone_number VARCHAR(20) DEFAULT '9999999999',
    password VARCHAR(255) NOT NULL,
    address VARCHAR(100) NOT NULL
);


# Clients schema
DROP TABLE IF EXISTS clients;
# CREATE TABLE IF NOT EXISTS clients(
#     client_id INT PRIMARY KEY AUTO_INCREMENT,
#     name VARCHAR(50) NOT NULL,
#     family_name VARCHAR(50) NOT NULL,
#     email VARCHAR(50) NOT NULL UNIQUE ,
#     password VARCHAR(255) NOT NULL,
#     address VARCHAR(100) NOT NULL
# );
INSERT INTO users (name, family_name, email, password, address) VALUES
                ('A', 'B', 'A1@esi.dz', PASSWORD('password'), 'address'),
                ('A', 'B', 'A2@esi.dz', PASSWORD('password'), 'address'),
                ('A', 'B', 'A3@esi.dz', PASSWORD('password'), 'address'),
                ('A', 'B', 'A4@esi.dz', PASSWORD('password'), 'address')
                ;

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
    status VARCHAR(20) NOT NULL,
    message TEXT,
    posted_at DATETIME DEFAULT NOW(),
    validated BOOLEAN DEFAULT FALSE,
    price DOUBLE DEFAULT 0.0,
    archived BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (start_point) REFERENCES wilayas(wilaya_id),
    FOREIGN KEY (end_point) REFERENCES wilayas(wilaya_id)
);
INSERT INTO announcements (user_id, start_point, end_point, type, weight, volume, status, message)
    VALUES (1, 1, 1, 'TYPE', 1000, 200, 'approved', 'Nothing'),
           (1, 2, 1, 'TYPE', 1000, 200, 'approved', 'Nothing'),
           (2, 1, 3, 'TYPE', 1000, 200, 'approved', 'Nothing'),
           (2, 3, 1, 'TYPE', 1000, 200, 'approved', 'Nothing'),
           (3, 4, 1, 'TYPE', 1000, 200, 'approved', 'Nothing'),
           (3, 2, 2, 'TYPE', 1000, 200, 'approved', 'Nothing'),
           (2, 1, 3, 'TYPE', 1000, 200, 'approved', 'Nothing'),
           (1, 4, 2, 'TYPE', 1000, 200, 'approved', 'Nothing')
    ;

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

DROP TABLE IF EXISTS transport_archive;
CREATE TABLE IF NOT EXISTS transport_archive(
    announcement_id INT NOT NULL,
    transporter_id INT NOT NULL,
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
    PRIMARY KEY (user_id, transporter_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (transporter_id) REFERENCES transporters(transporter_id)
);

# Client's signals
DROP TABLE IF EXISTS client_signals;
CREATE TABLE IF NOT EXISTS client_signals(
    user_id INT,
    transporter_id INT,
    message TEXT,
    PRIMARY KEY (user_id, transporter_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (transporter_id) REFERENCES transporters(transporter_id)
);

# Transporter's signals
DROP TABLE IF EXISTS transporter_signals;
CREATE TABLE IF NOT EXISTS transporter_signals(
    transporter_id INT,
    user_id INT,
    message TEXT,
    PRIMARY KEY (transporter_id, user_id),
    FOREIGN KEY (transporter_id) REFERENCES transporters(transporter_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

# News
DROP TABLE IF EXISTS news;
CREATE TABLE IF NOT EXISTS news(
    title TEXT,
    content TEXT
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
SELECT u.user_id as client_id, u.name, u.family_name, u.email, u.password, u.address FROM users u WHERE u.user_id NOT IN (SELECT transporter_id FROM transporters);
# Views
DROP VIEW IF EXISTS announcements_view;
CREATE VIEW announcements_view AS
SELECT R.*, w1.wilaya_name AS start_wilaya_name, w2.wilaya_name AS end_wilaya_name FROM (SELECT a.*, name, family_name, email, password, address  FROM announcements a JOIN users u ON u.user_id = a.user_id) AS R, wilayas w1, wilayas w2 WHERE R.start_point = w1.wilaya_id AND w2.wilaya_id = R.end_point AND archived = FALSE;

DROP VIEW IF EXISTS transporters_view;
CREATE VIEW transporters_view AS
    SELECT t.*, u.name, u.family_name, u.phone_number, u.email, u.password, u.address
    FROM transporters t JOIN users u on t.transporter_id = u.user_id;


DROP VIEW IF EXISTS prices_view;
CREATE VIEW prices_view AS
SELECT p.start_point, w.wilaya_name as start_wilaya_name, p.end_point, w2.wilaya_name AS end_wilaya_name, p.price
FROM prices p
         JOIN wilayas w ON p.start_point = w.wilaya_id
         JOIN wilayas w2 ON p.start_point = w2.wilaya_id;

# Get archived announcements
DROP VIEW IF EXISTS archived_announcements_view;
CREATE VIEW archived_announcements_view AS
SELECT R.*, w1.wilaya_name AS start_wilaya_name, w2.wilaya_name AS end_wilaya_name FROM (SELECT a.*, name, family_name, email, password, address  FROM announcements a JOIN users u ON u.user_id = a.user_id) AS R, wilayas w1, wilayas w2 WHERE R.start_point = w1.wilaya_id AND w2.wilaya_id = R.end_point AND archived = TRUE;


# Trajectories view
CREATE VIEW trajectories AS
SELECT c1.transporter_id as transporter_id, c1.wilaya_id as start_point, c2.wilaya_id as end_point FROM covered_wilayas as c1,covered_wilayas as c2;