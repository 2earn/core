<?php

namespace Database\Seeders;

use App\Models\BFSsBalances;
use Core\Enum\BalanceOperationsEnum;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BFSBalancePercentageUpdater extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Updates all BFS balance records with balance_operation_id = 50 (SPONSORSHIP_COMMISSION_BFS)
     * to have percentage = 100.00
     *
     * @return void
     */
    public function run(): void
    {
        $this->command->info('Starting BFS Balance Percentage Update...');

        // Get the balance operation ID for SPONSORSHIP_COMMISSION_BFS (which is 50)
        $balanceOperationId = BalanceOperationsEnum::SPONSORSHIP_COMMISSION_BFS->value;

        // Count records to be updated
        $recordsCount = BFSsBalances::where('balance_operation_id', $balanceOperationId)
            ->count();

        $this->command->info("Found {$recordsCount} records to update.");

        if ($recordsCount === 0) {
            $this->command->warn('No records found with balance_operation_id = 50');
            return;
        }

        DB::beginTransaction();

        try {
            // Update all records with balance_operation_id = 50 to have percentage = 100.00
            $updated = BFSsBalances::where('balance_operation_id', $balanceOperationId)
                ->update(['percentage' => BFSsBalances::BFS_100]);

            DB::commit();

            $this->command->info("Successfully updated {$updated} BFS balance records.");
            $this->command->info("All records with balance_operation_id = {$balanceOperationId} now have percentage = 100.00");

            Log::info("BFSBalancePercentageUpdater: Updated {$updated} records with balance_operation_id = {$balanceOperationId} to percentage = 100.00");

        } catch (\Exception $exception) {
            DB::rollBack();

            $this->command->error('Error updating BFS balances: ' . $exception->getMessage());
            Log::error('BFSBalancePercentageUpdater failed: ' . $exception->getMessage());

            throw $exception;
        }
    }
}
