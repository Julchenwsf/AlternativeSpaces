var events = [
{lat: 59.92265475733866, lng: 10.803101062774658, interest: "Football", title: "Practice", description: "Football practice for [someone].", time: 1416470400},
{lat: 59.925257101959204, lng: 10.778124332427979, interest: "Dance", title: "Show", description: "Writing descriptions is really hard.", time: 1416502800},
{lat: 59.92170839960051, lng: 10.800440311431885, interest: "Basketball", title: "G15 match", description: "Phasellus cursus pulvinar nulla.", time: 1416477600},
{lat: 59.92065446944626, lng: 10.788252353668213, interest: "Photography", title: "Beginner class", description: "Duis quis mi non neque ullamcorper fermentum quis eget turpis.", time: 1416499200},
{lat: 59.9157930522404, lng: 10.772287845611572, interest: "Movies", title: "Making music video", description: "Pellentesque sed justo eu arcu pharetra vestibulum.", time: 1416488400},
{lat: 59.91329753499344, lng: 10.79331636428833, interest: "Football", title: "Street match", description: "Phasellus porttitor ultrices ex at pulvinar.", time: 1416513600},
{lat: 59.920805032946184, lng: 10.764734745025635, interest: "Football", title: "Indoor practice", description: "Mauris bibendum arcu et ligula mattis, a auctor libero scelerisque.", time: 1416510000},
{lat: 59.92736463644395, lng: 10.78533411026001, interest: "Dance", title: "Breakdance", description: "Vivamus dolor orci, posuere id fermentum at, rhoncus vel lorem.", time: 1416513600}
];


//Gets events from 'events' array with interest set to 'inter'
function getEventsWithInterest(inter) {
    var relevantEvents = [];
    for(var i in events) {
        if(events[i].interest == inter) {
            relevantEvents.push(events[i]);
        }
    }

    return relevantEvents;
}
