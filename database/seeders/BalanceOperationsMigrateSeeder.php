<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BalanceOperationsMigrateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "╔═══════════════════════════════════════════════════════════════════════╗\n";
        echo "║  MIGRATION BALANCE_OPERATION_ID AVEC FACTEUR 1000 (ANTI-CROISEMENT)  ║\n";
        echo "╚═══════════════════════════════════════════════════════════════════════╝\n\n";

        // Mapping des IDs
        $mappings = [
            1 => 4, 5 => 29, 6 => 3, 13 => 24, 14 => 26, 16 => 23,
            23 => 39, 25 => 38, 28 => 25, 39 => 19, 42 => 22, 43 => 21,
            44 => 20, 46 => 27, 47 => 28, 48 => 15, 49 => 59, 50 => 60,
            51 => 61, 52 => 58, 53 => 30, 54 => 31, 55 => 32, 56 => 7,
            57 => 41, 58 => 18, 59 => 17, 60 => 16, 18 => 62, 38 => 17
        ];

        $tables = [
            'cash_balances', 'bfss_balances', 'discount_balances',
            'tree_balances', 'sms_balances', 'shares_balances', 'chance_balances',
        ];

        $log = [];
        $log[] = "Date: " . date(config('app.date_format'));
        $log[] = "Stratégie: Multiplication par 1000 pour éviter les croisements";
        $log[] = "";

        try {
            // ═══════════════════════════════════════════════════════════════════════
            // ÉTAPE 1: Suppression des contraintes FK
            // ═══════════════════════════════════════════════════════════════════════
            echo "╔═══════════════════════════════════════════════════════════════════════╗\n";
            echo "║ ÉTAPE 1: Suppression des contraintes FK                              ║\n";
            echo "╚═══════════════════════════════════════════════════════════════════════╝\n\n";

            $log[] = "=== ÉTAPE 1: Suppression des contraintes FK ===";

            foreach ($tables as $table) {
                if (!DB::getSchemaBuilder()->hasTable($table)) {
                    echo "⚠️  {$table}: Table non trouvée\n";
                    $log[] = "⚠️  {$table}: Table non trouvée";
                    continue;
                }

                // Récupérer les FK sur balance_operation_id
                $fks = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = '{$table}'
            AND COLUMN_NAME = 'balance_operation_id'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

                foreach ($fks as $fk) {
                    try {
                        DB::statement("ALTER TABLE {$table} DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
                        echo "✅ {$table}: FK '{$fk->CONSTRAINT_NAME}' supprimée\n";
                        $log[] = "✅ {$table}: FK '{$fk->CONSTRAINT_NAME}' supprimée";
                    } catch (Exception $e) {
                        echo "⚠️  {$table}: Erreur suppression FK - {$e->getMessage()}\n";
                        $log[] = "⚠️  {$table}: Erreur - {$e->getMessage()}";
                    }
                }
            }

            echo "\n";
            $log[] = "";

            // ═══════════════════════════════════════════════════════════════════════
            // ÉTAPE 2: Migration avec facteur 1000 (oldId → newId * 1000)
            // ═══════════════════════════════════════════════════════════════════════
            echo "╔═══════════════════════════════════════════════════════════════════════╗\n";
            echo "║ ÉTAPE 2: Migration avec facteur 1000                                 ║\n";
            echo "╚═══════════════════════════════════════════════════════════════════════╝\n\n";

            $log[] = "=== ÉTAPE 2: Migration avec facteur 1000 ===";
            $step2Results = [];

            foreach ($tables as $table) {
                if (!DB::getSchemaBuilder()->hasTable($table)) continue;

                echo "📋 {$table}:\n";
                $log[] = "{$table}:";
                $tableUpdated = 0;

                foreach ($mappings as $oldId => $newId) {
                    $count = DB::table($table)->where('balance_operation_id', $oldId)->count();

                    if ($count > 0) {
                        $tempId = $newId * 1000; // Facteur 1000
                        DB::table($table)
                            ->where('balance_operation_id', $oldId)
                            ->update(['balance_operation_id' => $tempId]);

                        echo "   ✓ {$oldId} → {$tempId} ({$count} enr.)\n";
                        $log[] = "   {$oldId} → {$tempId} ({$count})";
                        $tableUpdated += $count;
                        $step2Results[$table][$oldId] = ['temp_id' => $tempId, 'count' => $count];
                    }
                }

                if ($tableUpdated > 0) {
                    echo "   Total: {$tableUpdated} enregistrements\n\n";
                    $log[] = "   Total: {$tableUpdated}";
                } else {
                    echo "   Aucune modification\n\n";
                    $log[] = "   Aucune modification";
                }
                $log[] = "";
            }

            // ═══════════════════════════════════════════════════════════════════════
            // ÉTAPE 3: Identifier et isoler les IDs < 1000 (non migrés)
            // ═══════════════════════════════════════════════════════════════════════
            echo "╔═══════════════════════════════════════════════════════════════════════╗\n";
            echo "║ ÉTAPE 3: Isoler les IDs non migrés (< 1000)                          ║\n";
            echo "╚═══════════════════════════════════════════════════════════════════════╝\n\n";

            $log[] = "=== ÉTAPE 3: IDs non migrés (< 1000) ===";
            $nonMigrated = [];
            $totalNonMigrated = 0;

            foreach ($tables as $table) {
                if (!DB::getSchemaBuilder()->hasTable($table)) continue;

                // IDs < 1000 = non migrés
                $ids = DB::table($table)
                    ->where('balance_operation_id', '<', 1000)
                    ->select('balance_operation_id', DB::raw('COUNT(*) as count'))
                    ->groupBy('balance_operation_id')
                    ->get();

                if ($ids->isNotEmpty()) {
                    echo "📋 {$table}:\n";
                    $log[] = "{$table}:";

                    foreach ($ids as $id) {
                        $isolatedId = $id->balance_operation_id * 100000;

                        DB::table($table)
                            ->where('balance_operation_id', $id->balance_operation_id)
                            ->update(['balance_operation_id' => $isolatedId]);

                        echo "   ⚠️  ID {$id->balance_operation_id} → {$isolatedId} ({$id->count} enr.) [NON MIGRÉ]\n";
                        $log[] = "   ⚠️  {$id->balance_operation_id} → {$isolatedId} ({$id->count}) [NON MIGRÉ]";

                        $nonMigrated[$table][] = [
                            'original_id' => $id->balance_operation_id,
                            'isolated_id' => $isolatedId,
                            'count' => $id->count
                        ];
                        $totalNonMigrated += $id->count;
                    }
                    echo "\n";
                    $log[] = "";
                }
            }

            if ($totalNonMigrated === 0) {
                echo "✅ Aucun ID non migré trouvé!\n\n";
                $log[] = "✅ Aucun ID non migré";
            } else {
                echo "⚠️  Total: {$totalNonMigrated} enregistrements non migrés (isolés en x100000)\n\n";
                $log[] = "⚠️  Total: {$totalNonMigrated} enregistrements non migrés";
            }
            $log[] = "";

            // ═══════════════════════════════════════════════════════════════════════
            // ÉTAPE 4: Diviser par 1000 les IDs entre 1000 et 100000
            // ═══════════════════════════════════════════════════════════════════════
            echo "╔═══════════════════════════════════════════════════════════════════════╗\n";
            echo "║ ÉTAPE 4: Finalisation - Division par 1000                            ║\n";
            echo "╚═══════════════════════════════════════════════════════════════════════╝\n\n";

            $log[] = "=== ÉTAPE 4: Division par 1000 ===";
            $step4Results = [];

            foreach ($tables as $table) {
                if (!DB::getSchemaBuilder()->hasTable($table)) continue;

                // IDs >= 1000 et < 100000 = migrés avec facteur 1000
                $ids = DB::table($table)
                    ->where('balance_operation_id', '>=', 1000)
                    ->where('balance_operation_id', '<', 100000)
                    ->select('balance_operation_id', DB::raw('COUNT(*) as count'))
                    ->groupBy('balance_operation_id')
                    ->get();

                if ($ids->isNotEmpty()) {
                    echo "📋 {$table}:\n";
                    $log[] = "{$table}:";

                    foreach ($ids as $id) {
                        $finalId = intval($id->balance_operation_id / 1000);

                        DB::table($table)
                            ->where('balance_operation_id', $id->balance_operation_id)
                            ->update(['balance_operation_id' => $finalId]);

                        echo "   ✓ {$id->balance_operation_id} → {$finalId} ({$id->count} enr.)\n";
                        $log[] = "   {$id->balance_operation_id} → {$finalId} ({$id->count})";

                        $step4Results[$table][] = [
                            'temp_id' => $id->balance_operation_id,
                            'final_id' => $finalId,
                            'count' => $id->count
                        ];
                    }
                    echo "\n";
                    $log[] = "";
                }
            }

            // ═══════════════════════════════════════════════════════════════════════
            // RAPPORT FINAL
            // ═══════════════════════════════════════════════════════════════════════
            echo "╔═══════════════════════════════════════════════════════════════════════╗\n";
            echo "║ RAPPORT FINAL                                                         ║\n";
            echo "╚═══════════════════════════════════════════════════════════════════════╝\n\n";

            $log[] = "=== RAPPORT FINAL ===";

            echo "📊 État des migrations:\n\n";
            $log[] = "";
            $log[] = "État des migrations:";

            foreach ($tables as $table) {
                if (!DB::getSchemaBuilder()->hasTable($table)) continue;

                $migrated = DB::table($table)
                    ->where('balance_operation_id', '<', 100000)
                    ->count();

                $isolated = DB::table($table)
                    ->where('balance_operation_id', '>=', 100000)
                    ->count();

                $total = $migrated + $isolated;

                $status = $isolated === 0 ? "✅" : "⚠️";
                echo sprintf("%-22s: %s %4d migrés", $table, $status, $migrated);
                $log[] = sprintf("%-22s: %s %4d migrés", $table, $status, $migrated);

                if ($isolated > 0) {
                    echo sprintf(", ⚠️  %d NON migrés (IDs >= 100000)", $isolated);
                    $log[] = sprintf("                        ⚠️  %d NON migrés", $isolated);
                }
                echo "\n";
            }

            echo "\n";
            $log[] = "";

            // Liste des IDs non migrés
            if (!empty($nonMigrated)) {
                echo "⚠️  IDs NON MIGRÉS (à traiter manuellement):\n";
                echo str_repeat("-", 71) . "\n";
                $log[] = "⚠️  IDs NON MIGRÉS (à traiter manuellement):";
                $log[] = str_repeat("-", 50);

                foreach ($nonMigrated as $table => $ids) {
                    echo "\n{$table}:\n";
                    $log[] = "";
                    $log[] = "{$table}:";

                    foreach ($ids as $data) {
                        echo sprintf("  ID Original: %3d → Isolé: %7d (%d enr.)\n",
                            $data['original_id'],
                            $data['isolated_id'],
                            $data['count']
                        );
                        $log[] = sprintf("  %3d → %7d (%d enr.)",
                            $data['original_id'],
                            $data['isolated_id'],
                            $data['count']
                        );
                    }
                }
                echo "\n";
                $log[] = "";
            }

            echo "✅ Migration terminée avec succès!\n";
            echo "Date: " . date(config('app.date_format')) . "\n\n";
            $log[] = "✅ Migration terminée";
            $log[] = "Date: " . date(config('app.date_format'));

            // Sauvegarder le log
            file_put_contents(__DIR__ . '/migration_1000_factor_log.txt', implode("\n", $log));

            // Sauvegarder le rapport JSON
            $jsonReport = [
                'success' => true,
                'date' => date(config('app.date_format')),
                'step2_migrations' => $step2Results,
                'step3_non_migrated' => $nonMigrated,
                'step4_finalizations' => $step4Results,
                'total_non_migrated' => $totalNonMigrated,
            ];

            file_put_contents(__DIR__ . '/migration_1000_factor_report.json', json_encode($jsonReport, JSON_PRETTY_PRINT));

            echo "📄 Logs sauvegardés:\n";
            echo "   - migration_1000_factor_log.txt\n";
            echo "   - migration_1000_factor_report.json\n";

        } catch (Exception $e) {
            echo "\n❌ ERREUR: " . $e->getMessage() . "\n";
            echo "Trace: " . $e->getTraceAsString() . "\n";

            $log[] = "";
            $log[] = "❌ ERREUR: " . $e->getMessage();
            file_put_contents(__DIR__ . '/migration_1000_factor_ERROR.txt', implode("\n", $log));

            exit(1);
        }
    }
}
