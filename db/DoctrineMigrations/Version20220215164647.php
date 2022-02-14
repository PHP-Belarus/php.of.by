<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220215164647 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE body body TEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE title title VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE teaser teaser VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE body body LONGTEXT CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(180) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE username_canonical username_canonical VARCHAR(180) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE email email VARCHAR(180) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE email_canonical email_canonical VARCHAR(180) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE salt salt VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, CHANGE password password VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, CHANGE confirmation_token confirmation_token VARCHAR(180) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_unicode_ci`, CHANGE roles roles LONGTEXT CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci` COMMENT \'(DC2Type:array)\'');
    }
}
