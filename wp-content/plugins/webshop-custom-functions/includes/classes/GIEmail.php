<?php

class GIEmail
{
    public static function send(string $to, array $options): void
    {
        $mailer = WC()->mailer();
        $content = self::get_email_html($mailer, $options);

        $mailer->send($to, $options['subject'], $content);
    }

    private static function get_email_html($mailer, array $options): string
    {
        $default = array(
            'email_heading' => $options['subject'],
            'sent_to_admin' => false,
            'plain_text' => false,
            'email' => $mailer,
        );

        $args = array_merge($default, $options);

        return wc_get_template_html($options['template'], $args,'', WBC_PLUGIN_DIR);
    }
}
