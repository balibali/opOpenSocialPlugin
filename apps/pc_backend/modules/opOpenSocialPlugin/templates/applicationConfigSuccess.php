<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<h2><?php echo __('OpenSocialアプリケーション設定') ?></h2>

<form action="<?php echo url_for('opOpenSocialPlugin/applicationConfig') ?>" method="post">
<table>
<?php echo $applicationConfigForm ?>
<tr><td colspan="2"><input type="submit" value="<?php echo __('設定変更') ?>" /></td></tr>
</table>
</form>

