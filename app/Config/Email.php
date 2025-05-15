<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    /**
     * @var string
     */
    public string $fromEmail  = 'erik.malvilanda@gmail.com';
    public string $fromName   = 'PPIC System - PT. MTU Indonesia';
    public string $recipients = '';

    /**
     * The "user agent"
     */
    public string $userAgent = 'CodeIgniter';

    /**
     * The mail sending protocol: mail, sendmail, smtp
     */
    public string $protocol = 'smtp';

    /**
     * The server path to Sendmail.
     */
    public string $mailPath = '/usr/sbin/sendmail';

    /**
     * SMTP Server Address
     */
    public string $SMTPHost = 'smtp.gmail.com';

    /**
     * SMTP Username
     */
    public string $SMTPUser = 'erik.malvilanda@gmail.com';

    /**
     * SMTP Password
     * PENTING: Jangan gunakan password Gmail biasa!
     * Harus menggunakan App Password dengan langkah:
     * 1. Aktifkan 2-Step Verification di https://myaccount.google.com/security
     * 2. Buka https://myaccount.google.com/apppasswords
     * 3. Di "Select app" pilih "Mail"
     * 4. Di "Select device" pilih "Other" dan beri nama "PPIC System"
     * 5. Klik "Generate"
     * 6. Copy 16-digit password yang muncul dan paste di bawah ini
     * 7. Hapus spasi di antara 4 digit (contoh: abcdabcdabcdabcd)
     */
    public string $SMTPPass = 'neda dcdv qmas kyvc';

    /**
     * SMTP Port
     */
    public int $SMTPPort = 587;

    /**
     * SMTP Timeout (in seconds)
     */
    public int $SMTPTimeout = 60;

    /**
     * Enable persistent SMTP connections
     */
    public bool $SMTPKeepAlive = false;

    /**
     * SMTP Encryption.
     * Pilihan: '', 'tls' atau 'ssl'. 
     * Untuk Gmail gunakan 'tls'
     */
    public string $SMTPCrypto = 'tls';

    /**
     * Enable word-wrap
     */
    public bool $wordWrap = true;

    /**
     * Character count to wrap at
     */
    public int $wrapChars = 76;

    /**
     * Type of mail, either 'text' or 'html'
     */
    public string $mailType = 'html';

    /**
     * Character set (utf-8, iso-8859-1, etc.)
     */
    public string $charset = 'UTF-8';

    /**
     * Whether to validate the email address
     */
    public bool $validate = false;

    /**
     * Email Priority. 1 = highest. 5 = lowest. 3 = normal
     */
    public int $priority = 3;

    /**
     * Newline character. (Use "\r\n" to comply with RFC 822)
     */
    public string $CRLF = "\r\n";

    /**
     * Newline character. (Use "\r\n" to comply with RFC 822)
     */
    public string $newline = "\r\n";

    /**
     * Enable BCC Batch Mode.
     */
    public bool $BCCBatchMode = false;

    /**
     * Number of emails in each BCC batch
     */
    public int $BCCBatchSize = 200;

    /**
     * Enable notify message from server
     */
    public bool $DSN = false;
}
