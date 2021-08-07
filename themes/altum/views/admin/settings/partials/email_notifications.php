<?php defined('ALTUMCODE') || die() ?>

<div>
    <div class="form-group">
        <label for="emails"><?= language()->admin_settings->email_notifications->emails ?></label>
        <textarea id="emails" name="emails" class="form-control form-control-lg" rows="5"><?= settings()->email_notifications->emails ?></textarea>
        <small class="form-text text-muted"><?= language()->admin_settings->email_notifications->emails_help ?></small>
    </div>

    <div class="custom-control custom-switch my-3">
        <input id="new_user" name="new_user" type="checkbox" class="custom-control-input" <?= settings()->email_notifications->new_user ? 'checked' : null?>>
        <label class="custom-control-label" for="new_user"><?= language()->admin_settings->email_notifications->new_user ?></label>
        <small class="form-text text-muted"><?= language()->admin_settings->email_notifications->new_user_help ?></small>
    </div>

    <div class="custom-control custom-switch my-3">
        <input id="new_payment" name="new_payment" type="checkbox" class="custom-control-input" <?= settings()->email_notifications->new_payment ? 'checked' : null?>>
        <label class="custom-control-label" for="new_payment"><?= language()->admin_settings->email_notifications->new_payment ?></label>
        <small class="form-text text-muted"><?= language()->admin_settings->email_notifications->new_payment_help ?></small>
    </div>

    <div class="custom-control custom-switch my-3">
        <input id="new_domain" name="new_domain" type="checkbox" class="custom-control-input" <?= settings()->email_notifications->new_domain ? 'checked' : null?>>
        <label class="custom-control-label" for="new_domain"><?= language()->admin_settings->email_notifications->new_domain ?></label>
        <small class="form-text text-muted"><?= language()->admin_settings->email_notifications->new_domain_help ?></small>
    </div>

    <div class="custom-control custom-switch my-3">
        <input id="new_affiliate_withdrawal" name="new_affiliate_withdrawal" type="checkbox" class="custom-control-input" <?= settings()->email_notifications->new_affiliate_withdrawal ? 'checked' : null?>>
        <label class="custom-control-label" for="new_affiliate_withdrawal"><?= language()->admin_settings->email_notifications->new_affiliate_withdrawal ?></label>
        <small class="form-text text-muted"><?= language()->admin_settings->email_notifications->new_affiliate_withdrawal_help ?></small>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= language()->global->update ?></button>
