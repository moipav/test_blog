-- Таблица категорий
CREATE TABLE IF NOT EXISTS categories (
                                          id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                          name VARCHAR(255) NOT NULL,
                                          slug VARCHAR(255) NOT NULL UNIQUE,
                                          description TEXT,
                                          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                          updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                          INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Таблица статей
CREATE TABLE IF NOT EXISTS articles (
                                        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                                        title VARCHAR(255) NOT NULL,
                                        slug VARCHAR(255) NOT NULL UNIQUE,
                                        description TEXT,
                                        content LONGTEXT NOT NULL,
                                        image VARCHAR(500),
                                        views INT UNSIGNED DEFAULT 0,
                                        published_at TIMESTAMP NULL,
                                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                                        INDEX idx_slug (slug),
                                        INDEX idx_published_at (published_at),
                                        INDEX idx_views (views)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Связующая таблица (многие-ко-многим)
CREATE TABLE IF NOT EXISTS category_article (
                                                category_id INT UNSIGNED NOT NULL,
                                                article_id INT UNSIGNED NOT NULL,
                                                PRIMARY KEY (category_id, article_id),
                                                FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
                                                FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;