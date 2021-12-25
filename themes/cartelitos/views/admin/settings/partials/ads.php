<?php defined('ALTUMCODE') || die() ?>

<div>
    <p class="text-muted"><?= language()->admin_settings->ads->ads_help ?></p>

    <div class="form-group">
        <label for="header"><?= language()->admin_settings->ads->header ?></label>
        <textarea id="header" name="header" class="form-control form-control-lg"><?= settings()->ads->header ?></textarea>
    </div>

    <div class="form-group">
        <label for="footer"><?= language()->admin_settings->ads->footer ?></label>
        <textarea id="footer" name="footer" class="form-control form-control-lg"><?= settings()->ads->footer ?></textarea>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= language()->global->update ?></button>
