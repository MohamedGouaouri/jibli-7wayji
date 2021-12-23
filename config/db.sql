USE vtc;

# Clients schema
CREATE TABLE IF NOT EXISTS clients(
    client_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    family_name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    address VARCHAR(100) NOT NULL
);

# Transporters schema
CREATE TABLE IF NOT EXISTS transporters(
    transporter_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    family_name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    status VARCHAR(20) NOT NULL,
    inventory DOUBLE DEFAULT 0.0
);

# announcements schema
CREATE TABLE IF NOT EXISTS announcements(
    announcement_id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT NOT NULL ,
    start_point VARCHAR(20) NOT NULL ,
    end_point VARCHAR(20) NOT NULL ,
    type VARCHAR(20) NOT NULL ,
    weight DOUBLE NOT NULL ,
    volume DOUBLE NOT NULL ,
    status VARCHAR(20) NOT NULL,
    message TEXT,
    posted_at DATETIME DEFAULT NOW(),
    validated BOOLEAN DEFAULT FALSE,
    price DOUBLE DEFAULT 0.0,
    FOREIGN KEY (client_id) REFERENCES clients(client_id) ON DELETE CASCADE ON UPDATE CASCADE
);
# client demands
CREATE TABLE IF NOT EXISTS client_demands(
    announcement_id INT NOT NULL,
    transporter_id INT NOT NULL,
    PRIMARY KEY (transporter_id, announcement_id),
    FOREIGN KEY (transporter_id) REFERENCES transporters(transporter_id) ON DELETE CASCADE ON UPDATE CASCADE ,
    FOREIGN KEY (announcement_id) REFERENCES announcements(announcement_id) ON DELETE CASCADE ON UPDATE CASCADE
);

# Applications schema
CREATE TABLE IF NOT EXISTS transporter_applications(
    transporter_id INT NOT NULL ,
    announcement_id INT NOT NULL,
    applied_id DATETIME NOT NULL DEFAULT NOW(),
    PRIMARY KEY (transporter_id, announcement_id),
    FOREIGN KEY (transporter_id) REFERENCES transporters(transporter_id) ON DELETE CASCADE ON UPDATE CASCADE ,
    FOREIGN KEY (announcement_id) REFERENCES announcements(announcement_id) ON DELETE CASCADE ON UPDATE CASCADE
);

# transports
CREATE TABLE IF NOT EXISTS transport(
    transport_id INT PRIMARY KEY AUTO_INCREMENT,
    announcement_id INT NOT NULL,
    transporter_id INT NOT NULL,
    validated BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (announcement_id) REFERENCES announcements(announcement_id),
    FOREIGN KEY (transporter_id) REFERENCES transporters(transporter_id)
);

# notes
CREATE TABLE IF NOT EXISTS notes(
    client_id INT NOT NULL ,
    transporter_id INT NOT NULL,
    note SMALLINT,
    PRIMARY KEY (client_id, transporter_id),
    FOREIGN KEY (client_id) REFERENCES clients(client_id),
    FOREIGN KEY (transporter_id) REFERENCES transporters(transporter_id)
);

# Client's signals
CREATE TABLE IF NOT EXISTS client_signals(
    client_id INT,
    transporter_id INT,
    message TEXT,
    PRIMARY KEY (client_id, transporter_id),
    FOREIGN KEY (client_id) REFERENCES clients(client_id),
    FOREIGN KEY (transporter_id) REFERENCES transporters(transporter_id)
);

# Transporter's signals
CREATE TABLE IF NOT EXISTS transporter_signals(
    transporter_id INT,
    client_id INT,
    message TEXT,
    PRIMARY KEY (transporter_id, client_id),
    FOREIGN KEY (transporter_id) REFERENCES transporters(transporter_id),
    FOREIGN KEY (client_id) REFERENCES clients(client_id)
);

# News
CREATE TABLE IF NOT EXISTS news(
    title TEXT,
    content TEXT
);

CREATE TABLE IF NOT EXISTS wilayas(
    wilaya_id INT PRIMARY KEY,
    wilaya_name VARCHAR(10)
);

CREATE TABLE IF NOT EXISTS covered_wilayas(
  transporter_id INT NOT NULL,
  wilaya_id INT NOT NULL ,
  PRIMARY KEY (transporter_id, wilaya_id),
  FOREIGN KEY (transporter_id) REFERENCES transporters(transporter_id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (wilaya_id) REFERENCES wilayas(wilaya_id) ON DELETE CASCADE ON UPDATE CASCADE
);