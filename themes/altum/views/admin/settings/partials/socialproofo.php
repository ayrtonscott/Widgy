<?php defined('ALTUMCODE') || die() ?>

<div>
    <div class="form-group">
        <label for="branding"><?= language()->admin_settings->socialproofo->branding ?></label>
        <textarea id="branding" name="branding" class="form-control form-control-lg"><?= settings()->socialproofo->branding ?></textarea>
        <small class="form-text text-muted"><?= language()->admin_settings->socialproofo->branding_help ?></small>
    </div>

    <div class="form-group">
        <label for="analytics_is_enabled"><i class="fa fa-fw fa-sm fa-chart-bar text-muted mr-1"></i> <?= language()->admin_settings->socialproofo->analytics_is_enabled ?></label>
        <select id="analytics_is_enabled" name="analytics_is_enabled" class="form-control form-control-lg">
            <option value="1" <?= settings()->socialproofo->analytics_is_enabled ? 'selected="selected"' : null ?>><?= language()->global->yes ?></option>
            <option value="0" <?= !settings()->socialproofo->analytics_is_enabled ? 'selected="selected"' : null ?>><?= language()->global->no ?></option>
        </select>
        <small class="form-text text-muted"><?= language()->admin_settings->socialproofo->analytics_is_enabled_help ?></small>
    </div>

    <div class="form-group">
        <label for="pixel_cache"><?= language()->admin_settings->socialproofo->pixel_cache ?></label>
        <input id="pixel_cache" type="number" min="0" name="pixel_cache" class="form-control form-control-lg" value="<?= settings()->socialproofo->pixel_cache ?>" />
        <small class="form-text text-muted"><?= language()->admin_settings->socialproofo->pixel_cache_help ?></small>
    </div>

</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= language()->global->update ?></button>
