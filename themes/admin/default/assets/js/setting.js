let elInputLogo = document.querySelector("[name='web_logo']");
elInputLogo.addEventListener("change", function(e) {
	if (this.files && this.files[0]) {
		var reader = new FileReader();
		reader.onload = function(e) {
			document
				.querySelector('[data-name="web_logo"]')
				.setAttribute("src", e.target.result);
		};
		reader.readAsDataURL(this.files[0]);
	}
});
