CREATE TABLE user(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    name VARCHAR(100) NOT NULL,
    crampte INT NOT NULL DEFAULT 100,
    session VARCHAR(10) DEFAULT NULL,
    weight FLOAT NOT NULL DEFAULT 66.0,
    sexe FLOAT NOT NULL DEFAULT 0.6,
    eat TINYINT(1) NOT NULL DEFAULT 0
);

CREATE TABLE statistique(
    user_id INT NOT NULL,
    sum_max_gl FLOAT NOT NULL DEFAULT 0,
    max_gl FLOAT NOT NULL DEFAULT 0,
    sum_alcool_quantity FLOAT NOT NULL DEFAULT 0,
    max_alcool_quantity FLOAT NOT NULL DEFAULT 0,
    sum_pure_alcool_quantity FLOAT NOT NULL DEFAULT 0,
    max_pure_alcool_quantity FLOAT NOT NULL DEFAULT 0,
    sum_glass FLOAT NOT NULL DEFAULT 0,
    max_glass FLOAT NOT NULL DEFAULT 0,
    sum_shot FLOAT NOT NULL DEFAULT 0,
    max_shot FLOAT NOT NULL DEFAULT 0,

    FOREIGN KEY fk_statistique_userId(user_id) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE session(
    id VARCHAR(10) NOT NULL PRIMARY KEY,
    admin INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    private TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL,
    last_update DATETIME NOT NULL,
    deleted TINYINT(1) NOT NULL DEFAULT 0,

    FOREIGN KEY fk_sessions_admin(admin) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE drink(
    session_id VARCHAR(10) NOT NULL,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    number INT NOT NULL,
    size FLOAT NOT NULL,
    alcool_quantity FLOAT NOT NULL,
    alcool_degre FLOAT NOT NULL,
    bottoms_up TINYINT(1) NOT NULL DEFAULT 0,
    drink_at DATETIME NOT NULL,
    deleted TINYINT(1) NOT NULL DEFAULT 0,

    CONSTRAINT pk_drinK PRIMARY KEY (session_id,user_id),

    FOREIGN KEY fk_drink_sessionId(session_id) REFERENCES session(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY fk_drink_userId(user_id) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE item(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(100),
    name VARCHAR(100) DEFAULT NULL,
    html LONGTEXT,
    shop TINYINT(1) NOT NULL DEFAULT 0,
    price INT
);

CREATE TABLE inventory(
    user_id INT NOT NULL,
    item_id INT NOT NULL,
    bought_at INT,

    CONSTRAINT pk_inventory PRIMARY KEY (user_id,item_id),

    FOREIGN KEY fk_inventory_userId(user_id) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY fk_inventory_itemId(item_id) REFERENCES item(id) ON DELETE CASCADE ON UPDATE CASCADE
);