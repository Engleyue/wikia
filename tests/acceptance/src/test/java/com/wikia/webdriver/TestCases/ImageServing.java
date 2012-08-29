package com.wikia.webdriver.TestCases;

import org.testng.annotations.Test;

import com.wikia.webdriver.Common.Core.CommonFunctions;
import com.wikia.webdriver.Common.Core.Global;
import com.wikia.webdriver.Common.Properties.Properties;
import com.wikia.webdriver.Common.Templates.TestTemplate;
import com.wikia.webdriver.PageObjects.PageObject.FilePageObject;
import com.wikia.webdriver.PageObjects.PageObject.WikiBasePageObject;
import com.wikia.webdriver.PageObjects.PageObject.WikiPage.SpecialMultipleUploadPageObject;
import com.wikia.webdriver.PageObjects.PageObject.WikiPage.SpecialNewFilesPageObject;
import com.wikia.webdriver.PageObjects.PageObject.WikiPage.SpecialUploadPageObject;
import com.wikia.webdriver.PageObjects.PageObject.WikiPage.WikiArticleEditMode;
import com.wikia.webdriver.PageObjects.PageObject.WikiPage.WikiArticlePageObject;
//https://internal.wikia-inc.com/wiki/QA/Core_Features_and_Testing/Manual_Regression_Tests/Image_Serving

public class ImageServing extends TestTemplate {
	private String file = "Image001.jpg";
	private String[] ListOfFiles = {"Image001.jpg","Image002.jpg", "Image003.jpg", "Image004.jpg", "Image005.jpg", "Image006.jpg", "Image007.jpg", "Image008.jpg", "Image009.jpg", "Image010.jpg"};
	private String wikiArticle = "QAautoPage";
	private String Caption = "QAcaption1";
	private String Caption2 = "QAcaption2";
	private String videoURL = "http://www.youtube.com/watch?v=pZB6Dg1RJ_o";
	private String videoURL2 = "http://www.youtube.com/watch?v=TTchckhECwE";
	private String videoURL2name = "What is love (?) - on piano (Haddway)";
	
	
	@Test(groups = {"ImageServing001"}) 
//	https://internal.wikia-inc.com/wiki/QA/Core_Features_and_Testing/Manual_Regression_Tests/Image_Serving
	public void ImageServing001_SpecialNewFilesTest()
	{

	CommonFunctions.MoveCursorTo(0, 0);
	WikiBasePageObject wiki = new WikiBasePageObject(driver, Global.DOMAIN);
	SpecialNewFilesPageObject wikiSpecialNF = wiki.OpenSpecialNewFiles();
	
	
	CommonFunctions.logIn(Properties.userName2, Properties.password2);
	wikiSpecialNF.ClickOnAddaPhoto();
	wikiSpecialNF.ClickOnMoreOrFewerOptions();
	wikiSpecialNF.CheckIgnoreAnyWarnings();
	wikiSpecialNF.ClickOnMoreOrFewerOptions();
	
	wikiSpecialNF.TypeInFileToUploadPath(file);
	wikiSpecialNF.ClickOnUploadaPhoto();
	wikiSpecialNF.waitForFile(file); 

	}
	
	@Test(groups = {"ImageServing002"}) 
//	https://internal.wikia-inc.com/wiki/QA/Core_Features_and_Testing/Manual_Regression_Tests/Image_Serving
	public void ImageServing002_SpecialUploadTest()
	{
		CommonFunctions.MoveCursorTo(0, 0);
		WikiBasePageObject wiki = new WikiBasePageObject(driver, Global.DOMAIN);
		SpecialUploadPageObject wikiSpecialU = wiki.OpenSpecialUpload();
		CommonFunctions.logIn(Properties.userName2, Properties.password2);
		wikiSpecialU.TypeInFileToUploadPath(file);
		wikiSpecialU.verifyFilePreviewAppeared(file);
		wikiSpecialU.CheckIgnoreAnyWarnings();
		FilePageObject filePage = wikiSpecialU.ClickOnUploadFile(file);
		filePage.VerifyCorrectFilePage();
		CommonFunctions.logOut(Properties.userName2, driver);
	}
	@Test(groups = {"ImageServing003"}) 
//	https://internal.wikia-inc.com/wiki/QA/Core_Features_and_Testing/Manual_Regression_Tests/Image_Serving
	public void ImageServing003_SpecialMultipleUploadTest()
	{
		CommonFunctions.MoveCursorTo(0, 0);
		WikiBasePageObject wiki = new WikiBasePageObject(driver, Global.DOMAIN);
		SpecialMultipleUploadPageObject wikiSpecialMU = wiki.OpenSpecialMultipleUpload();
		CommonFunctions.logIn(Properties.userName2, Properties.password2);
		wikiSpecialMU.TypeInFilesToUpload(ListOfFiles);
		wikiSpecialMU.CheckIgnoreAnyWarnings();
		wikiSpecialMU.ClickOnUploadFile();
		wikiSpecialMU.VerifySuccessfulUpload(ListOfFiles);
		CommonFunctions.logOut(Properties.userName2, driver);
	}
	@Test(groups = {"ImageServing004"}) 
//	https://internal.wikia-inc.com/wiki/QA/Core_Features_and_Testing/Manual_Regression_Tests/Image_Serving
	// Test Case 004 Adding images to an article in edit mode
	public void ImageServing004_AddingImages()
	{
//		CommonFunctions.MoveCursorTo(0, 0);
		WikiBasePageObject wiki = new WikiBasePageObject(driver, Global.DOMAIN);
		WikiArticlePageObject article = wiki.OpenArticle(wikiArticle);
		CommonFunctions.logIn(Properties.userName2, Properties.password2);
		WikiArticleEditMode editArticle = article.Edit();
		editArticle.ClickOnAddObjectButton("Image");
		editArticle.WaitForModalAndClickAddThisPhoto();
		editArticle.TypeCaption(Caption);
		editArticle.ClickOnAddPhotoButton2();
		editArticle.VerifyThatThePhotoAppears(Caption);
		editArticle.ClickOnPreviewButton();
		editArticle.VerifyTheImageOnThePreview();
		editArticle.VerifyTheCaptionOnThePreview(Caption);
		article = editArticle.ClickOnPublishButtonInPreviewMode();
		article.VerifyTheImageOnThePage();
		editArticle = article.Edit();
		editArticle.deleteArticleContent();
		article = editArticle.ClickOnPublishButton();
		CommonFunctions.logOut(Properties.userName2, driver);
	}
	
	@Test(groups = {"ImageServing005"}) 
//	https://internal.wikia-inc.com/wiki/QA/Core_Features_and_Testing/Manual_Regression_Tests/Image_Serving
	// Test Case 005 Modifying images in an article in edit mode
	public void ImageServing005_ModifyingImages()
	{
		CommonFunctions.MoveCursorTo(0, 0);
		WikiBasePageObject wiki = new WikiBasePageObject(driver, Global.DOMAIN);
		WikiArticlePageObject article = wiki.OpenArticle(wikiArticle);
		CommonFunctions.logIn(Properties.userName2, Properties.password2);
		WikiArticleEditMode editArticle = article.Edit();
		editArticle.ClickOnAddObjectButton("Image");
		editArticle.WaitForModalAndClickAddThisPhoto();
		editArticle.TypeCaption(Caption);
		editArticle.ClickOnAddPhotoButton2();
		editArticle.ClickModifyButtonOfImage(Caption);
		editArticle.TypeCaption(Caption2);
		editArticle.ClickOnAddPhotoButton2();
		editArticle.VerifyThatThePhotoAppears(Caption2);
		editArticle.ClickOnPreviewButton();
		editArticle.VerifyTheImageOnThePreview();
		editArticle.VerifyTheCaptionOnThePreview(Caption2);
		article = editArticle.ClickOnPublishButtonInPreviewMode();
		article.VerifyTheImageOnThePage();
		editArticle = article.Edit();
		editArticle.deleteArticleContent();
		article = editArticle.ClickOnPublishButton();
		CommonFunctions.logOut(Properties.userName2, driver);
		CommonFunctions.MoveCursorTo(0, 0);
	}
	
	@Test(groups = {"ImageServing006"}) 
//	https://internal.wikia-inc.com/wiki/QA/Core_Features_and_Testing/Manual_Regression_Tests/Image_Serving	
	// Test Case 006  Removing images in an article in edit mode
	public void ImageServing006_RemovingImages()
	{
		CommonFunctions.MoveCursorTo(0, 0);
		WikiBasePageObject wiki = new WikiBasePageObject(driver, Global.DOMAIN);
		WikiArticlePageObject article = wiki.OpenArticle(wikiArticle);
		CommonFunctions.logIn(Properties.userName2, Properties.password2);
		WikiArticleEditMode editArticle = article.Edit();
		editArticle.ClickOnAddObjectButton("Image");
		editArticle.WaitForModalAndClickAddThisPhoto();
		editArticle.TypeCaption(Caption);
		editArticle.ClickOnAddPhotoButton2();
		editArticle.HoverCursorOverImage(Caption);
		editArticle.ClickRemoveButtonOfImage(Caption);
		editArticle.LeftClickCancelButton();
//		editArticle.VerifyModalDisappeared();  
		editArticle.HoverCursorOverImage(Caption);
		editArticle.ClickRemoveButtonOfImage(Caption);
		editArticle.LeftClickOkButton();
//		editArticle.VerifyModalDisappeared();
//		editArticle.VerifyTheImageNotOnTheArticleEditMode();
		article = editArticle.ClickOnPublishButton();
//		article.VerifyTheImageNotOnThePage();
		
		CommonFunctions.logOut(Properties.userName2, driver);
		CommonFunctions.MoveCursorTo(0, 0);
	}

	@Test(groups = {"ImageServing007"}) 
//	https://internal.wikia-inc.com/wiki/QA/Core_Features_and_Testing/Manual_Regression_Tests/Image_Serving	
	// Test Case 007  Adding galleries to an article in edit mode
	public void ImageServing007_AddingGalleries()
	{
		CommonFunctions.MoveCursorTo(0, 0);
		WikiBasePageObject wiki = new WikiBasePageObject(driver, Global.DOMAIN);
		WikiArticlePageObject article = wiki.OpenArticle(wikiArticle);
		CommonFunctions.logIn(Properties.userName2, Properties.password2);
		WikiArticleEditMode editArticle = article.Edit();
		editArticle.ClickOnAddObjectButton("Gallery");
		editArticle.WaitForObjectModalAndClickAddAphoto("Gallery");
		editArticle.GalleryCheckImageInputs(4);
		editArticle.GalleryClickOnSelectButton();
		editArticle.GallerySetPosition("Gallery", "Center");
		editArticle.GallerySetPhotoOrientation(2);
		editArticle.GalleryClickOnFinishButton();
		editArticle.VerifyObjectInEditMode("gallery");
		editArticle.ClickOnPreviewButton();
		editArticle.VerifyTheObjectOnThePreview("gallery");
		article = editArticle.ClickOnPublishButtonInPreviewMode();
		article.VerifyTheObjetOnThePage("gallery");
		editArticle = article.Edit();
		editArticle.deleteArticleContent();
		article = editArticle.ClickOnPublishButton();
		CommonFunctions.logOut(Properties.userName2, driver);
		CommonFunctions.MoveCursorTo(0, 0);
	}
	
	@Test(groups = {"ImageServing008"}) 
//	https://internal.wikia-inc.com/wiki/QA/Core_Features_and_Testing/Manual_Regression_Tests/Image_Serving	
	// Test Case 008 Adding slideshows to an article in edit mode
	public void ImageServing008_AddingSlideshow()
	{
		CommonFunctions.MoveCursorTo(0, 0);
		WikiBasePageObject wiki = new WikiBasePageObject(driver, Global.DOMAIN);
		WikiArticlePageObject article = wiki.OpenArticle(wikiArticle);
		CommonFunctions.logIn(Properties.userName2, Properties.password2);
		WikiArticleEditMode editArticle = article.Edit();
		editArticle.ClickOnAddObjectButton("Slideshow");
		editArticle.WaitForObjectModalAndClickAddAphoto("GallerySlideshow");
		editArticle.GalleryCheckImageInputs(4);
		editArticle.GalleryClickOnSelectButton();
		editArticle.GallerySetPosition("Slideshow", "Center");
		editArticle.GalleryClickOnFinishButton();
		editArticle.VerifyObjectInEditMode("slideshow");
		editArticle.ClickOnPreviewButton();
		editArticle.VerifyTheObjectOnThePreview("slideshow");
		article = editArticle.ClickOnPublishButtonInPreviewMode();
		article.VerifyTheObjetOnThePage("slideshow");
		editArticle = article.Edit();
		editArticle.deleteArticleContent();
		article = editArticle.ClickOnPublishButton();
		CommonFunctions.logOut(Properties.userName2, driver);
	
	}
	
	@Test(groups = {"ImageServing009"}) 
//	https://internal.wikia-inc.com/wiki/QA/Core_Features_and_Testing/Manual_Regression_Tests/Image_Serving	
	// Test Case 009 Adding sliders to an article in edit mode
	public void ImageServing009_AddingSliders()
	{
		CommonFunctions.MoveCursorTo(0, 0);
		WikiBasePageObject wiki = new WikiBasePageObject(driver, Global.DOMAIN);
		WikiArticlePageObject article = wiki.OpenArticle(wikiArticle);
		CommonFunctions.logIn(Properties.userName2, Properties.password2);
		WikiArticleEditMode editArticle = article.Edit();
		editArticle.ClickOnAddObjectButton("Slider");
		editArticle.WaitForObjectModalAndClickAddAphoto("GallerySlider");
		editArticle.GalleryCheckImageInputs(4);
		editArticle.GalleryClickOnSelectButton();
		editArticle.GallerySetSliderPosition(2);
		editArticle.GalleryClickOnFinishButton();
		editArticle.VerifyObjectInEditMode("gallery-slider");
		editArticle.ClickOnPreviewButton();
		editArticle.VerifyTheObjectOnThePreview("slider");
		article = editArticle.ClickOnPublishButtonInPreviewMode();
		article.VerifyTheObjetOnThePage("slider");
		editArticle = article.Edit();
		editArticle.deleteArticleContent();
		article = editArticle.ClickOnPublishButton();
		CommonFunctions.logOut(Properties.userName2, driver);
		
	}
	
	@Test(groups = {"ImageServing010"}) 
//	https://internal.wikia-inc.com/wiki/QA/Core_Features_and_Testing/Manual_Regression_Tests/Image_Serving	
	// Test Case 010 Adding videos to an article in edit mode
	public void ImageServing010_AddingVideo()
	{
		CommonFunctions.MoveCursorTo(0, 0);
		WikiBasePageObject wiki = new WikiBasePageObject(driver, Global.DOMAIN);
		WikiArticlePageObject article = wiki.OpenArticle(wikiArticle);
		CommonFunctions.logIn(Properties.userName2, Properties.password2);
		WikiArticleEditMode editArticle = article.Edit();
		editArticle.ClickOnAddObjectButton("Video");
		editArticle.WaitForVideoModalAndTypeVideoURL(videoURL);
		editArticle.ClickVideoNextButton();
		editArticle.WaitForVideoDialog();
		editArticle.TypeVideoCaption(Caption);
		editArticle.ClickAddAvideo();
		editArticle.WaitForSuccesDialogAndReturnToEditing();
		editArticle.VerifyVideoInEditMode();
		editArticle.ClickOnPreviewButton();
		editArticle.VerifyTheVideoOnThePreview();
		article = editArticle.ClickOnPublishButtonInPreviewMode();
		article.VerifyTheVideoOnThePage();
		editArticle = article.Edit();
		editArticle.deleteArticleContent();
		article = editArticle.ClickOnPublishButton();
		CommonFunctions.logOut(Properties.userName2, driver);
		
	}
	
	@Test(groups = {"ImageServing011"}) 
//	https://internal.wikia-inc.com/wiki/QA/Core_Features_and_Testing/Manual_Regression_Tests/Image_Serving	
	// Test Case 011 Adding related videos through Related Video (RV) module
	public void ImageServing011_AddingVideoThroughRV()
	{
		CommonFunctions.MoveCursorTo(0, 0);
		//delete all videos from RV module on QAAutopage using RelatedVideos:QAautoPage (message article)
		WikiBasePageObject wiki = new WikiBasePageObject(driver, Global.DOMAIN);
		WikiArticlePageObject RVmoduleMessage = wiki.OpenArticle("RelatedVideos:"+wikiArticle);
		CommonFunctions.logIn(Properties.userName2, Properties.password2);
		WikiArticleEditMode RVmoduleMessageEdit = RVmoduleMessage.Edit();
		RVmoduleMessageEdit.deleteArticleContent();
		RVmoduleMessage = RVmoduleMessageEdit.ClickOnPublishButton();
		// after deletion start testing
		WikiArticlePageObject article = RVmoduleMessage.OpenArticle(wikiArticle);
		article.VerifyRVModulePresence();
		article.ClickOnAddVideoRVModule();
		article.TypeInVideoURL(videoURL2);
		article.ClickOnRVModalAddButton();
//		article.WaitForProcessingToFinish();
		article.VerifyVideoAddedToRVModule(videoURL2name);
	
		CommonFunctions.logOut(Properties.userName2, driver);
		
	}
	

	}

