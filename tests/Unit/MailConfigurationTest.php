<?php

namespace Tests\Unit;

use Tests\TestCase;

class MailConfigurationTest extends TestCase
{
    public function test_smtp_mailer_uses_tls_and_relaxed_ssl_verification_for_local_development(): void
    {
        $this->assertSame('tls', config('mail.mailers.smtp.encryption'));
        $this->assertFalse(config('mail.mailers.smtp.stream.ssl.verify_peer'));
        $this->assertFalse(config('mail.mailers.smtp.stream.ssl.verify_peer_name'));
    }
}
