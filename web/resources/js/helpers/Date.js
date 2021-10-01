// © XlXi 2021
// Graphictoria 5

const Months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

const ConstructTime = (date) => {
	var newDate = new Date();
	newDate = new Date(newDate.getTime() + (newDate.getTimezoneOffset() * 60 * 1000));
	return `${Months[newDate.getMonth()] + ' ' + newDate.getDate() + ', ' + newDate.getFullYear()}`;
}

export { ConstructTime };