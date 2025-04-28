
CREATE TABLE utilisateurs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    Nid VARCHAR(20) UNIQUE not NULL,
    nom VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    mot_de_passe VARCHAR(255),
    role ENUM('electeur', 'admin', 'agent', 'observateur') NOT NULL,
    date_naissance DATE,
    sexe ENUM('Homme', 'Femme'),
    statut BOOLEAN DEFAULT TRUE
);
INSERT INTO utilisateurs (Nid, nom, email, mot_de_passe, role, date_naissance, sexe, statut)
VALUES (
    'ADM001',                            -- Nid unique
    'Administrateur Principal',          -- Nom
    'admin@gmail.com',                  -- Email unique
    '1234',      -- Mot de passe déjà haché en bcrypt
    'admin',                              -- Rôle
    '1990-01-01',                         -- Date de naissance
    'Homme',                              -- Sexe
    TRUE                                  -- Statut actif
);


CREATE TABLE elections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titre VARCHAR(255),
    description TEXT,
    type VARCHAR(100),
    date_debut DATETIME,
    date_fin DATETIME,
    statut ENUM('en_attente', 'en_cours', 'terminee') DEFAULT 'en_attente'
);

CREATE TABLE candidats (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT,
    election_id INT,
    parti_politique VARCHAR(100),
    programme TEXT,
    valide BOOLEAN DEFAULT FALSE,
    photo varcha(255),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (election_id) REFERENCES elections(id)
);

CREATE TABLE votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT,
    candidat_id INT,
    election_id INT,
    date_vote DATETIME,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (candidat_id) REFERENCES candidats(id),
    FOREIGN KEY (election_id) REFERENCES elections(id),
    UNIQUE (utilisateur_id, election_id)
);

CREATE TABLE bureaux_vote (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    localisation TEXT
);

CREATE TABLE affectations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utilisateur_id INT,
    bureau_id INT,
    election_id INT,
    fonction ENUM('electeur', 'agent', 'superviseur'),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (bureau_id) REFERENCES bureaux_vote(id),
    FOREIGN KEY (election_id) REFERENCES elections(id)
);
