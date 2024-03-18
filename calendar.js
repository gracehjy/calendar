// maybe put this in calendar.html? but might make the html file clutered

(function(){
    "use strict";

    Date.prototype.deltaDays=function(n){  
        return new Date(this.getFullYear(),this.getMonth(),this.getDate()+n);
    };

    Date.prototype.getSunday=function(){
        return this.deltaDays(-1*this.getDay());
    };
}());

/** Week
 * 
 * Represents a week.
 * 
 * Functions (Methods):
 *	.nextWeek() returns a Week object sequentially in the future
 *	.prevWeek() returns a Week object sequentially in the past
 *	.contains(date) returns true if this week's sunday is the same
 *		as date's sunday; false otherwise
 *	.getDates() returns an Array containing 7 Date objects, each representing
 *		one of the seven days in this month
 */
function Week(initial_d){
    this.sunday=initial_d.getSunday();
    this.nextWeek=function(){
        return new Week(this.sunday.deltaDays(7));
    };
    this.prevWeek=function(){
        return new Week(this.sunday.deltaDays(-7));
    };
    this.contains=function(d){
        return (this.sunday.valueOf()===d.getSunday().valueOf());
    };
        
    this.getDates=function(){
        let dates = [];
        for(var i=0; i < 7; i++){
            dates.push(this.sunday.deltaDays(i));
        }
        return dates;
    };
}

/** Month
 * 
 * Represents a month.
 * 
 * Properties:
 *	.year == the year associated with the month
 *	.month == the month number (January = 0)
 * 
 * Functions (Methods):
 *	.nextMonth() returns a Month object sequentially in the future
 *	.prevMonth() returns a Month object sequentially in the past
 *	.getDateObject(d) returns a Date object representing the date
 *		d in the month
 *	.getWeeks() returns an Array containing all weeks spanned by the
 *		month; the weeks are represented as Week objects
 */
function Month(year,month){
    "use strict";

    this.year=year;
    this.month=month;

    this.nextMonth=function(){
        return new Month(year+Math.floor((month+1)/12),(month+1)%12);
    };

    this.prevMonth=function(){
        return new Month(year+Math.floor((month-1)/12),(month+11)%12);
    };

    this.getDateObject=function(d){
        return new Date(this.year,this.month,d);
    };

    this.getWeeks=function(){
        let firstDay = this.getDateObject(1);
        let lastDay = this.nextMonth().getDateObject(0);

        let weeks=[];
        let currWeek = new Week(firstDay);

        weeks.push(currWeek);

        while(!currWeek.contains(lastDay)) {
            currWeek = currWeek.nextWeek();
            weeks.push(currWeek);
        }
        return weeks;
    };

    this.getMonthName = function () {
        let monthNames = [
            "January", "February", "March", "April",
            "May", "June", "July", "August",
            "September", "October", "November", "December"
        ];
        return monthNames[this.month];
    };
}

// fills the calendar cells with values representing the days of the month
function fillCalendar(month, events) {
    let days = document.querySelector('.calendar-days');
    let weeks = month.getWeeks();

    days.innerHTML = '';

    weeks.forEach(week => {
        let dates = week.getDates();
        dates.forEach(date => {
            let dateNumber = date.getDate();
            let dayCell = document.createElement('div');
            dayCell.classList.add('calendar-day');
            dayCell.textContent = dateNumber;

            // Check if there are events for this date
            let eventDate = date.toISOString().split('T')[0]; // Format the date as YYYY-MM-DD
            let eventsForDate = events.filter(event => event.date === eventDate);

            // If there are events, add them to the day cell
            if (eventsForDate.length > 0) {
                eventsForDate.forEach(event => {
                    let eventElement = document.createElement('div');
                    eventElement.classList.add('event');
                    eventElement.textContent = event.title;
                    dayCell.appendChild(eventElement);
                });
            }

            days.appendChild(dayCell);
        });
    });

    // adds the month and year on the calendar
    let monthYearTitle = document.getElementById("month-yearnum");
    monthYearTitle.textContent = month.getMonthName() + " " + month.year;
}

// gets event data
function getEventData(year, month) {
    // Construct the URL to fetch event data for the given year and month
    let url = 'getevents.php?year=' + year + '&month=' + month;

    // Fetch event data from the server
    return fetch(url)
        .then(response => response.json())
        .catch(error => console.error('Error fetching event data:', error));
}

// perform on load
document.addEventListener('DOMContentLoaded', function() {
    let currentDate = new Date();
    let currentMonth = new Month(currentDate.getFullYear(), currentDate.getMonth());

    function fillCalendar(year, month) {
        getEventData(year, month)
            .then(events => {
                fillCalendar(currentMonth, events);
            })
            .catch(error => console.error('Error fetching event data:', error));
    }

    fillCalendar(currentMonth.year, currentMonth.month + 1);

    // previous and next months
    document.getElementById('previous').addEventListener('click', function () {
        currentMonth = currentMonth.prevMonth();
        fillCalendar(currentMonth.year, currentMonth.month + 1);
    });

    document.getElementById('future').addEventListener('click', function () {
        currentMonth = currentMonth.nextMonth();
        fillCalendar(currentMonth.year, currentMonth.month + 1);
    });

    document.getElementById('create-event').addEventListener('click', function () {
        window.location.href = 'calendar.php';
    });
});

document.addEventListener("DOMContentLoaded", function() {
    // function to get and display events
    function displayEvents() {
        fetch("getevents.php")
            .then(response => response.json())
            .catch(error => console.error(error));
    }

    displayEvents();
});