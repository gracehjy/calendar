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
}