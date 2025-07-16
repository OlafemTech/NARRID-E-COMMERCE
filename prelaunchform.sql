-- contacts sent from the big form
CREATE TABLE contact_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(120) NOT NULL,
  gender      VARCHAR(20),
  email       VARCHAR(160) NOT NULL,
  address     VARCHAR(255),
  category    VARCHAR(60),
  subcategory VARCHAR(60),
  message     TEXT,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- “Notify Me” e-mail sign-ups from the hero section
CREATE TABLE newsletter_subs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email       VARCHAR(160) NOT NULL UNIQUE,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;