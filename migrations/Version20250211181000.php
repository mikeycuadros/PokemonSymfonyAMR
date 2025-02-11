<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250211181000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE battle DROP FOREIGN KEY FK_13991734900C982F');
        $this->addSql('DROP INDEX UNIQ_13991734900C982F ON battle');
        $this->addSql('ALTER TABLE battle DROP enemy_id');
        $this->addSql('ALTER TABLE pokedex DROP INDEX UNIQ_6336F6A7A76ED395, ADD INDEX IDX_6336F6A7A76ED395 (user_id)');
        $this->addSql('ALTER TABLE pokedex ADD pokemon_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pokedex ADD CONSTRAINT FK_6336F6A72FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('CREATE INDEX IDX_6336F6A72FE71C3E ON pokedex (pokemon_id)');
        $this->addSql('ALTER TABLE pokemon DROP FOREIGN KEY FK_62DC90F3372A5D14');
        $this->addSql('DROP INDEX IDX_62DC90F3372A5D14 ON pokemon');
        $this->addSql('ALTER TABLE pokemon DROP pokedex_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE battle ADD enemy_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE battle ADD CONSTRAINT FK_13991734900C982F FOREIGN KEY (enemy_id) REFERENCES pokemon (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_13991734900C982F ON battle (enemy_id)');
        $this->addSql('ALTER TABLE pokedex DROP INDEX IDX_6336F6A7A76ED395, ADD UNIQUE INDEX UNIQ_6336F6A7A76ED395 (user_id)');
        $this->addSql('ALTER TABLE pokedex DROP FOREIGN KEY FK_6336F6A72FE71C3E');
        $this->addSql('DROP INDEX IDX_6336F6A72FE71C3E ON pokedex');
        $this->addSql('ALTER TABLE pokedex DROP pokemon_id');
        $this->addSql('ALTER TABLE pokemon ADD pokedex_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F3372A5D14 FOREIGN KEY (pokedex_id) REFERENCES pokedex (id)');
        $this->addSql('CREATE INDEX IDX_62DC90F3372A5D14 ON pokemon (pokedex_id)');
    }
}
