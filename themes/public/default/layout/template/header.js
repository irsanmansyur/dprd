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
function getData(url, method = {}) {
	let response = fetch(baseUrl + url, method);
	let datas = response.then(res => res.json()).then(res => res);
	return datas;
}
function getTanggal(dateInt) {
	let d = new Date(parseInt(dateInt, 10) * 1000);
	let days = [
		"Sun", //senin
		"Mon", //selasa
		"Tuesd", //rabu
		"Wednd", //kamis
		"Thursd", //jumat
		"Frid", //sabtu
		"Saturd" //minggu
	];
	let months = [
		"01",
		"02",
		"04",
		"05",
		"06",
		"07",
		"08",
		"09",
		"10",
		"11",
		"12"
	];
	return days[d.getDay()] + "/" + months[d.getMonth()] + "/" + d.getFullYear();
}
function addCss(url) {
	let link = document.createElement("link");
	link.rel = "stylesheet";
	link.type = "text/css";
	link.href = url;
	document.querySelector("head").appendChild(link);
	return "Added";
}

const elNotif = document.querySelector(".nav-item.notif");

docReady(async function() {
	const notif = await getNotif();
	setNotif(notif);
});

function setNotif(notif) {
	const num = notif.length > 0 ? notif.length : 0;
	let elNum = elNotif.querySelector("a span.num");

	elNum.innerHTML = num;
	let isi = "";
	if (num > 0) {
		notif.map(res => {
			var kmt = res.komentar.substring(0, 50);
			var icon = `<i class="fas fa-redo-alt"> </i> <span class="text-muted" style="font-size: 14px;"> Membalas : </span>`;
			if (res.parent == 0) {
				icon =
					'<i class="fas fa-comment-dots"> </i> <span class="text-muted" style="font-size: 14px;"> Berkomentar : </span> ';
				if (res.namerole == "komisi") {
					icon =
						'<i class="fas fa-reply-all"> </i> <span class="text-muted" style="font-size: 14px;"> Menanggapi : </span> : ';
				}
			}

			let waktu = new Date(res.date_created * 1000);
			let ttime =
				waktu.getDate() + "-" + waktu.getMonth() + "-" + waktu.getFullYear();
			icon += `<span class="card-text text-justify text-monospace">${kmt}.</span><span class="position-absolute text-muted" style="top:6px;right:15px;font-size:12px">${ttime}</span>`;
			isi += `<li><div class="card-body identitas position-relative mt-3" style='padding-left:55px'>
			<img src="${baseUrl}assets/img/thumbnail/profile_${res.file}" style="width: 40px;height:40px;left:5px;top:10px" alt="" class="position-absolute rounded-circle">
			<h5 class="card-title mb-0 pb-0" style="margin-top:-15px;font-size: 16px;">${res.name}</h5>${icon}<p><a href="${baseUrl}aspirasi/id/${res.aspirasi_id}/${res.id_komentar}" class='badge badge-primary ml-2'>Lihat</a></p></div>
			</li>`;
		});
	} else {
		isi += `<a class="dropdown-item" href="#">Tidak Ada Notifikasi Baru</a>`;
	}
	elNotif.querySelector(".dropdown-menu").innerHTML = isi;
}
function getNotif() {
	return fetch(baseUrl + "api/notif?user_id=" + user.id_user)
		.then(res => res.json())
		.then(res => res.data);
}
