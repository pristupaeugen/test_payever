<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

use AppBundle\Entity\Role;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170611133302 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $roleProvider = Role::ROLE_PROVIDER;
        $roleCustomer = Role::ROLE_CUSTOMER;

        $this->addSql("
            INSERT INTO `roles` VALUES (1, '{$roleProvider}');
            INSERT INTO `roles` VALUES (2, '{$roleCustomer}');
        ");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql("DELETE FROM `roles`");
    }
}
