CREATE
OR REPLACE VIEW user_balances_view AS

SELECT `id`,
       `balance_operation_id`,
       `operator_id`,
       `beneficiary_id`,
       `value`,
       `actual_balance`,
       `ref`,
       `description`,
       `unit_price`,
       `win_purchase_amount`,
       `gifted_shares`,
       `created_at`,
       `updated_at`
FROM `action_balances`
UNION
SELECT `id`,
       `balance_operation_id`,
       `operator_id`,
       `beneficiary_id`,
       `value`,
       `actual_balance`,
       `ref`,
       `description`,
       NULL AS `unit_price`,
       NULL AS `win_purchase_amount`,
       NULL AS `gifted_shares`,
       `created_at`,
       `updated_at`
FROM `b_f_ss_balances`
UNION ALL
SELECT `id`,
       `balance_operation_id`,
       `operator_id`,
       `beneficiary_id`,
       `value`,
       `actual_balance`,
       `ref`,
       `description`,
       NULL AS `unit_price`,
       NULL AS `win_purchase_amount`,
       NULL AS `gifted_shares`,
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
       `ref`,
       `description`,
       NULL AS `unit_price`,
       NULL AS `win_purchase_amount`,
       NULL AS `gifted_shares`,
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
       `ref`,
       `description`,
       NULL AS `unit_price`,
       NULL AS `win_purchase_amount`,
       NULL AS `gifted_shares`,
       `created_at`,
       `updated_at`
FROM `s_m_s_balances`

ORDER BY `created_at` ASC;
