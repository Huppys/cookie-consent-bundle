<?php

declare(strict_types=1);

namespace huppys\CookieConsentBundle;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241014204019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cookieconsent_cookies (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, httpOnly BOOLEAN NOT NULL, secure BOOLEAN NOT NULL, sameSite BOOLEAN NOT NULL, expires DATETIME NOT NULL --(DC2Type:datetimetz_immutable)
        , domain VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE cookieconsent_log (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ipAddress VARCHAR(24) NOT NULL, consentKey VARCHAR(255) NOT NULL, cookieName VARCHAR(255) NOT NULL, cookieValue CLOB NOT NULL --(DC2Type:json)
        , timestamp DATETIME NOT NULL --(DC2Type:datetimetz_immutable)
        )');
        $this->addSql('CREATE TABLE cookieconsent_settings (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, namePrefix VARCHAR(255) NOT NULL, consentCookie_id INTEGER DEFAULT NULL, consentKeyCookie_id INTEGER DEFAULT NULL, consentCategoriesCookie_id INTEGER DEFAULT NULL, CONSTRAINT FK_D0E5A79717CEFD16 FOREIGN KEY (consentCookie_id) REFERENCES cookieconsent_cookies (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D0E5A7971156E1DB FOREIGN KEY (consentKeyCookie_id) REFERENCES cookieconsent_cookies (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D0E5A7971F876B45 FOREIGN KEY (consentCategoriesCookie_id) REFERENCES cookieconsent_cookies (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D0E5A79717CEFD16 ON cookieconsent_settings (consentCookie_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D0E5A7971156E1DB ON cookieconsent_settings (consentKeyCookie_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D0E5A7971F876B45 ON cookieconsent_settings (consentCategoriesCookie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE cookieconsent_cookies');
        $this->addSql('DROP TABLE cookieconsent_log');
        $this->addSql('DROP TABLE cookieconsent_settings');
    }
}
