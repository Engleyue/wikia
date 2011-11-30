<?php
if ( !empty($err) ) {
        print $err;
}

echo wfMsgExt( 'specialcontact-intro-bad-ad', array( 'parse' ) );
?>

<h2><?= wfMsg( 'specialcontact-form-header' ) ?></h2>

<form id="contactform" method="post" action="" enctype="multipart/form-data">
<input hidden="wpContactCategory" value="bad-ad" />

<p>
<label for="wpContactWikiName"><?= wfMsg( 'specialcontact-label-bad-ad-link' ) ?></label>
<input name="wpContactWikiName" />
</p>

<p>
<label for="wpDescription"><?= wfmsg( 'specialcontact-label-ba-ad-description' ) ?></label>
<textarea name="wpDescription"></textarea>
</p>

<p>
<label for="wpScreenshot"><?= wfMsg( 'specialcontact-label-screenshot' ) ?></label>
<input name="wpScreenshot" type="file" accept="image/*" />
</p>
         
<?php if( !$isLoggedIn ) { ?>
        <table><tr> 
        <td style='width:200px'><input type='text' id='wpCaptchaWord' name='wpCaptchaWord' value='' /><br/>
                        <span class='captchDesc'><?= wfMsg('specialcontact-captchainfo') ?></span>
                        <input type='hidden' value='<?= $captchaIndex ?>' id='wpCaptchaId' name='wpCaptchaId'>
        </td>
        <td><img class='contactCaptch' height='50' src='<?= $captchaUrl ?>' /></td>
        </tr></table>
</p>
<?php } ?>

<input type="submit" value="<?= wfMsg( 'specialcontact-mail' ) ?>" />

<input type="hidden" id="wpBrowser" name="wpBrowser" value="<?php echo $_SERVER['HTTP_USER_AGENT']; ?>" />
</form>

<p><?= wfMsgExt( 'specialcontact-noform-footer', array( 'parse' ) ) ?></p>
