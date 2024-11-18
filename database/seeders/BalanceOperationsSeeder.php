<?php

namespace Database\Seeders;

use Core\Models\BalanceOperation;
use Illuminate\Database\Seeder;

class BalanceOperationsSeeder extends Seeder
{


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $balanceOperations = array(
            array('id' => '31', 'designation' => 'TO OTHER USERS (PUBLIC)', 'io' => 'O', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '1', 'note' => 'je ne sais pas', 'modify_amount' => '1'),
            array('id' => '27', 'designation' => 'DEBLOCK BFS', 'io' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '2', 'note' => 'je ne sais pas', 'modify_amount' => '1'),
            array('id' => '40', 'designation' => 'ACHAT SMS', 'io' => 'O', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '5', 'note' => 'je ne sais pas', 'modify_amount' => '1'),
            array('id' => '29', 'designation' => '2EARN TO USER CC', 'io' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '2', 'note' => 'je ne sais pas', 'modify_amount' => '1'),
            array('id' => '28', 'designation' => 'TO OTHER USERS', 'io' => 'O', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '2', 'note' => 'je ne sais pas', 'modify_amount' => '1'),
            array('id' => '26', 'designation' => 'PRC', 'io' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '1', 'note' => 'montant proactif vercé dans CASH suite à l\'av', 'modify_amount' => '1'),
            array('id' => '25', 'designation' => 'CASH BACK', 'io' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '1', 'note' => 'montant cashback vercé dans CASH suite à l\'av', 'modify_amount' => '1'),
            array('id' => '24', 'designation' => 'PRC', 'io' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '2', 'note' => 'montant proactif vercé dans BFS suite à l\'ava', 'modify_amount' => '1'),
            array('id' => '23', 'designation' => 'CASH BACK', 'io' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '2', 'note' => 'montant cashback vercé dans BFS suite à l\'ava', 'modify_amount' => '1'),
            array('id' => '22', 'designation' => 'TO OTHER USERS', 'io' => 'O', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '1', 'note' => 'débit du compte cash apres l\'opération transf', 'modify_amount' => '1'),
            array('id' => '21', 'designation' => '2EARN.CASH SYSTEM', 'io' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '4', 'note' => 'initialisation du solde', 'modify_amount' => '1'),
            array('id' => '20', 'designation' => '2EARN.CASH SYSTEM', 'io' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '3', 'note' => 'initialisation du solde', 'modify_amount' => '1'),
            array('id' => '19', 'designation' => '2EARN.CASH SYSTEM', 'io' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '2', 'note' => 'initialisation du solde', 'modify_amount' => '1'),
            array('id' => '18', 'designation' => '2EARN.CASH SYSTEM', 'io' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '1', 'note' => 'initialisation du solde', 'modify_amount' => '1'),
            array('id' => '17', 'designation' => 'DEBIT DE SOLDE', 'io' => 'O', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '2', 'note' => 'montant débité  contre l\'operation d\'achat d\'', 'modify_amount' => '1'),
            array('id' => '16', 'designation' => 'CASH TO BFS', 'io' => 'O', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '1', 'note' => 'débit du compte cash apres l\'opération cash t', 'modify_amount' => '1'),
            array('id' => '15', 'designation' => 'FROM LIVRAISON', 'io' => 'I', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '2', 'note' => 'PAYEMENT A LA LIVRAISON', 'modify_amount' => '1'),
            array('id' => '14', 'designation' => 'FROM OTHER USERS', 'io' => 'I', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '2', 'note' => 'transfert de bfs d\'un autre utilisateur', 'modify_amount' => '1'),
            array('id' => '13', 'designation' => 'FROM CASH BALANCE', 'io' => 'I', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '2', 'note' => 'alimentation du compte BFS apré l\'operation c', 'modify_amount' => '1'),
            array('id' => '11', 'designation' => 'BLOCK BFS', 'io' => 'O', 'source' => 'SYSTEM', 'mode' => NULL, 'amounts_id' => '2', 'note' => 'non utilisable', 'modify_amount' => '1'),
            array('id' => '10', 'designation' => 'EACH SHARE LEADING TO A PURCHASE', 'io' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '3', 'note' => 'discount suite à l\'invitation à l\'achat', 'modify_amount' => '1'),
            array('id' => '4', 'designation' => 'HAVE SOMEONE ATTEND A PRESENTATION', 'io' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '4', 'note' => 'non utilisable', 'modify_amount' => '1'),
            array('id' => '9', 'designation' => 'HAVE SOMEONE ATTEND A PRESENTATION', 'io' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '3', 'note' => 'non utilisable', 'modify_amount' => '1'),
            array('id' => '8', 'designation' => 'BY VISITING THE PRODUCTS', 'io' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '3', 'note' => 'discount suite à la visite d\'un produit', 'modify_amount' => '1'),
            array('id' => '7', 'designation' => 'BY REGISTERING 5 PEOPLE', 'io' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '3', 'note' => 'non utilisable', 'modify_amount' => '1'),
            array('id' => '6', 'designation' => 'WELCOME GIFT', 'io' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '3', 'note' => 'cadeau de bienvenue dans le solde discount', 'modify_amount' => '1'),
            array('id' => '5', 'designation' => 'EACH SHARE LEADING TO A PURCHASE', 'io' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '4', 'note' => 'non utilisable', 'modify_amount' => '1'),
            array('id' => '3', 'designation' => 'BY VISITING THE PRODUCTS', 'io' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '4', 'note' => 'non utilisable', 'modify_amount' => '1'),
            array('id' => '2', 'designation' => 'BY REGISTERING 5 PEOPLE', 'io' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '4', 'note' => 'non utilisable', 'modify_amount' => '1'),
            array('id' => '1', 'designation' => 'WELCOME GIFT', 'io' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '4', 'note' => 'cadeau de bienvenue dans le solde de l\'arbre', 'modify_amount' => '1'),
            array('id' => '32', 'designation' => 'TO OTHER USERS (ADMIN)', 'io' => 'O', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '1', 'note' => 'je ne sais pas', 'modify_amount' => '1'),
            array('id' => '33', 'designation' => 'FROM REPRESENTATIVE', 'io' => 'I', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '2', 'note' => 'je ne sais pas', 'modify_amount' => '1'),
            array('id' => '34', 'designation' => 'FROM PUBLIC USER', 'io' => 'I', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '2', 'note' => 'je ne sais pas', 'modify_amount' => '1'),
            array('id' => '35', 'designation' => 'TO OTHER USERS (PUBLIC)', 'io' => 'O', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '2', 'note' => 'je ne sais pas', 'modify_amount' => '1'),
            array('id' => '36', 'designation' => 'TO OTHER USERS (ADMIN)', 'io' => 'O', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '2', 'note' => 'je ne sais pas', 'modify_amount' => '1'),
            array('id' => '37', 'designation' => 'SI', 'io' => 'I', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '5', 'note' => 'initialisation du solde', 'modify_amount' => '1'),
            array('id' => '38', 'designation' => 'BFS TO SMS', 'io' => 'O', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '2', 'note' => 'je ne sais pas', 'modify_amount' => '1'),
            array('id' => '39', 'designation' => 'FROM BFS BALANCE', 'io' => 'I', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '5', 'note' => 'je ne sais pas', 'modify_amount' => '1'),
            array('id' => '41', 'designation' => 'ACHAT SMS', 'io' => 'O', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '5', 'note' => 'je ne sais pas', 'modify_amount' => '1'),
            array('id' => '42', 'designation' => 'CASH TRANSFERT', 'io' => 'O', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '1', 'note' => 'transfert de cash (operation d\'envoi)', 'modify_amount' => '1'),
            array('id' => '43', 'designation' => 'CASH TRANSFERT', 'io' => 'I', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '1', 'note' => 'transfert de cash (operation de reception)', 'modify_amount' => '1'),
            array('id' => '44', 'designation' => 'SELLED SHARES', 'io' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '6', 'note' => 'on stocke les actions achetés', 'modify_amount' => '1'),
            array('id' => '46', 'designation' => 'BY ACQUIRING SHARES', 'io' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '2', 'note' => 'le montant vercé dans BFS quand on achete des', 'modify_amount' => '1'),
            array('id' => '47', 'designation' => 'FROM BFS', 'io' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '3', 'note' => 'le montant vercé dans discount suite à l\'alim', 'modify_amount' => '1'),
            array('id' => '48', 'designation' => 'SELL SHARES', 'io' => 'O', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '1', 'note' => 'montant débité de cash balance contre l\'opera', 'modify_amount' => '1'),
            array('id' => '49', 'designation' => 'SPONSORSHIP COMMISSION', 'io' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '1', 'note' => 'commission parrin à l\'achat d\'action  dans ca', 'modify_amount' => '1'),
            array('id' => '50', 'designation' => 'SPONSORSHIP COMMISSION', 'io' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '2', 'note' => 'commission parrin à l\'achat d\'action  dans bf', 'modify_amount' => '1'),
            array('id' => '51', 'designation' => 'CASH TOP-UP WITH CARD', 'io' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '1', 'note' => 'alimentation de cash balance via carte banqua', 'modify_amount' => '1')
        );

        if (BalanceOperation::all()->count()) {
            BalanceOperation::truncate();
        }
        foreach ($balanceOperations as $operation) {
            BalanceOperation::create(array_merge($operation, ['created_at' => now(), 'updated_at' => now()]));
        }

    }
}
