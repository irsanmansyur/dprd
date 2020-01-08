function docReady(fn) {
	// see if DOM is already available
	if (
		document.readyState === "complete" ||
		document.readyState === "interactive"
	) {
		// call on next available tick
		setTimeout(fn, 1);
	} else {
		document.addEventListener("DOMContentLoaded", fn);
	}
}
docReady(function() {
	let type = document.querySelectorAll("li.asp");
	for (const asp of type) {
		asp.addEventListener("click", function(event) {
			event.preventDefault();
			let status = asp.dataset.status;
			init(
				status != 0
					? aspirasi_all.filter(res => res.status == status)
					: aspirasi_all
			);
		});
	}

	let _parent = document.querySelectorAll("#data-aspirasi > .meta-info");
	for (const asp of _parent) {
		asp.addEventListener("click", function(event) {
			event.preventDefault();
			console.log("jjj");
		});
	}
});
