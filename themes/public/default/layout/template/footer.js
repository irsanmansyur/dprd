let modalAdd = document.querySelector("#modal-add-aspirasi");
console.log("ddd");

modalAdd.addEventListener("click", function(e) {
	let el = e.target;
	if (el.getAttribute("type") == "submit") {
		let elForm = el.closest("form");
		if(login){
			e.preventDefault();
			document.querySelector(".container2").style.visibility = "visible";
			let proses = async () => {
				if (kecId=="" || message=='') {
				document.querySelector(".container2").style.visibility = "hidden";
					return alert("lengkapi data");
				}			
				var formdata = new FormData();
				let elKec = elForm.querySelector("[name='kec_id']");
				let kecId = elKec.options[elKec.selectedIndex].value;
				let elMessage = elForm.querySelector("[name='message']");
				let message = elMessage.value;	
				formdata.append("kec_id", kecId);
				formdata.append("user_id", user.id_user);
				formdata.append("message", message);

				var requestOptions = {
					method: "POST",
					body: formdata,
					redirect: "follow"
				};
				let sendasp = await fetch("http://localhost/irsan/dprd/api/aspirasi/", requestOptions)
								.then(response => response.text())
								.then(result => console.log(result))
								.catch(error => console.log('error', error));
				// if(sendasp.status){
				// 	let a = document.write(baseUrl+"user");
				// 	let b = document.replace(a);
				// } else{
				// 	alert("Gagal Input aspirasi");
				// }
			document.querySelector(".container2").style.visibility = "hidden";


			};
			proses();
		} else{
			alert("Anda Belum login");
			location.replace(baseUrl+"admin/auth");
		}
	}
});

addCss(theme.folder + "layout/template/css/footer.css");
