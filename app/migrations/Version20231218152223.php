<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231218152223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE edition (id INT AUTO_INCREMENT NOT NULL, year INT NOT NULL, start_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', address VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_activity (job_id INT NOT NULL, activity_id INT NOT NULL, INDEX IDX_CB33AC21BE04EA9 (job_id), INDEX IDX_CB33AC2181C06096 (activity_id), PRIMARY KEY(job_id, activity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_skill (job_id INT NOT NULL, skill_id INT NOT NULL, INDEX IDX_5F615907BE04EA9 (job_id), INDEX IDX_5F6159075585C142 (skill_id), PRIMARY KEY(job_id, skill_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, quiz_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, INDEX IDX_B6F7494E853CD175 (quiz_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz (id INT AUTO_INCREMENT NOT NULL, edition_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_A412FA9274281A5E (edition_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ressource (id INT AUTO_INCREMENT NOT NULL, workshop_id INT NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) DEFAULT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_939F45441FDCE57C (workshop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, capacity_maximum INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE school (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, postal_code INT NOT NULL, country VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE section (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sector (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skill (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE speaker (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, edition_id INT NOT NULL, social_email VARCHAR(255) NOT NULL, resgistration_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_7B85DB61A76ED395 (user_id), INDEX IDX_7B85DB6174281A5E (edition_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT AUTO_INCREMENT NOT NULL, school_id INT NOT NULL, section_id INT DEFAULT NULL, user_id INT DEFAULT NULL, edition_id INT NOT NULL, scool_email VARCHAR(255) NOT NULL, registration_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B723AF33C32A47EE (school_id), INDEX IDX_B723AF33D823E37A (section_id), UNIQUE INDEX UNIQ_B723AF33A76ED395 (user_id), INDEX IDX_B723AF3374281A5E (edition_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_answer (id INT AUTO_INCREMENT NOT NULL, question_id INT DEFAULT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_BF8F51181E27F6BF (question_id), INDEX IDX_BF8F5118A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE workshop (id INT AUTO_INCREMENT NOT NULL, room_id INT NOT NULL, sector_id INT NOT NULL, edition_id INT NOT NULL, start_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_9B6F02C454177093 (room_id), INDEX IDX_9B6F02C4DE95C867 (sector_id), INDEX IDX_9B6F02C474281A5E (edition_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE workshop_job (workshop_id INT NOT NULL, job_id INT NOT NULL, INDEX IDX_C9A069991FDCE57C (workshop_id), INDEX IDX_C9A06999BE04EA9 (job_id), PRIMARY KEY(workshop_id, job_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE workshop_student (workshop_id INT NOT NULL, student_id INT NOT NULL, INDEX IDX_F56CBD241FDCE57C (workshop_id), INDEX IDX_F56CBD24CB944F1A (student_id), PRIMARY KEY(workshop_id, student_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE job_activity ADD CONSTRAINT FK_CB33AC21BE04EA9 FOREIGN KEY (job_id) REFERENCES job (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE job_activity ADD CONSTRAINT FK_CB33AC2181C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE job_skill ADD CONSTRAINT FK_5F615907BE04EA9 FOREIGN KEY (job_id) REFERENCES job (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE job_skill ADD CONSTRAINT FK_5F6159075585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id)');
        $this->addSql('ALTER TABLE quiz ADD CONSTRAINT FK_A412FA9274281A5E FOREIGN KEY (edition_id) REFERENCES edition (id)');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F45441FDCE57C FOREIGN KEY (workshop_id) REFERENCES workshop (id)');
        $this->addSql('ALTER TABLE speaker ADD CONSTRAINT FK_7B85DB61A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE speaker ADD CONSTRAINT FK_7B85DB6174281A5E FOREIGN KEY (edition_id) REFERENCES edition (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33C32A47EE FOREIGN KEY (school_id) REFERENCES school (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33D823E37A FOREIGN KEY (section_id) REFERENCES section (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF33A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF3374281A5E FOREIGN KEY (edition_id) REFERENCES edition (id)');
        $this->addSql('ALTER TABLE user_answer ADD CONSTRAINT FK_BF8F51181E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE user_answer ADD CONSTRAINT FK_BF8F5118A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE workshop ADD CONSTRAINT FK_9B6F02C454177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE workshop ADD CONSTRAINT FK_9B6F02C4DE95C867 FOREIGN KEY (sector_id) REFERENCES sector (id)');
        $this->addSql('ALTER TABLE workshop ADD CONSTRAINT FK_9B6F02C474281A5E FOREIGN KEY (edition_id) REFERENCES edition (id)');
        $this->addSql('ALTER TABLE workshop_job ADD CONSTRAINT FK_C9A069991FDCE57C FOREIGN KEY (workshop_id) REFERENCES workshop (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE workshop_job ADD CONSTRAINT FK_C9A06999BE04EA9 FOREIGN KEY (job_id) REFERENCES job (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE workshop_student ADD CONSTRAINT FK_F56CBD241FDCE57C FOREIGN KEY (workshop_id) REFERENCES workshop (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE workshop_student ADD CONSTRAINT FK_F56CBD24CB944F1A FOREIGN KEY (student_id) REFERENCES student (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job_activity DROP FOREIGN KEY FK_CB33AC21BE04EA9');
        $this->addSql('ALTER TABLE job_activity DROP FOREIGN KEY FK_CB33AC2181C06096');
        $this->addSql('ALTER TABLE job_skill DROP FOREIGN KEY FK_5F615907BE04EA9');
        $this->addSql('ALTER TABLE job_skill DROP FOREIGN KEY FK_5F6159075585C142');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E853CD175');
        $this->addSql('ALTER TABLE quiz DROP FOREIGN KEY FK_A412FA9274281A5E');
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F45441FDCE57C');
        $this->addSql('ALTER TABLE speaker DROP FOREIGN KEY FK_7B85DB61A76ED395');
        $this->addSql('ALTER TABLE speaker DROP FOREIGN KEY FK_7B85DB6174281A5E');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33C32A47EE');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33D823E37A');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF33A76ED395');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF3374281A5E');
        $this->addSql('ALTER TABLE user_answer DROP FOREIGN KEY FK_BF8F51181E27F6BF');
        $this->addSql('ALTER TABLE user_answer DROP FOREIGN KEY FK_BF8F5118A76ED395');
        $this->addSql('ALTER TABLE workshop DROP FOREIGN KEY FK_9B6F02C454177093');
        $this->addSql('ALTER TABLE workshop DROP FOREIGN KEY FK_9B6F02C4DE95C867');
        $this->addSql('ALTER TABLE workshop DROP FOREIGN KEY FK_9B6F02C474281A5E');
        $this->addSql('ALTER TABLE workshop_job DROP FOREIGN KEY FK_C9A069991FDCE57C');
        $this->addSql('ALTER TABLE workshop_job DROP FOREIGN KEY FK_C9A06999BE04EA9');
        $this->addSql('ALTER TABLE workshop_student DROP FOREIGN KEY FK_F56CBD241FDCE57C');
        $this->addSql('ALTER TABLE workshop_student DROP FOREIGN KEY FK_F56CBD24CB944F1A');
        $this->addSql('DROP TABLE activity');
        $this->addSql('DROP TABLE edition');
        $this->addSql('DROP TABLE job');
        $this->addSql('DROP TABLE job_activity');
        $this->addSql('DROP TABLE job_skill');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP TABLE ressource');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE school');
        $this->addSql('DROP TABLE section');
        $this->addSql('DROP TABLE sector');
        $this->addSql('DROP TABLE skill');
        $this->addSql('DROP TABLE speaker');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_answer');
        $this->addSql('DROP TABLE workshop');
        $this->addSql('DROP TABLE workshop_job');
        $this->addSql('DROP TABLE workshop_student');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
