package com.wikia.selenium.tests;

import static com.thoughtworks.selenium.grid.tools.ThreadSafeSeleniumSessionStorage.session;
import static org.testng.AssertJUnit.assertTrue;
import static org.testng.AssertJUnit.assertFalse;

import org.testng.annotations.Test;

public class NotificationsModuleTest extends BaseTest {

	private void randomPage() throws Exception {
		session().open("index.php?title=Special:Random");
		session().waitForPageToLoad(this.getTimeout());
	}

	@Test(groups={"oasis", "CI"})
	public void testTalkPageNotificationSend() throws Exception {
		loginAsRegular();
		session().open("index.php?title=User_talk:" + getTestConfig().getString("ci.user.wikiastaff.username") + "&action=edit&section=new");
		session().waitForPageToLoad(this.getTimeout());

		// leave message for staff
		session().type("//input[@name='wpSummary']", "Message");
		doEdit("Test message --~~~~");
		doSave();
	}

	@Test(groups={"oasis", "CI"},dependsOnMethods={"testTalkPageNotificationSend"})
	public void testTalkPageNotificationReceive() throws Exception {
		// check messages for staff
		loginAsStaff();

		String xPath = "//ul[@id='WikiaNotifications']//div[@data-type='1']";
		assertTrue(session().isElementPresent(xPath));

		// dismiss the message
		session().click(xPath + "//a[contains(@class,'close-notification')]");

		// message should be removed from DOM
		waitForElementNotPresent(xPath);

		// message should now be dismissed
		randomPage();
		assertFalse(session().isElementPresent(xPath));
	}

	@Test(groups={"oasis", "CI"})
	public void testCommunityMessageNotificationSend() throws Exception {
		loginAsStaff();

		// edit community message
		editArticle("Mediawiki:community-corner", "Community message test --~~~~");
	}

	@Test(groups={"oasis", "CI"},dependsOnMethods={"testCommunityMessageNotificationSend"})
	public void testCommunityMessageNotificationReceive() throws Exception {
		loginAsRegular();
	
		// check confirmation
		String xPath = "//ul[@id='WikiaNotifications']//div[@data-type='2']";
		assertTrue(session().isElementPresent(xPath));

		// dismiss the message
		session().click(xPath + "//a[contains(@class, 'close-notification')]");
		waitForElementNotPresent(xPath, this.getTimeout());

		// message should be removed from DOM
		assertFalse(session().isElementPresent(xPath));

		// message should now be dismissed
		randomPage();
		assertFalse(session().isElementPresent(xPath));
	}

	@Test(groups={"oasis", "CI"})
	public void testPreferencesAndLogoutConfirmation() throws Exception {
		loginAsRegular();

		// go to user preferences
		session().open("index.php?title=Special:Preferences");
		session().waitForPageToLoad(this.getTimeout());

		String xPath = "//div[@class='WikiaConfirmation']";
		assertFalse(session().isElementPresent(xPath));

		// save preferences
		session().click("prefcontrol");
		session().waitForPageToLoad(this.getTimeout());

		// check confirmation
		assertTrue(session().isElementPresent(xPath));

		// logout
		session().click("link=Log out");
		session().waitForPageToLoad(this.getTimeout());

		assertTrue(session().isElementPresent(xPath));
	}

	@Test(groups={"oasis", "CI"})
	public void testPageActionsConfirmation() throws Exception {
		String pageA = "User:" + getTestConfig().getString("ci.user.wikiastaff.username") + "/Foo";
		String pageB = "User:" + getTestConfig().getString("ci.user.wikiastaff.username") + "/Bar";
		String xPath = "//div[@class='WikiaConfirmation']";
		String reason = "Notifications test";

		loginAsStaff();
		
		editArticle("MediaWiki:Mainpage", "Mainpage");

		// let's create test page (A)
		editArticle(pageA, "Test --~~~~");
		assertFalse(session().isElementPresent(xPath));

		// move a page (A -> B)
		moveArticle(pageA, pageB, reason);

		// we should be redirected to B
		assertTrue(session().getEval("window.wgPageName").equals(pageB));
		assertTrue(session().isElementPresent(xPath));

		// delete a page (B)
		deleteArticle(pageB, "label=regexp:^.*Author request", reason);

		// we should be redirected to main page
		assertTrue(session().getEval("window.wgIsMainpage ? 'true' : 'false'").equals("true"));
		assertTrue(session().isElementPresent(xPath));

		// undelete a page (B)
		undeleteArticle(pageB, reason);

		// we should be redirected to B
		assertTrue(session().getEval("window.wgPageName").equals(pageB));
		assertTrue(session().isElementPresent(xPath));

		// cleanup
		deleteArticle(pageB, "label=regexp:^.*Author request", reason);
	}
}
