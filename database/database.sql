CREATE TABLE user(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    name VARCHAR(100) NOT NULL,
    crampte INT NOT NULL DEFAULT 100,
    weight FLOAT NOT NULL DEFAULT 66.0,
    sexe FLOAT NOT NULL DEFAULT 0.6,
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

CREATE TABLE user_session(
    user_id INT NOT NULL,
    session_id VARCHAR(10) NOT NULL,
    eat  TINYINT(1) NOT NULL DEFAULT 0,

    CONSTRAINT pk_userSession PRIMARY KEY (user_id,session_id),

    FOREIGN KEY fk_userSession_userId(user_id) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY fk_userSession_sessionId(session_id) REFERENCES session(id) ON DELETE CASCADE ON UPDATE CASCADE
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
    hidden TINYINT(1) NOT NULL DEFAULT 0,

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

CREATE TABLE meta_holdable_position(
    position VARCHAR(100) NOT NULL PRIMARY KEY
);

CREATE TABLE meta_holdable_category(
    category VARCHAR(100) NOT NULL PRIMARY KEY
);

CREATE TABLE holdable(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(100) NULL DEFAULT NULL,
    position VARCHAR(100) NULL DEFAULT NULL,
    tag VARCHAR(100) NULL DEFAULT NULL,
    name VARCHAR(100) DEFAULT NULL,
    data TEXT DEFAULT NULL,
    shop TINYINT(1) NOT NULL DEFAULT 0,
    price INT NOT NULL DEFAULT 0,
    auto_hold TINYINT(1) NOT NULL DEFAULT 0,

    FOREIGN KEY fk_holdable_category(category) REFERENCES meta_holdable_category(category) ON DELETE SET NULL ON UPDATE CASCADE,
    FOREIGN KEY fk_holdable_position(position) REFERENCES meta_holdable_position(position) ON DELETE SET NULL ON UPDATE CASCADE
);

CREATE TABLE inventory(
    user_id INT NOT NULL,
    item_id INT NOT NULL,
    bought_at INT NOT NULL DEFAULT 0,
    hold TINYINT(1) NOT NULL DEFAULT 0,

    CONSTRAINT pk_inventory PRIMARY KEY (user_id,item_id),

    FOREIGN KEY fk_inventory_userId(user_id) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY fk_inventory_itemId(item_id) REFERENCES holdable(id) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE article(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    smug VARCHAR(100) NOT NULL UNIQUE,
    title VARCHAR(100) NOT NULL,
    view VARCHAR(200) NOT NULL,
    description TEXT NOT NULL
);

CREATE TABLE gift(
    token VARCHAR(100) NOT NULL PRIMARY KEY,
    gift LONGTEXT NOT NULL,
    html TEXT NULL DEFAULT NULL,
    receive INT NOT NULL DEFAULT 0
);

CREATE TABLE friends(
    from INT NOT NULL,
    to INT NOT NULL,
    accepted TINYINT(1) NOT NULL DEFAULT 0,

    CONSTRAINT pk_inventory PRIMARY KEY (user_id1,user_id2),

    FOREIGN KEY fk_friends_userId1(user_id1) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY fk_friends_itemId2(user_id2) REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE
);