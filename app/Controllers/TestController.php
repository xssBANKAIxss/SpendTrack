<?php

namespace App\Controllers;

class TestController extends BaseController
{
  public function sendEmail()
  {
    $email = \Config\Services::email();

    $email->setTo('oliverfiesta30@gmail.com'); // <-- put YOUR real email here to receive the test

    $email->setFrom(env('email.fromEmail'), env('email.fromName'));
    $email->setSubject('SpendTrack Test Email');
    $email->setMessage('If you got this, your SMTP settings in .env are working correctly.');

    if ($email->send()) {
      return 'SUCCESS: Email sent! Check your inbox (and spam folder).';
    } else {
      return 'FAILED: ' . print_r($email->printDebugger(['headers']), true);
    }
  }
}
