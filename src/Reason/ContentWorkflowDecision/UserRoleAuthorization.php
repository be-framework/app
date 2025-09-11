<?php

declare(strict_types=1);

namespace Be\App\Reason\ContentWorkflowDecision;

/**
 * User role authorization ontology - determines user capabilities
 * Based on role hierarchy and business rules
 */
final readonly class UserRoleAuthorization
{
    private array $roleHierarchy;
    private array $permissions;

    public function __construct()
    {
        $this->roleHierarchy = [
            'admin' => ['editor', 'contributor', 'subscriber'],
            'editor' => ['contributor', 'subscriber'], 
            'contributor' => ['subscriber'],
            'subscriber' => []
        ];

        $this->permissions = [
            'admin' => ['publish', 'edit_any', 'delete_any', 'manage_users', 'system_config'],
            'editor' => ['publish', 'edit_any', 'delete_own', 'moderate'],
            'contributor' => ['create_draft', 'edit_own', 'submit_review'],
            'subscriber' => ['read', 'comment']
        ];
    }

    public function canUserPerformAction(string $userRole, string $action): bool
    {
        if (!isset($this->permissions[$userRole])) {
            return false;
        }

        // Check direct permissions
        if (in_array($action, $this->permissions[$userRole], true)) {
            return true;
        }

        // Check inherited permissions from lower roles
        if (isset($this->roleHierarchy[$userRole])) {
            foreach ($this->roleHierarchy[$userRole] as $inheritedRole) {
                if ($this->canUserPerformAction($inheritedRole, $action)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function hasRoleAuthority(string $userRole, string $targetRole): bool
    {
        if ($userRole === $targetRole) {
            return true;
        }

        if (!isset($this->roleHierarchy[$userRole])) {
            return false;
        }

        return in_array($targetRole, $this->roleHierarchy[$userRole], true);
    }

    public function getMaximumRole(string $currentRole): string
    {
        $roles = ['subscriber', 'contributor', 'editor', 'admin'];
        $maxIndex = array_search($currentRole, $roles, true);
        
        return $maxIndex !== false ? $roles[$maxIndex] : 'subscriber';
    }

    public function getAuthorizationReason(string $userRole, string $action, bool $isAuthorized): string
    {
        if ($isAuthorized) {
            return "User role '{$userRole}' has sufficient privileges for action '{$action}'";
        }

        $requiredRoles = [];
        foreach ($this->permissions as $role => $actions) {
            if (in_array($action, $actions, true)) {
                $requiredRoles[] = $role;
            }
        }

        $required = empty($requiredRoles) ? 'unknown role' : implode(' or ', $requiredRoles);
        return "User role '{$userRole}' lacks privileges for action '{$action}' (requires: {$required})";
    }
}