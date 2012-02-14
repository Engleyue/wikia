<?php
/**
* Internationalisation file for the UserSignup extension.
*
* @addtogroup Languages
*/

$messages = array();

$messages['en'] = array(
	'usersignup-page-title' => 'Join Wikia',
	'usersignup-page-captcha-label' => 'Blurry Word',

	'usersignup-error-username-length' => "Oops, your username can't be more than 50 characters.",
	'usersignup-error-invalid-user' => 'Invalid user. Please login first.',
	'usersignup-error-invalid-email' => 'Please enter a valid email address.',
	'usersignup-error-symbols-in-username' => 'Oops, your username can only contain letters and numbers.',
	'usersignup-error-empty-email' => 'Oops, please fill in your email address.',
	'usersignup-error-empty-username' => 'Oops, please fill in the username field.',
	'usersignup-error-already-confirmed' => "You've already confirmed this email address.",
	'usersignup-error-throttled-email' => "Oops, you've requested too many confirmation emails be sent to you today. Try again in a little while.",
	'usersignup-error-too-many-changes' => "You've reached the maximum limit for email changes today. Please try again later.",
	'usersignup-error-password-length' => "Oops, your password is too long. Please choose a password that's 50 characters or less.",
	'usersignup-error-confirmed-user' => 'Looks like you\'ve already confirmed your email address for $1!  Check our your [$2 user profile].',

	// facebook sign-up
	'usersignup-facebook-heading' => 'Finish Signing Up',
	'usersignup-facebook-create-account' => 'Create account',
	'usersignup-facebook-email-tooltip' => 'If you\'d like to use a different email address you can change it later in your Preferences.',
	'usersignup-facebook-have-an-account-heading' => ' Already have an account?',
	'usersignup-facebook-have-an-account' => ' Connect your existing Wikia username with Facebook instead.',
	'usersignup-facebook-proxy-email' => 'Anonymous facebook email',

	// user preferences
	'usersignup-user-pref-emailconfirmlink' => 'Request a new confirmation email',
	'usersignup-user-pref-confirmemail_send' => 'Resend my confirmation email',
	'usersignup-user-pref-emailauthenticated' => 'Thanks! Your email was confirmed on $2 at $3.',
	'usersignup-user-pref-emailnotauthenticated' => 'Check your email and click the confirmation link to finish changing your email to: $1',
	'usersignup-user-pref-unconfirmed-emailnotauthenticated' => 'Oh, no! Your email is unconfirmed. Email features won\'t work until you confirm your email address.',
	'usersignup-user-pref-reconfirmation-email-sent' => 'Almost there! We\'ve sent a new confirmation email to $1. Check your email and click on the link to finish confirming your email address.',
	'usersignup-user-pref-noemailprefs' => ' Looks like we don\'t have an email address for you. Please enter an email address above.',

	// Special:ConfirmEmail
	'usersignup-confirm-email-unconfirmed-emailnotauthenticated' => 'Oh, no! Your email is unconfirmed. We\'ve sent you an email, click the confirmation link there to confirm.',
	'usersignup-user-pref-confirmemail_noemail' => 'Looks like we don\'t have an email address for you. Go to [[Special:Preferences|user preferences]] to enter one.',

	// confirm email
	'usersignup-confirm-page-title' => 'Confirm your email',
	'usersignup-confirm-email-resend-email' => "Send me another confirmation email",
	'usersignup-confirm-email-change-email-content' => "I want to use a different email address.",
	'usersignup-confirm-email-change-email' => 'Change my email address',
	'usersignup-confirm-email-new-email-label' => 'New email',
	'usersignup-confirm-email-update' => 'Update',
	'usersignup-confirm-email-tooltip' => 'Did you enter an email address that you can\'t confirm, or do you want to use a different email address? Don\'t worry, use the link below to change your email address and get a new confirmation email.',
	'usersignup-resend-email-heading-success' => 'New email sent',
	'usersignup-resend-email-heading-failure' => 'Email not re-sent',
	'usersignup-confirm-page-heading-confirmed-user' => 'Congrats!',
	'usersignup-confirm-page-subheading-confirmed-user' => 'You\'re already confirmed',

	// confirmation email
	'usersignup-confirmation-heading' => 'Almost there',
	'usersignup-confirmation-heading-email-resent' => 'New email sent',
	'usersignup-confirmation-subheading' => 'Check your email',
	'usersignup-confirmation-email-sent' => "We sent an email to '''$1'''.
	
Click the confirmation link in your email to finish creating your account.",  // intentional line break
	'usersignup-confirmation-email_subject' => 'Almost there! Confirm your Wikia account',
	'usersignup-confirmation-email-greeting' => 'Hi $USERNAME,',
	'usersignup-confirmation-email-content' => 'You\'re one step away from creating your account on Wikia! Click the link below to confirm your email address and get started.
<br/><br/>
<a style="color:#2C85D5;" href="$CONFIRMURL">$CONFIRMURL</a>',
	'usersignup-confirmation-email-signature' => 'The Wikia Team',
	'usersignup-confirmation-email_body' => 'Hi $2,

You\'re one step away from creating your account on Wikia! Click the link below to confirm your email address and get started.

$3

The Wikia Team


___________________________________________

To check out the latest happenings on Wikia, visit http://community.wikia.com
Want to control which emails you receive? Go to: {{fullurl:{{ns:special}}:Preferences}}',
	'usersignup-confirmation-email_body-html' => 'Hi $1,

You\'re one step away from creating your account on Wikia! Click the link below to confirm your email address and get started.

<a style="color:#2C85D5;" href="$2">$2</a>

The Wikia Team


___________________________________________

To check out the latest happenings on Wikia, visit <a style="color:#2a87d5;text-decoration:none;" href="http://community.wikia.com">community.wikia.com</a>
Want to control which emails you receive? Go to your <a href="{{fullurl:{{ns:special}}:Preferences}}" style="color:#2a87d5;text-decoration:none;">Preferences</a>',

	// reconfirmation email
	'usersignup-reconfirmation-email-sent' => "Your email address has been changed to $1. We've sent you a new confirmation email. Please confirm the new email address.",
	'usersignup-reconfirmation-email_subject' => 'Confirm your email address change on Wikia',
	'usersignup-reconfirmation-email-greeting' => 'Hi $USERNAME',
	'usersignup-reconfirmation-email-content' => 'Please click the link below to confirm your change of email address on Wikia.
<br/><br/>
<a style="color:#2C85D5;" href="$CONFIRMURL">$CONFIRMURL</a>
<br/><br/>
You\'ll continue to recieve email at your old email address until you confirm this one.',
	'usersignup-reconfirmation-email-signature' => 'The Wikia Team',
	'usersignup-reconfirmation-email_body' => 'Hi $2,

Please click the link below to confirm your change of email address on Wikia.

$3

You\'ll continue to recieve email at your old email address until you confirm this one.

The Wikia Team


___________________________________________

To check out the latest happenings on Wikia, visit http://community.wikia.com
Want to control which emails you receive? Go to: {{fullurl:{{ns:special}}:Preferences}}',
	'usersignup-reconfirmation-email_body-HTML' => 'Hi $1,

Please click the link below to confirm your change of email address on Wikia.

<a style="color:#2C85D5;" href="$2">$2</a>

You\'ll continue to recieve email at your old email address until you confirm this one.

The Wikia Team


___________________________________________

To check out the latest happenings on Wikia, visit <a style="color:#2a87d5;text-decoration:none;" href="http://community.wikia.com">community.wikia.com</a>
Want to control which emails you receive? Go to your <a href="{{fullurl:{{ns:special}}:Preferences}}" style="color:#2a87d5;text-decoration:none;">Preferences</a>',

	// welcome email
	'usersignup-welcome-email-subject' => 'Wecome to Wikia, $USERNAME!',
	'usersignup-welcome-email-greeting' => 'Hi $USERNAME',
	'usersignup-welcome-email-heading' => 'We\'re happy to welcome you to Wikia and {{SITENAME}}! Here are some things you can do to get started.',
	'usersignup-welcome-email-edit-profile-heading' => 'Edit your profile.',
	'usersignup-welcome-email-edit-profile-content' => 'Add a profile photo and a few quick facts about yourself on your {{SITENAME}} profile.',
	'usersignup-welcome-email-edit-profile-button' => 'Go to profile',
	'usersignup-welcome-email-learn-basic-heading' => 'Learn the basics.',
	'usersignup-welcome-email-learn-basic-content' => 'Get a quick tutorial on the basics of Wikia: how to edit a page, your user profile, change your preferences, and more.',
	'usersignup-welcome-email-learn-basic-button' => 'Check it out',
	'usersignup-welcome-email-explore-wiki-heading' => 'Explore more wikis.',
	'usersignup-welcome-email-explore-wiki-content' => 'There are thousands of wikis on Wikia, find more wikis that interest you by heading to one of our hubs: <a style="color:#2C85D5;" href="http://www.wikia.com/Gaming">Video Games</a>, <a style="color:#2C85D5;" href="http://www.wikia.com/Entertainment">Entertainment</a>, or <a style="color:#2C85D5;" href="http://www.wikia.com/Lifestyle">Lifestyle</a>.',
	'usersignup-welcome-email-explore-wiki-button' => 'Go to wikia.com',
	'usersignup-welcome-email-content' => 'Want more information? Find advice, answers, and the Wikia community at <a style="color:#2C85D5;" href="http://community.wikia.com">Community Central</a>. Happy editing!',
	'usersignup-welcome-email-signature' => 'The Wikia Team',
	'usersignup-welcome-email-body' => 'Hi $USERNAME,

We\'re happy to welcome you to Wikia and {{SITENAME}}! Here are some things you can do to get started.

Edit your profile.

Add a profile photo and a few quick facts about yourself on your {{SITENAME}} profile.

Go to $EDITPROFILEURL

Learn the basics.

Get a quick tutorial on the basics of Wikia: how to edit a page, your user profile, change your preferences, and more.

Check it out ($LEARNBASICURL)

Explore more wikis.

There are thousands of wikis on Wikia, find more wikis that interest you by heading to one of our hubs: Video Games (http://www.wikia.com/Gaming), Entertainment (http://www.wikia.com/Entertainment), or Lifestyle (http://www.wikia.com/Lifestyle).

Go to $EXPLOREWIKISURL

Want more information? Find advice, answers, and the Wikia community at Community Central (http://www.community.wikia.com). Happy editing!

The Wikia Team


___________________________________________

To check out the latest happenings on Wikia, visit http://community.wikia.com
Want to control which emails you receive? Go to: {{fullurl:{{ns:special}}:Preferences}}',

	// Signup main form
	'usersignup-heading' => 'Join Wikia Today',
	'usersignup-heading-byemail' => 'Create an account for someone else',
	'usersignup-marketing-wikia' => 'Start collaborating with millions of people from around the world who come together to share what they know and love.',
	'usersignup-marketing-login' => 'Already a user? [[Special:UserLogin|Log in]]',
	'usersignup-marketing-benefits' => 'Be a part of something huge',
	'usersignup-marketing-community-heading' => 'Collaborate',
	'usersignup-marketing-community' => 'Discover and explore subjects ranging from video games to movies and tv. Meet people with similar interests and passions.',
	'usersignup-marketing-global-heading' => 'Create',
	'usersignup-marketing-global' => 'Start a wiki. Start small, grow big, with the help of others.',
	'usersignup-marketing-creativity-heading' => 'Be original',
	'usersignup-marketing-creativity' => 'Use Wikia to express your creativity with polls and top 10 lists, photo and video galleries, apps and more.',
	'usersignup-createaccount-byemail' => 'Create an account for someone else',

	// Signup form validation
	'usersignup-error-captcha' => "The word you entered didn't match the word in the box, try again!",

	// account creation email
	'usersignup-account-creation-heading' => 'Success!',
	'usersignup-account-creation-subheading' => 'We\'ve sent an email to $1',
	'usersignup-account-creation-email-sent' => 'You\'ve started the account creation process for $2. We\'ve sent them an email at $1 with a temporary password and a confirmation link.


$2 will need to click on the link in the email we sent them to confirm their account and change their temporary password to finish creating their account.


[{{fullurl:{{ns:special}}:UserSignup|byemail=1}} Create more accounts] on {{SITENAME}}',
	'usersignup-account-creation-email-subject' => 'An account has been created for you on Wikia!',
	'usersignup-account-creation-email-greeting' => 'Hello,',
	'usersignup-account-creation-email-content' => 'An account has been created for you on {{SITENAME}}. To access your account and change your temporary password click the link below and log in with username "$USERNAME" and password "$NEWPASSWORD".
<br/><br/>
Please log in at <a style="color:#2C85D5;" href="{{fullurl:{{ns:special}}:UserLogin}}">{{fullurl:{{ns:special}}:UserLogin}}</a>
<br/><br/>
If you did not want this account to be created you can simply ignore this email or contact our Community Support team with any questions.',
	'usersignup-account-creation-email-signature' => 'The Wikia Team',
	'usersignup-account-creation-email-body' => 'Hello,

An account has been created for you on {{SITENAME}}. To access your account and change your temporary password click the link below and log in with username "$2" and password "$3".

Please log in at {{fullurl:{{ns:special}}:UserLogin}}

If you did not want this account to be created you can simply ignore this email or contact our Community Support team with any questions.

The Wikia Team


___________________________________________

To check out the latest happenings on Wikia, visit http://community.wikia.com
Want to control which emails you receive? Go to: {{fullurl:{{ns:special}}:Preferences}}',
	'usersignup-account-creation-email-body-HTML' => 'Hello,

An account has been created for you on {{SITENAME}}. To access your account and change your temporary password click the link below and log in with username "$2" and password "$3".

Please log in at <a style="color:#2C85D5;" href="{{fullurl:{{ns:special}}:UserLogin}}">{{fullurl:{{ns:special}}:UserLogin}}</a>

If you did not want this account to be created you can simply ignore this email or contact our Community Support team with any questions.

The Wikia Team


___________________________________________

To check out the latest happenings on Wikia, visit <a style="color:#2a87d5;text-decoration:none;" href="http://community.wikia.com">community.wikia.com</a>
Want to control which emails you receive? Go to your <a href="{{fullurl:{{ns:special}}:Preferences}}" style="color:#2a87d5;text-decoration:none;">Preferences</a>',

	// confirmation reminder email
	'usersignup-confirmation-reminder-email_subject' => "Don't be a stranger...",
	'usersignup-confirmation-reminder-email-greeting' => 'Hi $USERNAME',
	'usersignup-confirmation-reminder-email-content' => 'It\'s been a few days, but it looks like you haven\'t finished creating your account on Wikia yet. It\'s easy. Just click the confirmation link below:
<br/><br/>
<a style="color:#2C85D5;" href="$CONFIRMURL">$CONFIRMURL</a>
<br/><br/>
If you don\'t confirm within 23 days your username, $USERNAME, will become available again, so don\'t wait!',
	'usersignup-confirmation-reminder-email-signature' => 'The Wikia Team',
	'usersignup-confirmation-reminder-email_body' => 'Hi $1,

It\'s been a few days, but it looks like you haven\'t finished creating your account on Wikia yet. It\'s easy. Just click the confirmation link below:

$2

If you don\'t confirm within 23 days your username, $1, will become available again, so don\'t wait!

The Wikia Team


___________________________________________

To check out the latest happenings on Wikia, visit http://community.wikia.com
Want to control which emails you receive? Go to: {{fullurl:{{ns:special}}:Preferences}}',
	'usersignup-confirmation-reminder-email_body-HTML' => 'Hi $1,

It\'s been a few days, but it looks like you haven\'t finished creating your account on Wikia yet. It\'s easy. Just click the confirmation link below:

<a style="color:#2C85D5;" href="$2">$2</a>

If you don\'t confirm within 23 days your username, $1, will become available again, so don\'t wait!

The Wikia Team


___________________________________________

To check out the latest happenings on Wikia, visit <a style="color:#2a87d5;text-decoration:none;" href="http://community.wikia.com">community.wikia.com</a>
Want to control which emails you receive? Go to your <a href="{{fullurl:{{ns:special}}:Preferences}}" style="color:#2a87d5;text-decoration:none;">Preferences</a>',

);