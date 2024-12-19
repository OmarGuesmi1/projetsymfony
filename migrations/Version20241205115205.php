<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241205115205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_livre (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description_livre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE emprunt_livre (id INT AUTO_INCREMENT NOT NULL, date_emprunt_livre DATE NOT NULL, date_retour_livre DATE NOT NULL, statut_livre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, reservation_evenement_id INT DEFAULT NULL, nom_evenement VARCHAR(255) NOT NULL, description_evenement VARCHAR(255) NOT NULL, image_evenement VARCHAR(255) NOT NULL, type_evenement VARCHAR(255) NOT NULL, INDEX IDX_B26681E6DC15E11 (reservation_evenement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livre (id INT AUTO_INCREMENT NOT NULL, titre_livre VARCHAR(255) NOT NULL, auteur_livre VARCHAR(255) NOT NULL, isbn_livre VARCHAR(255) NOT NULL, nombre_exemplaire_livre INT NOT NULL, annee_publication_livre DATE NOT NULL, image_livre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, message VARCHAR(255) NOT NULL, date_envoi DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notificationevent (id INT AUTO_INCREMENT NOT NULL, messageevent VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participant (id INT AUTO_INCREMENT NOT NULL, reservation_evenement_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, date_inscription DATE NOT NULL, telephone VARCHAR(255) NOT NULL, INDEX IDX_D79F6B116DC15E11 (reservation_evenement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation_evenement (id INT AUTO_INCREMENT NOT NULL, notificationevent_id INT DEFAULT NULL, date_debut_re DATE NOT NULL, date_fin_re DATE NOT NULL, class_reservation_event VARCHAR(255) NOT NULL, statut_reservation_event TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_1161098110A18341 (notificationevent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation_livre (id INT AUTO_INCREMENT NOT NULL, date_reservation_livre DATE NOT NULL, statut_reservation_livre VARCHAR(255) NOT NULL, date_expiration_reservation_livre DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, activation_token VARCHAR(255) DEFAULT NULL, activation_token_created_at DATETIME DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E6DC15E11 FOREIGN KEY (reservation_evenement_id) REFERENCES reservation_evenement (id)');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B116DC15E11 FOREIGN KEY (reservation_evenement_id) REFERENCES reservation_evenement (id)');
        $this->addSql('ALTER TABLE reservation_evenement ADD CONSTRAINT FK_1161098110A18341 FOREIGN KEY (notificationevent_id) REFERENCES notificationevent (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E6DC15E11');
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B116DC15E11');
        $this->addSql('ALTER TABLE reservation_evenement DROP FOREIGN KEY FK_1161098110A18341');
        $this->addSql('DROP TABLE category_livre');
        $this->addSql('DROP TABLE emprunt_livre');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE livre');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE notificationevent');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE reservation_evenement');
        $this->addSql('DROP TABLE reservation_livre');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
