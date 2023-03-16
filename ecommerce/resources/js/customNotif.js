const customNotif = {
    notif(status, message) {
		let toastr = new CustomEvent('toastr', {
			'detail': {
				'status': status, 
				'message': message
			}
		});

		return toastr;
	}
}

window.customNotif = customNotif;