let metaInfo = document.querySelectorAll(".meta-info");
metaInfo.forEach(function(e) {
	e.addEventListener("click", function(eClick) {
		metaInfo.forEach(function(el) {
			if (eClick.target != el) el.classList.remove("is-active");
		});
		eClick.target.classList.add("is-active");
	});
});

// add new css style
console.log("ok");

let a = addCss(theme.folder + "layout/user/css/main.css");
console.log(a);
