package com.wikia.selenium.tests;

import static com.thoughtworks.selenium.grid.tools.ThreadSafeSeleniumSessionStorage.session;
import static org.testng.AssertJUnit.assertFalse;
import static org.testng.AssertJUnit.assertTrue;

import org.testng.annotations.Test;

public class ToolbarTest extends BaseTest {

	@Test(groups="oasis")
	public void testEnsuresThatToolbarIsPresent() throws Exception {
		session().open("index.php");
		session().waitForPageToLoad(this.getTimeout());
		assertTrue(session().isElementPresent("WikiaFooter"));
		assertTrue(session().isElementPresent("//div[contains(@class, 'toolbar')]"));
	}
}
