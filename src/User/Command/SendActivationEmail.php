<?php namespace Anomaly\UsersModule\User\Command;

use Anomaly\SettingsModule\Setting\Contract\SettingRepositoryInterface;
use Anomaly\UsersModule\Activation\Contract\ActivationRepositoryInterface;
use Anomaly\UsersModule\User\Contract\UserInterface;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Mail\Mailer;
use Illuminate\Mail\Message;

/**
 * Class SendActivationEmail
 *
 * @link          http://anomaly.is/streams-platform
 * @author        AnomalyLabs, Inc. <hello@anomaly.is>
 * @author        Ryan Thompson <ryan@anomaly.is>
 * @package       Anomaly\UsersModule\User\Command
 */
class SendActivationEmail implements SelfHandling
{

    /**
     * The user instance.
     *
     * @var UserInterface
     */
    protected $user;

    /**
     * Create a new SendActivationEmail instance.
     *
     * @param UserInterface $user
     */
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * Handle the command.
     *
     * @param Mailer                     $mailer
     * @param SettingRepositoryInterface $settings
     * @return bool
     */
    public function handle(Mailer $mailer, SettingRepositoryInterface $settings)
    {
        return $mailer->send(
            'anomaly.module.users::emails/activate',
            ['user' => $this->user],
            function (Message $message) use ($settings) {
                $message
                    ->subject('Activate Your Account')
                    ->to($this->user->getEmail(), $this->user->getDisplayName())
                    ->from($settings->value('streams::server_email', 'noreply@localhost.com'));
            }
        );
    }
}