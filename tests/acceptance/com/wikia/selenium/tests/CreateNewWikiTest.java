package com.wikia.selenium.tests;

import static com.thoughtworks.selenium.grid.tools.ThreadSafeSeleniumSessionStorage.closeSeleniumSession;
import static com.thoughtworks.selenium.grid.tools.ThreadSafeSeleniumSessionStorage.session;
import static org.testng.AssertJUnit.assertTrue;
import static org.testng.AssertJUnit.assertFalse;

import java.util.ArrayList;
import java.util.Date;
import java.util.Iterator;
import java.util.List;
import java.util.Random;

import org.testng.annotations.BeforeMethod;
import org.testng.annotations.DataProvider;
import org.testng.annotations.Test;

public class CreateNewWikiTest extends BaseTest {
	public static final String TEST_USER_PREFIX = "WikiaTestAccount";
	public static final String TEST_EMAIL_FORMAT = "WikiaTestAccount%s@wikia-inc.com";
	private static String wikiName;
	private static List<String> testedLanguages = new ArrayList<String>();
	
	@BeforeMethod(alwaysRun=true)
	public void enforceMainWebsite() throws Exception {
		enforceWebsite("http://www.wikia.com");
	}
	
	public void enforceWebsite(String website) throws Exception {
		closeSeleniumSession();
		startSession(this.seleniumHost, this.seleniumPort, this.browser, website, this.timeout, this.noCloseAfterFail);
	}
	
	private static String getWikiName() {
		if (null == wikiName) {
			wikiName = "testwiki" + Long.toString(Math.abs(new Random().nextLong()), 36).toLowerCase();
		}

		return wikiName;
	}
	
	@DataProvider(name = "wikiLanguages")
	public Iterator<Object[]> wikiLanguages() throws Exception {
		String[] languages = getTestConfig().getStringArray("ci.extension.wikia.AutoCreateWiki.lang");
		List<Object[]> languageList = new ArrayList<Object[]>();
		for (String language : languages) {
			languageList.add(new Object[] { language });
		}
		return languageList.iterator();
	}
	
	@Test(groups="envProduction",dataProvider="wikiLanguages")
	public void testCreateWikiComprehensive(String language) throws Exception {
		loginAsStaff();
		
		session().open("/wiki/Special:CreateNewWiki?uselang=" + language);
		waitForElement("//input[@name='wiki-name']");
		session().type("//input[@name='wiki-name']", getWikiName());
		session().type("//input[@name='wiki-domain']", getWikiName());
		session().click("//li[@id='NameWiki']/form/nav/input[@class='next']");
		waitForElementVisible("DescWiki");
		session().select("//select[@name='wiki-category']", "value=3");
		session().click("//li[@id='DescWiki']/form/nav/input[@class='next']");
		waitForElementVisible("ThemeWiki", this.getTimeout());
		/* temporarily commenting out because we might add it back in later 
		if (language.equals("en")) {
			session().click("//li[@id='ThemeWiki']/nav/input[@class='next']");
			waitForElementVisible("UpgradeWiki", this.getTimeout());
			clickAndWait("//li[@id='UpgradeWiki']/nav/input[@class='next']");
		} else {*/
		clickAndWait("//li[@id='ThemeWiki']/nav/input[@class='next']");
		waitForElementVisible("WikiWelcome", this.getTimeout());
		
		String url = "http://" + (language.equals("en") ? "" : language + ".") + getWikiName() + ".wikia.com";
		
		assertTrue(session().getLocation().contains(url));
		
		enforceWebsite(url);
		
		editArticle("A new article", "Lorem ipsum dolor sit amet");
		session().open("index.php?title=A_new_article");
		session().waitForPageToLoad(this.getTimeout());
		assertTrue(session().isTextPresent("Lorem ipsum dolor sit amet"));
		editArticle("A new article", "consectetur adipiscing elit");
		session().open("index.php?title=A_new_article");
		session().waitForPageToLoad(this.getTimeout());
		assertFalse(session().isTextPresent("Lorem ipsum dolor sit amet"));
		assertTrue(session().isTextPresent("consectetur adipiscing elit"));
		
		testedLanguages.add(language);
	}
	
	@Test(groups="envProduction",dependsOnMethods={"testCreateWikiComprehensive"},alwaysRun=true)
	public void testDeleteComprehensive() throws Exception {
		loginAsStaff();
		
		for (String language : testedLanguages) {
			deleteWiki(language);
		}
		
		wikiName = null;
	}

	@Test(groups="envProduction")
	public void testCreateWikiAsLoggedOutUser() throws Exception {
		session().open("/wiki/Special:CreateNewWiki");
		waitForElement("//input[@name='wiki-name']");
		session().type("//input[@name='wiki-name']", getWikiName());
		session().type("//input[@name='wiki-domain']", getWikiName());
		session().click("//li[@id='NameWiki']/form/nav/input[@class='next']");
		waitForElementVisible("Auth");
		session().click("//p[@class='login-msg']/a");
		waitForElementVisible("AjaxLoginLoginForm");
		session().type("wpName2Ajax", getTestConfig().getString("ci.user.wikiastaff.username"));
		session().type("wpPassword2Ajax", getTestConfig().getString("ci.user.wikiastaff.password"));
		session().click("//li[@id='Auth']/nav/input[@class='login']");
		waitForElementVisible("DescWiki");
		session().select("//select[@name='wiki-category']", "value=3");
		session().click("//li[@id='DescWiki']/form/nav/input[@class='next']");
		waitForElementVisible("ThemeWiki", this.getTimeout());
		clickAndWait("//li[@id='ThemeWiki']/nav/input[@class='next']");
		/*
		waitForElementVisible("UpgradeWiki", this.getTimeout());
		clickAndWait("//li[@id='UpgradeWiki']/nav/input[@class='next']");
		*/
		waitForElementVisible("WikiWelcome", this.getTimeout());
	}
	
	@Test(groups="envProduction",dependsOnMethods={"testCreateWikiAsLoggedOutUser"},alwaysRun=true)
	public void testDeleteCreateWikiAsLoggedOutUser() throws Exception {
		loginAsStaff();
		
		deleteWiki("en");
		
		wikiName = null;
	}
	
	@Test(groups="envProduction")
	public void testCreateWikiAsNewUser() throws Exception {
		session().open("/wiki/Special:CreateNewWiki");
		waitForElement("//input[@name='wiki-name']");
		session().type("//input[@name='wiki-name']", getWikiName());
		session().type("//input[@name='wiki-domain']", getWikiName());
		session().click("//li[@id='NameWiki']/form/nav/input[@class='next']");
		waitForElementVisible("Auth");
		
		String time = Long.toString((new Date()).getTime());
		String password = Long.toString(Math.abs(new Random().nextLong()), 36).toLowerCase();
		String captchaWord = getWordFromCaptchaId(session().getValue("wpCaptchaId"));

		session().type("wpName2", TEST_USER_PREFIX + time);
		session().type("wpEmail", String.format(TEST_EMAIL_FORMAT, time));
		session().type("wpPassword2", password);
		session().type("wpRetype", password);
		session().select("wpBirthYear", "1900");
		session().select("wpBirthMonth", "1");
		session().select("wpBirthDay", "1");
		session().type("wpCaptchaWord", captchaWord);
		
		session().click("//li[@id='Auth']/nav/input[@class='signup']");
		
		waitForElementVisible("DescWiki");
		session().select("//select[@name='wiki-category']", "value=3");
		session().click("//li[@id='DescWiki']/form/nav/input[@class='next']");
		waitForElementVisible("ThemeWiki", this.getTimeout());
		clickAndWait("//li[@id='ThemeWiki']/nav/input[@class='next']");
		/*
		waitForElementVisible("UpgradeWiki", this.getTimeout());
		clickAndWait("//li[@id='UpgradeWiki']/nav/input[@class='next']");
		*/
		waitForElementVisible("WikiWelcome", this.getTimeout());
	}
	
	@Test(groups="envProduction",dependsOnMethods={"testCreateWikiAsNewUser"},alwaysRun=true)
	public void testDeleteCreateWikiAsNewUser() throws Exception {
		loginAsStaff();
		
		deleteWiki("en");
		
		wikiName = null;
	}
	
	public void deleteWiki(String language) throws Exception {
		session().open("http://community.wikia.com/wiki/Special:WikiFactory");
		session().waitForPageToLoad(this.getTimeout());

		session().type("citydomain", (language.equals("en") ? "" : language + ".") + getWikiName() + ".wikia.com");
		clickAndWait("//form[@id='WikiFactoryDomainSelector']/div/ul/li/button");
		session().waitForPageToLoad(this.getTimeout());
		waitForElementVisible("link=Close", this.getTimeout());
		
		if (session().isElementPresent("link=Close")) {
			clickAndWait("link=Close");

			session().uncheck("flag_1");
			session().uncheck("flag_2");
			session().check("flag_4");
			session().check("flag_8");
			clickAndWait("close_saveBtn");

			clickAndWait("close_saveBtn");
			assertTrue(session().isTextPresent("was closed"));
		}
	}

}
