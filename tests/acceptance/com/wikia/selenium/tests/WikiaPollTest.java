package com.wikia.selenium.tests;

import java.io.ByteArrayInputStream;
import java.util.UUID;
import java.util.Date;

import org.testng.annotations.Test;
import static com.thoughtworks.selenium.grid.tools.ThreadSafeSeleniumSessionStorage.session;
import static org.testng.AssertJUnit.*;

public class WikiaPollTest extends BaseTest {

	@Test(groups={"oasis"})
	public void testSpecialCreatePoll() throws Exception {
		login();
		session().open("index.php?title=Special:CreatePoll");
		session().waitForPageToLoad(this.getTimeout());

		// type some stuff
		String pollname = "thisismyreallongpollname" + new Date().toString();
		session().type("//input[@name='question']", pollname);
		session().type("//div[@id='CreateWikiaPoll']/form/ul/li[2]/span/input", "some answer");
		
		// test add field button
		Integer numberOfFields = Integer.parseInt(session().getEval("window.$('#CreateWikiaPoll').find('li').length;"));
		session().click("//div[@class='add-new']/a");
		Integer newNumberOfFields = numberOfFields + 1;
		waitForElement("//div[@id='CreateWikiaPoll']/form/ul/li[" + newNumberOfFields + "]");

		// click save
		session().click("//div[@class='toolbar']/input[@class='create']");
		session().waitForPageToLoad(this.getTimeout());
		
		// check for poll that can be voted on with at least one option to select
		assertTrue(session().isElementPresent("//section[@class='WikiaPoll']/form/ul[@class='vote']/li[1]/label/input"));

		// cast vote
		session().click("//section[@class='WikiaPoll']/form/ul[@class='vote']/li[1]/label/input");
		session().click("//section[@class='WikiaPoll']/form/details/input");
		waitForElement("//section[@class='WikiaPoll']/form/ul[@class='results']");
				

		// clean up
		doDelete("label=regexp:.*Author request", "Clean up after test");
	}
}
