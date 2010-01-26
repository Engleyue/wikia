var initTracker = function()
{
	String.prototype.replaceForTrac = function(){
		return this.replace(/ /g, "_").replace(/\./g, "");
	}

	var addTrack = function(name,url){
		 $(name).click(function(){
			 WET.byStr(url);
		 });
	 }

	 var addFooterTrack = function(id,name){
		 $('#' + id + ' a').click(function(e){
			 target = $(e.target);
			 if (target.hasClass('last4')){
				 data = target.attr('id').split('_');
				 WET.byStr('footer/link_' + data[2]);
			 } else {
				 WET.byStr('footer/' + name + '/' + target.html().replaceForTrac());	 
			 }
		 });
	 }
	 
	 addTrack('#wikia-search-submit', 'find_a_wiki');
	 addTrack('#wikia-login-link', 'log-in');
	 addTrack('#wikia-create-account-link', 'sign-up');
	 addTrack('.wikia-page-link','main_page/hotspots/article');
	 addTrack('.wikia-wiki-link','main_page/hotspots/wiki_name');
	 addTrack('.create-wiki-container .wikia_button','main_page/create_a_wiki');
	 addTrack('#homepage-feature-spotlight .nav','main_page/slider/thumb');
	 addTrack('.create-wiki-container .wikia_button','main_page/create_a_wiki');
	 addTrack('#wikia-create-wiki .wikia_button','bottom/create_a_wiki')
	 
	 addFooterTrack('wikia-international', 'left_column');
	 addFooterTrack('wikia-in-the-know', 'middle_column');
	 addFooterTrack('wikia-more-links', 'right_column');
	 addFooterTrack('SupplementalNav', 'bottom');
	 
	 $('.homepage-spotlight,#homepage-feature-spotlight .wikia_button').click(function(e){
		 switch(e.target.nodeName){
		 	case 'SPAN': element = e.target.parentNode.parentNode.parentNode ; break;
		 	case 'A': element = e.target.parentNode.parentNode; break;
		 	case 'IMG': element = e.target.parentNode.parentNode; break;
		 }
		 out = element.id.split('-');
		 WET.byStr('main_page/slider/featured/' + (parseInt(out[3]) + 1) );
	 });
	 
	 $("#GlobalNav ul:first > li").hover(function(e){
		 if (e.target.nodeName == "A"){
			 WET.byStr('nav-bar/' + $.trim($(e.target).html()).replaceForTrac()+ '/hoover');
		 }
	 },function(){});

	 $(".nav-link").click(function(e){
		 WET.byStr('nav-bar/' + $(e.target).html().replaceForTrac() + '/heading');
	 });
	 
	 $(".nav-sub-link").click(function(e) {
		 parent = $(e.target.parentNode.parentNode.parentNode).find("a").html().replaceForTrac();
		 targetId = e.target.id.split("_");
		 WET.byStr('nav-bar/' + parent + '/menu' + targetId[4]);
	 });
}