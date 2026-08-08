# Bootstrap administrator detection and remediation

Run these steps independently in each deployment. Back up the database first and follow the
organization's credential-handling and change-control procedures. The commands do not contact
another environment.

## Check

1. Run `php artisan admin:check-bootstrap`. Exit code `0` means the historical account identifiers
   were not found. Exit code `1` means the account exists and requires review.
2. To confirm whether the deployed account still accepts the historical password, obtain it from
   the approved secret source and run `php artisan admin:check-bootstrap --password='<candidate>'`.
   Avoid shell history and process-list exposure (for example, use a restricted maintenance shell
   and clear its history). The check is read-only and never prints the supplied password or hash.

## Remediate an existing deployment

The application deliberately does not mutate an existing user automatically. When the check finds
the account:

1. Confirm ownership and recent activity with the system owner before making changes.
2. Immediately rotate its password to a unique strong secret through the approved administrator
   workflow. Revoke active sessions/tokens using the deployment's established incident procedure.
3. If the account is not required, disable or remove it only through the approved, audited user
   administration procedure. Do not delete it directly from a migration.
4. Re-run the check. An identifier match will continue to report the account even after password
   rotation; use the optional password candidate check to confirm the historical password no longer
   matches, and record that result in the change ticket.

## Provision a new administrator

For a new environment, explicitly provide unique values and a password of at least 12 characters
containing upper- and lowercase letters, a number, and a symbol:

```sh
php artisan admin:provision \
  --first-name='Operations' --last-name='Administrator' \
  --email='unique-address@example.com' --mobile='unique-mobile' \
  --password='<unique-strong-secret>'
```

The command refuses weak credentials and conflicts; it never updates an existing user. Prefer an
ephemeral restricted shell and remove the password from shell history after provisioning.
