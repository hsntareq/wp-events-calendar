import './admin.scss';

import { log, generateCalendar, ajax_request } from './lib';

const calendarBody = document.getElementById('calendar-body');
const calendarHeading = document.getElementById('calendar-heading');

document.addEventListener('DOMContentLoaded', function () {
	// Function to fetch calendar data using WordPress AJAX
	function getCalendarData(year, month) {
		calendarHeading.textContent = `${getMonthName(month)}, ${year}`;
		calendarBody.innerHTML = '<tr><td colspan="7" style="text-align:center;"><span class="spinner is-active"></span></td></tr>';

		ajax_request('monthly_calendar_data', { year: year, month: month })
			.then(data => {
				setTimeout(() => {
					calendarBody.innerHTML = data;
				}, 10);
			})
			.catch(error => console.error('Error fetching calendar data:', error));
	}

	// Get current year and month
	const today = new Date();
	let currentYear = today.getFullYear();
	let currentMonth = today.getMonth() + 1; // JavaScript months are 0-indexed
	if (calendarBody) getCalendarData(currentYear, currentMonth); // Fetch calendar data for current month.

	// Event listener for next button
	let nextButton = document.getElementById('next-button');
	nextButton && nextButton.addEventListener('click', function () {
		currentMonth++;
		if (currentMonth > 12) {
			currentMonth = 1;
			currentYear++;
		}
		getCalendarData(currentYear, currentMonth);
	});

	// Event listener for previous button
	let previousButton = document.getElementById('previous-button');
	previousButton && previousButton.addEventListener('click', function () {
		currentMonth--;
		if (currentMonth < 1) {
			currentMonth = 12;
			currentYear--;
		}
		getCalendarData(currentYear, currentMonth);
	});
});

function getMonthName(monthNumber) {
	// Array of month names
	var monthNames = [
		"January", "February", "March", "April", "May", "June",
		"July", "August", "September", "October", "November", "December"
	];

	// Ensure the monthNumber is within valid range
	if (monthNumber < 1 || monthNumber > 12) {
		return "Invalid Month";
	}

	// Retrieve the month name corresponding to the monthNumber
	return monthNames[monthNumber - 1];
}
