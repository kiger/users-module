<?php namespace Anomaly\UsersModule\User\Form;

use Anomaly\UsersModule\User\UserModel;

/**
 * Class UserFormSections
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 * @package       Anomaly\UsersModule\User\Form
 */
class UserFormSections
{

    /**
     * Handle the form sections.
     *
     * @param UserFormBuilder $builder
     */
    public function handle(UserFormBuilder $builder, UserModel $users)
    {
        $fields = [
            'first_name',
            'last_name',
            'display_name',
            'username',
            'email',
            'activated',
            'enabled',
            'password',
            'roles'
        ];

        $assignments = $users->getAssignments();

        $profileFields = $assignments->notLocked()->fieldSlugs();

        $builder->setSections(
            [
                'general' => [
                    'fields' => $fields
                ],
                'profile' => [
                    'fields' => $profileFields
                ]
            ]
        );
    }
}
