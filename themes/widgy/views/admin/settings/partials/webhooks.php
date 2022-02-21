<?php defined('ALTUMCODE') || die() ?>

<div>
    <div class="form-group">
        <label for="user_new"><?= l('admin_settings.webhooks.user_new') ?></label>
        <input id="user_new" type="url" name="user_new" class="form-control form-control-lg" value="<?= settings()->webhooks->user_new ?>" />
        <small class="form-text text-muted"><?= l('admin_settings.webhooks.user_new_help') ?></small>
    </div>

    <div class="form-group">
        <label for="user_delete"><?= l('admin_settings.webhooks.user_delete') ?></label>
        <input id="user_delete" type="url" name="user_delete" class="form-control form-control-lg" value="<?= settings()->webhooks->user_delete ?>" />
        <small class="form-text text-muted"><?= l('admin_settings.webhooks.user_delete_help') ?></small>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
