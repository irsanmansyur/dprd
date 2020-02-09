var status, message;

function tbodySort() {
	$("tbody > tr")
		.sort(function(a, b) {
			return $("td.number", b).text() > +$("td.number", a).text();
		})
		.appendTo("tbody")
		.find("th:first")
		.text(function(index) {
			return ++index + ".";
		});
}
var UserId = "";

$(".mdl#delete").on("click", function() {
	const url_delete = $(this).data("url");
	UserId = $(this).data("iduser");
	console.log(UserId);
	$(".modal#logout")
		.find(".btn.url")
		.attr("href", url_delete)
		.html("Delete");
	$(".modal#logout")
		.find(".modal-title")
		.html("Jika Anda menghapus maka data yang berkaitan akan terhapus juga");
});

$(".setUser").on("click", function() {
	const userId = $(this).data("id_user");
	const url = $(this).data("url");
	$(".input.user-id").val(userId);
	$(".form.setKomisi").attr("action", url);
	console.log(url);
});

$(".setKfomisi").on("submit", function(e) {
	e.preventDefault();
	var myForm = $(this);
	let url = myForm.attr("action");
	var data = $(this).serializeArray(); //serialize form inputs and pass them to php
	console.log();
	$.ajax({
		url: url,
		type: "post",
		data: data,
		dataType: "json",
		beforeSend: () => {
			myForm.after("<div class='mdlProgresBarr'></div>");
			$(".fullpage").addClass("tampil");
		},
		success: dt => {
			let userId = $('input[name="user_id"]').val();
			let nameKomisi = $("select[name='komisi_id'] option:selected").text();
			if (dt.status) {
				$(".komisi." + userId).text(nameKomisi);
			}
			console.log("Gagal");
			status = dt.status;
			message = dt.message;
		},
		error: e => {
			console.log(e);
		},
		complete: function() {
			$("head").append("<style>.mdlProgresBarr::before{width:100%;}</style>");
			$(".mdlProgresBarr").remove();
			$(".modal").modal("hide");
			let css = status ? "success" : "danger";
			let notif = `<div class="alert alert-${css}">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <i class="material-icons">close</i>
            </button>
            <span>${message}</span>
          </div>`;
			$(".fullpage").hide("slow", function() {
				$(".fullpage").remove();
			});
			$(".notif#ajax").html(notif);
			$(".main-panel").animate(
				{
					scrollTop: $(".notif#ajax").offset().top
				},
				"slow"
			);
		}
	});
});
let inputValid = data => {
	let error = [];
	data.forEach(el => {
		let val = document.querySelector(`[name=${el.name}]`);

		if (val && (val.value == "" || val.value == null)) {
			val.classList.add("not-filled");
			error.push(val);
		}
	});
	if (error.length > 0) {
		error[0].focus();

		return false;
	} else return true;
};

addCss("layout/komisi/css/user.css");
const elMdlUser = document.querySelector(".modal-content.user");

let imaGe = null;

elMdlUser.addEventListener("keyup", function(e) {
	let el = e.target;
	if (el.hasAttribute("name")) {
		let nama = el.getAttribute("name");
		let value = el.value;
		dataModal.dtUser[nama] = value;
	}
	console.log(dataModal.dtUser);
});
elMdlUser.addEventListener("click", function(e) {
	let el = e.target;
	if (el.hasAttribute("evAddUser")) {
		if (
			!inputValid([
				{
					name: "name"
				},
				{
					name: "email"
				},
				{
					name: "password"
				},
				{
					name: "no_hp"
				},
				{
					name: "alamat"
				},
				{
					name: "dapil_id"
				},
				{
					name: "jabatan"
				},
				{
					name: "komisi_id"
				}
			])
		) {
			e.preventDefault();
		}
	} else if (el.hasAttribute("atImg")) {
		el.addEventListener("change", function() {
			imaGe = this.files[0];
			if (imaGe) {
				const reader = new FileReader();
				reader.addEventListener("load", function() {
					let img = elMdlUser.querySelector("img.profile");
					img.setAttribute("src", this.result);
				});
				reader.readAsDataURL(imaGe);
			}
		});
	}
});

let dataModal = {
	dtUser: {
		role_id: 2,
		is_active: 2,
		name: null,
		email: null,
		password: null,
		no_hp: null,
		alamat: null
	},
	dtKomisi: {
		user_id: null,
		dapil_id: null,
		komisi_id: null,
		jabatan: null
	},
	lbl: null,
	methodU: null,
	methodK: null,
	urlU: null,
	urlK: null
};
const elListUser = document.querySelector("tbody");
elListUser.addEventListener("click", e => {
	let el = e.target;
	const elUser = el.closest(".user-id");
	if (el.hasAttribute("evChange")) {
		elMdlUser
			.querySelector("form")
			.setAttribute(
				"action",
				baseUrl + "admin/komisi/user/edit/" + elUser.dataset.iduser
			);
		imaGe = null;
		e.preventDefault();
		dataModal.dtKomisi = {
			user_id: elUser.dataset.iduser,
			dapil_id: elUser.querySelector("[data-name='dapil_id']").dataset.set,
			komisi_id: elUser.querySelector("#komisiId").dataset.set,
			jabatan: elUser.querySelector("#jabatan").dataset.set
		};
		let image = elUser.querySelector("img").getAttribute("src")
			? elUser.querySelector("img").getAttribute("src")
			: baseUrl + "assets/img/thumbnail/default.png";

		elMdlUser.querySelector("img").setAttribute("src", image);
		let elChek = elUser.querySelector('[type="checkbox"');
		dataModal.dtUser = {
			role_id: 2,
			is_active: elChek.checked ? 1 : 0,
			name: elUser.querySelector('[data-name="name"]').dataset.set,
			email: elUser.querySelector('[data-name="email"]').dataset.set,
			password: null,
			no_hp: elUser.querySelector('[data-name="noHp"]').dataset.set,
			alamat: elUser.querySelector('[data-name="alamat"]').dataset.set
		};
		dataModal.title = "Edit User";
		ChangeMdl(dataModal);
	} else if (el.hasAttribute("evActive")) {
		const userId = elUser.dataset.iduser;
		const is_active = el.checked ? "1" : "0";

		var myHeaders = new Headers();
		myHeaders.append("Content-Type", "application/x-www-form-urlencoded");

		var urlencoded = new URLSearchParams();
		urlencoded.append("is_active", is_active === "1" ? "1" : "0");

		var requestOptions = {
			method: "PUT",
			headers: myHeaders,
			body: urlencoded,
			redirect: "follow"
		};
		document.querySelector("#loader-wrapper").style.display = "block";
		fetch(
			"http://localhost/irsan/dprd/api/komisi/user/user_006",
			requestOptions
		)
			.then(response => response.json())
			.then(res => {
				if (res.status) {
					el.closest("div").querySelector(".label").innerHTML = el.checked
						? "Active"
						: "Non active";
					alert("Succes Change");
				} else {
					alert("gagal");
				}
			})
			.then(res => {
				document.querySelector("#loader-wrapper").style.display = "none";
			})
			.catch(error => console.log("error", error));
	}
});
function ChangeMdl({ dtKomisi, dtUser, ...option }) {
	let elName = elMdlUser.querySelector("[name='name'");
	elName.closest(".form-group ").classList.add("is-filled");
	elName.value = dtUser.name;

	let elEmail = elMdlUser.querySelector("[name='email'");

	elEmail.closest(".form-group ").classList.add("is-filled");
	elEmail.value = dtUser.email;
	let elAlamat = elMdlUser.querySelector("[name='alamat'");
	elAlamat.closest(".form-group ").classList.add("is-filled");
	elAlamat.value = dtUser.alamat;
	let elNohp = elMdlUser.querySelector("[name='no_hp'");
	elNohp.closest(".form-group ").classList.add("is-filled");
	elNohp.value = dtUser.no_hp;

	elMdlUser
		.querySelector("[name='komisi_id'")
		.querySelector(`[data-name='${dtKomisi.komisi_id}']`).selected = "selected";
	elMdlUser
		.querySelector("[name='jabatan'")
		.querySelector(`[data-name='${dtKomisi.jabatan}']`).selected = "selected";

	elMdlUser.querySelector("[data-name='title'").innerHTML = option.title;
	elMdlUser
		.querySelector("[name='dapil_id'")
		.querySelector(`[data-name='${dtKomisi.dapil_id}']`).selected = "selected";
}

let elAdd = document.querySelector("[data-name='evAdd']");
elAdd.addEventListener("click", e => {
	dataModal.title = "Tambah User";
	elMdlUser
		.querySelector("form")
		.setAttribute("action", baseUrl + "admin/komisi/user/add");
	elMdlUser.querySelector("[data-name='title'").innerHTML = dataModal.title;
	e.preventDefault();
	dataModal.urlU = "api/user";
	dataModal.methodU = {
		method: "POST",
		body: new FormData()
	};
	dataModal.urlK = "api/komisi/user";
	dataModal.methodK = {
		method: "POST",
		body: new FormData()
	};
	let input = Array.from(elMdlUser.querySelectorAll("input"));
	input.forEach(res => {
		if (res.getAttribute("type") != "submit") {
			if (res.getAttribute("name") == "is_active") res.value = 1;
			let paren = res.closest(".form-group");

			if (paren.classList.contains("is-filled")) {
				paren.classList.remove("is-filled");
				res.value = "";
			}
		}
	});

	let select = Array.from(elMdlUser.querySelectorAll("select"));
	select.forEach(res => {
		res.querySelector("option").selected = "selected";
	});
});
