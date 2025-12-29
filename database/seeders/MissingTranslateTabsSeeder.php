<?php

namespace Database\Seeders;

use Core\Models\translatetabs;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MissingTranslateTabsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $translations = [
            [
                'name' => 'langar',
                'value' => 'العربية',
                'valueEn' => 'Arabic',
                'valueFr' => 'Arabe',
                'valueTr' => 'Arapça',
                'valueEs' => 'Árabe',
                'valueRu' => 'Арабский',
                'valueDe' => 'Arabisch',
            ],
            [
                'name' => 'langfr',
                'value' => 'الفرنسية',
                'valueEn' => 'French',
                'valueFr' => 'Français',
                'valueTr' => 'Fransızca',
                'valueEs' => 'Francés',
                'valueRu' => 'Французский',
                'valueDe' => 'Französisch',
            ],
            [
                'name' => 'langtr',
                'value' => 'التركية',
                'valueEn' => 'Turkish',
                'valueFr' => 'Turc',
                'valueTr' => 'Türkçe',
                'valueEs' => 'Turco',
                'valueRu' => 'Турецкий',
                'valueDe' => 'Türkisch',
            ],
            [
                'name' => 'langen',
                'value' => 'الإنجليزية',
                'valueEn' => 'English',
                'valueFr' => 'Anglais',
                'valueTr' => 'İngilizce',
                'valueEs' => 'Inglés',
                'valueRu' => 'Английский',
                'valueDe' => 'Englisch',
            ],
            [
                'name' => 'langru',
                'value' => 'الروسية',
                'valueEn' => 'Russian',
                'valueFr' => 'Russe',
                'valueTr' => 'Rusça',
                'valueEs' => 'Ruso',
                'valueRu' => 'Русский',
                'valueDe' => 'Russisch',
            ],
            [
                'name' => 'langes',
                'value' => 'الإسبانية',
                'valueEn' => 'Spanish',
                'valueFr' => 'Espagnol',
                'valueTr' => 'İspanyolca',
                'valueEs' => 'Español',
                'valueRu' => 'Испанский',
                'valueDe' => 'Spanisch',
            ],
            [
                'name' => 'langde',
                'value' => 'الألمانية',
                'valueEn' => 'German',
                'valueFr' => 'Allemand',
                'valueTr' => 'Almanca',
                'valueEs' => 'Alemán',
                'valueRu' => 'Немецкий',
                'valueDe' => 'Deutsch',
            ],
            [
                'name' => 'Users balances Recaps',
                'value' => 'ملخصات أرصدة المستخدمين',
                'valueEn' => 'Users balances Recaps',
                'valueFr' => 'Récapitulatifs des soldes des utilisateurs',
                'valueTr' => 'Kullanıcı bakiye özetleri',
                'valueEs' => 'Resúmenes de saldos de usuarios',
                'valueRu' => 'Сводки балансов пользователей',
                'valueDe' => 'Zusammenfassungen der Benutzerguthaben',
            ],
            [
                'name' => 'Business Sectors',
                'value' => 'القطاعات التجارية',
                'valueEn' => 'Business Sectors',
                'valueFr' => 'Secteurs d\'activité',
                'valueTr' => 'İş Sektörleri',
                'valueEs' => 'Sectores empresariales',
                'valueRu' => 'Бизнес-секторы',
                'valueDe' => 'Geschäftsbereiche',
            ],
            [
                'name' => 'Explore our diverse range of business opportunities',
                'value' => 'استكشف مجموعتنا المتنوعة من فرص الأعمال',
                'valueEn' => 'Explore our diverse range of business opportunities',
                'valueFr' => 'Explorez notre gamme diversifiée d\'opportunités commerciales',
                'valueTr' => 'Çeşitli iş fırsatlarımızı keşfedin',
                'valueEs' => 'Explore nuestra diversa gama de oportunidades de negocio',
                'valueRu' => 'Изучите наш разнообразный спектр бизнес-возможностей',
                'valueDe' => 'Entdecken Sie unser vielfältiges Angebot an Geschäftsmöglichkeiten',
            ],
            [
                'name' => 'Database to File Completed',
                'value' => 'اكتمل التصدير من قاعدة البيانات إلى الملف',
                'valueEn' => 'Database to File Completed',
                'valueFr' => 'Exportation de la base de données vers le fichier terminée',
                'valueTr' => 'Veritabanından dosyaya aktarım tamamlandı',
                'valueEs' => 'Exportación de base de datos a archivo completada',
                'valueRu' => 'Экспорт базы данных в файл завершен',
                'valueDe' => 'Datenbank zu Datei Export abgeschlossen',
            ],
            [
                'name' => 'Translation Merge',
                'value' => 'دمج الترجمات',
                'valueEn' => 'Translation Merge',
                'valueFr' => 'Fusion des traductions',
                'valueTr' => 'Çeviri birleştirme',
                'valueEs' => 'Fusión de traducciones',
                'valueRu' => 'Объединение переводов',
                'valueDe' => 'Übersetzungszusammenführung',
            ],
            [
                'name' => 'notifications.order_completed.action',
                'value' => 'عرض الطلب',
                'valueEn' => 'View Order',
                'valueFr' => 'Voir la commande',
                'valueTr' => 'Siparişi görüntüle',
                'valueEs' => 'Ver pedido',
                'valueRu' => 'Просмотреть заказ',
                'valueDe' => 'Bestellung anzeigen',
            ],
            [
                'name' => 'Mark them all as read',
                'value' => 'وضع علامة على الكل كمقروء',
                'valueEn' => 'Mark them all as read',
                'valueFr' => 'Tout marquer comme lu',
                'valueTr' => 'Tümünü okundu olarak işaretle',
                'valueEs' => 'Marcar todos como leídos',
                'valueRu' => 'Отметить все как прочитанные',
                'valueDe' => 'Alle als gelesen markieren',
            ],
            [
                'name' => 'Notifications list Completed',
                'value' => 'قائمة الإشعارات مكتملة',
                'valueEn' => 'Notifications list Completed',
                'valueFr' => 'Liste des notifications terminée',
                'valueTr' => 'Bildirim listesi tamamlandı',
                'valueEs' => 'Lista de notificaciones completada',
                'valueRu' => 'Список уведомлений завершен',
                'valueDe' => 'Benachrichtigungsliste abgeschlossen',
            ],
            [
                'name' => 'notifications.settings.order_completed',
                'value' => 'إشعارات إكمال الطلب',
                'valueEn' => 'Order completion notifications',
                'valueFr' => 'Notifications de commande terminée',
                'valueTr' => 'Sipariş tamamlama bildirimleri',
                'valueEs' => 'Notificaciones de pedido completado',
                'valueRu' => 'Уведомления о завершении заказа',
                'valueDe' => 'Benachrichtigungen über Bestellabschluss',
            ],
        ];

        foreach ($translations as $translation) {
            translatetabs::updateOrCreate(
                ['name' => $translation['name']],
                $translation
            );
        }

        $this->command->info('Missing translation keys have been successfully seeded!');
    }
}

