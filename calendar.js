// maybe put this in calendar.html? but might make the html file clutered

(function(){
    Date.prototype.deltaDays=function(c){  
        return new Date(this.getFullYear(),this.getMonth(),this.getDate()+c)};
    Date.prototype.getSunday=function(){
        return this.deltaDays(-1*this.getDay())}})();

function Week(c){
    this.sunday=c.getSunday();
    this.nextWeek=function(){
        return new Week(this.sunday.deltaDays(7))
    };
    this.prevWeek=function(){
        return new Week(this.sunday.deltaDays(-7))
    };
    this.contains=function(b){
        return this.sunday.valueOf()===b.getSunday().valueOf()
    };
        
    this.getDates=function(){
        for(var dates=[], i = 0 ; 7 > i; i++){
            dates.push(this.sunday.deltaDays(i));
        }
        return dates
    };
}
function Month(year,month){
    this.year=year;
    this.month=month;
    this.nextMonth=function(){
        return new Month(year+Math.floor((month+1)/12),(month+1)%12)
    };

    this.prevMonth=function(){
        return new Month(year+Math.floor((month-1)/12),(month+11)%12)
    };

    this.getDateObject=function(a){
        return new Date(this.year,this.month,a)
    };

    this.getWeeks=function(){
        var firstDay = this.getDateObject(1);
        lastDay = this.nextMonth().getDateObject(0);

        weeks=[],
        currWeek = new Week(firstDay);

        weeks.push(currWeek);

        while(!currWeek.contains(lastDay)) {
            currWeek = currWeek.nextWeek();
            weeks.push(currWeek);
        }
        return weeks;
    }
};