<?php

/**
 * Global message partial
 *
 * This partial is used globally to display messages on all site pages.
 *
 * @package WTS
 * @subpackage Global Message
 * @since 1.0.0
 */

require_once __DIR__ . '/../Helpers/DateHelper.php';

use WTS\Helpers\DateHelper;

/**
 * Banner messages.
 *
 * Date logic:
 * - start: first day the message can appear
 * - end: first day the message no longer appears
 *
 * Example:
 * start = 2026-05-01
 * end   = 2026-05-19
 *
 * This means the message shows from May 1 through May 18.
 */
function wts_get_banner_messages(): array
{
    return [
        [
            'enabled' => true,
            'start'   => '2026-05-11',
            'end'     => '2026-05-12',

            'fr' => [
                'class' => 'banner-urgent',
                'title_class' => '',
                'title' => '*** Avis important ***',
                'lines' => [
                    'Veuillez noter que le réseau sera interrompu aujourd’hui à 16 h 15 pour une durée approximative de 15 à 30 minutes afin d’effectuer des réparations d’urgence.',
                    'Pendant cette période, tous les appels seront interrompus et les courriels seront temporairement suspendus.',
                    'Les opérations normales devraient reprendre peu après l’interruption.',
                    'Merci de votre compréhension.',
                ],
            ],

            'en' => [
                'class' => 'banner-urgent',
                'title_class' => '',
                'title' => '*** Important Notice ***',
                'lines' => [
                    'Please note that the network will go down today at 4:15 PM for approximately 15–30 minutes to conduct emergency repairs.',
                    'During this time, all calls will be dropped and emails will be temporarily paused.',
                    'Normal operations are expected to resume shortly after the interruption.',
                    'Thank you for your understanding.',
                ],
            ],
        ],
        [
            'enabled' => true,
            'start'   => '2026-05-01',
            'end'     => '2026-05-19',

            'fr' => [
                'class' => 'banner-warning',
                'title_class' => '',
                'title' => '*** Portes Standard sera fermé le lundi 18 mai ***',
                'lines' => [
                    'Veuillez noter que nous serons fermés le lundi 18 mai à l’occasion de la fête de la Reine.',
                    'Nous reprendrons nos heures d’ouverture habituelles le mardi 19 mai. Merci de votre compréhension.',
                ],
            ],

            'en' => [
                'class' => 'banner-warning',
                'title_class' => '',
                'title' => '*** Standard Doors Will be Closed on Monday, May 18th ***',
                'lines' => [
                    'Please note that we will be closed on Monday, May 18<sup>th</sup>, in observance of Victoria Day.',
                    'We will resume regular business hours on Tuesday, May 19<sup>th</sup>. Thank you for your understanding.',
                ],
            ],
        ],

        [
            'enabled' => false,
            'start'   => '2026-12-15',
            'end'     => '2026-12-24',

            'fr' => [
                'class' => 'banner-warning',
                'title_class' => '',
                'title' => '*** Vacances d’hiver 2026 ***',
                'lines' => [
                    'Veuillez noter que nos bureaux seront fermés pour la période des Fêtes.',
                    'Toute l’équipe STANDARD vous remercie pour votre soutien continu et vous souhaite de Joyeuses Fêtes!',
                ],
            ],

            'en' => [
                'class' => 'banner-warning',
                'title_class' => '',
                'title' => '*** Winter Holidays 2026 ***',
                'lines' => [
                    'Please note that our offices will be closed for the holiday season.',
                    'The entire team at STANDARD thanks you for your continued support and wishes you Happy Holidays!',
                ],
            ],
        ],
    ];
}

/**
 * Render active banner messages.
 */
function wts_render_message_banner(string $lang, bool $isDev): void
{
    $dateHelper = new DateHelper();
    $messages = wts_get_banner_messages();

    foreach ($messages as $message) {
        if (($message['enabled'] ?? false) !== true) {
            continue;
        }

        /**
         * On the dev site, show all enabled messages regardless of date.
         * On production, only show messages within their date range.
         */
        if ($isDev !== true) {
            if (!wts_banner_is_active($dateHelper, $message)) {
                continue;
            }
        }

        if ($lang === 'bi') {
            echo wts_render_single_banner_message($message, 'fr');
            echo wts_render_single_banner_message($message, 'en');
        } else {
            echo wts_render_single_banner_message($message, $lang);
        }
    }
}

/**
 * Check whether a banner message is active today.
 */
function wts_banner_is_active(DateHelper $dateHelper, array $message): bool
{
    $start = $message['start'] ?? null;
    $end = $message['end'] ?? null;

    if ($start === null || $end === null) {
        return false;
    }

    return $dateHelper->isBetweenDates($start, $end);
}

/**
 * Render one banner message in one language.
 */
function wts_render_single_banner_message(array $message, string $lang): string
{
    if (!isset($message[$lang])) {
        return '';
    }

    $content = $message[$lang];

    $class = $content['class'] ?? 'leftalign';
    $titleClass = $content['title_class'] ?? 'redtext';
    $title = $content['title'] ?? '';
    $lines = $content['lines'] ?? [];

    ob_start();
    ?>
    <div class="<?php echo htmlspecialchars($class, ENT_QUOTES, 'UTF-8'); ?>">
        <?php if ($title !== '') : ?>
            <h1<?php echo $titleClass !== '' ? ' class="' . htmlspecialchars($titleClass, ENT_QUOTES, 'UTF-8') . '"' : ''; ?>>
                <?php echo wts_banner_allowed_html($title); ?>
            </h1>
        <?php endif; ?>

        <?php foreach ($lines as $line) : ?>
            <h3><?php echo wts_banner_allowed_html($line); ?></h3>
        <?php endforeach; ?>
    </div>
    <?php

    return ob_get_clean();
}

function wts_banner_allowed_html(string $html): string
{
    return strip_tags($html, '<sup><br><strong><b><em><i>');
}
