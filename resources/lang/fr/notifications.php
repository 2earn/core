<?php
return [
    'settings' => [
        'change_pwd_sms' => 'Mot de passe modifié par SMS',
        'validate_phone_email' => 'Validation du téléphone par e-mail',
        'delivery_sms' => 'Notification de livraison par SMS',
        'share_purchase' => 'Achat d\'actions',
        'cash_to_bfs' => 'Espèces converties en BFS',
        'order_completed' => 'Commande terminée',
        'survey_participation' => 'Participation au sondage',
        'financial_request_sent' => 'Demande financière envoyée',
        'partnership_request_sent' => 'Demande de partenariat envoyée',
        'partnership_request_validated' => 'Demande de partenariat validée',
        'platform_validation_request_approved' => 'Demande de validation de plateforme approuvée',
        'platform_validation_request_rejected' => 'Demande de validation de plateforme rejetée',
        'platform_type_change_request_approved' => 'Demande de changement de type de plateforme approuvée',
        'platform_type_change_request_rejected' => 'Demande de changement de type de plateforme rejetée',
        'platform_change_request_approved' => 'Demande de modification de plateforme approuvée',
        'platform_change_request_rejected' => 'Demande de modification de plateforme rejetée',
        'platform_role_assignment_approved' => 'Attribution de rôle de plateforme approuvée',
        'platform_role_assignment_rejected' => 'Attribution de rôle de plateforme rejetée',
        'deal_validation_request_approved' => 'Demande de validation de deal approuvée',
        'deal_validation_request_rejected' => 'Demande de validation de deal rejetée',
        'deal_change_request_approved' => 'Demande de modification de deal approuvée',
        'deal_change_request_rejected' => 'Demande de modification de deal rejetée',
        'partner_payment_validated' => 'Paiement partenaire validé',
        'partner_payment_rejected' => 'Paiement partenaire rejeté',
    ],
    'delivery_sms' => [
        'body' => 'Bonjour :name, votre colis est en route !',
        'action' => 'Suivre votre livraison',
    ],
    'share_purchase' => [
        'body' => 'Achat d\'actions confirmé !',
        'action' => 'Suivre vos actions',
    ],
    'cash_to_bfs' => [
        'body' => 'Espèces converties en BFS avec succès',
        'action' => 'Suivre vos BFS',
    ],
    'order_completed' => [
        'body' => 'Commande terminée avec succès',
        'action' => 'Voir votre commande',
    ],
    'survey_participation' => [
        'body' => 'Participation au sondage terminée avec succès',
        'action' => 'Voir le sondage',
    ],
    'financial_request_sent' => [
        'body' => 'Demande financière envoyée avec succès',
        'action' => 'Voir la demande financière',
    ],
    'partnership_request_sent' => [
        'body' => 'Votre demande de partenariat a été envoyée avec succès',
        'action' => 'Voir la demande de partenariat',
    ],
    'partnership_request_validated' => [
        'body' => 'Votre demande de partenariat a été validée avec succès',
        'action' => 'Voir les détails du partenariat',
    ],
    'platform_validation_request_approved' => [
        'body' => 'Votre demande de validation pour la plateforme ":platform_name" a été approuvée avec succès',
        'action' => 'Voir les détails de la plateforme',
    ],
    'platform_validation_request_rejected' => [
        'body' => 'Votre demande de validation pour la plateforme ":platform_name" a été rejetée',
        'action' => 'Voir les détails de la plateforme',
    ],
    'platform_type_change_request_approved' => [
        'body' => 'Votre demande de changement de type pour la plateforme ":platform_name" de :old_type à :new_type a été approuvée',
        'action' => 'Voir les détails de la plateforme',
    ],
    'platform_type_change_request_rejected' => [
        'body' => 'Votre demande de changement de type pour la plateforme ":platform_name" de :old_type à :new_type a été rejetée',
        'action' => 'Voir les détails de la plateforme',
    ],
    'platform_change_request_approved' => [
        'body' => 'Votre demande de modification pour la plateforme ":platform_name" a été approuvée',
        'action' => 'Voir les détails de la plateforme',
    ],
    'platform_change_request_rejected' => [
        'body' => 'Votre demande de modification pour la plateforme ":platform_name" a été rejetée',
        'action' => 'Voir les détails de la plateforme',
    ],
    'platform_role_assignment_approved' => [
        'body' => 'Vous avez été assigné comme :role pour la plateforme ":platform_name"',
        'action' => 'Voir les détails de la plateforme',
    ],
    'platform_role_assignment_rejected' => [
        'body' => 'Votre attribution de rôle comme :role pour la plateforme ":platform_name" a été rejetée',
        'action' => 'Voir les détails',
    ],
    'deal_validation_request_approved' => [
        'body' => 'Votre demande de validation pour le deal ":deal_name" a été approuvée avec succès',
        'action' => 'Voir les détails du deal',
    ],
    'deal_validation_request_rejected' => [
        'body' => 'Votre demande de validation pour le deal ":deal_name" a été rejetée',
        'action' => 'Voir les détails du deal',
    ],
    'deal_change_request_approved' => [
        'body' => 'Votre demande de modification pour le deal ":deal_name" a été approuvée',
        'action' => 'Voir les détails du deal',
    ],
    'deal_change_request_rejected' => [
        'body' => 'Votre demande de modification pour le deal ":deal_name" a été rejetée',
        'action' => 'Voir les détails du deal',
    ],
    'partner_payment_validated' => [
        'body' => 'Votre paiement partenaire de :amount a été validé avec succès',
        'action' => 'Voir les détails du paiement',
    ],
    'partner_payment_rejected' => [
        'body' => 'Votre paiement partenaire de :amount a été rejeté. Raison: :reason',
        'action' => 'Voir les détails du paiement',
    ],
];
