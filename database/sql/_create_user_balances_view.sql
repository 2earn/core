CREATE
OR REPLACE VIEW user_balances_view AS

SELECT `id`,
       `balance_operation_id`,
       `operator_id`,
       `beneficiary_id`,
       `value`,
       `actual_balance`,
       `reference`,
       `description`,
       `unit_price`,
       `created_at`,
       `updated_at`
FROM `shares_balances`
UNION
SELECT `id`,
       `balance_operation_id`,
       `operator_id`,
       `beneficiary_id`,
       `value`,
       `actual_balance`,
       `reference`,
       `description`,
       NULL AS `unit_price`,
       `created_at`,
       `updated_at`
FROM `bfss_balances`
UNION ALL
SELECT `id`,
       `balance_operation_id`,
       `operator_id`,
       `beneficiary_id`,
       `value`,
       `actual_balance`,
       `reference`,
       `description`,
       NULL AS `unit_price`,
       `created_at`,
       `updated_at`
FROM `cash_balances`
UNION ALL
SELECT `id`,
       `balance_operation_id`,
       `operator_id`,
       `beneficiary_id`,
       `value`,
       `actual_balance`,
       `reference`,
       `description`,
       NULL AS `unit_price`,
       `created_at`,
       `updated_at`
FROM `discount_balances`
UNION ALL
SELECT `id`,
       `balance_operation_id`,
       `operator_id`,
       `beneficiary_id`,
       `value`,
       `actual_balance`,
       `reference`,
       `description`,
       NULL AS `unit_price`,
       `created_at`,
       `updated_at`
FROM `sms_balances`

ORDER BY `created_at` ASC;
