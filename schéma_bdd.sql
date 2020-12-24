CREATE TABLE users ( -- représente les infos d'un utilisateur
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    nom VARCHAR(20),
    prénom VARCHAR(20) NOT NULL,
    motDePasse CHAR(97) NOT NULL,
    année_naissance YEAR,
    entraîneur BOOLEAN NOT NULL,
    typeCompte BOOLEAN NOT NULL
);

CREATE TABLE planning ( -- chaque instance représentera un entraînement unique
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    jour DATE NOT NULL,
    heure_début TIME NOT NULL,
    heure_fin TIME NOT NULL,
    entraîneur_id INT NOT NULL,
    ancien_planning INT,
    FOREIGN KEY(ancien_planning) REFERENCES planning (id),
    FOREIGN KEY(entraîneur_id) REFERENCES users (id)
);

CREATE TABLE users_in_planning ( -- lie un utilisateur à un planning via leurs id
    id_user INT NOT NULL,
    id_planning  INT NOT NULL,
    FOREIGN KEY(id_user) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY(id_planning) REFERENCES planning (id) ON DELETE CASCADE
);
