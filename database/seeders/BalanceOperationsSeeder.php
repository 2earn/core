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
            array('id' => '31', 'designation' => 'TO OTHER USERS (PUBLIC)', 'IO' => 'O', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '1', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '27', 'designation' => 'DEBLOCK BFS', 'IO' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '2', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '40', 'designation' => 'ACHAT SMS', 'IO' => 'O', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '5', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '29', 'designation' => '2EARN TO USER CC', 'IO' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '2', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '28', 'designation' => 'TO OTHER USERS', 'IO' => 'O', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '2', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '26', 'designation' => 'PRC', 'IO' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '1', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '25', 'designation' => 'CASH BACK', 'IO' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '1', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '24', 'designation' => 'PRC', 'IO' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '2', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '23', 'designation' => 'CASH BACK', 'IO' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '2', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '22', 'designation' => 'TO OTHER USERS', 'IO' => 'O', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '1', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '21', 'designation' => '2EARN.CASH SYSTEM', 'IO' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '4', 'Note' => 'non utilisable', 'MODIFY_AMOUNT' => '1'),
            array('id' => '20', 'designation' => '2EARN.CASH SYSTEM', 'IO' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '3', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '19', 'designation' => '2EARN.CASH SYSTEM', 'IO' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '2', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '18', 'designation' => '2EARN.CASH SYSTEM', 'IO' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '1', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '17', 'designation' => 'DEBIT DE SOLDE', 'IO' => 'O', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '2', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '16', 'designation' => 'CASH TO BFS', 'IO' => 'O', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '1', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '15', 'designation' => 'FROM LIVRAISON', 'IO' => 'I', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '2', 'Note' => 'PAYEMENT A LA LIVRAISON', 'MODIFY_AMOUNT' => '1'),
            array('id' => '14', 'designation' => 'FROM OTHER USERS', 'IO' => 'I', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '2', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '13', 'designation' => 'FROM CASH BALANCE', 'IO' => 'I', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '2', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '11', 'designation' => 'BLOCK BFS', 'IO' => 'O', 'source' => 'SYSTEM', 'mode' => NULL, 'amounts_id' => '2', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '10', 'designation' => 'EACH SHARE LEADING TO A PURCHASE', 'IO' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '3', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '4', 'designation' => 'HAVE SOMEONE ATTEND A PRESENTATION', 'IO' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '4', 'Note' => 'non utilisable', 'MODIFY_AMOUNT' => '1'),
            array('id' => '9', 'designation' => 'HAVE SOMEONE ATTEND A PRESENTATION', 'IO' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '3', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '8', 'designation' => 'BY VISITING THE PRODUCTS', 'IO' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '3', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '7', 'designation' => 'BY REGISTERING 5 PEOPLE', 'IO' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '3', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '6', 'designation' => 'WELCOME GIFT', 'IO' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '3', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '5', 'designation' => 'EACH SHARE LEADING TO A PURCHASE', 'IO' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '4', 'Note' => 'non utilisable', 'MODIFY_AMOUNT' => '1'),
            array('id' => '3', 'designation' => 'BY VISITING THE PRODUCTS', 'IO' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '4', 'Note' => 'non utilisable', 'MODIFY_AMOUNT' => '1'),
            array('id' => '2', 'designation' => 'BY REGISTERING 5 PEOPLE', 'IO' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '4', 'Note' => 'non utilisable', 'MODIFY_AMOUNT' => '1'),
            array('id' => '1', 'designation' => 'WELCOME GIFT', 'IO' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '4', 'Note' => 'cadeau de bienvenue dans le solde de l\'arbre', 'MODIFY_AMOUNT' => '1'),
            array('id' => '32', 'designation' => 'TO OTHER USERS (ADMIN)', 'IO' => 'O', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '1', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '33', 'designation' => 'FROM REPRESENTATIVE', 'IO' => 'I', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '2', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '34', 'designation' => 'FROM PUBLIC USER', 'IO' => 'I', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '2', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '35', 'designation' => 'TO OTHER USERS (PUBLIC)', 'IO' => 'O', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '2', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '36', 'designation' => 'TO OTHER USERS (ADMIN)', 'IO' => 'O', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '2', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '37', 'designation' => 'SI', 'IO' => 'I', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '5', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '38', 'designation' => 'BFS TO SMS
', 'IO' => 'O', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '2', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '39', 'designation' => 'FROM BFS BALANCE', 'IO' => 'I', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '5', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '41', 'designation' => 'ACHAT SMS', 'IO' => 'O', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '5', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '42', 'designation' => 'CASH TRANSFERT', 'IO' => 'O', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '1', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '43', 'designation' => 'CASH TRANSFERT', 'IO' => 'I', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '1', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '44', 'designation' => 'SELLED SHARES', 'IO' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '6', 'Note' => 'achat des actions', 'MODIFY_AMOUNT' => '1'),
            array('id' => '46', 'designation' => 'BY ACQUIRING SHARES', 'IO' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '2', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '47', 'designation' => 'FROM BFS', 'IO' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '3', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '48', 'designation' => 'SELL SHARES', 'IO' => 'O', 'source' => 'IDUSER', 'mode' => NULL, 'amounts_id' => '1', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '49', 'designation' => 'SPONSORSHIP COMMISSION', 'IO' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '1', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '50', 'designation' => 'SPONSORSHIP COMMISSION', 'IO' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '2', 'Note' => NULL, 'MODIFY_AMOUNT' => '1'),
            array('id' => '51', 'designation' => 'CASH TOP-UP WITH CARD', 'IO' => 'I', 'source' => '11111111', 'mode' => NULL, 'amounts_id' => '1', 'Note' => NULL, 'MODIFY_AMOUNT' => '1')
        );

        if (BalanceOperation::all()->count()) {
            BalanceOperation::truncate();
        }
        foreach ($balanceOperations as $operation) {
            BalanceOperation::create(array_merge($operation, ['created_at' => now(), 'updated_at' => now()]));
        }

    }
}
