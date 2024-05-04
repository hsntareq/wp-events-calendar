import './admin.scss';

import { log, generateCalendar, ajax_request } from './lib';

const calendarBody = document.getElementById('calendar-body');
const ecWrap = document.querySelector('.events-calendar-wrap');
var ecPrev = ecWrap && ecWrap.querySelector('.ec-prev');
var ecNext = ecWrap && ecWrap.querySelector('.ec-next');

// ecPrev.addEventListener('click', function (e) {
// 	console.log('prev clicked');
// 	// add htmlMarkup to calendarBody
// 	// calendarBody.innerHTML = htmlMarkup(); monthly_calendar_data

// 	ajax_request('monthly_calendar_data', { year: 2024, month: 8 }).then(data => {
// 		console.log(data);
// 	});

// 	// generateCalendar(2024, 8);

// 	// generateCalendar(2024, 10);
// });

document.addEventListener('DOMContentLoaded', function () {
	// Function to fetch calendar data using WordPress AJAX
	function getCalendarData(year, month) {
		fetch(ec_data.ajaxurl, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			},
			body: 'action=get_monthly_calendar_data&year=' + year + '&month=' + month // Specify the action and month/year to fetch
		})
			.then(response => {
				if (!response.ok) {
					throw new Error('Network response was not ok');
				}
				return response.json();
			})
			.then(data => {
				console.log(data);
				// Insert the returned calendar HTML into the specified element
				document.getElementById("calendar-body").innerHTML = data.data;
			})
			.catch(error => console.error('Error fetching calendar data:', error));
	}

	// Get current year and month
	const today = new Date();
	let currentYear = today.getFullYear();
	let currentMonth = today.getMonth() + 1; // JavaScript months are 0-indexed

	// Initial load of calendar data
	getCalendarData(currentYear, currentMonth);

	// Event listener for next button
	document.getElementById('next-button').addEventListener('click', function () {
		console.log('next clicked');
		currentMonth++;
		if (currentMonth > 12) {
			currentMonth = 1;
			currentYear++;
		}
		getCalendarData(currentYear, currentMonth);
	});

	// Event listener for previous button
	document.getElementById('previous-button').addEventListener('click', function () {
		console.log('prev clicked');
		currentMonth--;
		if (currentMonth < 1) {
			currentMonth = 12;
			currentYear--;
		}
		getCalendarData(currentYear, currentMonth);
	});
});
