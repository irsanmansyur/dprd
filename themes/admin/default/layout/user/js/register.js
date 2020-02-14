loadFileJs(baseUrl + "node_modules/sweetalert2/dist/sweetalert2.min.js", "url");
addCss(baseUrl + "node_modules/sweetalert2/sweetalert2.min.css", "url");
addCss("layout/user/css/style.css");
let formLogin = document.querySelector("#formInput");
formLogin.addEventListener("submit", async function(e) {
	Swal.showLoading();

	e.preventDefault();
	let email = formLogin.querySelector("[name='email'").value;
	let name = formLogin.querySelector("[name='name'").value;
	let no_hp = formLogin.querySelector("[name='no_hp'").value;
	let tgl_lahir = formLogin.querySelector("[name='tgl_lahir'").value;
	let password = formLogin.querySelector("[name='password'").value;
	let alamat = formLogin.querySelector("[name='alamat'").value;
	const files = document.querySelector("[type=file]").files;

	try {
		let formdata = new FormData();
		formdata.append("email", email);
		formdata.append("alamat", alamat);
		formdata.append("name", name);
		formdata.append("no_hp", no_hp);
		formdata.append("tgl_lahir", tgl_lahir);
		formdata.append("no_hp", no_hp);
		formdata.append("password", password);
		if (files.length > 0) formdata.append("image", files[0]);
		Swal.showLoading();
		let requestOptions = {
			method: "POST",
			body: formdata
		};
		let login = await getData("api/user/register", requestOptions);

		await Swal.fire({
			icon: "success",
			title: "Register Berhasil Silahkan cek email anda untuk verifikasi"
		});
		location.replace(baseUrl + "admin/auth");
	} catch (error) {
		Swal.fire({
			icon: "error",
			title: "Oops...",
			html: error
		});
	}
});
