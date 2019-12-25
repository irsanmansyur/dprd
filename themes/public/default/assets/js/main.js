$(".nav-link").each(function() {
	if ($(this).attr("href") == window.location.href) {
		$(".nav-link").removeClass("active");
		$(this).addClass("active");
	}
	$(this).click(function() {
		console.log("clik link");
		$(".nav-link").removeClass("active");
		$(this).addClass("active");
	});
});
