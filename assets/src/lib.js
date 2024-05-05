

export const ajax_request = async (action, data = null) => {
	// Add nonce to the data object
	const formData = new FormData();
	formData.append('action', action);
	formData.append('nonce', ec_data.nonce);
	formData.append('data', JSON.stringify(data));

	const response = await fetch(ec_data.ajaxurl, {
		method: "POST",
		body: formData,
	});
	if (!response.ok) {
		throw new Error('Network response was not ok');
	}
	return response.json();
}



export function generateCalendar(inYear = '', inMonth = '') {
	const today = new Date();
	const year = inYear !== '' ? parseInt(inYear) : today.getFullYear();
	const month = inMonth !== '' ? parseInt(inMonth) - 1 : today.getMonth();
	const daysInMonth = new Date(year, month + 1, 0).getDate();

	const calendarBody = document.getElementById("calendar-body");
	calendarBody.innerHTML = "";
	let date = 1;
	for (let i = 0; i < 6; i++) {
		const row = document.createElement("tr");
		for (let j = 0; j < 7; j++) {
			if (i === 0 && j < new Date(year, month, 1).getDay()) {
				const cell = document.createElement("td");
				row.appendChild(cell);
			} else if (date > daysInMonth) {
				break;
			} else {
				const cell = document.createElement("td");
				cell.textContent = date;
				// add class if it is today
				if (date === today.getDate() && year === today.getFullYear() && month === today.getMonth()) {
					cell.classList.add("today");
				}
				row.appendChild(cell);
				date++;
			}
		}
		calendarBody.appendChild(row);
	}
}


export const log = (message) => {
	console.log(message);
}
