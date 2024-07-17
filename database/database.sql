CREATE TABLE user(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    name VARCHAR(100) NOT NULL,
    crampte INT NOT NULL DEFAULT 100,
    session VARCHAR(10) DEFAULT NULL,
    weight FLOAT NOT NULL DEFAULT 66.0,
    sexe FLOAT NOT NULL DEFAULT 0.6,
    eat TINYINT(1) NOT NULL DEFAULT 0,
    style LONGTEXT NOT NULL DEFAULT '{ \"badge\" : 1, \"font\" : 2, \"color\" : 3 }'
);

CREATE TABLE password_reset_tokens(
    email VARCHAR(200) NOT NULL,
    token VARCHAR(200) NOT NULL,
    created_at DATETIME,

    CONSTRAINT pk_passwordResetTokens PRIMARY KEY (email,token),
    FOREIGN KEY fk_passwordResetTokens_email(email) REFERENCES user(email) ON DELETE CASCADE ON UPDATE CASCADE 

);

CREATE TABLE session(
    id VARCHAR(10) NOT NULL PRIMARY KEY,
    admin INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    private TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL,
    last_update DATETIME NOT NULL,
    ended TINYINT(1) NOT NULL DEFAULT 0,

    FOREIGN KEY fk_sessions_admin(admin) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE drink(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(10) NOT NULL,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    type VARCHAR(100) DEFAULT NULL,
    number INT NOT NULL,
    size FLOAT NOT NULL,
    alcool_quantity FLOAT NOT NULL,
    alcool_degre FLOAT NOT NULL,
    bottoms_up TINYINT(1) NOT NULL DEFAULT 0,
    drink_at DATETIME NOT NULL,

    FOREIGN KEY fk_drink_sessionId(session_id) REFERENCES session(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY fk_drink_userId(user_id) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE statistiques(
    user_id INT NOT NULL,
    session_id VARCHAR(10) NOT NULL,
    max_gl FLOAT NOT NULL DEFAULT 0,
    alcool_quantity FLOAT NOT NULL DEFAULT 0,
    pure_alcool_quantity FLOAT NOT NULL DEFAULT 0,
    glass INT NOT NULL DEFAULT 0,
    shot INT NOT NULL DEFAULT 0,

    CONSTRAINT pk_statistiques PRIMARY KEY (user_id,session_id),

    FOREIGN KEY fk_statistiques_userId(user_id) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY fk_statistique_sessionId(session_id) REFERENCES session(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE item(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(100),
    name VARCHAR(100) DEFAULT NULL,
    data LONGTEXT,
    shop TINYINT(1) NOT NULL DEFAULT 0,
    price INT NOT NULL DEFAULT 0
);

CREATE TABLE inventory(
    user_id INT NOT NULL,
    item_id INT NOT NULL,
    bought_at INT NOT NULL DEFAULT 0,

    CONSTRAINT pk_inventory PRIMARY KEY (user_id,item_id),

    FOREIGN KEY fk_inventory_userId(user_id) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY fk_inventory_itemId(item_id) REFERENCES item(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE article(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    link VARCHAR(200) NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL
);