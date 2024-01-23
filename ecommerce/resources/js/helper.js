const helper = {
	rupiahFormatter(number) {
		// Check if the input is a valid number
		if (isNaN(number)) {
			console.error('Invalid input. Please provide a valid number.');
			return '';
		}

		// Convert the number to a string and add the currency symbol
		let rupiah = 'Rp.' + Math.floor(number).toString().replace(/\d(?=(\d{3})+$)/g, '$&.');

		return rupiah;
	}
}

window.helper = helper;