<?php
	if (!empty($notifications)) {
?>
<ul id="WikiaNotifications" class="WikiaNotifications">
<?php
		foreach ($notifications as $notification) {
?>
	<li>
<?php
			switch($notification['type']) {
				// render badge notification
				case NotificationsModule::NOTIFICATION_NEW_ACHIEVEMENTS_BADGE:
?>
		<div data-type="<?= $notification['type'] ?>" class="WikiaBadgeNotification">
			<a class="close"></a>
			<img class="badge" src="<?= $notification['data']['picture'] ?>" width="90" height="90" alt="<?= $notification['data']['name'] ?>">
			<p>
				<big><?= $notification['data']['points'] ?></big>
				<?= $notification['message'] ?>
			</p>
			<details><a href="<?= htmlspecialchars($notification['data']['userPage']) ?>"><?= wfMsg('oasis-badge-notification-see-more') ?></a></details>
		</div>
<?php
					break;

				// render talk page / edit similar / community message notification
				case NotificationsModule::NOTIFICATION_TALK_PAGE_MESSAGE:
				case NotificationsModule::NOTIFICATION_EDIT_SIMILAR:
				case NotificationsModule::NOTIFICATION_COMMUNITY_MESSAGE:
?>
		<div data-type="<?= $notification['type'] ?>">
			<a class="close"></a>
			<?= $notification['message'] ?>
		</div>
<?php
					break;

				case NotificationsModule::NOTIFICATION_SITEWIDE:
					$first = 1;
					foreach ($notification['message'] as $msgId => $data) {
?>
		<div data-type="<?= $notification['type'] ?>" id="msg_<?= $msgId ?>" style="display: <?= $first ? 'block' : 'none' ?>">
			<a class="close"></a><?= $data['text'] ?>
		</div>
<?php
						$first = 0;
					}
					break;
				
				// render generic notification
				default:
?>
		<div data-type="<?= $notification['type'] ?>"><?= $notification['message'] ?></div>
<?php
			}
?>
	</li>
<?php
		}
?>
</ul>
<?php
	}
?>
