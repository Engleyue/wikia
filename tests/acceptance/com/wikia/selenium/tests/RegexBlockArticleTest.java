package com.wikia.selenium.tests;

import static com.thoughtworks.selenium.grid.tools.ThreadSafeSeleniumSessionStorage.session;
import static org.testng.AssertJUnit.assertFalse;
import static org.testng.AssertJUnit.assertTrue;

import org.testng.annotations.Test;

import java.util.Date;

// FIXME: the tests here need to be redesigned

public class RegexBlockArticleTest extends BaseTest {

	@Test(groups={"CI","uncyclopedia"})
	public void testRegexBlockArticleSave() throws Exception {
		loginAsRegular();
		String content = (new Date()).toString();
		editArticle("Project:WikiaBotAutomatedTest",
				"Regex Block Test. Tested: " + content + " \n");
		assertFalse(session().isElementPresent("wpSave"));
	}

	@Test(groups={"CI","uncyclopedia"})
	public void testRegexBlockArticleSaveUnmatching() throws Exception {
		login();
		String content = (new Date()).toString();
		editArticle("Project:WikiaBotAutomatedTest",
				"Regex Block Test. Tested: " + content + " \n");
		assertTrue(session().isElementPresent("wpSave"));
	}

	@Test(groups={"CI","uncyclopedia"})
	public void testRegexBlockArticleAddFreshMatch() throws Exception {
		loginAsStaff();
		session().open("index.php?title=Special:RegexBlock");
		session().type("wpRegexBlockedAddress", getTestConfig().getString("ci.user.wikiabot.username"));
		session().check("wpRegexBlockedExact");
		clickAndWait("wpRegexBlockedSubmit");

		login();
		session().open("index.php?title=Project:WikiaBotAutomatedTest");
		clickAndWait("//a[@data-id='edit']");
		assertTrue(session().isElementPresent("wpSave"));
	}

	@Test(groups={"CI","uncyclopedia"})
	public void testRegexBlockArticleClearFreshMatch() throws Exception {
		loginAsStaff();
		String content = (new Date()).toString();
		// session().open("index.php?title=Special:SpamRegex&action=delete&limit=50&offset=0&text=Sonoelementariakillus");
		editArticle("Project:WikiaBotAutomatedTest",
				"Regex Block Test. Tested: " + content + " \n");
		assertFalse(session().isElementPresent("spamprotected"));
	}
}
