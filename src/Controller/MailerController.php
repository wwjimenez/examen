<?php 
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerController extends AbstractController
{
    /**
     * @Route("/email")
     */
    public function sendEmail(MailerInterface $mailer)
    {
        $senduser='walter86.79@gmail.com';
        $email = (new Email())
        ->from('walter86.79@gmail.com')
        ->To($senduser)
        ->text('Hey! Learn the best practices of building HTML emails and play with ready-to-go templates. Mailtrap’s Guide on How to Build HTML Email is live on our blog')
    ->html('<html>
          <body>
            <p>Hey<br>
               Hey! Learn the best practices of building HTML emails and play with ready-to-go templates.</p>
            <p><a href="/blog/build-html-email/">Mailtrap’s Guide on How to Build HTML Email</a> is live on our blog</p>
          </body>
        </html>');
//….  
  $mailer->send($email);

        // …
      return new Response(
          'Email was sent:'.$senduser
       );
    }
}
