<?php namespace Anomaly\UsersModule\User\Permission;

use Anomaly\Streams\Platform\Addon\Addon;
use Anomaly\Streams\Platform\Addon\AddonCollection;
use Anomaly\UsersModule\User\Contract\UserInterface;
use Illuminate\Config\Repository;
use Illuminate\Translation\Translator;

/**
 * Class PermissionFormFields
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 * @package       Anomaly\UsersModule\User\Permission
 */
class PermissionFormFields
{

    /**
     * Handle the fields.
     *
     * @param PermissionFormBuilder $builder
     * @param AddonCollection       $addons
     * @param Translator            $translator
     * @param Repository            $config
     */
    public function handle(
        PermissionFormBuilder $builder,
        AddonCollection $addons,
        Translator $translator,
        Repository $config
    ) {
        /* @var UserInterface $user */
        $user      = $builder->getEntry();
        $roles     = $user->getRoles();
        $inherited = $roles->permissions();

        $fields = [];

        $namespaces = array_merge(['streams'], $addons->withConfig('permissions')->namespaces());

        /**
         * gather all the addons with a
         * permissions configuration file.
         *
         * @var Addon $addon
         */
        foreach ($namespaces as $namespace) {

            foreach ($config->get($namespace . '::permissions', []) as $group => $permissions) {

                /**
                 * Determine the general
                 * form UI components.
                 */
                $label = $namespace . '::permission.' . $group . '.name';

                if (!$translator->has($warning = $namespace . '::permission.' . $group . '.warning')) {
                    $warning = null;
                }

                if (!$translator->has($instructions = $namespace . '::permission.' . $group . '.instructions')) {
                    $instructions = null;
                }

                /**
                 * Gather the available
                 * permissions for the group.
                 */
                $available = array_combine(
                    array_map(
                        function ($permission) use ($namespace, $group) {
                            return $namespace . '::' . $group . '.' . $permission;
                        },
                        $permissions
                    ),
                    array_map(
                        function ($permission) use ($namespace, $group) {
                            return $namespace . '::permission.' . $group . '.option.' . $permission;
                        },
                        $permissions
                    )
                );

                /**
                 * Build the checkboxes field
                 * type to handle the UI.
                 */
                $fields[$namespace . '::' . $group] = [
                    'label'        => $label,
                    'warning'      => $warning,
                    'instructions' => $instructions,
                    'type'         => 'anomaly.field_type.checkboxes',
                    'value'        => array_merge($user->getPermissions(), $inherited),
                    'config'       => [
                        'disabled' => $inherited,
                        'options'  => $available
                    ]
                ];
            }
        }

        $builder->setFields($fields);
    }
}
