var LatestQuestions = {
    init: function() {
        $.getScript(wgAnswersServer + wgAnswersScript + '?action=ajax&rs=moduleProxy&moduleName=LatestActivity&actionName=Index&outputType=data&callback=LatestQuestions.onLoad&uselang='+wgUserLanguage);
    },
    onLoad: function(data) {
		if(data.changeList.length > 0) {
			var html = "<section class='LatestQuestionsModule module'>" +
				"<h1 class='activity-heading'>"+wgLatestQuestionsHeader+"</h1>" +
				"<ul>";

			for(var i=0; i<data.changeList.length; i++) {
				html += "<li><img src='"+wgBlankImgUrl+"' class='sprite "+data.changeList[i].changeicon+"' height='20' width='20'><em>"+data.changeList[i].page_href+"</em><div class=\"edited-by\">"+data.changeList[i].changemessage+"</div></li>";
			}

			html += "</ul>";
			html += "<a href='/wiki/Special:WikiActivity' title='Special:WikiActivity' class='more'>"+wgOasisMoreMsg+"</a>";
			html += "</section>";

			if ($.browser.msie) {
				$("section.LatestQuestionsModule").empty().append(html);
			} else {
				$("section.LatestQuestionsModule").replaceWith(html);
		    }

			$("section.LatestQuestionsModule a").attr("href", function(i, val) {
				return wgAnswersServer + val;
			});
		}
	}
}

$(window).load(function() {
    LatestQuestions.init();
});