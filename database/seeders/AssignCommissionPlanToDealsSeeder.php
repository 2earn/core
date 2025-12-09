<?php

namespace Database\Seeders;

use App\Models\PlanLabel;
use App\Models\Deal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AssignCommissionPlanToDealsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting commission plan assignment for deals...');

        // Get all deals without a commission plan
        $dealsWithoutPlan = Deal::whereNull('plan')
            ->whereNotNull('final_commission')
            ->get();

        $this->command->info("Found {$dealsWithoutPlan->count()} deals without commission plans.");

        if ($dealsWithoutPlan->isEmpty()) {
            $this->command->info('No deals found without commission plans. All done!');
            return;
        }

        $updated = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($dealsWithoutPlan as $deal) {
            try {
                $planId = $this->determineNearestPlan($deal->final_commission);

                if ($planId) {
                    $deal->plan = $planId;
                    $deal->save();
                    $updated++;

                    $formula = PlanLabel::find($planId);
                    $this->command->line("✓ Deal #{$deal->id} '{$deal->name}' assigned to plan: {$formula->name} (Final Commission: {$deal->final_commission}%)");
                } else {
                    $skipped++;
                    $this->command->warn("⚠ Deal #{$deal->id} '{$deal->name}' skipped - no active plan label found (Final Commission: {$deal->final_commission}%)");
                }
            } catch (\Exception $e) {
                $errors++;
                $this->command->error("✗ Error processing Deal #{$deal->id}: {$e->getMessage()}");
                Log::error("Error assigning commission plan to deal {$deal->id}: {$e->getMessage()}");
            }
        }

        $this->command->newLine();
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('Commission Plan Assignment Summary:');
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info("✓ Successfully assigned: {$updated} deals");

        if ($skipped > 0) {
            $this->command->warn("⚠ Skipped (no matching plan): {$skipped} deals");
        }

        if ($errors > 0) {
            $this->command->error("✗ Errors: {$errors} deals");
        }

        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('Commission plan assignment completed!');
    }

    /**
     * Determine the nearest commission plan based on final_commission value
     *
     * @param float $finalCommission
     * @return int|null
     */
    private function determineNearestPlan(float $finalCommission): ?int
    {
        $formula = PlanLabel::where('is_active', true)
            ->selectRaw('*, ABS(final_commission - ?) as commission_diff', [$finalCommission])
            ->orderBy('commission_diff', 'asc')
            ->first();

        return $formula ? $formula->id : null;
    }
}

