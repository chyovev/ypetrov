<?php
trait MailerTrait {

    ///////////////////////////////////////////////////////////////////////////
    // email addresses for sending notifications
    // are stored in the `config` table following the structure
    // address1,address2,address3
    public function getNotificationEmailAddresses(string $var): array {
        $configRepository = $this->entityManager->getRepository('Config');
        $configObject     = $configRepository->findOneBy(['setting' => $var]);
        
        $emails           = $configObject ? explode(',', $configObject->getValue()) : [];

        return $this->filterOutInvalidAddresses($emails);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function filterOutInvalidAddresses(array $emailsArray): array {
        // remove duplicates
        $emailsArray = array_filter($emailsArray);

        // log invalid email addresses and remove them from the array
        foreach ($emailsArray as $key => $email) {
            if ( ! isEmailValid($email)) {
                Logger::logError('Notification email address "' . $email . '" is invalid and therefore filtered out (' . get_class($this)) . ')';
                unset($emailsArray[$key]);
            }
        }

        // set in tact keys
        return array_values($emailsArray);
    }

    ///////////////////////////////////////////////////////////////////////////
    private function sendNotificationEmail(array $emailContent): void {
        $mail     = Initializer::PHPMailer(true); // throw exceptions = true for logging purposes
        $settings = SMTPConfig::getParams();

        try {
            // server settings
            if ($settings['is_smtp']) {
                $mail->isSMTP();
                $mail->SMTPAuth = $settings['has_smtp_auth'];
            }
            $mail->SMTPDebug  = false;
            $mail->Host       = $settings['host'];
            $mail->Username   = $settings['username'];
            $mail->Password   = $settings['password'];
            $mail->SMTPSecure = $settings['smtp_secure'];
            $mail->Port       = $settings['port'];
            $mail->CharSet    = $settings['charset'];
            $mail->From       = $settings['from_address'];
            $mail->FromName   = $settings['from_name'];

            foreach ($emailContent['recipients'] as $item) {
                $mail->addAddress($item);
            }

            $mail->isHTML(true);
            $mail->Subject = $emailContent['subject'];
            $mail->Body    = $emailContent['body'];
            $mail->AltBody = strip_tags($emailContent['body']);

            $mail->send();
        }
        catch (Exception $e) {
            Logger::logError($mail->ErrorInfo);
        }
    }

}