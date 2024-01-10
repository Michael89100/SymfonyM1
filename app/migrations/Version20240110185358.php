<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240110185358 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE workshop_speaker (workshop_id INT NOT NULL, speaker_id INT NOT NULL, INDEX IDX_39CAC9761FDCE57C (workshop_id), INDEX IDX_39CAC976D04A0F27 (speaker_id), PRIMARY KEY(workshop_id, speaker_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE workshop_speaker ADD CONSTRAINT FK_39CAC9761FDCE57C FOREIGN KEY (workshop_id) REFERENCES workshop (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE workshop_speaker ADD CONSTRAINT FK_39CAC976D04A0F27 FOREIGN KEY (speaker_id) REFERENCES speaker (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quiz DROP FOREIGN KEY FK_A412FA9274281A5E');
        $this->addSql('DROP INDEX IDX_A412FA9274281A5E ON quiz');
        $this->addSql('ALTER TABLE quiz DROP edition_id');
        $this->addSql('ALTER TABLE speaker DROP INDEX UNIQ_7B85DB61A76ED395, ADD INDEX IDX_7B85DB61A76ED395 (user_id)');
        $this->addSql('ALTER TABLE speaker DROP FOREIGN KEY FK_7B85DB6174281A5E');
        $this->addSql('DROP INDEX IDX_7B85DB6174281A5E ON speaker');
        $this->addSql('ALTER TABLE speaker DROP edition_id, CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE student DROP INDEX UNIQ_B723AF33A76ED395, ADD INDEX IDX_B723AF33A76ED395 (user_id)');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF3374281A5E');
        $this->addSql('DROP INDEX IDX_B723AF3374281A5E ON student');
        $this->addSql('ALTER TABLE student DROP edition_id, CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE workshop ADD quiz_id INT NOT NULL');
        $this->addSql('ALTER TABLE workshop ADD CONSTRAINT FK_9B6F02C4853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9B6F02C4853CD175 ON workshop (quiz_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE workshop_speaker DROP FOREIGN KEY FK_39CAC9761FDCE57C');
        $this->addSql('ALTER TABLE workshop_speaker DROP FOREIGN KEY FK_39CAC976D04A0F27');
        $this->addSql('DROP TABLE workshop_speaker');
        $this->addSql('ALTER TABLE speaker DROP INDEX IDX_7B85DB61A76ED395, ADD UNIQUE INDEX UNIQ_7B85DB61A76ED395 (user_id)');
        $this->addSql('ALTER TABLE speaker ADD edition_id INT NOT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE speaker ADD CONSTRAINT FK_7B85DB6174281A5E FOREIGN KEY (edition_id) REFERENCES edition (id)');
        $this->addSql('CREATE INDEX IDX_7B85DB6174281A5E ON speaker (edition_id)');
        $this->addSql('ALTER TABLE workshop DROP FOREIGN KEY FK_9B6F02C4853CD175');
        $this->addSql('DROP INDEX UNIQ_9B6F02C4853CD175 ON workshop');
        $this->addSql('ALTER TABLE workshop DROP quiz_id');
        $this->addSql('ALTER TABLE student DROP INDEX IDX_B723AF33A76ED395, ADD UNIQUE INDEX UNIQ_B723AF33A76ED395 (user_id)');
        $this->addSql('ALTER TABLE student ADD edition_id INT NOT NULL, CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF3374281A5E FOREIGN KEY (edition_id) REFERENCES edition (id)');
        $this->addSql('CREATE INDEX IDX_B723AF3374281A5E ON student (edition_id)');
        $this->addSql('ALTER TABLE quiz ADD edition_id INT NOT NULL');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA9274281A5E FOREIGN KEY (edition_id) REFERENCES edition (id)');
        $this->addSql('CREATE INDEX IDX_A412FA9274281A5E ON quiz (edition_id)');
    }
}
