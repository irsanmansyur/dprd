let modalAdd = document.querySelector("#modal-add-aspirasi");
modalAdd.addEventListener("click", function(e) {
	let el = e.target;
	if (el.getAttribute("type") == "submit") {
		let elForm = el.closest("form");
		if (login) {
			e.preventDefault();
			document.querySelector(".container2").style.visibility = "visible";
			let proses = async () => {
				let elKec = elForm.querySelector("[name='kec_id']");
				let kecId = elKec.options[elKec.selectedIndex].value;
				let elMessage = elForm.querySelector("[name='message']");
				let message = elMessage.value;

				if (kecId == "" || message == "") {
					document.querySelector(".container2").style.visibility = "hidden";
					return alert("lengkapi data");
				}
				var formdata = new FormData();
				formdata.append("kec_id", kecId);
				formdata.append("user_id", user.id_user);
				formdata.append("message", message);

				var requestOptions = {
					method: "POST",
					body: formdata,
					redirect: "follow"
				};
				let sendasp = await fetch(baseUrl + "api/aspirasi/", requestOptions)
					.then(response => response.json())
					.then(result => result)
					.catch(error => console.log("error", error));
				if (sendasp.status) {
					alert("Aspirasi Berhasil di input");

					location.replace(baseUrl + "user");
				} else {
					alert("Gagal Input aspirasi");
				}
				document.querySelector(".container2").style.visibility = "hidden";
			};
			proses();
		} else {
			alert("Anda Belum login");
			location.replace(baseUrl + "admin/auth");
		}
	}
});
addCss(theme.folder + "layout/template/css/footer.css");
