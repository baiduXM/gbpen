(function() {
	if (!
	/*@cc_on!@*/
	0) return;
	var e = "abbr, article, aside, audio, canvas, datalist, details, dialog, eventsource, figure, footer, header, hgroup, mark, menu, meter, nav, output, progress, section, time, video, ng-include, ng-pluralize, ng-view, ng:include, ng:pluralize, ng:view".split(', ');
	var i= e.length;
	while (i--){
		document.createElement(e[i])
	}
	document.write('<style>article,aside,dialog,footer,header,section,footer,nav,figure,menu{display:block}</style>');
})()