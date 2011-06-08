package com.wikia.selenium.tests;

import java.io.File;
import java.util.Random;

import static com.thoughtworks.selenium.grid.tools.ThreadSafeSeleniumSessionStorage.session;
import static org.testng.AssertJUnit.assertEquals;
import static org.testng.AssertJUnit.assertTrue;
import static org.testng.AssertJUnit.assertFalse;

import org.testng.annotations.Parameters;
import org.testng.annotations.Test;
import org.testng.annotations.BeforeMethod;
import org.testng.SkipException;

public class PhalanxTest extends BaseTest {

	private static final String uploadFileUrl = "http://images.wikia.com/wikiaglobal/images/b/bc/Wiki.png";

	private static final String testArticleName = "PhalanxTest";

	// word added to article title
	private static final String badWord = "WikiaTestBlockDontRemoveMe";

	// used for content and summary
	private static final String goodWords = "Wikia test text";
	private static final String badWords = "WikiaTestBadWordDontRemoveMe is a dirty word";

	// used in answers related tests
	private static final String goodQuestion = "Is bar a foo";
	private static final String badQuestion = "Is this a fucking question";

	private static final String filteredQuestionPhrase = "Is this a WikiaTestFilteredPhraseDontRemoveMe";

	// MW message with whitelist
	private static final String whitelistMessage = "MediaWiki:Spam-whitelist";
	private static final String whitelistWord = "WikiaTestBlockDontRemoveMeWhitelisted";

	// did cleanup method have been run?
	private static boolean run = false;

	// used by contentBlockTest tests
	private String blockId;
	private String blockFilter;

	/**
	 * Login on selected testing account (with rights to remove articles)
	 */
	protected void login() throws Exception {
		loginAsSysop();
	}

	/**
 	 * Console logging
 	 */
	private void log(String message) {
		//System.out.println(message);
	}

	/**
	 * Check whether current page is view mode of given article
	 */
	private boolean isArticleViewMode(String articleName) throws Exception {
		String content = session().getHtmlSource();
		String patternTitle = "wgTitle=\"" + articleName + "\"";
		String patternAction = "wgAction=\"view\"";

		return (content.indexOf(patternTitle) > -1 && content.indexOf(patternAction) > -1);
	}

	/**
	 * Delete given article
	 */
	private void deleteArticle(String articleName) throws Exception {
		this.log(" Removing an article: '" + articleName + "'");

		session().open("index.php?action=delete&title=" + articleName);
		session().waitForPageToLoad(this.getTimeout());

		// seems that given article does not exist
		if (!session().isElementPresent("wpDeleteReasonList")) {
			return;
		}

		session().select("wpDeleteReasonList", "label=regexp:^.*Author request");
		session().type("wpReason", "Cleanup after Phalanx test");
		session().uncheck("wpWatch");
		session().click("wpConfirmB");
		session().waitForPageToLoad(this.getTimeout());
	}

	/**
	 * Delete given image
	 */
	private void deleteImage(String imageName) throws Exception {
		this.log(" Removing an image: '" + imageName + "'");

		session().open("index.php?title=File:" + imageName);
		session().waitForPageToLoad(this.getTimeout());

		clickAndWait("//a[@data-id='delete']");

		session().type("wpReason", "this was for test");
		session().uncheck("wpWatch");
		clickAndWait("mw-filedelete-submit");

		assertTrue(session().isTextPresent(imageName + "\" has been deleted."));
	}

	/**
	 * Ask question using new answers skin and verify whether it's blocked or not
	 */
	private boolean askQuestion(String question) throws Exception {
		this.log(" Asking a question: '" + question + "'");

		session().open("index.php");
		session().waitForPageToLoad(this.getTimeout());

		// ask question (usage of new answers skin is assumed here)
		session().type("answers_ask_field", question);
		session().click("ask_button");
		session().waitForPageToLoad(this.getTimeout());

		// return true if article creation was successful
		return this.isArticleViewMode(question);
	}

	/**
	 * Ask question using new answers skin and verify whether it's on list of recenlty asked (via HomePageList)
	 */
	private boolean askQuestionAndCheckHPL(String question) throws Exception {
		this.log(" Asking a question: '" + question + "'");

		session().open("index.php");
		session().waitForPageToLoad(this.getTimeout());

		// ask question (usage of new answers skin is assumed here)
		session().type("answers_ask_field", question);
		session().click("ask_button");
		session().waitForPageToLoad(this.getTimeout());

		assertTrue(this.isArticleViewMode(question));

		// check HomePageList
		return this.isQuestionOnListOfRecentlyAsked(question);
	}

	/**
	 * Check whether given question is on list of recent unanswered questions
	 */
	private boolean isQuestionOnListOfRecentlyAsked(String question) throws Exception {
		session().open("index.php?smaxage=1&action=ajax&rs=HomePageListAjax&method=recent_unanswered_questions");
		session().waitForPageToLoad(this.getTimeout());

		String content = session().getHtmlSource();
		String pattern = ">" + question + "?</a></li>";

		return (content.indexOf(pattern) > -1);
	}

	/**
	 * Create an article and verify whether it's blocked or not
	 */
	private boolean createArticle(String articleName, String articleContent) throws Exception {
		return this.createArticle(articleName, articleContent, "");
	}

	/**
	 * Create an article (with summary) and verify whether it's blocked or not
	 */
	private boolean createArticle(String articleName, String articleContent, String articleSummary) throws Exception {
		this.log(" Creating an article: '" + articleName + "'");

		session().open("index.php?title=" + articleName + "&action=edit&useeditor=mediawiki");
		session().waitForPageToLoad(this.getTimeout());

		doEdit(articleContent);

		if (articleSummary != "") {
			session().type("//input[@name='wpSummary']", articleSummary);
		}

		doSave();

		// check for spam filter message when editing an article
		if (session().isTextPresent("Spam protection filter") && !this.isArticleViewMode(articleName)) {
			this.log(" Edit has been blocked");
			return false;
		}

		return true;
	}

	/**
	 * Move an article and verify whether it's blocked or not
	 */
	private boolean moveArticle(String oldName, String newName) throws Exception {
		this.log(" Moving an article from '" + oldName + "' to '" + newName + "'");

		// let's create test article
		editArticle(oldName, "Phalanx test article");
		assertTrue(this.isArticleViewMode(oldName));

		// try to move it
		session().open("index.php?title=Special:MovePage/" + oldName);
		session().waitForPageToLoad(this.getTimeout());

		session().type("wpNewTitle", newName);
		session().type("wpReason", "Phalanx test");

		if (session().isElementPresent("wpLeaveRedirect")) {
			session().uncheck("wpLeaveRedirect");
		}
		session().uncheck("watch");

		clickAndWait("wpMove");

		if (session().isElementPresent("wpConfirm")) {
			if (session().isElementPresent("wpLeaveRedirect")) {
				session().uncheck("wpLeaveRedirect");
			}
			session().uncheck("watch");
			session().check("wpConfirm");
			clickAndWait("wpDeleteAndMove");
		}

		// check whether move was correct
		if (session().isTextPresent("\"" + oldName + "\" has been moved to \"" + newName + "\"")) {
			return true;
		}

		assertTrue(session().isTextPresent("Spam protection filter"));
		this.log(" Move has been blocked");

		return false;
	}

	/**
	 * Upload a file and verify whether it's blocked or not
	 */
	private boolean uploadFile(String srcName, String destName) throws Exception {
		// keep file extensions consistent (uploaded image can be either PNG or JPG)
		String srcNameExtension = srcName.substring(srcName.length() - 3, srcName.length());
		destName += "." + srcNameExtension;

		this.log(" Uploading an image: '" + srcName + "' as '" + destName + "'");

		session().open("index.php?title=Special:Upload");
		session().waitForPageToLoad(this.getTimeout());

		session().attachFile("wpUploadFile", srcName);
		session().type("wpDestFile", destName);
		session().type("wpUploadDescription", "Phalanx test");
		session().uncheck("wpWatchthis");
		clickAndWait("//input[@name='wpUpload']");

		if (session().isTextPresent("Upload warning")) {
			clickAndWait("//input[@name='wpUpload']");
		}

		assertFalse(session().isTextPresent("Upload error"));

		// upload warning - duplicate ...
		if (session().isTextPresent("Upload warning")) {
			clickAndWait("//input[@name='wpUploadIgnoreWarning']");
		}

		assertTrue(session().isTextPresent("Image:" + destName) || session().isTextPresent("File:" + destName));

		return true;
	}

	/**
	 * Perform cleanup tasks before running test methods
	 *
	 * FIXME
	@BeforeMethod
	@Test(groups={"CI"})
	public void cleanupBeforeTest() throws Exception {
		// run only once
		if (!this.run) {
			loginAsStaff();

			this.log("Cleanup - removing articles");
			this.deleteArticle(this.testArticleName);
			this.deleteArticle(this.testArticleName + "New");
			this.deleteArticle(this.testArticleName + this.badWord);
			this.deleteArticle(this.testArticleName + this.badWord + "New");

			this.log("Cleanup - removing questions");
			this.deleteArticle(this.goodQuestion);
			this.deleteArticle(this.badQuestion);

			this.log("Cleanup - removing images");
			this.deleteImage(this.testArticleName + "Image");
			this.deleteImage(this.testArticleName + this.badWord + "Image");

			this.run = true;
		}
		else {
			this.log("");
		}
	}
	**/

	/**
	 * Test answers creation on answers site
	 *
	 * Hook triggered: CreateDefaultQuestionPageFilter
	 * Tests blocks: TitleBlackList, BadWords
	 */
	@Test(groups={"answers", "CI"})
	public void createAnswersQuestionTest() throws Exception {
		this.log("Test answers creation on answers site");

		login();

		// ask "good" question
		assertTrue(this.askQuestion(this.goodQuestion));

		// ask "bad" question
		assertFalse(this.askQuestion(this.badQuestion));
	}

	/**
	 * Test recent questions filtering
	 *
	 * Hook triggered: FilterWords
	 * Tests blocks: DefaultQuestion::filterWordsTest
	 */
	@Test(groups={"answers", "CI"})
	public void filterRecentQuestionsTest() throws Exception {
		this.log("Test recent questions filtering");

		login();

		// ask question which wont be filtered out
		assertTrue(this.askQuestionAndCheckHPL(this.goodQuestion));

		// ask question which will be filtered out
		assertFalse(this.askQuestionAndCheckHPL(this.filteredQuestionPhrase));
	}

	/**
	 * Test article move
	 *
	 * Hook triggered: AbortMove, SpecialMovepageBeforeMove
	 * Tests blocks: SpamRegex, TitleBlackList
	 */
	@Test(groups={"CI"})
	public void articleMoveTest() throws Exception {
		this.log("Test article move");

		login();

		// try moving an article using "good" title
		assertTrue(this.moveArticle(this.testArticleName, this.testArticleName + "New"));

		// try moving an article using "bad" title
		assertFalse(this.moveArticle(this.testArticleName, this.testArticleName + this.badWord + "New"));
	}

	@Test(groups={"CI"}, dependsOnMethods={"articleMoveTest"}, alwaysRun=true)
	public void articleMoveCleanupTest() throws Exception {
		this.log("Test article move - cleanup");

		loginAsStaff();

		this.deleteArticle(this.testArticleName + "New");
	}

	/**
	 * Test article edit (scan title)
	 *
	 * Hook triggered: EditFilter
	 * Tests blocks: TitleBlackList
	 */
	@Test(groups={"CI"})
	public void createArticleTitleTest() throws Exception {
		this.log("Test article edit (scan title)");

		login();

		// try creating an article with "good" title
		assertTrue(this.createArticle(this.testArticleName, this.goodWords));

		// try creating an article with "bad" title
		assertFalse(this.createArticle(this.testArticleName + this.badWord, this.goodWords));
	}

	/**
	 * Test article edit (scan content)
	 *
	 * Hook triggered: EditFilter
	 * Tests blocks: SpamRegex
	 */
	@Test(groups={"CI"})
	public void editArticleContentTest() throws Exception {
		this.log("Test article edit (scan content)");

		login();

		// try creating an article with "good" content
		assertTrue(this.createArticle(this.testArticleName, this.goodWords));

		// try creating an article with "bad" content
		assertFalse(this.createArticle(this.testArticleName, this.badWords));

		// perform edit with blocked word in the URL
		assertFalse(this.createArticle(this.testArticleName, "http://www." + this.badWord + ".net"));
	}

	/**
	 * Test article edit (scan summary)
	 *
	 * Hook triggered: EditFilter
	 * Tests blocks: SpamRegex
	 */
	@Test(groups={"CI"})
	public void editArticleSummaryTest() throws Exception {
		this.log("Test article edit (scan summary)");

		login();

		// try creating an article with "good" summary
		assertTrue(this.createArticle(this.testArticleName, this.goodWords, this.goodWords));

		// try creating an article with "bad" summary
		assertFalse(this.createArticle(this.testArticleName, this.goodWords, this.badWords));
	}

	/**
	 * Test articles creation (via NewWikiBuilder)
	 *
	 * Hook triggered: ApiCreateMultiplePagesBeforeCreation
	 * Tests blocks: TitleBlackList
	 */
	/**
	@Test(groups={"deprecated"})
	public void articlesCreationNewWikiBuilderTest() throws Exception {
		this.log("Test articles creation (via WikiBuilder)");

		Random randomGenerator = new Random();
		String articleNameSuffix = Integer.toString(randomGenerator.nextInt(99999));

		loginAsSysop();

		session().open("index.php?title=Special:WikiBuilder");
		session().waitForPageToLoad(this.getTimeout());
		session().click("//form[@name='step1-form']//input[contains(@class, 'skip')]");
		waitForElementVisible("//div[@class='step2']");
		session().click("//div[@class='step2']//input[contains(@class, 'skip')]");
		waitForElementVisible("//div[@class='step3']");

		assertTrue(session().isTextPresent("Start some pages"));

		// try to create articles
		session().type("//form[@id='Pages']//input[1]", this.testArticleName + articleNameSuffix);
		session().type("//form[@id='Pages']//input[2]", this.testArticleName + this.badWord + articleNameSuffix);
		session().type("//form[@id='Pages']//input[3]", this.testArticleName + articleNameSuffix + "Foo");

		// create pages
		session().click("//form[@id='Pages']//input[contains(@class, 'save')]");
		waitForElementVisible("//div[@class='step4']");

		// test how many pages were created
		session().open("index.php?title=" + this.testArticleName + articleNameSuffix);
		assertFalse(session().isTextPresent("This page needs content"));
		session().open("index.php?title=" + this.testArticleName + articleNameSuffix + "Foo");
		assertFalse(session().isTextPresent("This page needs content"));
		try {
			session().open("index.php?title=" + this.testArticleName + this.badWord + articleNameSuffix);

			// this page shouldn't be created, should be blocked by Phalanx
			this.log(" Page " + this.testArticleName + this.badWord + articleNameSuffix +  " should not be created");
			assertTrue(false);
		} catch (Exception e) {
			// catch 404
		}

		// cleanup
		this.deleteArticle(this.testArticleName + articleNameSuffix);
		this.deleteArticle(this.testArticleName + articleNameSuffix + "Foo");
	}
	**/

	/**
	 * Test file upload (using Special:Upload)
	 *
	 * Hook triggered: UploadForm:BeforeProcessing
	 * Tests blocks: TitleBlackList
	 */
	@Test(groups={"CI"})
	public void fileUploadTest() throws Exception {
		this.log("Test file upload (using Special:Upload)");

		login();

		// try uploading a file with "good" destination name
		assertTrue(this.uploadFile(uploadFileUrl, this.testArticleName + "Image"));

		// try uploading a file with "bad" destination name - this one should NOT be blocked
		assertTrue(this.uploadFile(uploadFileUrl, this.testArticleName + this.badWord + "Image"));
	}

	/**
	 * Test user block (on edit page)
	 *
	 * Hook triggered: GetBlockedStatus
	 * Tests blocks: RegexBlock
	 */
	@Test(groups={"CI"})
	public void userBlockOnEditPageTest() throws Exception {
		this.log("Test user block (on edit page)");

		login("TestUserFuck", "fuck");

		session().open("index.php?title=User:TestUserFuck&action=edit");
		session().waitForPageToLoad(this.getTimeout());

		// user should not be able to save the page
		assertFalse(session().isElementPresent("wpSave"));

		// block info should be shown
		assertTrue(session().isElementPresent("//div[@class='permissions-errors']"));
	}

	/**
	 * Test whitelist
	 *
	 * Tests blocks from ContentBlock group (maintain the execution order of tests below)
	 */
	@Test(groups={"CI"})
	public void beforeWhitelistTest() throws Exception {
		this.log("Test whitelist - prepare");

		loginAsStaff();

		// edit whitelist - allow "fuck" word
		String whitelist = " #<!-- Phalanx whitelist text --> <pre>\n" +
			this.whitelistWord +
			"\n #</pre> <!-- Phalanx whitelist text -->";

		this.log(" Adding whitelist entry");

		editArticle(this.whitelistMessage, whitelist);
	}

	@Test(groups={"CI"}, dependsOnMethods={"beforeWhitelistTest"})
	public void whitelistTest() throws Exception {
		this.log("Test whitelist - check");

		login();

		// perform edits with whitelisted domain in URL
		assertTrue(this.createArticle(this.testArticleName, "http://www." + this.whitelistWord + ".net"));
		assertFalse(this.createArticle(this.testArticleName, "https://www.foo.net/" + this.whitelistWord + ".html"));
		assertFalse(this.createArticle(this.testArticleName, this.goodWords + " " + this.whitelistWord));
	}

	@Test(groups={"CI"}, dependsOnMethods={"whitelistTest"})
	public void afterWhitelistTest() throws Exception {
		this.log("Test whitelist - reverting");

		loginAsStaff();

		// revert last change to whitelist message
		undoLastEdit(this.whitelistMessage, "Cleanup after Phalanx test");
	}

	/**
	 * Test adding, applying and removing content block via Special:Phalanx
	 */
	@Test(groups={"CI"})
	public void contentBlockTest() throws Exception {
		this.log("Test adding, applying and removing content block via Special:Phalanx");

		loginAsStaff();

		try {
			session().open("index.php?title=Special:Phalanx");
		} catch(Exception e) {
			this.log("Special:Phalanx not enabled on this wiki - skipping this test");
			throw new SkipException("Skip");
		};

		session().waitForPageToLoad(this.getTimeout());
		assertTrue(session().isElementPresent("wpPhalanxFilter"));

		// let's create block which will be applied to article content
		Random randomGenerator = new Random();
		this.blockFilter = "<big>Test / \\ Block" + Integer.toString(randomGenerator.nextInt(666)) + "</big>";
		String blockReason = "Phalanx test block";

		this.log(" 1) Adding content block for '" + this.blockFilter + "'");

		// fill-in the form
		session().type("wpPhalanxFilter", this.blockFilter);
		session().check("wpPhalanxTypeContent");
		session().type("wpPhalanxReason", blockReason);
		session().click("wpPhalanxSubmit");
		Thread.sleep(2000);

		// refresh the page
		session().open("index.php?title=Special:Phalanx");
		session().waitForPageToLoad(this.getTimeout());

		// get ID of added block
		this.blockId = session().getAttribute("//li[contains(@id, 'phalanx-block-')]/b[text() = '" + this.blockFilter + "']/..@id");
		this.blockId = this.blockId.substring(14, this.blockId.length());

		this.log(" Block #" + this.blockId + " added");

		// check whether new block is on the list
		assertTrue(session().isTextPresent(this.blockFilter));
	}

	@Test(groups={"CI"}, dependsOnMethods={"contentBlockTest"})
	public void contentBlockApplyTest() throws Exception {
		// test edit blocking
		this.log(" 2) Testing article edit blocking");

		login();

		assertFalse(this.createArticle(this.testArticleName, this.goodWords + " " + this.blockFilter));
	}

	@Test(groups={"CI"}, dependsOnMethods={"contentBlockApplyTest"})
	public void contentBlockStatsTest() throws Exception {
		// test stats page
		this.log(" 3) Testing stats page");

		loginAsStaff();

		session().open("index.php?title=Special:PhalanxStats/" + this.blockId);
		session().waitForPageToLoad(this.getTimeout());

		assertTrue(session().isElementPresent("phalanx-block-" + this.blockId + "-stats"));

		// remove the block (assume it's the first one on the list)
		this.log(" 4) Removing content block for '" + this.blockFilter + "' (#" + this.blockId + ")");

		session().open("index.php?title=Special:Phalanx");
		session().waitForPageToLoad(this.getTimeout());

		session().click("//li[@id='phalanx-block-" + this.blockId + "']/a[@class='unblock']");
		Thread.sleep(2000);

		// refresh the page
		session().open("index.php?title=Special:Phalanx");
		session().waitForPageToLoad(this.getTimeout());

		// check whether block is not on the list
		assertFalse(session().isTextPresent(this.blockFilter));
	}

	@Test(groups={"CI"}, dependsOnMethods={"contentBlockStatsTest"})
	public void contentBlockAfterRemovalTest() throws Exception {
		// test edit blocking
		this.log(" 5) Testing article edit blocking");

		login();

		assertTrue(this.createArticle(this.testArticleName, this.goodWords + " " + this.blockFilter));
	}
}
