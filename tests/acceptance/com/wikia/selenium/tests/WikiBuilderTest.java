package com.wikia.selenium.tests;

import static com.thoughtworks.selenium.grid.tools.ThreadSafeSeleniumSessionStorage.session;
import static org.testng.AssertJUnit.assertTrue;

import java.math.BigInteger;
import java.util.Random;

import org.testng.annotations.Test;

public class WikiBuilderTest extends BaseTest {

	@Test(groups={"oasis","CI"})
	public void testWikiBuilderTest() throws Exception {
		String rand = Long.toString(Math.abs(new Random().nextLong()), 36).toUpperCase();
		loginAsStaff();
		session().open("index.php?title=Special:WikiBuilder");
		waitForElement("//textarea[@id='Description']");
		session().type("Description", "abc123");
		session().click("//input[@value='Save Intro']");

		waitForElementVisible("ThemeTab");
		session().click("//section[@id='ThemeTab']/div/ul/li[2]/img");
		session().click("//input[@value='Save Theme']");

		waitForElementVisible("//section[@id='WikiBuilder']/div/div[@class='step3']");
		session().type("//input[@type='text']", rand);
		session().click("//input[@value='Save Pages']");

		waitForElementVisible("//section[@id='WikiBuilder']/div/div[@class='step4']");
		session().click("//input[@value='Continue to your wiki']");

		session().waitForPageToLoad(TIMEOUT);
		waitForElement("//div[@id='WikiaArticle']");
		assertTrue(session().isTextPresent("abc123"));

		session().type("search", rand);
		session().click("//input[@type='submit']");
		waitForElement("//header/h1");
		assertTrue(session().isTextPresent(rand));
		session().waitForPageToLoad(TIMEOUT);
	}
}